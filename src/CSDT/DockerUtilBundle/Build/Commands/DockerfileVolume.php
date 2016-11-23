<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileArray;

/**
 * Dockerfile volume
 *
 * This class is used to design a dockerfile
 * volume
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileVolume extends DockerfileArray
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileArray::__toString()
     */
    public function __toString()
    {
        $volumes = array();
        foreach ($this->values as $value) {
            $volumes[] = sprintf('"%s"', $value);
        }

        return sprintf("VOLUME [%s]", implode(", ", $volumes));
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 18;
    }
}
