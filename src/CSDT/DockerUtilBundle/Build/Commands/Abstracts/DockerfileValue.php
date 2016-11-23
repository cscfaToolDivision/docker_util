<?php
namespace CSDT\DockerUtilBundle\Build\Commands\Abstracts;

use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile value
 *
 * This class is used to design a dockerfile
 * value
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
abstract class DockerfileValue implements DockerfileCommand
{
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
     * The default DockerfileValue construct
     *
     * @param string $value The value
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
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
     * @return DockerfileValue
     */
    public function setValue(array $value)
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