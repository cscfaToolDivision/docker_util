<?php
namespace CSDT\DockerUtilBundle\Container;

use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;
use Docker\API\Model\Container as ContainerModel;
use DeepCopy\DeepCopy;

/**
 * Container informations
 *
 * This class is used to store the docker containers informations
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ContainerInformations
{

    /**
     * Linked id
     *
     * The linked container ID
     *
     * @var string
     */
    private $linkedId;

    /**
     * Informations
     *
     * The container informations
     *
     * @var ContainerModel
     */
    private $informations;

    /**
     * Manager
     *
     * The container manager
     *
     * @var AdvancedContainerManager
     */
    private $manager;

    /**
     * Removed
     *
     * The removed state
     *
     * @var boolean
     */
    private $removed = false;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param string $containerId The container id
     * @param AdvancedContainerManager $manager The container manager
     *
     * @return void
     */
    public function __construct($containerId, AdvancedContainerManager $manager)
    {
        $this->linkedId = $containerId;
        $this->manager = $manager;

        $this->inspect();
    }

    /**
     * Inspect
     *
     * Inspect the current linked container
     *
     * @return void
     */
    public function inspect()
    {
        if ($this->removed) {
            return;
        }

        $this->informations = $this->manager->inspect($this->linkedId);
    }

    /**
     * Set removed
     *
     * Set the informations to removed state
     *
     * @return void
     */
    public function setRemoved()
    {
        $this->removed = true;
        $this->informations = new ContainerModel();
    }

    /**
     * Get informations
     *
     * Return the container informations. The result of this method
     * is a copy of the current container state and the main ContainerInformation
     * property instance are immutable.
     *
     * @return ContainerModel
     */
    public function getInformations()
    {
        $copyer = new DeepCopy();
        return $copyer->copy($this->informations);
    }

}
