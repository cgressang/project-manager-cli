<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\PHP;

use Pmc\Commands\PHP\{Laminas, LaminasCommand};
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Exception\IOException;

class LaminasCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private LaminasCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('laminas');
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

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals(LaminasCommand::SUCCESS, $result);
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

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals(LaminasCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $name): array
    {
        return [
            'composer',
            'create-project',
            '-s',
            'dev',
            Laminas::PACKAGE,
            $name,
        ];
    }
}