<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;

/**
 * Dockerfile stop signal
 *
 * This class is used to design a dockerfile
 * stop signal
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileStopsignal extends DockerfileValue
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        return sprintf('STOPSIGNAL %s', $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 22;
    }
}
