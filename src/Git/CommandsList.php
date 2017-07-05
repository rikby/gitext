<?php
namespace Rikby\GitExt\Git;

use Symfony\Component\Finder\Finder;

/**
 * Class CommandsList
 *
 * @package Rikby\GitExt\Git
 */
class CommandsList
{
    /**
     * Files mask
     *
     * It uses for finder
     *
     * @var string
     */
    protected $mask;

    /**
     * Commands list
     *
     * @var array
     */
    protected $commands;

    /**
     * Paths list where commands can be found
     *
     * @var array
     */
    private $paths;

    /**
     * CommandsList constructor.
     *
     * @param array  $paths
     * @param string $mask
     */
    public function __construct(array $paths, $mask = '*.sh')
    {
        $this->mask  = $mask;
        $this->paths = $paths;
    }

    /**
     * Get commands
     *
     * @return \Rikby\GitExt\Git\CommandInterface[]
     */
    public function commands()
    {
        if ($this->commands === null) {
            $this->commands = [];

            foreach ($this->finder() as $file) {
                $command = new Command($file->getRealPath());
                $this->commands[$command->command()] = $command;
            }
        }

        return $this->commands;
    }

    /**
     * Get commands only
     *
     * @return array
     */
    public function commandsOnly()
    {
        return array_keys($this->commands);
    }

    /**
     * Get command
     *
     * @param string $command
     * @return \Rikby\GitExt\Git\CommandInterface
     */
    public function command($command)
    {
        return $this->commands()[$command];
    }

    /**
     * Create files finder
     *
     * @return Finder
     */
    protected function finder()
    {
        $finder = new Finder();

        return $finder->files()->name($this->mask)->depth(0)->in($this->paths);
    }
}
