<?php declare(strict_types=1);

namespace Pmc\Tests\Commands;

use Mockery;

use Pmc\Commands\{Slim, SlimCommand};
use Pmc\Tests\BaseCommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Exception\IOException;

class SlimCommandTest extends BaseCommandTestCase
{
    private CommandTester $commandTester;

    private SlimCommand $testCommand;

    public function setUp(): void
    {
        parent::setUp();

        $this->testCommand = $this->application->find('slim');
        $this->commandTester = new CommandTester($this->testCommand);
    }

    public function testInstall(): void
    {
        $name = 'testApp';
        $psr7 = 'guzzle';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->exactly(2))
            ->method('process')
            ->withConsecutive(
                [$this->getProcessCommand(SLIM::PACKAGE)],
                [$this->getProcessCommand(SLIM::PSR_PACKAGES['guzzle'])])
            ->willReturn($this->process);

        $this->process->expects($this->exactly(2))
            ->method('setWorkingDirectory');

        $this->process->expects($this->exactly(2))
            ->method('run');

        $this->process->expects($this->exactly(2))
            ->method('isSuccessful')
            ->willReturn(true);

        $this->commandTester->execute([
            'name' => $name,
            'psr7' => $psr7,
        ]);
        $this->assertEquals('Installation complete', trim($this->commandTester->getDisplay()));
    }

    public function testWrongPsr(): void
    {
        $name = 'testApp';
        $psr7 = 'testing';

        $this->commandTester->execute([
            'name' => $name,
            'psr7' => $psr7,
        ]);
        $this->assertEquals('Valid psr-7: slim,nyholm,guzzle,laminas', trim($this->commandTester->getDisplay()));
    }

    public function testMakeDirFail(): void
    {
        $name = 'testApp';
        $psr7 = 'slim';

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
            'psr7' => $psr7,
        ]);
        $this->assertEquals('Could not create directory: testApp', trim($this->commandTester->getDisplay()));
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';
        $psr7 = 'nyholm';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(SLIM::PACKAGE))
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
            'psr7' => $psr7,
        ]);
        $this->assertEquals('FAILED', trim($this->commandTester->getDisplay()));
    }

   public function testFailedPsrInstall(): void
    {
        $name = 'testApp';
        $psr7 = 'nyholm';

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->exactly(2))
            ->method('process')
            ->withConsecutive(
                [$this->getProcessCommand(SLIM::PACKAGE)],
                [$this->getProcessCommand(SLIM::PSR_PACKAGES['nyholm'])])
            ->willReturn($this->process);

        $this->process->expects($this->exactly(2))
            ->method('setWorkingDirectory');

        $this->process->expects($this->exactly(2))
            ->method('run');

        $this->process->expects($this->exactly(2))
            ->method('isSuccessful')
            ->will($this->onConsecutiveCalls(true, false));

        $this->process->expects($this->once())
            ->method('getErrorOutput')
            ->willReturn('FAILED');

        $this->commandTester->execute([
            'name' => $name,
            'psr7' => $psr7,
        ]);
        $this->assertEquals('FAILED', trim($this->commandTester->getDisplay()));
    }

    protected function getProcessCommand($package): array
    {
        $commandArr = [
            'composer',
            'require',
        ];

        if (!is_array($package)) {
            $commandArr[] = $package;
            return $commandArr;
        } else {
            return array_merge($commandArr, $package);
        }
    }
}