<?php
namespace CSDT\DockerUtilBundle\Container;

use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileExecCMD;
use CSDT\DockerUtilBundle\IO\ExecuteIOResult;
use CSDT\DockerUtilBundle\IO\Websocket;

/**
 * Container
 *
 * This class is used to manage the docker containers input/output
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ContainerIOController
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
    public function __construct(AdvancedContainerManager $manager, $containerId)
    {
        $this->manager = $manager;
        $this->containerId = $containerId;
    }

    /**
     * Execute
     *
     * Execute a command into the container
     *
     * @param DockerfileExecCMD $command The command to execute
     *
     * @return ExecuteIOResult
     */
    public function execute(DockerfileExecCMD $command)
    {
        $execution = new ExecuteIOResult($command, $this->manager, $this->containerId);
        $execution->reRun();

        return $execution;
    }

    /**
     * Get websocket
     *
     * Return a container websocket
     *
     * @return Websocket
     */
    public function getWebsocket()
    {
        $websocket = $this->manager->getWebsocket($this->containerId);

        return new Websocket($websocket);
    }
}
