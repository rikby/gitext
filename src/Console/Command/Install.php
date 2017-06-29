<?php
namespace Rikby\GitExt\Console\Command;

use Rikby\Console\Command\AbstractCommand;
use Rikby\Console\Helper\Shell\ShellHelper;
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
     */
    protected function getCommands()
    {
        if (null === $this->commands) {
            $this->commands = [
                'git tags' => sprintf(
                    trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tags'))),
                    str_replace('\\', '/', realpath(__DIR__.'/../../../bin/gitext-sort-versions.php'))
                ),

                'git flow-namespace' => sprintf(
                    trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-flow-namespace'))),
                    str_replace('\\', '/', realpath(__DIR__.'/../../shell/git-flow-namespace-branch.sh'))
                ),

                'git tag-semver' => sprintf(
                    trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tag-semver'))),
                    str_replace('\\', '/', realpath(__DIR__.'/../../shell/git-tag-semver-branch.sh'))
                ),

                'git tag-preminor-alpha' => sprintf(
                    trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tag-preminor-alpha'))),
                    str_replace('\\', '/', realpath(__DIR__.'/../../shell/git-tag-preminor-alpha'))
                ),

                'git tag-prerelease' => sprintf(
                    trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tag-prerelease'))),
                    str_replace('\\', '/', realpath(__DIR__.'/../../shell/git-tag-prerelease'))
                ),
            ];
        }

        return $this->commands;
    }

    /**
     * Get commands list help
     *
     * @return mixed
     */
    protected function getCommandsHelp()
    {
        return [
            'git flow-namespace'     => 'Set GitFlow configuration by namespace in multi- composer repository.',

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
