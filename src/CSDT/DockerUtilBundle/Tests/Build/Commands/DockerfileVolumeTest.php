<?php

namespace CSDT\DockerUtilBundle\Tests\Build\Commands;

use CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileVolume;
use CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileArrayTrait;

/**
 * Dockerfile volume test
 *
 * This class is used to validate the dockerfile
 * volume logic
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileVolumeTest extends DockerfileTestAbstract
{
    use DockerfileArrayTrait;
    
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract::getInstanceClass()
     */
    public function getInstanceClass()
    {
        return DockerfileVolume::class;
    }
    
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract::getInstancePriority()
     */
    public function getInstancePriority()
    {
        return 18;
    }
    
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract::getString()
     */
    public function getString()
    {
        return "VOLUME [\"test\"]";
    }
    
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract::getStringInstance()
     */
    public function getStringInstance()
    {
        return new DockerfileVolume(array("test"));
    }
}
