<?php
namespace CSDT\DockerUtilBundle\Build;

use CSDT\DockerUtilBundle\Build\Commands\Interfaces\DockerfileCommand;
use CSDT\CollectionsBundle\Collections\MapCollection;
use CSDT\CollectionsBundle\Collections\ValueCollection;
use Symfony\Component\Filesystem\Filesystem;
use DeepCopy\DeepCopy;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileAdd;
use CSDT\DockerUtilBundle\Build\Commands\DockerfileCopy;
use Symfony\Component\Finder\Finder;

class Dockerfile
{

    /**
     * Commands
     *
     * The dockerfile command storage
     *
     * @var MapCollection
     */
    public $commands;

    /**
     * Construct
     *
     * The default class constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->commands = new MapCollection();
    }

    /**
     * Add command
     *
     * Register a new command
     *
     * @param DockerfileCommand $command The command to register
     *
     * @return Dockerfile
     */
    public function addCommand(DockerfileCommand $command)
    {
        $this->getPriority($command->getPriority())->add($command);
        return $this;
    }

    /**
     * Get priority
     *
     * Return the valueCollection of the given priority
     *
     * @param integer $priority The priority
     *
     * @return ValueCollection
     */
    private function getPriority($priority)
    {
        if (!$this->commands->has($priority)) {
            $this->commands->set($priority, new ValueCollection());
        }

        return $this->commands->get($priority);
    }

    /**
     * To string
     *
     * the default class to string casting method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString($this->commands);
    }

    /**
     * To string
     *
     * Return the string representation of the docker file
     *
     * @return string
     */
    protected function toString(MapCollection $context)
    {
        $keys = array_keys($context->toArray());
        sort($keys);

        $output = "";
        foreach ($keys as $key) {
            foreach ($context->get($key) as $command) {
                $output .= ((string)$command) . "\n";
            }
        }

        return $output;
    }

    /**
     * Copy context
     *
     * Copy the current context and return a new one
     *
     * @return MapCollection
     */
    private function copyContext()
    {
        $copyer = new DeepCopy();

        return $copyer->copy($this->commands);
    }

    /**
     * Create tar
     *
     * Create the tar archive from the current context
     * The options are defined as it :
     * <ul>
     *  <li><strong>"no-import" => array()</strong> A list of file or directories to not import into the archive</li>
     *  <li><strong>"build-dir" => sys_get_temp_dir()</strong> The build directory where store the archive and create the temporary Dockerfile</li>
     *  <li><strong>"archive-prefix" => "docker_build_archive_"</strong> The archive prefix</li>
     *  <li><strong>"dockerfile-prefix" => "dockerfile_"</strong> The temporary Dockerfile prefix</li>
     *  <li><strong>"remove-tmp-dockerfile" => true</strong> Remove temporary Dockerfile after tar creation</li>
     * </ul>
     *
     * @param array $options The creation options
     *
     * @return string
     */
    public function createTar(array $options = array())
    {
        $options = array_merge(array(
            "no-import" => array(),
            "build-dir" => sys_get_temp_dir(),
            "archive-prefix" => "docker_build_archive_",
            "dockerfile-prefix" => "dockerfile_",
            "remove-tmp-dockerfile" => true
        ), $options);

        $context = $this->copyContext();

        $filename = $this->getArchiveName($options["build-dir"], $options["archive-prefix"]);
        $archive = new \PharData($filename);

        $this->resolveFiles($context, $archive, $options["no-import"]);
        $this->resolveDirectories($context, $archive, $options["no-import"]);

        $dockerfilePath = tempnam($options["build-dir"], $options["dockerfile-prefix"]);
        file_put_contents($dockerfilePath, $this->toString($context));

        $archive->addFile($dockerfilePath, "Dockerfile");

        if ($options["remove-tmp-dockerfile"]) {
            $filesystem = new Filesystem();
            $filesystem->remove($dockerfilePath);
        }

        return $filename;
    }

    /**
     * Resolve directories
     *
     * Import the specified directories into the archive
     *
     * @param MapCollection $context The current context
     * @param \PharData $archive The created archive
     * @param array $noImport The no import option
     *
     * @return void
     */
    private function resolveDirectories(MapCollection $context, \PharData $archive, array $noImport)
    {
        foreach ($context as $priority) {
            foreach ($priority as $command) {
                if ($command instanceof DockerfileAdd || $command instanceof DockerfileCopy) {
                    $srcDirectory = $command->getKey();

                    if (in_array($srcDirectory, $noImport) || !is_dir($srcDirectory)) {
                        continue;
                    }

                    $dirname = $this->createTree($archive, $srcDirectory);
                    $command->setKey($dirname);
                }
            }
        }
    }

    /**
     * Create tree
     *
     * Create the archive tree of the directory
     *
     * @param \PharData $archive The archive
     * @param unknown $srcDirectory The source directory
     * @param string $precedence The current precedence
     *
     * @return void
     */
    private function createTree(\PharData $archive, $srcDirectory, $precedence = "", $targetDirectory = null)
    {
        if (is_null($targetDirectory)) {
            $targetDirectory = $srcDirectory;
        }
        $dirname = $precedence . basename($srcDirectory);
        $archive->addEmptyDir($dirname);

        $finder = new Finder();
        $files = $finder->in($targetDirectory)->files()->depth(0);

        foreach ($files as $file) {
            $archive->addFile($file->getPathname(), $dirname.DIRECTORY_SEPARATOR.$file->getBasename());
        }

        $directories = $finder->in($targetDirectory)->directories()->depth(0);

        foreach ($directories as $directory) {
            $this->createTree(
                $archive,
                $directory->getBasename(),
                $dirname.DIRECTORY_SEPARATOR,
                $directory->getPathname()
            );
        }

        return $dirname;
    }

    /**
     * Resolve files
     *
     * Import the specified files into the archive
     *
     * @param MapCollection $context The current context
     * @param \PharData $archive The created archive
     * @param array $noImport The no import option
     *
     * @return void
     */
    private function resolveFiles(MapCollection $context, \PharData $archive, array $noImport)
    {
        foreach ($context as $priority) {
            foreach ($priority as $command) {
                if ($command instanceof DockerfileAdd || $command instanceof DockerfileCopy) {
                    $srcFile = $command->getKey();

                    if (in_array($srcFile, $noImport) || !is_file($srcFile)) {
                        continue;
                    }

                    $fileInfo = new \SplFileInfo($srcFile);
                    $localFileName = $fileInfo->getBasename();

                    $archive->addFile($srcFile, $localFileName);
                    $command->setKey($localFileName);
                }
            }
        }
    }

    /**
     * Get archive name
     *
     * Generate a new archive name
     *
     * @param string $buildDir The building directory
     * @param string $prefix The archive prefix
     *
     * @return string
     */
    private function getArchiveName($buildDir, $prefix)
    {
        $fileName = "";
        do{
            $fileName = $buildDir . DIRECTORY_SEPARATOR . $prefix . crc32(uniqid()) . ".tar";
        }while(is_file($fileName));

        return $fileName;
    }

}
