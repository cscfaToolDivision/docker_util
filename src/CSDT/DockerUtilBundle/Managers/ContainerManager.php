<?php
namespace CSDT\DockerUtilBundle\Managers;

use CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager;
use Psr\Log\LoggerInterface;
use Docker\Docker;
use CSDT\DockerUtilBundle\Container\Container;
use Docker\Manager\ContainerManager as InternalManager;
use CSDT\DockerUtilBundle\Container\ContainerBuilder;
use Docker\Manager\ExecManager;
use Docker\Manager\ImageManager as DockerImageManager;

/**
 * Container manager
 *
 * This class is used to manage the docker containers
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ContainerManager extends LoggingManager
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
     * The default ContainerManager constructor
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
     * New container builder
     *
     * Return a new instance of container builder
     *
     * @return ContainerBuilder
     */
    public function newContainerBuilder()
    {
        $containerManager = new AdvancedContainerManager($this->docker, $this->logger);
        $containerBuilder = new ContainerBuilder($containerManager);

        return $containerBuilder;
    }

    /**
     * Get container manager
     *
     * Return the docker container manager
     *
     * @return InternalManager
     */
    protected function getContainerManager()
    {
        return $this->docker->getContainerManager();
    }

    /**
     * Get execution manager
     *
     * Return the docker execution manager
     *
     * @return ExecManager
     */
    protected function getExecutionManager()
    {
        return $this->docker->getExecManager();
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
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Managers\Abstracts\LoggingManager::getName()
     */
    protected function getName()
    {
        return "DockerContainerManager";
    }
}
