<?php
namespace CSDT\DockerUtilBundle\Images;

/**
 * Label
 *
 * This class is used to store an image label information
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class Label
{
    /**
     * Key
     *
     * The label key
     *
     * @var string
     */
    private $key;

    /**
     * Value
     *
     * The label value
     *
     * @var string
     */
    private $value;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param string $key The label key
     * @param string $value The label value
     *
     * @return void
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Get key
     *
     * Return the label key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get value
     *
     * Return the label value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
