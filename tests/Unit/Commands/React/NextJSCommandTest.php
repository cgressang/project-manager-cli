<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\React;

use Pmc\Commands\React\NextJSCommand;
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class NextJSCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private NextJSCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('nextjs');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testInstall(): void
    {
        $typescript = false;
        $useNpm = false;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($typescript, $useNpm))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $result = $this->commandTester->execute([]);

        $this->assertEquals(NextJSCommand::SUCCESS, $result);
    }

    public function testOptionsInstall(): void
    {
        $typescript = true;
        $useNpm = true;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($typescript, $useNpm))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $result = $this->commandTester->execute([
            '--typescript' => $typescript,
            '--use-npm' => $useNpm,
        ]);

        $this->assertEquals(NextJSCommand::SUCCESS, $result);
    }

    public function testFailedInstall(): void
    {
        $typescript = false;
        $useNpm = false;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($typescript, $useNpm))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(false);

        $result = $this->commandTester->execute([]);
        $this->assertEquals(NextJSCommand::FAILURE, $result);
    }

    protected function getProcessCommand(bool $typescript, bool $useNpm): array
    {
        $commandArr = [
            'npx',
            'create-next-app@latest',
        ];

        if ($typescript) {
            $commandArr[] = '--typescript';
        }

        if ($useNpm) {
            $commandArr[] = '--use-npm';
        }

        return $commandArr;
    }
}