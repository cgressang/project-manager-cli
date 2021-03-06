<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\React;

use Pmc\Commands\React\CreateReactAppCommand;
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateReactAppCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private CreateReactAppCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('createreactapp');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testInstall(): void
    {
        $name = 'testApp';
        $template = null;
        $useNpm = false;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $template, $useNpm))
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

        $this->assertEquals(CreateReactAppCommand::SUCCESS, $result);
    }

    public function testTemplateInstall(): void
    {
        $name = 'testApp';
        $template = 'typescript';
        $useNpm = true;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $template, $useNpm))
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
            '--template' => $template,
            '--use-npm' => $useNpm,
        ]);

        $this->assertEquals(CreateReactAppCommand::SUCCESS, $result);
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';
        $template = null;
        $useNpm = false;

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand($name, $template, $useNpm))
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
        $this->assertEquals(CreateReactAppCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $name, ?string $template, bool $useNpm): array
    {
        $commandArr = [
            'npx',
            'create-react-app',
            $name,
        ];

        if (!is_null($template)) {
            $commandArr[] = '--template';
            $commandArr[] = $template;
        }

        if ($useNpm) {
            $commandArr[] = '--use-npm';
        }

        return $commandArr;
    }
}