<?php declare(strict_types=1);

namespace Repository;

use Application\DataProvider\DataProviderInterface;
use Model\SerializableEntityInterface;

abstract class AbstractRepository
{
    protected string $entity;
    protected DataProviderInterface $dataProvider;

    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider->useCollection(str_replace('Repository', '', static::class));
    }

    protected function getDataProvider(): DataProviderInterface
    {
        return $this->dataProvider;
    }

    public function save(SerializableEntityInterface $entity): void
    {
        $this->dataProvider->set($entity->getId(), $entity);
    }
}
