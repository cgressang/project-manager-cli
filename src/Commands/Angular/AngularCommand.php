<?php declare(strict_types=1);

namespace Pmc\Commands\Angular;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class AngularCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'angular';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'Create a Angular project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('angular')
            ->setDescription('Create a Angular project.')
            ->setHelp('This command creates a Angular project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name');
    }

    /**
     * Execution of command to install a Angular project in current directory
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
            'ng',
            'new',
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