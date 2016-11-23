<?php
namespace CSDT\DockerUtilBundle\Build;

use CSDT\CollectionsBundle\Collections\ValueCollection;
use Docker\API\Model\BuildInfo;

/**
 * Build response parser
 *
 * This class is used to parse the docker image building response
 * to a BuildResponse instance
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class BuildResponseParser
{

    /**
     * Parse
     *
     * Parse an image build response to a BuildResponse object
     *
     * @param array $responseSet The response raw set
     *
     * @return BuildResponse
     */
    public function parse(array $responseSet)
    {
        $outputs = new ValueCollection();

        $stream = new ValueCollection();
        $errors = new ValueCollection();

        foreach ($responseSet as $responseRaw) {
            $transient = new \stdClass();
            $this->parseRaw($responseRaw, $transient);
            $this->parseTransient($transient, $outputs, $stream, $errors);
        }

        $object = new BuildResponse($outputs, $stream, $errors);

        return $object;
    }

    /**
     * Parse transient
     *
     * Explode a transient into the context sets
     *
     * @param \stdClass $transient The transient object
     * @param ValueCollection $output The output context
     * @param ValueCollection $stream The status context
     * @param ValueCollection $errors The error context
     *
     * @return void
     */
    private function parseTransient(\stdClass $transient, ValueCollection $output, ValueCollection $stream, ValueCollection $errors)
    {
        if ($transient->isError) {
            $errors->add($output->count());
            $output->add($transient->error);
        } else if ($transient->isStream) {
            $stream->add($output->count());
            $output->add($transient->stream);
        }
    }

    /**
     * Parse raw
     *
     * Parse a response raw
     *
     * @param BuildInfo $info The response information raw
     * @param \stdClass $transient The transient object
     *
     * @return void
     */
    private function parseRaw(BuildInfo $info, \stdClass $transient)
    {
        $this->resolveError($transient, $info->getError());
        $this->resolveStream($transient, $info->getStream());
    }

    /**
     * Resolve stream
     *
     * Hydrate the transient object in stream context
     *
     * @param \stdClass $transient The transient object
     * @param string $stream The status state as string
     *
     * @return void
     */
    private function resolveStream(\stdClass $transient, $stream)
    {
        if (!is_null($stream)) {
            $transient->isStream = true;
            $transient->stream = trim($stream);
            return;
        }

        $transient->isStream = false;
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
            $transient->error = trim($error);
            return;
        }

        $transient->isError = false;
    }
}
