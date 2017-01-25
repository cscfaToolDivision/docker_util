<?php

namespace CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;

/**
 * Dockerfile value trait
 *
 * This class is used to validate the DockerfileValue class logic
 *
 * @category Test
 * @package DockerUtil
 * @author matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license MIT <https://opensource.org/licenses/MIT>
 * @link http://cscfa.fr
 */
trait DockerfileValueTrait
{
    /**
     * Storage property
     * 
     * The tested instance storage property
     * 
     * @var string
     */
    const STORAGE_PROPERTY = "value";
    
    /**
     * Value provider
     * 
     * Return a value test set
     * 
     * @return string[][]
     */
    public function valueProvider()
    {
        return array(
            array("testValue")
        );
    }
    
    /**
     * Test constructor
     * 
     * This method validate the DockerfileValue constructor
     * logic
     * 
     * @param string $value The test value
     * 
     * @dataProvider valueProvider
     * @return void
     */
    public function testConstructor($value)
    {
        $this->constructorTestMethod($this, $value);
    }
    
    /**
     * Test setValue
     * 
     * This method validate the DockerfileValue setValue
     * logic
     * 
     * @param string $value The test value
     * 
     * @dataProvider valueProvider
     * @return void
     */
    public function testSetValues($value)
    {
        $this->setValueTestMethod($this, $value);
    }
    
    /**
     * Set value test method
     * 
     * This method validate the Dockerfile setValue
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param string                 $value    The test value
     * 
     * @return void
     */
    private function setValueTestMethod(
        DockerfileTestAbstract $testCase,
        $value
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        
        $instance = $instanceReflex->newInstance();
        
        $this->setInstanceValue($instance, $valueReflex, $testCase, $value);
    }
    
    /**
     * Set instance value
     * 
     * This method validate the Dockerfile setValue
     * method logic
     * 
     * @param DockerfileValue        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param string                 $value       The test values
     * 
     * @return void
     */
    private function setInstanceValue(
        DockerfileValue $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        $value
    ) {
        $returnValue = $instance->setValues($value);
        
        $this->testValue(
            $instance,
            $valueReflex,
            $testCase,
            $value,
            $returnValue
        );
    }
    
    /**
     * Test value
     * 
     * This method validate the setted instance value
     * 
     * @param DockerfileValue        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param string                 $value       The test values
     * @param mixed                  $returnValue The returned value
     * 
     * @return void
     */
    private function testValue(
        DockerfileValue $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        $value,
        $returnValue
    ) {
        $testCase->assertSame(
            $instance,
            $returnValue,
            "The method setValue is expected to return the called instance"
        );
        
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertSame(
            $value,
            $instanceValue,
            sprintf(
                "The method setValue is expected to store the given value".
                " in %s property",
                self::STORAGE_PROPERTY
            )
        );
    }
    
    /**
     * Constructor test method
     * 
     * This method validate the DockerfileValue constructor
     * logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param string                 $value    The test values
     * 
     * @return void
     */
    private function constructorTestMethod(
        DockerfileTestAbstract $testCase,
        $value
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        
        $instance = $instanceReflex->newInstance();
        
        $stringAssert = sprintf(
            "The %s instance is expected to store the values as%%s string",
            $instanceClass
        );
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertTrue(
            is_string($instanceValue),
            sprintf($stringAssert, "")
        );
        $testCase->assertEmpty(
            $instanceValue,
            sprintf($stringAssert, " empty")
        );
        
        $instance = $instanceReflex->newInstance($value);
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertTrue(
            is_string($instanceValue),
            sprintf($stringAssert, "")
        );
        $testCase->assertNotEmpty(
            $instanceValue,
            sprintf($stringAssert, " not empty")
        );
        $testCase->assertSame(
            $value,
            $instanceValue,
            sprintf(
                "The %s instance is expected to store the given value as string",
                $instanceClass
            )
        );
        
    }
    
    /**
     * Get storage reflex
     * 
     * Return the storage property reflection of the current tested instance
     * 
     * @param \ReflectionClass       $reflex The current tested instance
     * @param DockerfileTestAbstract $test   The current test case
     * 
     * @throws \ReflectionException
     * @return \ReflectionProperty
     */
    private function getStorageReflex(
        \ReflectionClass $reflex,
        DockerfileTestAbstract $test
    ) {
        if ($reflex->hasProperty(self::STORAGE_PROPERTY)) {
            return $reflex->getProperty(self::STORAGE_PROPERTY);
        }
        
        $messageFormat = "The instance of %s must inherit DockerfileValue and ";
        $messageFormat .= "contain a %s property";
        
        throw new \ReflectionException(
            sprintf(
                $messageFormat,
                $test->getInstanceClass(),
                self::STORAGE_PROPERTY
            )
        );
    }
}
