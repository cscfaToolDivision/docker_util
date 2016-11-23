<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;

/**
 * Dockerfile from
 *
 * This class is used to design a dockerfile
 * from
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileFrom implements DockerfileCommand
{
    /**
     * Image name
     *
     * The image name
     *
     * @var string
     */
    protected $imageName;

    /**
     * Image tag
     *
     * The image tag
     *
     * @var string
     */
    protected $imageTag;

    /**
     * Construct
     *
     * The default DockerfileAssoc construct
     *
     * @param string $imageName The image name
     * @param string $imageTag The image tag
     *
     * @return void
     */
    public function __construct($imageName, $imageTag)
    {
        $this->imageName = $imageName;
        $this->imageTag = $imageTag;
    }

    /**
     * Get image name
     *
     * Return the image name
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set image name
     *
     * Set the image name
     *
     * @param string $imageName The image name
     *
     * @return DockerfileAssoc
     */
    public function setImageName(array $imageName)
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * Get image tag
     *
     * Return the image tag
     *
     * @return string
     */
    public function getValue()
    {
        return $this->imageTag;
    }

    /**
     * Set image tag
     *
     * Set the image tag
     *
     * @param string $imageTag The image tag
     *
     * @return DockerfileAssoc
     */
    public function setValue(array $imageTag)
    {
        $this->imageTag = $imageTag;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::__toString()
     */
    public function __toString()
    {
        $image = $this->imageName . (is_null($this->imageTag) ? "" : ":".$this->imageTag);

        return sprintf("FROM %s", $image);
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 2;
    }
}