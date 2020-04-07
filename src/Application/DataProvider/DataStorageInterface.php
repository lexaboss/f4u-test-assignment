<?php declare(strict_types=1);

namespace Application\DataProvider;

use RuntimeException;

interface DataStorageInterface
{
    /** @throws RuntimeException */
    public function setCollection(string $collection): self;

    public function get(string $key): ?array;

    public function set(string $key, ?array $value): void;

    public function remove(string $key): void;
}
