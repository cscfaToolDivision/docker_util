<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;
use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile on build
 *
 * This class is used to design a dockerfile
 * on build
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileOnbuild extends DockerfileValue
{
    /**
     * Construct
     *
     * The default class constructor
     *
     * @param DockerfileCommand $value The command to trigger
     *
     * @throws \LogicException If an instance of OnBuild, From or Maintainer command is passed
     */
    public function __construct(DockerfileCommand $value)
    {
        $exclude = array(
            DockerfileOnbuild::class,
            DockerfileFrom::class,
            DockerfileMaintainer::class
        );

        if (in_array(get_class($value), $exclude)) {
            throw new \LogicException(
                sprintf(
                    "The class %s cannot trigger %s command",
                    self::class,
                    get_class($value)
                )
            );
        }

        parent::__construct($value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        return sprintf("ONBUILD %s", $this->value);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 23;
    }
}