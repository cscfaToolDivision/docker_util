<?php
namespace CSDT\DockerUtilBundle\IO;

use CSDT\DockerUtilBundle\Build\Commands\DockerfileExecCMD;
use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;

class ExecuteIOResult
{
    /**
     * Command
     *
     * The executed command
     *
     * @var DockerfileExecCMD
     */
    private $command;

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
     * The execution container id
     *
     * @var string
     */
    private $containerId;

    /**
     * Raws
     *
     * The response raws
     *
     * @var array
     */
    private $raws;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param DockerfileExecCMD $command The executed command
     * @param AdvancedContainerManager $manager The container manager
     * @param string $containerId The execution container id
     * @param array $raws The response raw
     *
     * @return void
     */
    public function __construct(DockerfileExecCMD $command, AdvancedContainerManager $manager, $containerId, array $raws = array())
    {
        $this->command = $command;
        $this->manager = $manager;
        $this->containerId = $containerId;
        $this->raws = $raws;
    }

    /**
     * Get raws
     *
     * Return the execution result raws
     *
     * @return string[]
     */
    public function getRaws()
    {
        return $this->raws;
    }

    /**
     * Rerun
     *
     * Re-execute the command on the container
     *
     * @return void
     */
    public function reRun()
    {
        $response = $this->manager->execute($this->containerId, $this->getInputRaw());

        $this->raws = $this->parseOutput($response);
    }

    /**
     * Parse output
     *
     * Return the parsed raws from the output
     *
     * @param string $output The command execution output
     *
     * @return string[]
     */
    protected function parseOutput($output)
    {
        $delimiter = "\r\n";
        $token = strtok($output, $delimiter);

        $raws = array();
        while ($token !== false) {

            $token = trim($token);
            if (!empty($token)) {
                array_push($raws, $token);
            }

            $token = strtok($delimiter);
        }

        return $raws;
    }

    /**
     * Get input raw
     *
     * Return the input array of the command
     *
     * @return string[]
     */
    protected function getInputRaw()
    {
        $commands = array($this->command->getCommand());
        return array_merge($commands, $this->command->getValues());
    }

    /**
     * To string
     *
     * The default to string method
     *
     * @return string
     */
    public function __toString()
    {
        $input = implode(" ", array_merge(array('Command "'), $this->getInputRaw(), array('" :')));

        return implode("\n", array_merge(array($input), $this->raws));
    }
}
