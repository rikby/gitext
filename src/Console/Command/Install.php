<?php

namespace Rikby\GitExt\Console\Command;

use Rikby\Console\Command\AbstractCommand;
use Rikby\Console\Helper\Shell\ShellHelper;
use Rikby\GitExt\Git\CommandInterface as GitCommandInterface;
use Rikby\GitExt\Git\CommandsList;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CommitHooks files installer
 *
 * @package PreCommit
 */
class Install extends AbstractCommand
{
    /**
     * Commands list for installation
     *
     * @var CommandsList
     */
    protected $commands;

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        foreach ($this->commands()->commands() as $command) {
            $this->execCommand($this->makeAliasCommand($command));
            if ($this->isVerbose()) {
                $this->output->writeln("Installed '{$command->command()}'.");
            }
        }

        return 0;
    }

    /**
     * Get shell helper
     *
     * @return ShellHelper
     */
    public function getShellHelper()
    {
        if (!$this->getHelperSet()->has('shell')) {
            $this->getHelperSet()->set(new ShellHelper());
            $this->getHelperSet()->get('shell')->setOutput($this->output);
        }

        return $this->getHelperSet()->get('shell');
    }

    /**
     * Get commands list collection
     *
     * @return CommandsList
     */
    public function commands()
    {
        if ($this->commands === null) {
            $this->commands = new CommandsList([
                __DIR__.'/../../shell/command',
            ]);
        }

        return $this->commands;
    }

    /**
     * Get command
     *
     * @param string $command
     * @return \Rikby\GitExt\Git\CommandInterface
     */
    public function getCommand($command)
    {
        return $this->commands()->command($command);
    }

    /**
     * Make alias command
     *
     * @param GitCommandInterface $command
     * @return string
     */
    protected function makeAliasCommand($command)
    {
        return sprintf(
            'git config --global alias.%s "!bash %s"',
            $command->alias(),
            str_replace('\\', '/', $command->file())
        );
    }

    /**
     * Get commands list help
     *
     * @return mixed
     */
    protected function getCommandsHelp()
    {
        $list = [];

        foreach ($this->commands()->commands() as $command) {
            $list[$command->command()] = $command->description();
        }

        return $list;
    }

    /**
     * Execute shell command
     *
     * @param string $command
     * @return string
     */
    protected function execCommand($command)
    {
        return $this->getShellHelper()->shellExec($command, true, null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureCommand()
    {
        $this->setName('install');

        // @codingStandardsIgnoreStart
        $this->setHelp(
            <<<TXT
Install extra GIT commands. It will set GIT commands:
{$this->formatList($this->getCommandsHelp())}
TXT
        );
        // @codingStandardsIgnoreEnd
        $this->setDescription(
            'Install extra GIT commands.'
        );
    }

    /**
     * Format list
     *
     * @param array $list
     * @param int   $minIndent
     * @return string
     */
    protected function formatList($list, $minIndent = 1)
    {
        $maxWidth = 0;
        $result   = '';
        foreach ($list as $command => $help) {
            $maxWidth = strlen($command) > $maxWidth ? strlen($command) : $maxWidth;
        }
        foreach ($list as $command => $help) {
            // "3" is a default left indent
            $help   = trim(
                $this->getCommand($command)
                    ->description(str_repeat(' ', $maxWidth + 3))
            );
            $result .= sprintf(
                '  <info>%s</info>%s%s'.PHP_EOL,
                $command,
                str_repeat(' ', $maxWidth - strlen($command) + $minIndent),
                $help
            );
        }

        return $result;
    }
}
