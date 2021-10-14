<?php declare(strict_types=1);

namespace Pmc\Commands\PHP;

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
    protected static $defaultDescription = 'Create a CakePHP project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('cakephp')
            ->setDescription('Create a CakePHP project.')
            ->setHelp('This command creates a CakePHP project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name');
    }

    /**
     * Execution of command to install a CakePHP project in current directory
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
            CakePHP::PACKAGE,
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