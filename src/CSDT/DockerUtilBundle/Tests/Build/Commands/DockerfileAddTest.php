<?php

namespace CSDT\DockerUtilBundle\Tests\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\DockerfileAdd;
use CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract;

/**
 * Dockerfile add test
 *
 * This class is used to validate the DockerfileAdd class logic
 *
 * @category Test
 * @package DockerUtil
 * @author matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license MIT <https://opensource.org/licenses/MIT>
 * @link http://cscfa.fr
 */
class DockerfileAddTest
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts\DockerfileTestAbstract::getInstanceClass()
     */
    protected function getInstanceClass()
    {
        return DockerfileAdd::class;
    }

}
