<?php
namespace CSDT\DockerUtilBundle\Managers;

use CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager;
use Docker\Docker;
use Psr\Log\LoggerInterface;
use CSDT\CollectionsBundle\Collections\ValueCollection;
use Doctrine\DBAL\Exception\ServerException;
use CSDT\DockerUtilBundle\Exceptions\DockerImageException;
use Docker\Manager\ImageManager as DockerImageManager;
use Psr\Http\Message\ResponseInterface;
use Docker\API\Model\ImageItem;
use CSDT\DockerUtilBundle\Images\ImageItemParser;
use CSDT\CollectionsBundle\Collections\MapCollection;
use GuzzleHttp\Psr7\Response;
use CSDT\DockerUtilBundle\Images\PullResponseParser;
use CSDT\DockerUtilBundle\Images\PullResponse;
use CSDT\DockerUtilBundle\Images\RemoveResponseParser;
use CSDT\DockerUtilBundle\Images\Image;

/**
 * Image manager
 *
 * This class is used to manage the docker image
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ImageManager extends LoggingManager
{
    /**
     * Fetch value
     *
     * Fetch the getImages by values, as a set
     *
     * @var integer
     */
    const FETCH_VALUE = 0;

    /**
     * Fetch tag
     *
     * Fetch the getImages by name and tag, as a map
     *
     * @var integer
     */
    const FETCH_TAG = 1;

    /**
     * Docker
     *
     * The docker instance
     *
     * @var Docker
     */
    private $docker;

    /**
     * Cache
     *
     * The getImages cache storage
     *
     * @var ValueCollection <Image>
     */
    private $cache = null;

    /**
     * Mapped cache
     *
     * The getImages mapped cache storage
     *
     * @var MapCollection <string, MapCollection<string, Image>>
     */
    private $mappedCache = null;

    /**
     * Construct
     *
     * The default ImageManager constructor
     *
     * @param Docker $docker The docker instance to manage
     * @param LoggerInterface $logger The logger
     *
     * @return void
     */
    public function __construct(Docker $docker, LoggerInterface $logger = null)
    {
        $this->setDocker($docker);
        $this->setLogger($logger);
    }

    /**
     * Set docker
     *
     * Set the docker instance to manage
     *
     * @param Docker $docker The docker instance
     *
     * @return ImageManager
     */
    protected function setDocker(Docker $docker)
    {
        $this->docker = $docker;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager::getName()
     */
    protected function getName()
    {
        return "DockerImageManager";
    }

    /**
     * Clear cache
     *
     * Clear the getImages cache storage
     *
     * @return void
     */
    protected function clearCache()
    {
        $this->cache = null;
        $this->mappedCache = null;
    }

    /**
     * Get image manager
     *
     * Return the docker image manager
     *
     * @return DockerImageManager
     */
    protected function getImageManager()
    {
        return $this->docker->getImageManager();
    }

    /**
     * Image exist
     *
     * Validate that a given image already exist in the local repository
     *
     * @param string $imageName The image name
     * @param string $imageTag  [optional] The image tag
     *
     * @return boolean
     */
    public function imageExist($imageName, $imageTag = null)
    {
        return !boolval($this->getImageInfo($imageName, $imageTag)->isEmpty());
    }

    /**
     * Get image info
     *
     * Return the given image information or null if the
     * image cannot be resolved. A set of image can be
     * returned if no tags are spécified.
     *
     * @param string $imageName The image name
     * @param string $imageTag  [optional] The image tag
     *
     * @return ValueCollection <Image>
     */
    public function getImageInfo($imageName, $imageTag = null)
    {
        $images = $this->getImagesByTag();
        $namedImages = $this->getImagesByName($images, $imageName);

        $result = new ValueCollection();
        if (is_null($namedImages)) {
            return $result;
        }

        if (!is_null($imageTag) && $namedImages->has($imageTag)) {
            $result->add($namedImages->get($imageTag));
            return $result;
        } else if (!is_null($imageTag)) {
            return $result;
        }

        $result->addAll($namedImages->toArray());
        return $result;
    }

    /**
     * Get images by name
     *
     * Return the MapCollection of the image name by tag
     * or null if the image name is not stored
     *
     * @param MapCollection $images The MapCollection of the images names
     * @param string $name The image name
     *
     * @return MapCollection|NULL
     */
    private function getImagesByName(MapCollection $images, $name)
    {
        if ($images->has($name)) {
            return $images->get($name);
        }

        return null;
    }

    /**
     * Get images set
     *
     * Return all of the images informations retreived
     * from the docker API. A cache is generated to preserve
     * the data.
     *
     * @throws DockerImageException
     * @return ValueCollection
     */
    public function getImagesSet()
    {
        return $this->getImages(self::FETCH_VALUE);
    }

    /**
     * Get images by tag
     *
     * Return all of the images informations retreived
     * from the docker API. A cache is generated to preserve
     * the data. The images are stored into a map by name,
     * and into a submap by tag.
     *
     * @throws DockerImageException
     * @return MapCollection
     */
    public function getImagesByTag()
    {
        return $this->getImages(self::FETCH_TAG);
    }

    /**
     * Get images
     *
     * Return all of the images informations retreived
     * from the docker API. A cache is generated to preserve
     * the data.
     *
     * @throws DockerImageException
     * @return ValueCollection<Image>|MapCollection<string, MapCollection<string, Image>>
     */
    protected function getImages($fetchType = self::FETCH_VALUE)
    {
        if (!is_null($this->cache) && $fetchType == self::FETCH_VALUE) {
            $this->debug("Return ".$this->cache->count()." images from cache");
            return $this->cache;
        } else if (!is_null($this->mappedCache) && $fetchType == self::FETCH_TAG) {
            $this->debug("Return ".$this->cache->count()." images from cache");
            return $this->mappedCache;
        }
        $this->debug("Resolving images set from API");

        $internalManager = $this->getImageManager();

        try {
            $response = $internalManager->findAll();
        } catch (ServerException $serverException) {
            $this->error("Error during image retreiving : ".((string)$serverException));
            throw new DockerImageException("Error during image retreiving", 500, $serverException);
        }

        if ($response instanceof ResponseInterface) {
            $this->error("Unexpected server response : ".$response->getBody()->getContents());
            throw new DockerImageException("Unexpected server response", 500);
        }

        $set = $this->fetchValue($response);
        $map = $this->fetchTag($response);

        if (!$set->isEmpty()) {
            $imageToString = array();
            foreach ($set as $image) {
                if ($image instanceof Image) {
                    $imageToString[] = "\t".$image->getName().":".$image->getTag()." (".$image->getShortId().")";
                }
            }
            $this->debug($set->count()." images resolved from API : \n".implode("\n", $imageToString));
        } else {
            $this->debug($set->count()." images resolved from API");
        }

        if ($fetchType == self::FETCH_VALUE) {
            return $set;
        }
        return $map;
    }

    /**
     * Fetch tag
     *
     * Return the images into a MapCollection instance
     *
     * @param ImageItem[] $response The getImages Response
     *
     * @throws DockerImageException
     * @return MapCollection
     */
    private function fetchTag($response)
    {
        $imageMap = new MapCollection();
        $parser = new ImageItemParser();
        foreach ($response as $imageItem) {
            if ($imageItem instanceof ImageItem) {
                for ($imageIndex = 0; $imageIndex < $parser->getImageCount($imageItem); $imageIndex++) {
                    $indexedImage = $parser->parse($imageItem, $imageIndex);

                    if (!$imageMap->has($indexedImage->getName())) {
                        $imageMap->set($indexedImage->getName(), new MapCollection());
                    }

                    $imageMap->get($indexedImage->getName())->set($indexedImage->getTag(), $indexedImage);
                }
            } else {
                $this->error("Unexpected object of type ".(gettype($imageItem) == "object" ? get_class($imageItem) : gettype($imageItem)));
                throw new DockerImageException("Unexpected object type");
            }
        }

        $this->mappedCache = $imageMap;
        return $imageMap;
    }

    /**
     * Fetch value
     *
     * Return the images into a ValueCollection instance
     *
     * @param ImageItem[] $response The getImages Response
     *
     * @throws DockerImageException
     * @return ValueCollection
     */
    private function fetchValue($response)
    {
        $images = new ValueCollection();
        $parser = new ImageItemParser();
        foreach ($response as $imageItem) {
            if ($imageItem instanceof ImageItem) {
                for ($imageIndex = 0; $imageIndex < $parser->getImageCount($imageItem); $imageIndex++) {
                    $images->add($parser->parse($imageItem, $imageIndex));
                }
            } else {
                $this->error("Unexpected object of type ".(gettype($imageItem) == "object" ? get_class($imageItem) : gettype($imageItem)));
                throw new DockerImageException("Unexpected object type");
            }
        }

        $this->cache = $images;
        return $images;
    }

    /**
     * Pull image
     *
     * Pull an image from the remote repository
     *
     * @param string $imageName The image name
     * @param string $imageTag  [optional] The image tag
     * @param boolean $throwOnError [optional] Throw an exception on response error state
     *
     * @throws DockerImageException
     * @return PullResponse
     */
    public function pullImage($imageName, $imageTag = null, $throwOnError = true)
    {
        $internalManager = $this->getImageManager();
        $imageDesignation = $imageName.(is_null($imageTag) ? "" : ":".$imageTag);

        $parameters = array(
            'fromImage' => $imageName,
            'tag' => $imageTag
        );

        try {
            $this->info("Pulling image ".$imageDesignation);
            $response = $internalManager->create(null, $parameters);
        } catch (ServerException $serverException) {
            $this->error("Error during image pulling : ".((string)$serverException));
            throw new DockerImageException("Error during image pulling", 500, $serverException);
        }

        $parser = new PullResponseParser();
        $responseObject = $parser->parse($response);

        if ($responseObject->isError()) {
            $this->error("Error during image pulling : ".implode("\n", $responseObject->getOutput(PullResponse::BOTH_OUTPUT)));
        } else {
            $this->debug("Pulling image ".$imageDesignation." with success");
            $this->clearCache();
        }

        if ($throwOnError && $responseObject->isError()) {
            throw new DockerImageException("Error during image pulling : ".implode("\n", $responseObject->getOutput(PullResponse::ERROR_OUTPUT)), 500);
        }

        return $responseObject;
    }

    /**
     * Remove image
     *
     * Remove an image from the local repository
     *
     * @param string $imageName The image name
     * @param string $imageTag  [optional] The image tag
     *
     * @throws DockerImageException
     * @return RemoveResponse
     */
    public function removeImage($imageName, $imageTag = null)
    {
        $internalManager = $this->getImageManager();
        $imageDesignation = $imageName.(is_null($imageTag) ? "" : ":".$imageTag);

        try {
            $this->notice("Removing image ".$imageDesignation);
            $response = $internalManager->remove($imageDesignation);
        } catch (ServerException $serverException) {
            $this->error("Error during image removing : ".((string)$serverException));
            throw new DockerImageException("Error during image removing", 500, $serverException);
        }

        $this->debug("Removing image ".$imageDesignation." with success");
        $this->clearCache();

        $parser = new RemoveResponseParser();
        return $parser->parse($response);
    }
}