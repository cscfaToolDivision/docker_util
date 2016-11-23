<?php
namespace CSDT\DockerUtilBundle\Build;

use CSDT\DockerUtilBundle\Build\Commands\DockerfileAdd;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileHealthcheck;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileOnbuild;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileShell;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileStopsignal;
use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileArg;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileCopy;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileEnv;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileEscape;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileExecCMD;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileExecEntrypoint;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileExecRun;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileExpose;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileFrom;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileLabel;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileMaintainer;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileShellCMD;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileShellEntrypoint;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileShellRun;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileUser;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileVolume;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileWorkdir;

/**
 * Dockerfile builder
 *
 * This class is used to build a Dockerfile
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileBuilder
{

    /**
     * Docker file
     *
     * The building docker file
     *
     * @var Dockerfile
     */
    private $dockerfile;

    public function __construct()
    {
        $this->dockerfile = new Dockerfile();
    }

    /**
     * Add
     *
     * Add a file to the builded container
     *
     * @param string $src  The host location
     * @param string $dest The container location
     *
     * @return DockerfileBuilder
     */
    public function add($src, $dest)
    {
        $this->dockerfile->addCommand(
            new DockerfileAdd($src, $dest)
            );

        return $this;
    }

    /**
     * Arg
     *
     * Add an argument to the build
     *
     * @param string $name    The argument name
     * @param string $default The default value of the argument
     *
     * @return DockerfileBuilder
     */
    public function arg($name, $default = null)
    {
        $this->dockerfile->addCommand(
            new DockerfileArg($name, $default)
            );

        return $this;
    }

    /**
     * Copy
     *
     * Copy a file to the builded container
     *
     * @param string $src  The host location
     * @param string $dest The container location
     *
     * @return DockerfileBuilder
     */
    public function copy($src, $dest)
    {
        $this->dockerfile->addCommand(
            new DockerfileCopy($src, $dest)
            );

        return $this;
    }

    /**
     * Env
     *
     * Add an environment value to the build
     *
     * @param string $key   The environment key
     * @param string $value The environment value
     *
     * @return DockerfileBuilder
     */
    public function env($key, $value)
    {
        $this->dockerfile->addCommand(
            new DockerfileEnv($key, $value)
            );

        return $this;
    }

    /**
     * Escape
     *
     * Set the escape character of the dockerfile
     *
     * @param string $value The escape sequence
     *
     * @return DockerfileBuilder
     */
    public function escape($value)
    {
        $this->dockerfile->addCommand(
            new DockerfileEscape($value)
            );

        return $this;
    }

    /**
     * Exec cmd
     *
     * Execute a command
     *
     * @param string $command   The command
     * @param array $parameters The command parameters
     *
     * @return DockerfileBuilder
     */
    public function execCmd($command, array $parameters = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileExecCMD($command, $parameters)
            );

        return $this;
    }

    /**
     * Exec entrypoint
     *
     * Specify an entrypoint
     *
     * @param string $command   The command
     * @param array $parameters The command parameters
     *
     * @return DockerfileBuilder
     */
    public function execEntrypoint($command, array $parameters = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileExecEntrypoint($command, $parameters)
            );

        return $this;
    }

    /**
     * Exec run
     *
     * Execute a command
     *
     * @param string $command   The command
     * @param array $parameters The command parameters
     *
     * @return DockerfileBuilder
     */
    public function execRun($command, array $parameters = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileExecRun($command, $parameters)
            );

        return $this;
    }

    /**
     * Expose
     *
     * Expose a set of network port
     *
     * @param array $values The set of port
     *
     * @return DockerfileBuilder
     */
    public function expose(array $values = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileExpose($values)
            );

        return $this;
    }

    /**
     * From
     *
     * Specify the parent image
     *
     * @param string $imageName The parent image name
     * @param string $imageTag  The parent image tag
     *
     * @return DockerfileBuilder
     */
    public function from($imageName, $imageTag = null)
    {
        $this->dockerfile->addCommand(
            new DockerfileFrom($imageName, $imageTag)
            );

        return $this;
    }

    /**
     * Label
     *
     * Add an image label
     *
     * @param string $key   The label key
     * @param string $value The label value
     *
     * @return DockerfileBuilder
     */
    public function label($key, $value)
    {
        $this->dockerfile->addCommand(
            new DockerfileLabel($key, $value)
            );

        return $this;
    }

    /**
     * Maintainer
     *
     * Specify the image maintainer
     *
     * @param string $value The maintainer
     *
     * @return DockerfileBuilder
     */
    public function maintainer($value)
    {
        $this->dockerfile->addCommand(
            new DockerfileMaintainer($value)
            );

        return $this;
    }

    /**
     * Shell cmd
     *
     * Execute a shell command
     *
     * @param string $command The command
     *
     * @return DockerfileBuilder
     */
    public function shellCmd($command)
    {
        $this->dockerfile->addCommand(
            new DockerfileShellCMD($command)
            );

        return $this;
    }

    /**
     * Shell entrypoint
     *
     * Specify a shell entrypoint
     *
     * @param string $command The command to execute
     *
     * @return DockerfileBuilder
     */
    public function shellEntrypoint($command)
    {
        $this->dockerfile->addCommand(
            new DockerfileShellEntrypoint($command)
            );

        return $this;
    }

    /**
     * Shell run
     *
     * Execute a shell command
     *
     * @param string $command The command to execute
     *
     * @return DockerfileBuilder
     */
    public function shellRun($command)
    {
        $this->dockerfile->addCommand(
            new DockerfileShellRun($command)
            );

        return $this;
    }

    /**
     * User
     *
     * Build as an user
     *
     * @param string $value The user
     *
     * @return DockerfileBuilder
     */
    public function user($value)
    {
        $this->dockerfile->addCommand(
            new DockerfileUser($value)
            );

        return $this;
    }

    /**
     * Volume
     *
     * Attach a volume
     *
     * @param array $values The volumes to attach
     *
     * @return DockerfileBuilder
     */
    public function volume(array $values = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileVolume($values)
            );

        return $this;
    }

    /**
     * Wordir
     *
     * Set a working directory
     *
     * @param string $value The working directory path
     *
     * @return DockerfileBuilder
     */
    public function workdir($value)
    {
        $this->dockerfile->addCommand(
            new DockerfileWorkdir($value)
            );

        return $this;
    }

    /**
     * On build
     *
     * Set a child building trigger
     *
     * @param DockerfileCommand $value The command to trigger
     *
     * @throws \LogicException If an instance of OnBuild, From or Maintainer command is passed
     * @return DockerfileBuilder
     */
    public function onbuild(DockerfileCommand $value)
    {
        $this->dockerfile->addCommand(
            new DockerfileOnbuild($value)
        );

        return $this;
    }

    /**
     * Stop signal
     *
     * Specify the container stop signal
     *
     * @param mixed $value The signal to send
     *
     * @return DockerfileBuilder
     */
    public function stopsignal($value)
    {
        $this->dockerfile->addCommand(
            new DockerfileStopsignal($value)
        );

        return $this;
    }

    /**
     * Health check
     *
     * health check operation signal
     *
     * @param DockerfileCommand $value The command to execute (DockerfileExecCMD or DockerfileShellCMD only)
     * @param string $interval The health check interval (default '30s')
     * @param string $timeout The health check timeout (default '30s')
     * @param number $retries The health check retries count on failure (default 3)
     *
     * @throws \LogicException
     * @return DockerfileBuilder
     */
    public function healthcheck(DockerfileCommand $value = null, $interval = '30s', $timeout = '30s', $retries = 3)
    {
        $this->dockerfile->addCommand(
            new DockerfileHealthcheck($value, $interval, $timeout, $retries)
        );

        return $this;
    }

    /**
     * Shell
     *
     * Specify the session shell
     *
     * @param string $command The command to execute
     * @param array $parameters The command parameters
     *
     * @return DockerfileBuilder
     */
    public function shell($command, array $parameters = array())
    {
        $this->dockerfile->addCommand(
            new DockerfileShell($command, $parameters)
        );

        return $this;
    }

    /**
     * Get dockerfile
     *
     * Return the dockerfile instance
     *
     * @return Dockerfile
     */
    public function getDockerfile()
    {
        return $this->dockerfile;
    }

}
