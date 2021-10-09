<?php declare(strict_types=1);

namespace Pmc\Tests\Commands;

use Mockery;

use Pmc\Commands\{Symfony, SymfonyCommand};
use Pmc\Tests\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SymfonyCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private SymfonyCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('symfony');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testWebInstall(): void
    {
        $name = 'testApp';
        $package = Symfony::WEB_PACKAGE;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $package))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
    }

    public function testMicroServiceInstall(): void
    {
        $name = 'testApp';
        $package = Symfony::SKELETON_PACKAGE;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $package))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $this->commandTester->execute([
            'name' => $name,
            '--microservice' => true,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
    }

    public function testConsoleInstall(): void
    {
        $name = 'testApp';
        $package = Symfony::SKELETON_PACKAGE;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $package))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $this->commandTester->execute([
            'name' => $name,
            '--console' => true,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
    }

    public function testApiInstall(): void
    {
        $name = 'testApp';
        $package = Symfony::SKELETON_PACKAGE;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $package))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $this->commandTester->execute([
            'name' => $name,
            '--api' => true,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';
        $package = Symfony::WEB_PACKAGE;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $package))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(false);

        $this->process->expects($this->once())
            ->method('getErrorOutput')
            ->willReturn('FAILED');

        $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals('FAILED', trim($this->commandTester->getDisplay()));
    }

    protected function getProcessCommand(string $name, string $package): array
    {
        return [
            'composer',
            'create-project',
            $package,
            $name,
        ];
    }
}