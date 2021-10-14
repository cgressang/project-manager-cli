<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\PHP;

use Pmc\Commands\PHP\{CodeIgniter, CodeIgniterCommand};
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CodeIgniterCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private CodeIgniterCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('codeigniter');
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

        $this->assertEquals(CodeIgniterCommand::SUCCESS, $result);
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
        $this->assertEquals(CodeIgniterCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $name): array
    {
        return [
            'composer',
            'create-project',
            CodeIgniter::PACKAGE,
            $name,
        ];
    }
}