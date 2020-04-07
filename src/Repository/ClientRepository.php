<?php declare(strict_types=1);

namespace Repository;

use Model\Client;

class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
    public function findById(string $id): ?Client
    {
        return $this->getDataProvider()->get($id);
    }
}
