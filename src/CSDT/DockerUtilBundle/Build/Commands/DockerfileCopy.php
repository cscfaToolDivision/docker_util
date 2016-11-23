<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc;

/**
 * Dockerfile copy
 *
 * This class is used to design a dockerfile
 * copy
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileCopy extends DockerfileAssoc
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc::__toString()
     */
    public function __toString()
    {
        return sprintf('COPY ["%s", "%s"]', $this->key, $this->value);
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
