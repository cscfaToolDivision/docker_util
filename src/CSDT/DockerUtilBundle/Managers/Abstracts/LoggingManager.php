<?php
namespace CSDT\DockerUtilBundle\Managers\Abstracts;

use Psr\Log\LoggerInterface;
use Monolog\Logger;

/**
 * Logging manager
 *
 * This class is used to improve logging system of the managers
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
abstract class LoggingManager
{
    /**
     * Logger
     *
     * The logger instance
     *
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * Get name
     *
     * Return the current manager name. Can be used
     * to define the logger name.
     *
     * @return string
     */
    abstract protected function getName();

    /**
     * Set logger
     *
     * Set the logger
     *
     * @param LoggerInterface $logger The current used Logger
     *
     * @return void
     */
    protected function setLogger(LoggerInterface $logger = null)
    {
        if ($logger instanceof Logger) {
            $this->logger = $logger->withName($this->getName());
            return;
        }

        $this->logger = $logger;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function emergency($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->emergency($message, $context);
        }

        return $this;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function alert($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->alert($message, $context);
        }

        return $this;
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function critical($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->critical($message, $context);
        }

        return $this;
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function error($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->error($message, $context);
        }

        return $this;
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function warning($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->warning($message, $context);
        }

        return $this;
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function notice($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->notice($message, $context);
        }

        return $this;
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function info($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->info($message, $context);
        }

        return $this;
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return LoggingManager
     */
    protected function debug($message, array $context = array())
    {
        if (!is_null($this->logger)) {
            $this->logger->debug($message, $context);
        }

        return $this;
    }

}