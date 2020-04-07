<?php declare(strict_types=1);

namespace Application\Console;

interface OutputInterface
{
    public function writeln(string $message): void;

    public function write(string $message): void;
}
