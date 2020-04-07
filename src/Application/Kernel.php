<?php declare(strict_types=1);

namespace Application;

use Application\Console\Input;
use Application\Console\Output;
use Application\DataProvider\DataProvider;
use Application\DataProvider\JsonDataStorage;
use Command\CommandInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use RuntimeException;

final class Kernel
{
    /**
     * @throws RuntimeException
     */
    public function run(array $arguments): int
    {
        return $this
            ->createCommand($arguments[1])
            ->execute(array_slice($arguments, 2), new Input(), new Output());
    }

    /**
     * @throws RuntimeException
     */
    private function createCommand(string $commandName): CommandInterface
    {
        $command = $this->resolveCommandClassName($commandName);
        $dataProvider = new DataProvider(
            new JsonDataStorage($_ENV['DB_PATH']),
            new Serializer([new ObjectNormalizer(null, null, null, new ReflectionExtractor()), new ArrayDenormalizer()])
        );

        return new $command($dataProvider);
    }

    /**
     * @throws RuntimeException
     */
    private function resolveCommandClassName(string $commandName): string
    {
        $commandClassName =  $this->normalizeCommandClassName($commandName);

        if (!class_exists($commandClassName)) {
            throw new RuntimeException(sprintf('Command %s doesn\'t exists.', $commandName));
        }

        return $commandClassName;
    }

    private function normalizeCommandClassName(string $commandName): string
    {
        $commandNameChunks = array_map(
            fn($chunk) => ucfirst($chunk),
            explode('-', basename($commandName))
        );

        return 'Command\\' . implode('', $commandNameChunks) . 'Command';
    }
}
