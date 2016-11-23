<?php
namespace CSDT\DockerUtilBundle\Build\Commands\Abstracts;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileArray;

/**
 * Dockerfile exec form
 *
 * This class is used to design a dockerfile
 * exec form
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
abstract class DockerfileExecForm extends DockerfileArray
{
    /**
     * Command
     *
     * The command to execute
     *
     * @var string
     */
    protected $command;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param string $command The command to execute
     * @param array $parameters The command parameters
     *
     * @return void
     */
    public function __construct($command, array $parameters = array())
    {
        $this->command = $command;

        parent::__construct($parameters);
    }

    /**
     * Get command
     *
     * Return the command to execute
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set command
     *
     * Set the command to execute
     *
     * @param string $command The command
     *
     * @return DockerfileExecForm
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }
}