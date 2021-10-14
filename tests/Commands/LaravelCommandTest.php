<?php declare(strict_types=1);

namespace Pmc\Tests\Commands;

use Mockery;

use Pmc\Commands\{Laravel, LaravelCommand};
use Pmc\Tests\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class LaravelCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private LaravelCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('laravel');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testWithWrongVersionOption(): void
    {
        $result = $this->commandTester->execute([
            'name' => 'test',
            '--laravel-version' => '2',
        ]);

        $this->assertEquals('Valid versions: 6,8', trim($this->commandTester->getDisplay()));
        $this->assertEquals(LaravelCommand::FAILURE, $result);
    }

    public function testNoVersionOption(): void
    {
        $name = 'testApp';
        $version = Laravel::ACTIVE_VERSIONS[8];

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $version))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);

        $this->assertEquals(LaravelCommand::SUCCESS, $result);
    }

    public function testVersionOptionSix(): void
    {
        $name = 'testApp';
        $version = Laravel::ACTIVE_VERSIONS[6];

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $version))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
        ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true);

        $result = $this->commandTester->execute([
            'name' => $name,
            '--laravel-version' => 6
        ]);

        $this->assertEquals(LaravelCommand::SUCCESS, $result);
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';
        $version = Laravel::ACTIVE_VERSIONS[8];

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $version))
            ->willReturn($this->process);

        $this->process->expects($this->once())
            ->method('setWorkingDirectory');

        $this->process->expects($this->once())
            ->method('run');

        $this->process->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(false);

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);

        $this->assertEquals(LaravelCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $name, string $version): array
    {
        return [
            'composer',
            'create-project',
            '--prefer-dist',
            'laravel/laravel',
            $name,
            $version
        ];
    }
}