<?php
namespace CSDT\DockerUtilBundle\Images;

use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Remove response
 *
 * This class is used to manage the docker image removing response
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class RemoveResponse
{
    /**
     * Untagged
     *
     * The untagged images
     *
     * @var ValueCollection
     */
    private $untagged;

    /**
     * Deleted
     *
     * The deleted images
     *
     * @var ValueCollection
     */
    private $deleted;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param ValueCollection $untagged the untagged images
     * @param ValueCollection $deleted the deleted images
     *
     * @return void
     */
    public function __construct(ValueCollection $untagged, ValueCollection $deleted)
    {
        $this->untagged = $untagged;
        $this->deleted = $deleted;
    }

    /**
     * Get untagged
     *
     * Teturn the untagged images
     *
     * @return ValueCollection
     */
    public function getUntagged()
    {
        return $this->untagged;
    }

    /**
     * Get deleted
     *
     * Teturn the deleted images
     *
     * @return ValueCollection
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

}
