<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\PHP;

use Pmc\Commands\PHP\{CakePHP, CakePHPCommand};
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
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

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);

        $this->assertEquals(CakePHPCommand::SUCCESS, $result);
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
        $this->assertEquals(CakePHPCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $name): array
    {
        return [
            'composer',
            'create-project',
            '--prefer-dist',
            CakePHP::PACKAGE,
            $name,
        ];
    }
}