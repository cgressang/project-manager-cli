<?php declare(strict_types=1);

namespace Pmc\Tests\Commands;

use Mockery;

use Pmc\Commands\{Zend, ZendCommand};
use Pmc\Tests\BaseCommandTestCase;
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

        $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
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

        $this->commandTester->execute([
            'name' => $name,
            '--mvc' => $mvc,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
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

        $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals('Could not create directory: testApp', trim($this->commandTester->getDisplay()));
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

        $this->process->expects($this->once())
            ->method('getErrorOutput')
            ->willReturn('FAILED');

        $this->commandTester->execute([
            'name' => $name,
        ]);
        $this->assertEquals('FAILED', trim($this->commandTester->getDisplay()));
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