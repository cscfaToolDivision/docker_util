<?php
namespace CSDT\DockerUtilBundle\Container;

use Docker\API\Model\ContainerConfig;
use Docker\API\Model\HostConfig;
use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;
use Docker\API\Model\ContainerCreateResult;
use CSDT\DockerUtilBundle\Container\ContainerLifeController;
use Docker\API\Model\NetworkingConfig;

/**
 * Container
 *
 * This class is used to store the docker containers items
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class Container
{
    /**
     * Stopped
     *
     * Indicate a stopped state
     *
     * @var integer
     */
    const STOPPED = 0;

    /**
     * Running
     *
     * Indicate a running state
     *
     * @var integer
     */
    const RUNNING = 1;

    /**
     * Restarting
     *
     * Indicate a restarting state
     *
     * @var integer
     */
    const RESTARTING = 2;

    /**
     * Paused
     *
     * Indicate a paused state
     *
     * @var integer
     */
    const PAUSED = 3;

    /**
     * Configuration
     *
     * The container configuration
     *
     * @var ContainerConfig
     */
    private $configuration;

    /**
     * Manager
     *
     * The container manager
     *
     * @var AdvancedContainerManager
     */
    private $manager;

    /**
     * Informations
     *
     * The container internal informations
     *
     * @var ContainerInformations
     */
    private $informations;

    /**
     * Life cycle controller
     *
     * The container life cycle controller
     *
     * @var ContainerLifeController
     */
    private $lifeCycleController;

    /**
     * I/O controller
     *
     * The Input/Output controller
     *
     * @var ContainerIOController
     */
    private $IOController;

    /**
     * Construct
     *
     * The defualt class constructor
     *
     * @param AdvancedContainerManager $manager The container manager
     * @param ContainerConfig $configuration The container configuration
     *
     * @return void
     */
    public function __construct(AdvancedContainerManager $manager, ContainerConfig $configuration)
    {
        $this->setConfiguration($configuration);

        $this->manager = $manager;

        $creationResult = $this->create();

        $this->informations = new ContainerInformations(
            $creationResult->getId(),
            $manager
        );

        $this->lifeCycleController = new ContainerLifeController($manager, $this->informations);

        $this->IOController = new ContainerIOController($manager, $this->getId());
    }

    /**
     * Set configuration
     *
     * Set and init the container configuration
     *
     * @param ContainerConfig $config The container configuration
     *
     * @return void
     */
    protected function setConfiguration(ContainerConfig $config = null)
    {
        $this->configuration = $config;

        if (is_null($this->configuration)) {
            $this->configuration = new ContainerConfig();
        }

        if (is_null($this->configuration->getHostConfig())) {
            $this->configuration->setHostConfig(new HostConfig());
        }

        if (is_null($this->configuration->getNetworkingConfig())) {
            $this->configuration->setNetworkingConfig(new NetworkingConfig());
        }

        if (is_null($this->configuration->getNetworkingConfig()->getEndpointsConfig())) {
            $this->configuration->getNetworkingConfig()->setEndpointsConfig(new \ArrayObject(array()));
        }
    }

    /**
     * Create
     *
     * Create the container
     *
     * @return ContainerCreateResult
     */
    protected function create()
    {
        return $this->manager->createContainer($this->configuration);
    }

    /**
     * Get id
     *
     * Return the container id
     *
     * @return string
     */
    public function getId()
    {
        return $this->informations->getInformations()->getId();
    }

    /**
     * Get lifecycle
     *
     * Return the container licycle controller
     *
     * @return ContainerLifeController
     */
    public function getLifecycle()
    {
        return $this->lifeCycleController;
    }

    /**
     * Get I/O
     *
     * Return the Input/Output controller of the container
     *
     * @return ContainerIOController
     */
    public function getIO()
    {
        return $this->IOController;
    }

    /**
     * Get state
     *
     * Return the container execution state as
     * constant
     *
     * @return integer
     */
    public function getState()
    {
        $this->informations->inspect();

        $state = $this->informations->getInformations()->getState();

        if ($state->getPaused()) {
            return self::PAUSED;
        } else if ($state->getRestarting()) {
            return self::RESTARTING;
        } else if ($state->getRunning()) {
            return self::RUNNING;
        }

        return self::STOPPED;
    }

    /**
     * Commit
     *
     * Commit the container to create new tagged
     * image into the local repository
     *
     * @param string $newName The new image name and version
     * @param string $newTag The new image tag
     *
     * @return void
     */
    public function commit($newName, $newTag)
    {
        $this->manager->commitContainer($this->configuration, $this->getId(), $newName, $newTag);
    }

}
