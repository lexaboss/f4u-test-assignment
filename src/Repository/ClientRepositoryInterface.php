<?php declare(strict_types=1);

namespace Repository;

use Model\Client;

interface ClientRepositoryInterface
{
    public function findById(string $id): ?Client;
}
