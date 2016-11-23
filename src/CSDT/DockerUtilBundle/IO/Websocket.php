<?php
namespace CSDT\DockerUtilBundle\IO;

use Docker\Stream\AttachWebsocketStream;

/**
 * Websocket
 *
 * This class is used to manage the docker containers websocket
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class Websocket
{
    /**
     * No error
     *
     * The socket has no error
     *
     * @var integer
     */
    const NO_ERROR = 0;

    /**
     * No ressource
     *
     * The socket ressource does not exist
     *
     * @var integer
     */
    const NO_RESSOURCE = 1;

    /**
     * No output
     *
     * No output to display
     *
     * @var integer
     */
    const NO_OUTPUT = 2;

    /**
     * Attached websocket
     *
     * The attached websocket
     *
     * @var AttachWebsocketStream
     */
    private $attachedWebsocket;

    /**
     * Last null cause
     *
     * The last null readed cause
     *
     * @var integer
     */
    private $lastNullCause = self::NO_ERROR;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @param AttachWebsocketStream $websocket The attached websocket
     *
     * @return null
     */
    public function __construct(AttachWebsocketStream $websocket)
    {
        $this->attachedWebsocket = $websocket;
    }

    /**
     * Get last null cause
     *
     * Get the last null result cause
     *
     * @return number
     */
    public function getLastNullCause()
    {
        return $this->lastNullCause;
    }

    /**
     * String read
     *
     * Return the readed string without substitute
     *
     * @return string|NULL
     */
    public function sread($waitTime = 0, $waitMicroTime = 200000)
    {
        return $this->read(0, 200000, true);
    }

    /**
     * Block until it receive a frame from websocket or return null if no more connexion
     *
     * @param int     $waitTime      Time to wait in seconds before return false
     * @param int     $waitMicroTime Time to wait in microseconds before return false
     * @param boolean $noSubstitute  Whether to return the unsupported character
     *
     * @return string|null
     */
    public function read($waitTime = 0, $waitMicroTime = 200000, $noSubstitute = false)
    {
        $readed = $this->attachedWebsocket->read($waitTime, $waitMicroTime, false);

        if ($readed === null) {
            $this->lastNullCause = self::NO_RESSOURCE;
            return null;
        } else if ($readed === false) {
            $this->lastNullCause = self::NO_OUTPUT;
            return null;
        }

        if ($noSubstitute) {
            $replace = "";
            $pattern = '/[\x00-\x1f]/';
        } else {
            $replace = "\x1A";
            $pattern = '/[\x00-\x19\x1B-\x1f]/';
        }

        $result = preg_replace($pattern, "", $readed);
        $result = preg_replace('/[\x7f-\xFF]/', $replace, $result);

        return $result;
    }

    /**
     * Write
     *
     * Send input to the container
     *
     * @param string $data Data to send
     *
     * @return null
     */
    public function write($data)
    {
        $this->attachedWebsocket->write($data);
    }
}