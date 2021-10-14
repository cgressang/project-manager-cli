<?php declare(strict_types=1);

namespace Pmc\Commands;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LumenCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'lumen';


    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'create a Lumen project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('lumen')
            ->setDescription('create a Lumen project.')
            ->setHelp('This command creates a Lumen project.')
            ->addArgument('name', InputArgument::REQUIRED, 'project name')
            ->addOption('lumen-version', null, InputOption::VALUE_OPTIONAL, 'lumen version');
    }

    /**
     * Execution of command to install a Lumen project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $version = $input->getOption('lumen-version');

        // Version validation. default is lumen 8
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
            'laravel/lumen',
            $name,
            Laravel::ACTIVE_VERSIONS[$version]
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