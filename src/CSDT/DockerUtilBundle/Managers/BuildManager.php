<?php
namespace CSDT\DockerUtilBundle\Managers;

use CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager;
use Psr\Log\LoggerInterface;
use Docker\Docker;
use CSDT\DockerUtilBundle\Build\DockerfileBuilder;
use CSDT\DockerUtilBundle\Build\Dockerfile;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\DBAL\Exception\ServerException;
use CSDT\DockerUtilBundle\Exceptions\DockerImageException;
use CSDT\DockerUtilBundle\Build\BuildResponseParser;
use CSDT\DockerUtilBundle\Build\BuildResponse;

/**
 * Builder manager
 *
 * This class is used to manage the docker image building
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class BuildManager extends LoggingManager
{
    /**
     * Docker
     *
     * The docker instance
     *
     * @var Docker
     */
    private $docker;

    /**
     * Construct
     *
     * The default BuildManager constructor
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
     * @return BuildManager
     */
    protected function setDocker(Docker $docker)
    {
        $this->docker = $docker;
        return $this;
    }

    /**
     * Get dockerfile builder
     *
     * Return a new dockerfile builder instance
     *
     * @return DockerfileBuilder
     */
    public function getDockerfileBuilder()
    {
        return new DockerfileBuilder();
    }

    /**
     * Build image
     *
     * Build the given dockerfile image
     *
     * @param Dockerfile $dockerfile The dockerfile instance to build
     * @param string $imageName The builded image name
     * @param string $imageTag [optional] The builded image tag
     * @param array $options [optional] The tar creation options
     * @param array $buildArgs [optional] The docker building arguments
     * @param boolean $throwOnError [optional] Throw an exception on build failure
     *
     * @return BuildResponse
     */
    public function buildImage(
        Dockerfile $dockerfile,
        $imageName,
        $imageTag = null,
        array $options = array(),
        array $buildArgs = array(),
        $throwOnError = true
    ) {
        $archive = $dockerfile->createTar($options);
        $fileSystem = new Filesystem();

        $tarContent = file_get_contents($archive);
        $imageDesignation = $imageName . (is_null($imageTag) ? "" : ":".$imageTag);

        try {
            $this->info("Building image ".$imageDesignation);
            $result = $this->docker->getImageManager()->build(
                $tarContent,
                array(
                    't' => $imageDesignation,
                    'buildargs' => $buildArgs
                )
            );
        } catch (ServerException $serverException) {
            $fileSystem->remove($archive);
            $this->error("Error during image building : ".((string)$serverException));
            throw new DockerImageException("Error during image building", 500, $serverException);
        }

        $fileSystem->remove($archive);

        $parser = new BuildResponseParser();
        $response = $parser->parse($result);

        if ($response->isError()) {
            $this->error("Error during image building : ".implode("\n", $response->getOutput(BuildResponse::BOTH_OUTPUT)));
        } else {
            $this->debug("Building image ".$imageDesignation." with success");
        }

        if ($throwOnError && $response->isError()) {
            throw new DockerImageException("Error during image building : ".implode("\n", $response->getOutput(BuildResponse::ERROR_OUTPUT)), 500);
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager::getName()
     */
    protected function getName()
    {
        return "DockerBuilderManager";
    }


}
