<?php
namespace CSDT\DockerUtilBundle\Managers;

use CSDT\DockerUtilBundle\Managers\ContainerManager;
use CSDT\DockerUtilBundle\Exceptions\DockerContainerException;
use Docker\API\Model\ContainerConfig;
use Docker\API\Model\ExecStartConfig;
use Docker\API\Model\ExecConfig;
use Psr\Http\Message\ResponseInterface;
use Docker\API\Model\Container;
use Docker\API\Model\ContainerCreateResult;
use Http\Client\Common\Exception\ServerErrorException;
use Docker\Manager\ContainerManager as InternalManager;
use Docker\Stream\AttachWebsocketStream;
use Docker\API\Model\CommitResult;

/**
 * Advanced container manager
 *
 * This class is used to manage the docker containers execution states
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class AdvancedContainerManager extends ContainerManager
{

    /**
     * Create container
     *
     * Create a container using the given configuration
     *
     * @param ContainerConfig $configuration The container configuration
     *
     * @throws DockerContainerException
     * @return ContainerCreateResult
     */
    public function createContainer(ContainerConfig $configuration)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Creating container for image : ".$configuration->getImage());
            $response = $internalManager->create($configuration);
        } catch (ServerException $serverException) {
            $this->error("Error during container creation : ".((string)$serverException));
            throw new DockerContainerException("Error during container creation", 500, $serverException);
        }

        if (!($response instanceof ContainerCreateResult)) {
            throw new DockerContainerException("Error during container creation. Unexpected response type.", 500);
        }

        return $response;
    }

    /**
     * Start
     *
     * Start a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function startContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Starting container ".$id);
            $response = $internalManager->start($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container starting : ".((string)$serverException));
            throw new DockerContainerException("Error during container starting", 500, $serverException);
        }

        if (
            $response instanceof ResponseInterface &&
            !($response->getStatusCode() != 204 || $response->getStatusCode() != 304)
        ) {
            throw new DockerContainerException("Error during container starting : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container starting. Unexpected response type.", 500);
        }
    }

    /**
     * Stop
     *
     * Stop a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function stopContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Stoping container ".$id);
            $response = $internalManager->stop($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container stop : ".((string)$serverException));
            throw new DockerContainerException("Error during container stop", 500, $serverException);
        }

        if (
            $response instanceof ResponseInterface &&
            !($response->getStatusCode() != 204 || $response->getStatusCode() != 304)
        ) {
            throw new DockerContainerException("Error during container stop : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container stop. Unexpected response type.", 500);
        }
    }

    /**
     * Restart
     *
     * Restart a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function restartContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Restarting container ".$id);
            $response = $internalManager->restart($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container restarting : ".((string)$serverException));
            throw new DockerContainerException("Error during container restarting", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 204) {
            throw new DockerContainerException("Error during container restarting : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container restarting. Unexpected response type.", 500);
        }
    }

    /**
     * Kill
     *
     * Kill a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function killContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Killing container ".$id);
            $response = $internalManager->kill($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container killing : ".((string)$serverException));
            throw new DockerContainerException("Error during container killing", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 204) {
            throw new DockerContainerException("Error during container killing : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container killing. Unexpected response type.", 500);
        }
    }

    /**
     * Pause
     *
     * Pause a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function pauseContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Pause container ".$id);
            $response = $internalManager->pause($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container pause : ".((string)$serverException));
            throw new DockerContainerException("Error during container pause", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 204) {
            throw new DockerContainerException("Error during container pause : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container pause. Unexpected response type.", 500);
        }
    }

    /**
     * Unpause
     *
     * Unpause a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function unpauseContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Unpause container ".$id);
            $response = $internalManager->unpause($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container unpause : ".((string)$serverException));
            throw new DockerContainerException("Error during container unpause", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 204) {
            throw new DockerContainerException("Error during container unpause : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container unpause. Unexpected response type.", 500);
        }
    }

    /**
     * Wait
     *
     * Wait a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function waitContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Waiting container ".$id);
            $response = $internalManager->wait($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container waiting : ".((string)$serverException));
            throw new DockerContainerException("Error during container waiting", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 200) {
            throw new DockerContainerException("Error during container waiting : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container waiting. Unexpected response type.", 500);
        }
    }

    /**
     * Remove
     *
     * Remove a container instance
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return void
     */
    public function removeContainer($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->info("Removing container ".$id);
            $response = $internalManager->remove($id, array(), InternalManager::FETCH_RESPONSE);
        } catch (ServerException $serverException) {
            $this->error("Error during container removing : ".((string)$serverException));
            throw new DockerContainerException("Error during container removing", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 204) {
            throw new DockerContainerException("Error during container removing : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during container removing. Unexpected response type.", 500);
        }
    }

    /**
     * Execute
     *
     * Execute a command into a container
     *
     * @param string $id The container id
     * @param array $cmd The command to execute
     *
     * @throws DockerContainerException
     * @return ResponseInterface
     */
    public function execute($id, array $cmd)
    {
        $execConfig = new ExecConfig();
        $execConfig->setAttachStderr(true);
        $execConfig->setAttachStdin(true);
        $execConfig->setAttachStdout(true);
        $execConfig->setTty(true);
        $execConfig->setCmd($cmd);

        $execStartConfig = new ExecStartConfig();
        $execStartConfig->setDetach(false);
        $execStartConfig->setTty(true);

        try {
            $this->info("Execution command '".implode(" ", $cmd)."' on container ".$id);
            $execCreateResult = $this->getExecutionManager()->create($id, $execConfig);
        } catch (ServerErrorException $serverException) {
            $this->error("Error during execution creation : ".((string)$serverException));
            throw new DockerContainerException("Error during execution creation", 500, $serverException);
        }

        try {
            $response = $this->getExecutionManager()->start($execCreateResult->getId(), $execStartConfig);
        } catch (ServerErrorException $serverException) {
            $this->error("Error during execution : ".((string)$serverException));
            throw new DockerContainerException("Error during execution", 500, $serverException);
        }

        if ($response instanceof ResponseInterface && $response->getStatusCode() != 200) {
            throw new DockerContainerException("Error during command execution : (".$response->getStatusCode().")".$response->getBody()->getContents(), 500);
        } else if (!($response instanceof ResponseInterface)) {
            throw new DockerContainerException("Error during command execution. Unexpected response type.", 500);
        }

        $this->info("Execution success on container ".$id);
        return trim($response->getBody()->getContents());
    }

    /**
     * Inspect
     *
     * Return the informations about a given container
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return DockerContainer
     */
    public function inspect($id)
    {
        $internalManager = $this->getContainerManager();

        try {
            $this->debug("Inspecting container ".$id);
            $response = $internalManager->find($id);
        } catch (ServerException $serverException) {
            $this->error("Error during container inspection : ".((string)$serverException));
            throw new DockerContainerException("Error during container inspection", 500, $serverException);
        }

        if (!($response instanceof Container)) {
            throw new DockerContainerException("Error during container inspection. Unexpected response type.", 500);
        }

        return $response;
    }

    /**
     * Get websocket
     *
     * Return a container websocket
     *
     * @param string $id The container id
     *
     * @throws DockerContainerException
     * @return AttachWebsocketStream
     */
    public function getWebsocket($id)
    {
        $internalManager = $this->getContainerManager();

        $parameters = array(
            'logs' => false,
            'stream' => true,
            'stdin' => true,
            'stderr' => true,
            'stdout' => true
        );

        try {
            $this->debug("Attaching websocket to container ".$id);
            $websocket = $internalManager->attachWebsocket($id, $parameters);
        } catch (ServerErrorException $serverException) {
            $this->error("Error during websocket attachment : ".((string)$serverException));
            throw new DockerContainerException("Error during websocket attachment", 500, $serverException);
        }

        if (!($websocket instanceof AttachWebsocketStream)) {
            throw new DockerContainerException("Error during websocket attachment. Unexpected response type.", 500);
        }

        return $websocket;
    }

    /**
     * Commint container
     *
     * Commit a container to create new tagged
     * image into the local repository
     *
     * @param ContainerConfig $containerConfig The container configuration
     * @param string $containerId The container id
     * @param string $newName The new image name
     * @param string $newTag The new image tag
     *
     * @return CommitResult
     */
    public function commitContainer(ContainerConfig $containerConfig, $containerId, $newName, $newTag)
    {
        $internalManager = $this->getImageManager();

        $parameters = array(
            'container'=>$containerId,
            'repo'=>$newName,
            'tag'=>$newTag
        );

        try {
            $this->info("Commiting container ".$containerId);
            $result = $internalManager->commit($containerConfig, $parameters);
        } catch (ServerErrorException $serverException) {
            $this->error("Error during container commit : ".((string)$serverException));
            throw new DockerContainerException("Error during container commit", 500, $serverException);
        }

        if (!($result instanceof CommitResult)) {
            throw new DockerContainerException("Error during container commit. Unexpected response type.", 500);
        }

        $this->info("Commiting container ".$containerId." done");

        return $result;
    }
}
