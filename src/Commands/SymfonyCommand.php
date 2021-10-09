<?php declare(strict_types=1);

namespace Pmc\Commands;

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
    protected static $defaultDescription = 'create a symfony project.';

    /**
     * Configuration of command
     * Setting name and description for symfony tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('symfony')
            ->setDescription('create a symfony project.')
            ->setHelp('This command creates a symfony project.');

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'project name')
            ->addOption('microservice', null, InputOption::VALUE_OPTIONAL, 'microservice')
            ->addOption('console', null, InputOption::VALUE_OPTIONAL, 'console')
            ->addOption('api', null, InputOption::VALUE_OPTIONAL, 'api');
    }

    /**
     * Execution of command to install a symfony project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $microservice = $input->getOption('microservice');
        $console = $input->getOption('console');
        $api = $input->getOption('api');

        // Check for install package
        if ($microservice || $console || $api) {
            $package = Symfony::SKELETON_PACKAGE;
        } else {
            $package = Symfony::WEB_PACKAGE;
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