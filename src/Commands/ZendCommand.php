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
    protected static $defaultDescription = 'create a zend project.';

    /**
     * Configuration of command
     * Setting name and description for zend tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('zend')
            ->setDescription('create a zend project.')
            ->setHelp('This command creates a zend project.');

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'project name')
            ->addOption('mvc', null, InputOption::VALUE_NONE, 'mvc');
    }

    /**
     * Execution of command to install a zend project in current directory
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $mvc = $input->getOption('mvc');

        // Check for install package
        if ($mvc) {
            $package = Zend::MVC_PACKAGE;
        } else {
            $package = Zend::FRAMEWORK_PACKAGE;
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
            $output->writeln($process->getErrorOutput());
            return Command::FAILURE;
        }

        $output->writeln('Installation complete');

        return Command::SUCCESS;
    }
}