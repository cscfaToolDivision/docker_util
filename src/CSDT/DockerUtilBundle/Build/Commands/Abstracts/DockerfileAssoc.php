<?php
namespace CSDT\DockerUtilBundle\Build\Commands\Abstracts;

use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile assoc
 *
 * This class is used to design a dockerfile
 * assoc
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
abstract class DockerfileAssoc implements DockerfileCommand
{
    /**
     * Key
     *
     * The key
     *
     * @var string
     */
    protected $key;

    /**
     * Value
     *
     * The value
     *
     * @var string
     */
    protected $value;

    /**
     * Construct
     *
     * The default DockerfileAssoc construct
     *
     * @param string $key The key
     * @param string $value The value
     *
     * @return void
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Get key
     *
     * Return the key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * Set the key
     *
     * @param string $key The key
     *
     * @return DockerfileAssoc
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get value
     *
     * Return the value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * Set the value
     *
     * @param string $value The value
     *
     * @return DockerfileAssoc
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::__toString()
     */
    abstract public function __toString();
}