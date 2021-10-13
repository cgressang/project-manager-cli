<?php declare(strict_types=1);

namespace Pmc\Tests\Commands;

use Mockery;

use Pmc\Commands\CakePHPCommand;
use Pmc\Tests\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CakePHPCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private CakePHPCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('cakephp');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testInstall(): void
    {
        $name = 'testApp';

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name))
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

    public function testFailedInstall(): void
    {
        $name = 'testApp';

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name))
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

    protected function getProcessCommand(string $name): array
    {
        return [
            'composer',
            'create-project',
            '--prefer-dist',
            'cakephp/app:^4.0',
            $name,
        ];
    }
}