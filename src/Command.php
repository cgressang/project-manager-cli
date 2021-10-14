<?php declare(strict_types=1);

namespace Pmc;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Base Command
 */
class Command extends SymfonyCommand
{
    /**
     * Creates new process
     *
     * @param  array  $command
     * @return Process
     */
    public function process(array $command): Process
    {
        return new Process($command);
    }

    /**
     * Creates new filesystem
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem
    {
        return new Filesystem();
    }
}