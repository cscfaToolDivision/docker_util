<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileAssoc;

/**
 * Dockerfile arg
 *
 * This class is used to design a dockerfile
 * arg
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileArg extends DockerfileAssoc
{
    /**
     * Construct
     *
     * The default class constructor
     *
     * @param string $name The argument name
     * @param string $default The argument default value
     */
    public function __construct($name, $default = null)
    {
        parent::__construct($name, $default);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        if (is_null($this->value)) {
            return sprintf("ARG %s", $this->key);
        }
        return sprintf("ARG %s=%s", $this->key, $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 5;
    }
}