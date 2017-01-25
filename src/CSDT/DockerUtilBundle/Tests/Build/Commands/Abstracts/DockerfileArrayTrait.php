<?php

namespace CSDT\DockerUtilBundle\Tests\Build\Commands\Abstracts;

use PHPUnit\Framework\TestCase;
use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileArray;

/**
 * Dockerfile array trait
 *
 * This class is used to validate the DockerfileArray class logic
 *
 * @category Test
 * @package DockerUtil
 * @author matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license MIT <https://opensource.org/licenses/MIT>
 * @link http://cscfa.fr
 */
trait DockerfileArrayTrait
{
    /**
     * Get storage property
     *
     * Return the tested instance storage property
     *
     * @return string
     */
    protected function getStorageProperty()
    {
        return "values";
    }
    
    /**
     * Value provider
     * 
     * Return a value test set
     * 
     * @return string[][][]
     */
    public function valueProvider()
    {
        return array(
            array(
                array(
                    "testValue1",
                    "testValue2",
                    "testValue3"
                )
            )
        );
    }
    
    /**
     * Test constructor
     * 
     * This method validate the DockerfileArray constructor
     * logic
     * 
     * @param array $values The test set values
     * 
     * @dataProvider valueProvider
     * @return void
     */
    public function testConstructor(array $values)
    {
        $this->constructorTestMethod($this, $values);
    }
    
    /**
     * Test setValue
     * 
     * This method validate the DockerfileArray setValue
     * logic
     * 
     * @param array $values The test set values
     * 
     * @dataProvider valueProvider
     * @return void
     */
    public function testSetValues(array $values)
    {
        $this->setValueTestMethod($this, $values);
    }

    /**
     * Test addValue
     *
     * This method validate the DockerfileArray addValue
     * logic
     *
     * @param array $values The test set values
     *
     * @dataProvider valueProvider
     * @return void
     */
    public function testAddValue(array $values)
    {
        $this->addValueTestMethod($this, $values);
    }

    /**
     * Test hasValue
     *
     * This method validate the DockerfileArray hasValue
     * logic
     *
     * @param array $values The test set values
     *
     * @dataProvider valueProvider
     * @return void
     */
    public function testHasValue(array $values)
    {
        $this->hasValueTestMethod($this, $values);
    }

    /**
     * Test getValueIndex
     *
     * This method validate the DockerfileArray getValueIndex
     * logic
     *
     * @param array $values The test set values
     *
     * @dataProvider valueProvider
     * @return void
     */
    public function testGetValueIndex(array $values)
    {
        $this->getValueIndexTestMethod($this, $values);
    }

    /**
     * Test removeValue
     *
     * This method validate the DockerfileArray removeValue
     * logic
     *
     * @param array $values The test set values
     *
     * @dataProvider valueProvider
     * @return void
     */
    public function testRemoveValue(array $values)
    {
        $this->removeValueTestMethod($this, $values);
    }
    
    /**
     * Remove value test method
     * 
     * This method validate the Dockerfile removeValue
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set value
     * 
     * @return void
     */
    private function removeValueTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        $valueReflex->setValue($instance, $values);
        
