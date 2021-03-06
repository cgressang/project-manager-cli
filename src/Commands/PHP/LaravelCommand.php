<?php declare(strict_types=1);

namespace Pmc\Commands\PHP;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LaravelCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'laravel';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'Create a Laravel project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('laravel')
            ->setDescription('Create a Laravel project.')
            ->setHelp('This command creates a Laravel project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('laravel-version', null, InputOption::VALUE_OPTIONAL, 'Laravel version');
    }

    /**
     * Execution of command to install a Laravel project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $version = $input->getOption('laravel-version');

        // Version validation. default is laravel 8
        if (!$version) {
            $version = Laravel::VERSION_EIGHT;
        } else if (!array_key_exists($version, Laravel::ACTIVE_VERSIONS)) {
            $validVersions = array_keys(Laravel::ACTIVE_VERSIONS);
            $output->writeln(sprintf('<error>%s</error>', sprintf('Valid versions: %s', implode(',', $validVersions))));
            return Command::FAILURE;
        }

        // create new process
        $process = $this->process([
            'composer',
            'create-project',
            '--prefer-dist',
            Laravel::PACKAGE,
            $name,
            Laravel::ACTIVE_VERSIONS[$version]
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