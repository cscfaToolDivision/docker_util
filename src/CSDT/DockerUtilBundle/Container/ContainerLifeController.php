<?php
namespace CSDT\DockerUtilBundle\Container;

use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;

/**
 * Container
 *
 * This class is used to manage docker containers lifecycle
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ContainerLifeController
{
    /**
     * Manager
     *
     * The container manager
     *
     * @var AdvancedContainerManager
     */
    private $manager;

    /**
     * Container informations
     *
     * The current ContainerInformations instance
     *
     * @var ContainerInformations
     */
    private $containerIformations;

    /**
     * Container id
     *
     * The managed container id
     *
     * @var string
     */
    private $containerId;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param Container $manager The container to manage
     *
     * @return void
     */
    public function __construct(AdvancedContainerManager $manager, ContainerInformations $containerInfo)
    {
        $this->manager = $manager;
        $this->containerIformations = $containerInfo;

        $this->containerId = $this->containerIformations->getInformations()->getId();
    }

    /**
     * Execute action
     *
     * Execute an action and reinspect the container
     *
     * @param callable $method The method to execute
     * @param boolean $removing The removing state
     *
     * @return void
     */
    protected function executeAction(Callable $method, $removing = false)
    {
        $method($this->containerId);

        if ($removing) {
            $this->containerIformations->setRemoved();
            return;
        }
        $this->containerIformations->inspect();
    }

    /**
     * Start
     *
     * Start the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function start()
    {
        $this->executeAction(array($this->manager, "startContainer"));
    }

    /**
     * Stop
     *
     * Stop the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function stop()
    {
        $this->executeAction(array($this->manager, "stopContainer"));
    }

    /**
     * Restart
     *
     * Restart the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function restart()
    {
        $this->executeAction(array($this->manager, "restartContainer"));
    }

    /**
     * Kill
     *
     * Kill the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function kill()
    {
        $this->executeAction(array($this->manager, "killContainer"));
    }

    /**
     * Pause
     *
     * Pause the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function pause()
    {
        $this->executeAction(array($this->manager, "pauseContainer"));
    }

    /**
     * Unpause
     *
     * Unpause the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function unpause()
    {
        $this->executeAction(array($this->manager, "unpauseContainer"));
    }

    /**
     * Wait
     *
     * Wait the container to finish
     *
     * @throws DockerContainerException
     * @return void
     */
    public function wait()
    {
        $this->executeAction(array($this->manager, "waitContainer"));
    }

    /**
     * Remove
     *
     * Remove the container
     *
     * @throws DockerContainerException
     * @return void
     */
    public function remove()
    {
        $this->executeAction(array($this->manager, "removeContainer"), true);
    }
}
