<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\PHP;

use Pmc\Commands\PHP\{Zend, ZendCommand};
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Exception\IOException;

class ZendCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private ZendCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('zend');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testInstall(): void
    {
        $name = 'testApp';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(Zend::FRAMEWORK_PACKAGE))
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

        $this->assertEquals(ZendCommand::SUCCESS, $result);
    }

    public function testMvcInstall(): void
    {
        $name = 'testApp';
        $mvc = true;

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(Zend::MVC_PACKAGE))
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
            '--mvc' => $mvc,
        ]);

        $this->assertEquals(ZendCommand::SUCCESS, $result);
    }

    public function testMakeDirFail(): void
    {
        $name = 'testApp';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(false);

        $this->filesystem->expects($this->once())
            ->method('mkdir')
            ->with($name)
            ->will($this->throwException(new IOException('')));

        $result = $this->commandTester->execute([
            'name' => $name,
        ]);

        $this->assertEquals('Could not create directory: testApp', trim($this->commandTester->getDisplay()));
        $this->assertEquals(ZendCommand::FAILURE, $result);
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(Zend::FRAMEWORK_PACKAGE))
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

        $this->assertEquals(ZendCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $package): array
    {
        return [
            'composer',
            'require',
            $package,
        ];
    }
}