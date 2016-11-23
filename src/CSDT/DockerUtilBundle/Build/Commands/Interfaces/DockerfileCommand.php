<?php
namespace CSDT\DockerUtilBundle\Build\Commands\Interfaces;

/**
 * Dockerfile command
 *
 * This interface is used to define the dockerfile
 * command methods
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
interface DockerfileCommand
{
    /**
     * To string
     *
     * Return the string representation of the object
     *
     * @return string
     */
    public function __toString();

    /**
     * Get priority
     *
     * Return the command execution priority
     *
     * @return integer
     */
    public function getPriority();
}
