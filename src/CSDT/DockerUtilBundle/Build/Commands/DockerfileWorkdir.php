<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;

/**
 * Dockerfile workdir
 *
 * This class is used to design a dockerfile
 * workdir
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileWorkdir extends DockerfileValue
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        return sprintf("WORKDIR %s", $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 10;
    }
}
