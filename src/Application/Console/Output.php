<?php declare(strict_types=1);

namespace Application\Console;

use RuntimeException;

final class Output implements OutputInterface
{
    /** @var resource */
    private $stdout;

    public function __construct()
    {
        $stdout = fopen('php://stdout', 'w');
        if (false === $stdout) {
            throw new RuntimeException('Command line is unavailable');
        }

        $this->stdout = $stdout;
    }

    /**
     * @throws RuntimeException
     */
    public function writeln(string $message): void
    {
        $this->write("$message\n");
    }

    /**
     * @throws RuntimeException
     */
    public function write(string $message): void
    {
        $result = fwrite($this->stdout, "$message");
        if (false === $result) {
            throw new RuntimeException('Can\'t write to standard stream input.');
        }
    }

    public function __destruct()
    {
        fclose($this->stdout);
    }
}
