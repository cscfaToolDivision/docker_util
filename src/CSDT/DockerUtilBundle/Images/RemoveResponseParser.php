<?php
namespace CSDT\DockerUtilBundle\Images;

use Psr\Http\Message\ResponseInterface;
use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Remove response parser
 *
 * This class is used to parse the docker image removing response
 * to RemoveResponse
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class RemoveResponseParser
{

    /**
     * Parse
     *
     * Parse a removing response to a RemoveResponse instance
     *
     * @param ResponseInterface $response The removing response
     *
     * @return RemoveResponse
     */
    public function parse(ResponseInterface $response)
    {
        $responseArray = json_decode($response->getBody()->getContents(), true);

        $untagged = new ValueCollection();
        $deleted = new ValueCollection();
        foreach ($responseArray as $responseItem) {
            if (isset($responseItem["Untagged"])) {
                $untagged->add($responseItem["Untagged"]);
            }
            if (isset($responseItem["Deleted"])) {
                $deleted->add($responseItem["Deleted"]);
            }
        }

        return new RemoveResponse($untagged, $deleted);
    }

}
