<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue;
use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile health check
 *
 * This class is used to design a dockerfile
 * health check
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileHealthcheck extends DockerfileValue
{
    /**
     * Interval
     *
     * The health check interval
     *
     * @var integer
     */
    protected $interval;

    /**
     * Timeout
     *
     * The health check timeout
     *
     * @var integer
     */
    protected $timeout;

    /**
     * Retries
     *
     * The health check retries count on failure
     *
     * @var integer
     */
    protected $retries;

    /**
     * Construct
     *
     * The default class constructor
     * Note  : Given no value deactive health check, also
     * from parent image
     *
     * @param DockerfileCommand $value The command to execute (DockerfileExecCMD or DockerfileShellCMD only)
     * @param string $interval The health check interval
     * @param string $timeout The health check timeout
     * @param number $retries The health check retries count on failure
     *
     * @throws \LogicException
     * @return void
     */
    public function __construct(DockerfileCommand $value = null, $interval = '30s', $timeout = '30s', $retries = 3)
    {
        $accepted = array(
            DockerfileExecCMD::class,
            DockerfileShellCMD::class
        );

        if (!is_null($value) && !in_array(get_class($value), $accepted)) {
            throw new \LogicException(
                sprintf(
                    "The class %s can trigger [%s] class. %s given",
                    self::class,
                    implode(", ", $accepted),
                    get_class($value)
                )
            );
        }

        parent::__construct($value);

        $this->interval = $interval;
        $this->timeout = $timeout;
        $this->retries = $retries;
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileValue::__toString()
     */
    public function __toString()
    {
        if (is_null($this->value)) {
            return sprintf("HEALTHCHECK NONE");
        }

        return sprintf(
            "HEALTHCHECK --interval=%s --timeout=%s --retries=%d %s",
            $this->interval,
            $this->timeout,
            $this->retries,
            $this->value
        );
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 22;
    }
}