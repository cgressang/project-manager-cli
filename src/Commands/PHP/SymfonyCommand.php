<?php declare(strict_types=1);

namespace Pmc\Commands\PHP;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'symfony';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'Create a Symfony project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('symfony')
            ->setDescription('Create a Symfony project.')
            ->setHelp('This command creates a Symfony project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('microservice', null, InputOption::VALUE_NONE, 'Microservice')
            ->addOption('console', null, InputOption::VALUE_NONE, 'Console')
            ->addOption('api', null, InputOption::VALUE_NONE, 'Api');
    }

    /**
     * Execution of command to install a Symfony project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // Check for install package
        $package = Symfony::WEB_PACKAGE;
        if (
            $input->getOption('microservice')
            || $input->getOption('console')
            || $input->getOption('api')
        ) {
            $package = Symfony::SKELETON_PACKAGE;
        }

        // create new process
        $process = $this->process([
            'composer',
            'create-project',
            $package,
            $name,
        ]);
        $process->setWorkingDirectory(getcwd());

        $process->run();

        // Check and handle error from process
        if (!$process->isSuccessful()) {
            $output->writeln($process->getErrorOutput());
            return Command::FAILURE;
        }

        $output->writeln('Installation complete');

        return Command::SUCCESS;
    }
}