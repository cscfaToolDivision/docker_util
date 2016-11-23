<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc;

/**
 * Dockerfile add
 *
 * This class is used to design a dockerfile
 * add
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileAdd extends DockerfileAssoc
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc::__toString()
     */
    public function __toString()
    {
        return sprintf('ADD ["%s", "%s"]', $this->key, $this->value);
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