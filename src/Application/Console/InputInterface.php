<?php declare(strict_types=1);

namespace Application\Console;

interface InputInterface
{
    public function prompt(?array $values = null): string;
}
