<?php declare(strict_types=1);

namespace Application\DataProvider;

use Spatie\Valuestore\Valuestore;
use RuntimeException;

final class JsonDataStorage implements DataStorageInterface
{
    private Valuestore $store;
    private string $storageFolder;

    public function __construct(string $storageFolder)
    {
        $this->storageFolder = $storageFolder;
    }

    public function setCollection(string $collection): self
    {
        $filePath = $this->getDbFilePathFromCollectionName($collection);
        if (!is_file($filePath)) {
            throw new RuntimeException('Database file is not found' . $filePath);
        }
        $this->store = Valuestore::make($filePath);

        return $this;
    }

    public function get(string $key): ?array
    {
        $value = $this->store->get($key);
        if (null === $value) {
            return null;
        }

        return $value;
    }

    public function set(string $key, ?array $value): void
    {
        $this->store->put($key, $value);
    }

    public function remove(string $key): void
    {
        $this->store->forget($key);
    }

    private function getDbFilePathFromCollectionName(string $collection): string
    {
        return rtrim($this->storageFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . preg_replace('/[^A-Za-z0-9_\-]/', '', $collection) . '.json';
    }
}
