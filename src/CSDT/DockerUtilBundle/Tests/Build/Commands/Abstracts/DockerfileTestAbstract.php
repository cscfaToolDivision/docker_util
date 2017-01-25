<?php

namespace CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts;

use PHPUnit\Framework\TestCase;
use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile test abstract
 *
 * This abstract class is used to validate the 
 * Dockerfile class logic
 *
 * @category Test
 * @package DockerUtil
 * @author matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license MIT <https://opensource.org/licenses/MIT>
 * @link http://cscfa.fr
 */
abstract class DockerfileTestAbstract extends TestCase
{
    /**
     * Get instance class
     * 
     * Return the current tested instance class name
     * 
     * @return string
     */
    abstract public function getInstanceClass();
    
    /**
     * Get instance priority
     * 
     * Return the current tested instance priority
     * 
     * @return integer
     */
    abstract public function getInstancePriority();
    
    /**
     * Get string
     * 
     * Return the current tested instance string representation
     * 
     * @return string
     */
    abstract public function getString();
    
    /**
     * Get string instance
     * 
     * Return the current tested instance for string representation
     * 
     * @return DockerfileCommand
     */
    abstract public function getStringInstance();
    
    /**
     * Test priority
     * 
     * This method validate the getPriority method
     * 
     * @return void
     */
    public function testPriority()
    {
        $instanceClass = $this->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $instance = $instanceReflex->newInstanceWithoutConstructor();
        
        $this->priorityTestMethod($instance);
    }
    
    /**
     * Priority test method
     * 
     * This method validate the getPriority method
     * 
     * @param DockerfileCommand $instance The tested instance
     * 
     * @return void
     */
    private function priorityTestMethod(DockerfileCommand $instance)
    {
        $returnedPriority = $instance->getPriority();
        
        $this->assertEquals(
            $this->getInstancePriority(),
            $returnedPriority,
            sprintf(
                "The %s instance is expected to return %d as priority",
                $this->getInstanceClass(),
                $this->getInstancePriority()
            )
        );
    }
    
    /**
     * Test to string
     * 
     * This method validate the toString method
     * 
     * @return void
     */
    public function testToString()
    {
        $instance = $this->getStringInstance();
        
        $this->assertEquals(
            $this->getString(),
            $instance->__toString(),
            sprintf(
                "The %s instance is expected to be string represented by %s",
                $this->getInstanceClass(),
                $this->getString()
            )
        );
    }
}
