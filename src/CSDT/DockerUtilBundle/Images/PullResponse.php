<?php
namespace CSDT\DockerUtilBundle\Images;

use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Pull response
 *
 * This class is used to manage the docker image pulling response
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class PullResponse
{
    /**
     * Status output
     *
     * Define the status output type
     *
     * @var binary
     */
    const STATUS_OUTPUT = 0b001;

    /**
     * Error output
     *
     * Define the error output type
     *
     * @var binary
     */
    const ERROR_OUTPUT = 0b010;

    /**
     * Progress output
     *
     * Define the progress output type
     *
     * @var binary
     */
    const PROGRESS_OUTPUT = 0b100;

    /**
     * Both output
     *
     * Define the both output type
     *
     * @var binary
     */
    const BOTH_OUTPUT = self::STATUS_OUTPUT | self::ERROR_OUTPUT | self::PROGRESS_OUTPUT;

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
    private $statusSet;

    /**
     * Error set
     *
     * The set of output index for the errors
     *
     * @var ValueCollection
     */
    private $errorSet;

    /**
     * Progress set
     *
     * The set of output index for the progress
     *
     * @var ValueCollection
     */
    private $progressState;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param ValueCollection $outputSet The set of output
     * @param ValueCollection $statusSet The set of output index for the status
     * @param ValueCollection $errorSet The set of output index for the errors
     * @param ValueCollection $progressState The set of output index for the progress
     *
     * @return void
     */
    public function __construct(ValueCollection $outputSet, ValueCollection $statusSet, ValueCollection $errorSet, ValueCollection $progressState)
    {
        $this->outputSet = $outputSet;
        $this->statusSet = $statusSet;
        $this->errorSet = $errorSet;
        $this->progressState = $progressState;
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
     * Return the output designed by type. Use the PullResponse constants
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
        if ($type & self::STATUS_OUTPUT) {
            $indexes = array_merge($indexes, $this->statusSet->toArray());
        }
        if ($type & self::ERROR_OUTPUT) {
            $indexes = array_merge($indexes, $this->errorSet->toArray());
        }
        if ($type & self::PROGRESS_OUTPUT) {
            $indexes = array_merge($indexes, $this->progressState->toArray());
        }

        $output = array();
        foreach ($indexes as $index) {
            $output[] = $this->outputSet->get($index);
        }

        return $output;
    }

}
