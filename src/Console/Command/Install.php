<?php

namespace Rikby\GitExt\Console\Command;

use Rikby\Console\Command\AbstractCommand;
use Rikby\Console\Helper\Shell\ShellHelper;
use Rikby\GitExt\Console\Exception;
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
     * @var array
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
        $commands = $this->getCommands();

        foreach ($commands as $name => $command) {
            $this->execCommand($command);
            if ($this->isVerbose()) {
                $this->output->writeln("Installed '$name'.");
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
     * Get commands list
     *
     * @return mixed
     * @todo Refactor getting commands list
     */
    protected function getCommands()
    {
        if (null === $this->commands) {
            $this->commands = array_fill_keys(
                array_keys($this->getCommandsHelp()),
                ''
            );
            foreach ($this->commands as $command => &$alias) {
                $alias = $this->makeAliasCommand($command);
            }
        }

        return $this->commands;
    }

    /**
     * Make alias command
     *
     * @param string $command
     * @return string
     */
    protected function makeAliasCommand($command)
    {
        $alias = str_replace('git-', '', $command);
        $alias = str_replace('git ', '', $alias);

        return sprintf(
            'git config --global alias.%s "!bash %s"',
            $alias,
            str_replace('\\', '/', $this->getCommandFile($command))
        );
    }

    /**
     * Get commands list help
     *
     * @return mixed
     */
    protected function getCommandsHelp()
    {
        return [
            'git flow-namespace'     => '',
            'git flow-feature-root'  => '',

            'git tags'               => 'Show tags sorted by version.',
            'git tag-semver'         => 'Increase tag version through SemVer API.',
            'git tag-prerelease'     => 'Create new SemVer PreRelease tag based upon the last one.',
            'git tag-preminor-alpha' => 'Create new SemVer PreMinor Alpha tag based upon the last one.',
        ];
    }

    /**
     * Execute shell command
     *
     * @param string $command
     * @return string
     * @internal param bool $showCommand
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
            if (!$help) {
                // "3" is a default left indent
                // "2" is an extra shift
                $help = trim($this->getCommandDescription(
                    $command,
                    str_repeat(' ', $maxWidth + 3 + 2)
                ));
            }
            $result .= sprintf(
                '  <info>%s</info>%s%s'.PHP_EOL,
                $command,
                str_repeat(' ', $maxWidth - strlen($command) + $minIndent),
                $help
            );
        }

        return $result;
    }

    /**
     * Get path to command file
     *
     * @param string $command
     * @return bool|string
     * @throws Exception
     */
    protected function getCommandFile($command)
    {
        $command = str_replace(' ', '-', $command);
        $path = realpath(__DIR__.'/../../shell/'.$command.'.sh');
        if (!$path) {
            throw new Exception(
                sprintf(
                    'Could not found command "%s" by path "%s".',
                    $command,
                    __DIR__.'/../../shell/'.$command.'.sh'
                )
            );
        }

        return $path;
    }

    /**
     * Get command string
     *
     * @param string $command
     * @return mixed
     */
    protected function getCommandString($command)
    {
        return $this->readCommandSingleMeta($command, 'CMD');
    }

    /**
     * Get command description
     *
     * @param string $command
     * @return string
     */
    protected function getCommandDescription($command, $indent = '')
    {
        return $indent.implode("\n$indent", $this->readCommandMeta($command, 'DESCR'));
    }

    /**
     * Read command meta info
     *
     * @param string $command
     * @param string $metaTag
     * @return mixed
     */
    protected function readCommandMeta($command, $metaTag)
    {
        preg_match_all(
            '~\#\s*'.$metaTag.':\s*(.+)~',
            file_get_contents(
                $this->getCommandFile($command)
            ),
            $matches
        );

        list(, $matches) = $matches;

        return $matches;
    }

    /**
     * Read command single meta info
     *
     * Get only first match.
     *
     * @param string $command
     * @param string $metaTag
     * @return mixed
     */
    protected function readCommandSingleMeta($command, $metaTag)
    {
        $matches = $this->readCommandMeta($command, $metaTag);

        list(, $matches) = $matches;

        return trim($matches);
    }
}
