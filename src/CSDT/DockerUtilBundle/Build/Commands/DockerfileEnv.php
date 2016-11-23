<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc;

/**
 * Dockerfile environment
 *
 * This class is used to design a dockerfile
 * environment
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileEnv extends DockerfileAssoc
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc::__toString()
     */
    public function __toString()
    {
        return sprintf("ENV %s=%s", $this->key, $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 6;
    }
}
