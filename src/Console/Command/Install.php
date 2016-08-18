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
        return [
            'git tags'       => sprintf(
                trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tags'))),
                str_replace('\\', '/', realpath(__DIR__.'/../../shell/version-sort.php'))
            ),
            'git tag-move'   => trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tag-move'))),
            'git tag-remove' => trim(file_get_contents(realpath(__DIR__.'/../../shell/command/git-tag-remove'))),
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
Install extra GIT commands. It will set
  - git tags (Sorted tags by SemVer)
  - git tag-move (Command which moves a tag to last commit. It will move it on "origin" as well)
  - git tag-remove (Command which removes a tag to last commit. It will remove it on "origin" as well)
TXT
        );
// @codingStandardsIgnoreEnd
        $this->setDescription(
            'Install extra GIT commands.'
        );
    }
}
