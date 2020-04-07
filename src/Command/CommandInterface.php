<?php declare(strict_types=1);

namespace Command;

use Application\Console\InputInterface;
use Application\Console\OutputInterface;

interface CommandInterface
{
    public const RESPONSE_CODE_OK = 0;
    public const RESPONSE_CODE_FAIL = 1;

    public function execute(array $arguments, InputInterface $input, OutputInterface $output): int;
}
