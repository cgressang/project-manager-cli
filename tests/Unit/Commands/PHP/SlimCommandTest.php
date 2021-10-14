<?php declare(strict_types=1);

namespace Pmc\Tests\Unit\Commands\PHP;

use Pmc\Commands\PHP\{Slim, SlimCommand};
use Pmc\Tests\Unit\Commands\BaseCommandTestCase;
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
        $psr7 = Slim::GUZZLE;

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(SLIM::PACKAGE, SLIM::PSR_PACKAGES[$psr7]))
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
            'psr7' => $psr7,
        ]);

        $this->assertEquals(SlimCommand::SUCCESS, $result);
    }

    public function testWrongPsr(): void
    {
        $name = 'testApp';
        $psr7 = 'testing';

        $result = $this->commandTester->execute([
            'name' => $name,
            'psr7' => $psr7,
        ]);

        $this->assertEquals('Valid psr-7: slim,nyholm,guzzle,laminas', trim($this->commandTester->getDisplay()));
        $this->assertEquals(SlimCommand::FAILURE, $result);
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

        $result = $this->commandTester->execute([
            'name' => $name,
            'psr7' => $psr7,
        ]);

        $this->assertEquals('Could not create directory: testApp', trim($this->commandTester->getDisplay()));
        $this->assertEquals(SlimCommand::FAILURE, $result);
    }

    public function testFailedInstall(): void
    {
        $name = 'testApp';
        $psr7 = Slim::NYHOLM;

        $this->testCommand->expects($this->once())
            ->method('filesystem')
            ->willReturn($this->filesystem);

        $this->filesystem->expects($this->once())
            ->method('exists')
            ->with($name)
            ->willReturn(true);

        $this->testCommand->expects($this->once())
            ->method('process')
            ->with($this->getProcessCommand(SLIM::PACKAGE, SLIM::PSR_PACKAGES[$psr7]))
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
            'psr7' => $psr7,
        ]);

        $this->assertEquals(SlimCommand::FAILURE, $result);
    }

    protected function getProcessCommand(string $slimPackage, array $psr7Package): array
    {
        $commandArr = [
            'composer',
            'require',
            $slimPackage,
        ];

        return array_merge($commandArr, $psr7Package);
    }
}