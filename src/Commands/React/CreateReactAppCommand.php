<?php declare(strict_types=1);

namespace Pmc\Commands\React;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateReactAppCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'createreactapp';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'Create a React App project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('createreactapp')
            ->setDescription('Create a React App project.')
            ->setHelp('This command creates a React App project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('template', null, InputOption::VALUE_REQUIRED, 'Template');
    }

    /**
     * Execution of command to install a React App project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $template = $input->getOption('template');

        $commandArr = [
            'npx',
            'create-react-app',
            $name,
        ];

        if ($template) {
            $commandArr[] = '--template';
            $template[] = $template;
        }

        // create new process
        $process = $this->process($commandArr);
        $process->setWorkingDirectory(getcwd());

        $process->run();

        // Check and handle error from process
        if (!$process->isSuccessful()) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}