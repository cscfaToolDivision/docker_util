<?php
namespace CSDT\DockerUtilBundle\Container;

use Docker\API\Model\ContainerConfig;
use CSDT\DockerUtilBundle\Managers\AdvancedContainerManager;
use CSDT\DockerUtilBundle\Container\Container;

/**
 * Container builder
 *
 * This class is used to build a docker container
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class ContainerBuilder extends ContainerConfig
{
    /**
     * Manager
     *
     * The manager instance to inject into the created container
     *
     * @var AdvancedContainerManager
     */
    private $manager;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param AdvancedContainerManager $manager The manager instance to inject into the created container
     *
     * @return void
     */
    public function __construct(AdvancedContainerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get container
     *
     * Return the container according with the current configuration
     *
     * @return Container
     */
    public function getContainer()
    {
        return new Container($this->manager, $this);
    }
}
