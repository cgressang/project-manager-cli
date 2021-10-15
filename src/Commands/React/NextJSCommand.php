<?php declare(strict_types=1);

namespace Pmc\Commands\React;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NextJSCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'nextjs';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'Create a NextJS project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('nextjs')
            ->setDescription('Create a NextJS project.')
            ->setHelp('This command creates a NextJS project.')
            ->addOption('typescript', null, InputOption::VALUE_NONE, 'Initialize as a typescript project')
            ->addOption('use-npm', null, InputOption::VALUE_NONE, 'Bootstrap using npm');
    }

    /**
     * Execution of command to install a NextJS project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = $input->getOption('typescript');
        $useNpm = $input->getOption('use-npm');

        $commandArr = [
            'npx',
            'create-next-app@latest',
        ];

        if ($template) {
            $commandArr[] = '--typescript';
        }

        if ($useNpm) {
            $commandArr[] = '--use-npm';
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