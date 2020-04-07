<?php declare(strict_types=1);

namespace Application\DataProvider;

use Model\SerializableEntityInterface;
use RuntimeException;
use Throwable;

final class DataProvider implements DataProviderInterface
{
    /**
     * @var mixed
     */
    private                      $normalizer;
    private DataStorageInterface $dataProvider;

    /**
     * @param DataStorageInterface $dataProvider
     * @param mixed                $normalizer
     *
     * @throws RuntimeException
     */
    public function __construct(
        DataStorageInterface $dataProvider,
        $normalizer
    ) {
        $this->dataProvider = $dataProvider;
        $this->normalizer = $normalizer;

        if (!method_exists($this->normalizer, 'normalize')) {
            throw new RuntimeException('The normalizer must have a "normalize" method.');
        }
        if (!method_exists($this->normalizer, 'denormalize')) {
            throw new RuntimeException('The normalizer must have a "denormalize" method.');
        }
    }

    public function useCollection(string $name): self
    {
        $this->dataProvider->setCollection($name);

        return $this;
    }

    /**
     * @throws RuntimeException
     */
    public function get(string $key): ?SerializableEntityInterface
    {
        $object = $this->dataProvider->get($key);

        if (null === $object) {
            throw new RuntimeException(sprintf('Object with key %s can\'t be found', $key));
        }
        if (!isset($object['className']) || !class_exists($object['className'])) {
            throw new RuntimeException(sprintf('Object with key %s can\'t be deserialized', $key));
        }

        try {
            return $this->normalizer->denormalize($object, $object['className']);
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf('Object with key %s can\'t be deserialized', $key), $e->getCode(), $e);
        }
    }

    public function set(string $key, ?SerializableEntityInterface $value): void
    {
        $classSpecification = ['className' => get_class($value)];

        $this->dataProvider->set($key, array_merge(
            $this->normalizer->normalize($value),
            $classSpecification
        ));
    }

    public function remove(string $key): void
    {
        $this->dataProvider->remove($key);
    }
}