        $this->removeInstanceValue($instance, $valueReflex, $testCase, $values);
    }
    
    /**
     * remove instance value
     * 
     * This method validate the Dockerfile removeValue
     * method logic
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * 
     * @return void
     */
    private function removeInstanceValue(
        DockerfileArray $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $currentValues = $values;
        foreach ($values as $value) {
            $returnValue = $instance->removeValue($value);
            array_shift($currentValues);

            $testCase->assertSame(
                $instance,
                $returnValue,
                "The method removeValue is expected to return the called instance"
            );
            
            $testCase->assertEquals(
                $currentValues,
                $valueReflex->getValue($instance),
                "The removeValue method is expected to remove ".
                "the stored value"
            );
        }
    }
    
    /**
     * Get value index test method
     * 
     * This method validate the Dockerfile getValueIndex
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set value
     * 
     * @return void
     */
    private function getValueIndexTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        $valueReflex->setValue($instance, $values);
        
        $this->getInstanceValueIndex($instance, $testCase, $values);
    }
    
    /**
     * get instance value index
     * 
     * This method validate the Dockerfile getValueIndex
     * method logic
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * 
     * @return void
     */
    private function getInstanceValueIndex(
        DockerfileArray $instance,
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        foreach ($values as $index=>$value) {
            $returnValue = $instance->getValueIndex($index);
            
            $testCase->assertEquals(
                $returnValue,
                $value,
                "The getValueIndex method is expected to return ".
                "the stored value index"
            );
        }
    }
    
    /**
     * Has value test method
     * 
     * This method validate the Dockerfile hasValue
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set value
     * 
     * @return void
     */
    private function hasValueTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        $valueReflex->setValue($instance, $values);
        
        $this->hasInstanceValue($instance, $testCase, $values);
    }
    
    /**
     * Has instance value
     * 
     * This method validate the Dockerfile hasValue
     * method logic
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * 
     * @return void
     */
    private function hasInstanceValue(
        DockerfileArray $instance,
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        foreach ($values as $value) {
            $returnValue = $instance->hasValue($value);
            
            $testCase->assertTrue(
                $returnValue,
                "The hasValue method is expected to return true"
            );

            $returnValue = $instance->hasValue($value.uniqid());
            
            $testCase->assertFalse(
                $returnValue,
                "The hasValue method is expected to return false"
            );
        }
    }
    
    /**
     * Add value test method
     * 
     * This method validate the Dockerfile addValue
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set value
     * 
     * @return void
     */
    private function addValueTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        
        $this->addInstanceValue($instance, $valueReflex, $testCase, $values);
    }
    
    /**
     * Add instance value
     * 
     * This method validate the Dockerfile addValue
     * method logic
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * 
     * @return void
     */
    private function addInstanceValue(
        DockerfileArray $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $currentValues = array();
        
        foreach ($values as $value) {
            $returnValue = $instance->addValue($values);
            array_push($currentValues, $value);
            
            $this->testValue(
                $instance,
                $valueReflex,
                $testCase,
                $currentValues,
                $returnValue
            );
        }
    }
    
    /**
     * Set value test method
     * 
     * This method validate the Dockerfile setValue
     * method logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set value
     * 
     * @return void
     */
    private function setValueTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        
        $this->setInstanceValue($instance, $valueReflex, $testCase, $values);
    }
    
    /**
     * Set instance value
     * 
     * This method validate the Dockerfile setValue
     * method logic
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * 
     * @return void
     */
    private function setInstanceValue(
        DockerfileArray $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $returnValue = $instance->setValues($values);
        
        $this->testValue(
            $instance,
            $valueReflex,
            $testCase,
            $values,
            $returnValue
        );
    }
    
    /**
     * Test value
     * 
     * This method validate the setted instance value
     * 
     * @param DockerfileArray        $instance    The tested instance
     * @param \ReflectionProperty    $valueReflex The value reflection
     * @param DockerfileTestAbstract $testCase    The current test case
     * @param array                  $values      The test set values
     * @param mixed                  $returnValue The returned value
     * 
     * @return void
     */
    private function testValue(
        DockerfileArray $instance,
        \ReflectionProperty $valueReflex,
        DockerfileTestAbstract $testCase,
        array $values,
        $returnValue
    ) {
        $testCase->assertSame(
            $instance,
            $returnValue,
            "The method setValue is expected to return the called instance"
        );
        
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertSame(
            $values,
            $instanceValue,
            sprintf(
                "The method setValue is expected to store the given values".
                " in %s property",
                $this->getStorageProperty()
            )
        );
    }
    
    /**
     * Constructor test method
     * 
     * This method validate the DockerfileArray constructor
     * logic
     * 
     * @param DockerfileTestAbstract $testCase The current test case
     * @param array                  $values   The test set values
     * 
     * @return void
     */
    private function constructorTestMethod(
        DockerfileTestAbstract $testCase,
        array $values
    ) {
        $instanceClass = $testCase->getInstanceClass();
        $instanceReflex = new \ReflectionClass($instanceClass);
        
        $valueReflex = $this->getStorageReflex($instanceReflex, $testCase);
        $valueReflex->setAccessible(true);
        
        $instance = $instanceReflex->newInstance();
        
        $arrayAssert = sprintf(
            "The %s instance is expected to store the values as%%s array",
            $instanceClass
        );
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertTrue(
            is_array($instanceValue),
            sprintf($arrayAssert, "")
        );
        $testCase->assertEmpty(
            $instanceValue,
            sprintf($arrayAssert, " empty")
        );
        
        $instance = $instanceReflex->newInstance($values);
        $instanceValue = $valueReflex->getValue($instance);
        $testCase->assertTrue(
            is_array($instanceValue),
            sprintf($arrayAssert, "")
        );
        $testCase->assertNotEmpty(
            $instanceValue,
            sprintf($arrayAssert, " not empty")
        );
        $testCase->assertSame(
            $values,
            $instanceValue,
            sprintf(
                "The %s instance is expected to store the given values as array",
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
        if ($reflex->hasProperty($this->getStorageProperty())) {
            return $reflex->getProperty($this->getStorageProperty());
        }
        
        $messageFormat = "The instance of %s must inherit DockerfileArray and ";
        $messageFormat .= "contain a %s property";
        
        throw new \ReflectionException(
            sprintf(
                $messageFormat,
                $test->getInstanceClass(),
                $this->getStorageProperty()
            )
        );
    }
    
}
