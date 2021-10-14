<?php declare(strict_types=1);

namespace Pmc\Tests;

use PHPUnit\Framework\TestCase;
use Pmc\Commands\{
    CakePHPCommand,
    LaravelCommand,
    LumenCommand,
    SlimCommand,
    SymfonyCommand
};
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Base Command Test Case
 */
class BaseCommandTestCase extends TestCase
{
    /**
     * Applicaiton. used to find command for testing
     *
     * @var Application
     */
    protected Application $application;

    /**
     * Process. used for mocking
     *
     * @var Process
     */
    protected Process $process;

    /**
     * Filesystem. used for mocking
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Commands. Add new commands to be tested
     *
     * @var array
     */
    private array $commands = [
        CakePHPCommand::class,
        LaravelCommand::class,
        LumenCommand::class,
        SlimCommand::class,
        SymfonyCommand::class,
    ];

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        // Create process mock
        $this->process = $this->createMock(Process::class);

        $this->filesystem = $this->createMock(Filesystem::class);

        $this->application = new Application();

        // Create partial mocks for commands and add them to application
        foreach ($this->commands as $command) {
            $mock = $this->getMockBuilder($command)
                ->setMethods(['process', 'filesystem'])
                ->getMock();

            $this->application->add($mock);
        }
    }
}