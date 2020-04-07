<?php declare(strict_types=1);

namespace Application\Console;

use RuntimeException;

final class Input implements InputInterface
{
    /** @var resource */
    private $stdin;

    public function __construct()
    {
        $this->stdin = fopen('php://stdin', 'r');
        if (false === $this->stdin) {
            throw new RuntimeException('Command line is unavailable');
        }
    }

    /**
     * @throws RuntimeException
     */
    public function prompt(?array $values = null): string
    {
        $input = fgets($this->stdin);
        if (false === $input) {
            throw new RuntimeException('Can\'t read from standard stream input.');
        }

        if (null !== $values) {
            $values = array_map( fn ($value) => (string) $value, $values);
            if (!in_array(trim($input), $values, true)) {
                return $this->prompt($values);
            }
        }

        return trim($input);
    }

    public function __destruct()
    {
        fclose($this->stdin);
    }
}
