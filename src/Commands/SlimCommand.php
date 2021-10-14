<?php declare(strict_types=1);

namespace Pmc\Commands;

use Pmc\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class SlimCommand extends Command
{
    /**
     * Command name
     * @var string
     */
    protected static $defaultName = 'slim';

    /**
     * Command description
     * @var string
     */
    protected static $defaultDescription = 'create a slim project.';

    /**
     * Configuration of command
     * Setting name and description for tests.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('slim')
            ->setDescription('create a slim project.')
            ->setHelp('This command creates a slim project.')
            ->addArgument('name', InputArgument::REQUIRED, 'project name')
            ->addArgument('psr7', InputArgument::REQUIRED, 'what psr-7 implementation to use?');
    }

    /**
     * Execution of command to install a slim project.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $psr7 = strtolower($input->getArgument('psr7'));

        $psrOptions = array_keys(Slim::PSR_PACKAGES);

        if (!in_array($psr7, $psrOptions)) {
            $output->writeln(sprintf('<error>%s</error>', sprintf('Valid psr-7: %s', implode(',', $psrOptions))));
            return Command::FAILURE;
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

        $commandArr = [
            'composer',
            'require',
            SLIM::PACKAGE,
        ];

        $commandArr = array_merge($commandArr, SLIM::PSR_PACKAGES[$psr7]);

        // create new process for slim and psr7 install
        $process = $this->process($commandArr);
        $process->setWorkingDirectory(getcwd().DIRECTORY_SEPARATOR.$name);

        $process->run();

        // Check and handle error from process
        if (!$process->isSuccessful()) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}