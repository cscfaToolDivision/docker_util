<?php
namespace CSDT\DockerUtilBundle\Managers;

use CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager;
use Docker\Docker;
use Psr\Log\LoggerInterface;
use CSDT\CollectionsBundle\Collections\MapCollection;

/**
 * Docker manager
 *
 * This class is used to manage the docker instance
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerManager extends LoggingManager
{
    /**
     * Build manager store
     *
     * The build manager instance storage key
     *
     * @var integer
     */
    const STORE_BUILD_MANAGER = 1;

    /**
     * Container manager store
     *
     * The container manager instance storage key
     *
     * @var integer
     */
    const STORE_CONTAINER_MANAGER = 2;

    /**
     * Image manager store
     *
     * The image manager instance storage key
     *
     * @var integer
     */
    const STORE_IMAGE_MANAGER = 3;

    /**
     * Docker
     *
     * The docker instance
     *
     * @var Docker
     */
    private $docker;

    /**
     * Manager store
     *
     * The manager storage
     *
     * @var MapCollection
     */
    private $managerStore;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param LoggerInterface $logger [optional] The logger interface
     * @param Docker $docker [optional] The docker instance
     *
     * @return void
     */
    public function __construct(LoggerInterface $logger = null, Docker $docker = null)
    {
        $this->initDocker($docker);

        if (!is_null($logger)) {
            parent::setLogger($logger);
        }

        $this->managerStore = new MapCollection();

        $this->managerStore->set(
            self::STORE_IMAGE_MANAGER,
            new ImageManager($this->docker, $this->logger)
        );

        $this->managerStore->set(
            self::STORE_CONTAINER_MANAGER,
            new ContainerManager($this->docker, $this->logger)
        );

        $this->managerStore->set(
            self::STORE_BUILD_MANAGER,
            new BuildManager($this->docker, $this->logger)
        );
    }

    /**
     * Get image manager
     *
     * Return the ImageManager instance
     *
     * @return ImageManager
     */
    public function getImageManager()
    {
        return $this->getStore(self::STORE_IMAGE_MANAGER);
    }

    /**
     * Get container manager
     *
     * Return the ContainerManager instance
     *
     * @return ContainerManager
     */
    public function getContainerManager()
    {
        return $this->getStore(self::STORE_CONTAINER_MANAGER);
    }

    /**
     * Get build manager
     *
     * Return the BuildManager instance
     *
     * @return BuildManager
     */
    public function getBuildManager()
    {
        return $this->getStore(self::STORE_BUILD_MANAGER);
    }

    /**
     * Get store
     *
     * Return a stored element
     *
     * @param integer $storageKey The storage key
     *
     * @throws \LogicException
     * @return mixed|NULL
     */
    protected function getStore($storageKey)
    {
        if ($this->managerStore->has($storageKey)) {
            return $this->managerStore->get($storageKey);
        }

        $this->critical("Manager not stored into docker manager");
        throw new \LogicException("Manager not stored");
    }

    /**
     * Init docker
     *
     * Initialize the internal docker instance
     *
     * @param Docker $docker [optional] The docker instance
     *
     * @return void
     */
    protected function initDocker(Docker $docker = null)
    {
        $this->docker = $docker;

        if (is_null($this->docker)) {
            $this->docker = new Docker();
        }
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager::getName()
     */
    protected function getName()
    {
        return "DockerManager";
    }
}