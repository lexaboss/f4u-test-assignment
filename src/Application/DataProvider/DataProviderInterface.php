<?php declare(strict_types=1);

namespace Application\DataProvider;

use Model\SerializableEntityInterface;

interface DataProviderInterface
{
    public function useCollection(string $name): self;

    public function get(string $key): ?SerializableEntityInterface;

    public function set(string $key, ?SerializableEntityInterface $value): void;

    public function remove(string $key): void;
}
