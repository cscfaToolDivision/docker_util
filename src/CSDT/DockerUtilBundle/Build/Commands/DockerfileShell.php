<?php
namespace CSDT\DockerUtilBundle\Build\Commands;

use CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileExecForm;

/**
 * Dockerfile shell
 *
 * This class is used to design a dockerfile
 * shell
 *
 * @author Matthieu Vallance <matthieu.vallance@cscfa.fr>
 */
class DockerfileShell extends DockerfileExecForm
{
    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Abstracts\DockerfileExecForm::__toString()
     */
    public function __toString()
    {
        if (empty($this->values)) {
            return sprintf('SHELL ["%s"]', $this->command);
        }

        $parameters = array();
        foreach ($this->values as $value) {
            $parameters[] = sprintf('"%s"', $value);
        }

        return sprintf('SHELL ["%s", %s]', $this->command, implode(', ', $parameters));
    }

    /**
     * {@inheritDoc}
     * @see \CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand::getPriority()
     */
    public function getPriority()
    {
        return 3;
    }
}
