<?php
namespace CSDT\DockerUtilBundle\Build\Commands\Abstracts;

use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile array
 *
 * This class is used to design a dockerfile
 * array
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
abstract class DockerfileArray implements DockerfileCommand
{
    /**
     * Values
     *
     * The values
     *
     * @var array
     */
    protected $values;

    /**
     * Construct
     *
     * The default DockerfileArray construct
     *
     * @param array $values The values
     *
     * @return void
     */
    public function __construct(array $values = array())
    {
        $this->values = $values;
    }

    /**
     * Get values
     *
     * Return the values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Set values
     *
     * Set the values
     *
     * @param array $values The values
     *
     * @return DockerfileArray
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Add value
     *
     * Add a value to the current value set
     *
     * @param string $value The value to add
     *
     * @return DockerfileArray
     */
    public function addValue($value)
    {
        if (!$this->hasValue($value)) {
            array_push($this->values, $value);
        }

        return $this;
    }

    /**
     * Has value
     *
     * Validate the existance of a value
     *
     * @param string $value The value to validate
     *
     * @return boolean
     */
    public function hasValue($value)
    {
        return boolval($this->getValueIndex($value) !== false);
    }

    /**
     * Get value index
     *
     * Return the current value index
     *
     * @param string $value The value to search
     *
     * @return integer|false
     */
    public function getValueIndex($value)
    {
        return array_search($value, $this->values);
    }

    /**
     * Remove value
     *
     * Remove a value from the current value set
     *
     * @param string $value The value to remove
     *
     * @return DockerfileArray
     */
    public function removeValue($value)
    {
        if ($this->hasValue($value)) {
            unset($this->values[$this->getValueIndex($value)]);
        }

        return $this;
    }

    /**
     * To string
     *
     * Return the string representation of the object
     *
     * @return string
     */
    abstract public function __toString();
}
