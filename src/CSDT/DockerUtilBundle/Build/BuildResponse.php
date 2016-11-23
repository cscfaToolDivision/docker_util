<?php
namespace CSDT\DockerUtilBundle\Build;

use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Build response
 *
 * This class is used to manage the docker image building response
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class BuildResponse
{
    /**
     * Status output
     *
     * Define the status output type
     *
     * @var binary
     */
    const STREAM_OUTPUT = 0b01;

    /**
     * Error output
     *
     * Define the error output type
     *
     * @var binary
     */
    const ERROR_OUTPUT = 0b10;

    /**
     * Both output
     *
     * Define the both output type
     *
     * @var binary
     */
    const BOTH_OUTPUT = self::STREAM_OUTPUT | self::ERROR_OUTPUT;

    /**
     * Output set
     *
     * The set of output
     *
     * @var ValueCollection
     */
    private $outputSet;

    /**
     * Status set
     *
     * The set of output index for the status
     *
     * @var ValueCollection
     */
    private $streamSet;

    /**
     * Error set
     *
     * The set of output index for the errors
     *
     * @var ValueCollection
     */
    private $errorSet;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param ValueCollection $outputSet The set of output
     * @param ValueCollection $streamSet The set of output index for the status
     * @param ValueCollection $errorSet The set of output index for the errors
     *
     * @return void
     */
    public function __construct(ValueCollection $outputSet, ValueCollection $streamSet, ValueCollection $errorSet)
    {
        $this->outputSet = $outputSet;
        $this->streamSet = $streamSet;
        $this->errorSet = $errorSet;
    }

    /**
     * Is error
     *
     * Check if the response contain an error state
     *
     * @return boolean
     */
    public function isError()
    {
        return !$this->errorSet->isEmpty();
    }

    /**
     * Get output
     *
     * Return the output designed by type. Use the BuildResponse constants
     * to define the type. The types are allowed to be combined with binary
     * OR operator.
     *
     * @param binary $type The output type to return
     *
     * @return string[]
     */
    public function getOutput($type = self::BOTH_OUTPUT)
    {
        $indexes = array();
        if ($type & self::STREAM_OUTPUT) {
            $indexes = array_merge($indexes, $this->streamSet->toArray());
        }
        if ($type & self::ERROR_OUTPUT) {
            $indexes = array_merge($indexes, $this->errorSet->toArray());
        }

        $output = array();
        foreach ($indexes as $index) {
            $output[] = $this->outputSet->get($index);
        }

        return $output;
    }
}
