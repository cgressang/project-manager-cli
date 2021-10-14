<?php declare(strict_types=1);

namespace Pmc\Commands;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ZendCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'zend';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'create a Zend project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('zend')
            ->setDescription('Create a Zend project.')
            ->setHelp('This command creates a Zend project.')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('mvc', null, InputOption::VALUE_NONE, 'mvc');
    }

    /**
     * Execution of command to install a Zend project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // Check for install package
        $package = Zend::FRAMEWORK_PACKAGE;
        if ($input->getOption('mvc')) {
            $package = Zend::MVC_PACKAGE;
        }

        $filesystem = $this->Filesystem();

        if (!$filesystem->exists($name)) {
            try {
                $filesystem->mkdir($name);
            } catch (IOExceptionInterface $exception) {
                $output->writeln(sprintf('<error>%s</error>', sprintf('Could not create directory: %s', $name)));
                return Command::FAILURE;
            }
        }

        // create new process
        $process = $this->process([
            'composer',
            'require',
            $package,
        ]);
        $process->setWorkingDirectory(getcwd().DIRECTORY_SEPARATOR.$name);

        $process->run();

        // Check and handle error from process
        if (!$process->isSuccessful()) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}