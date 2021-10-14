<?php declare(strict_types=1);

namespace Pmc\Commands;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CakePHPCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'cakephp';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'create a cakePHP project.';

    /**
     * Configuration of command
     * Setting name and description for symfony tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('cakephp')
            ->setDescription('create a cakePHP project.')
            ->setHelp('This command creates a cakePHP project.');

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'project name');
    }

    /**
     * Execution of command to install a cakePHP project in current directory
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
            '--prefer-dist',
            'cakephp/app:^4.0',
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