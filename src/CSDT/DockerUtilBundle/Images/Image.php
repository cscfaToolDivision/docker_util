<?php
namespace CSDT\DockerUtilBundle\Images;

use CSDT\CollectionsBundle\Collections\ValueCollection;

/**
 * Image
 *
 * This class is used to store an image information
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class Image
{
    /**
     * Name
     *
     * The image name
     *
     * @var string
     */
    private $name;

    /**
     * Tag
     *
     * The image tag
     *
     * @var string
     */
    private $tag;

    /**
     * Id
     *
     * The image id
     *
     * @var string
     */
    private $id;

    /**
     * Created
     *
     * The image creation date
     *
     * @var \DateTime
     */
    private $created;

    /**
     * Size
     *
     * The image size
     *
     * @var integer
     */
    private $size;

    /**
     * Virtual size
     *
     * The image virtual size
     *
     * @var integer
     */
    private $virtualSize;

    /**
     * Labels
     *
     * The image labels
     *
     * @var ValueCollection<Label>
     */
    private $labels;

    /**
     * Parent id
     *
     * The parent image id
     *
     * @var string
     */
    private $parentId;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param string $name The image name
     * @param string $tag The image tag
     * @param string $id The image id
     * @param string $created The image creation date
     * @param string $size The image size
     * @param string $virtualSize The image virtual size
     * @param string $labels The image labels
     * @param string $parentId The parent image id
     *
     * @return void
     */
    public function __construct($name, $tag, $id, $created, $size, $virtualSize, $labels, $parentId)
    {
        $this->name = $name;
        $this->tag = $tag;
        $this->id = $id;
        $this->created = $created;
        $this->size = $size;
        $this->virtualSize = $virtualSize;
        $this->labels = $labels;
        $this->parentId = $parentId;
    }

    /**
     * Get name
     *
     * Return the image name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get tag
     *
     * Return the image tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get id
     *
     * Return the image id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get created
     *
     * Return the image created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get size
     *
     * Return the image size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get virtualSize
     *
     * Return the image virtualSize
     *
     * @return string
     */
    public function getVirtualSize()
    {
        return $this->virtualSize;
    }

    /**
     * Get labels
     *
     * Return the image labels
     *
     * @return string
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Get parentId
     *
     * Return the image parentId
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Get short id
     *
     * Return the short image id
     *
     * @return string
     */
    public function getShortId()
    {
        return substr($this->getId(), 7, 12);
    }

    /**
     * To string
     *
     * The default class to string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s:%s (%s)", $this->getName(), $this->getTag(), $this->getShortId());
    }
}
