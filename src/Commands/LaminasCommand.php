<?php declare(strict_types=1);

namespace Pmc\Commands;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class LaminasCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'laminas';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'create a laminas project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('laminas')
            ->setDescription('create a laminas project.')
            ->setHelp('This command creates a laminas project.')
            ->addArgument('name', InputArgument::REQUIRED, 'project name');
    }

    /**
     * Execution of command to install a laminas project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // create new process
        $process = $this->process([
            'composer',
            'create-project',
            '-s',
            'dev',
            Laminas::PACKAGE,
            $name,
        ]);
        $process->setWorkingDirectory(getcwd());

        $process->run();

        // Check and handle error from process
        if (!$process->isSuccessful()) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}