<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;

/**
 * Dockerfile shell entrypoint
 *
 * This class is used to design a dockerfile
 * shell entrypoint
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileShellEntrypoint extends DockerfileValue
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        return sprintf("ENTRYPOINT %s", $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 20;
    }
}
