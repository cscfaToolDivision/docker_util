<?php
namespace CSDT\DockerUtilBundle\Images;

use Docker\API\Model\CreateImageInfo;
use Docker\API\Model\ProgressDetail;
use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Pull response parser
 *
 * This class is used to parse the docker image pulling response
 * to a PullingResponse instance
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class PullResponseParser
{

    /**
     * Parse
     *
     * Parse an image pull response to a PullResponse object
     *
     * @param array $responseSet The response raw set
     *
     * @return PullResponse
     */
    public function parse(array $responseSet)
    {
        $outputs = new ValueCollection();

        $status = new ValueCollection();
        $errors = new ValueCollection();
        $progress = new ValueCollection();

        foreach ($responseSet as $responseRaw) {
            $transient = new \stdClass();
            $this->parseRaw($responseRaw, $transient);
            $this->parseTransient($transient, $outputs, $status, $errors, $progress);
        }

        $object = new PullResponse($outputs, $status, $errors, $progress);

        return $object;
    }

    /**
     * Parse transient
     *
     * Explode a transient into the context sets
     *
     * @param \stdClass $transient The transient object
     * @param ValueCollection $output The output context
     * @param ValueCollection $status The status context
     * @param ValueCollection $errors The error context
     * @param ValueCollection $progress The progress context
     *
     * @return void
     */
    private function parseTransient(\stdClass $transient, ValueCollection $output, ValueCollection $status, ValueCollection $errors, ValueCollection $progress)
    {
        if ($transient->isError) {
            $errors->add($output->count());
            $output->add($transient->error);
        } else if ($transient->isProgress) {
            $progress->add($output->count());
            $output->add($transient->progress);
        } else if ($transient->isStream) {
            $status->add($output->count());
            $output->add($transient->status);
        }
    }

    /**
     * Parse raw
     *
     * Parse a response raw
     *
     * @param CreateImageInfo $info The response information raw
     * @param \stdClass $transient The transient object
     *
     * @return void
     */
    private function parseRaw(CreateImageInfo $info, \stdClass $transient)
    {
        $this->resolveError($transient, $info->getError());
        $this->resolveProgress($transient, $info->getStatus(), $info->getProgress(), $info->getProgressDetail());
        $this->resolveStatus($transient, $info->getStatus());
    }

    /**
     * Resolve status
     *
     * Hydrate the transient object in status context
     *
     * @param \stdClass $transient The transient object
     * @param string $status The status state as string
     *
     * @return void
     */
    private function resolveStatus(\stdClass $transient, $status)
    {
        if (!is_null($status)) {
            $transient->isStream = true;
            $transient->status = $status;
            return;
        }

        $transient->isStream = false;
    }

    /**
     * Resolve progress
     *
     * Hydrate the transient object in progress context
     *
     * @param \stdClass $transient The transient object
     * @param string $status The status state as string
     * @param string $progress The progress state as string
     * @param ProgressDetail $detail The progress detail
     *
     * @param string $error The error state
     *
     * @return void
     */
    private function resolveProgress(\stdClass $transient, $status, $progress, ProgressDetail $detail = null)
    {
        if (!is_null($progress)) {
            $progression = $status . " : " . $progress;

            if (!is_null($detail) && !empty($detail->getMessage())) {
                $progression .= $detail->getMessage();
            }

            $transient->isProgress = true;
            $transient->progress = $progression;
            return;
        }

        $transient->isProgress = false;
    }

    /**
     * Resolve error
     *
     * Hydrate the transient object in error context
     *
     * @param \stdClass $transient The transient object
     * @param string $error The error state as string
     *
     * @return void
     */
    private function resolveError(\stdClass $transient, $error)
    {
        if (!is_null($error)) {
            $transient->isError = true;
            $transient->error = $error;
            return;
        }

        $transient->isError = false;
    }

}
