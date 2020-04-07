<?php declare(strict_types=1);

namespace Command;

use Application\Console\InputInterface;
use Application\Console\OutputInterface;
use Application\DataProvider\DataProviderInterface;
use Repository\ClientRepository;
use Services\ClientService;
use UnexpectedValueException;

class GetAddressCommand extends AbstractCommand
{
    private ClientService $clientService;

    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->clientService = new ClientService(new ClientRepository($dataProvider));
    }

    public function execute(array $arguments, InputInterface $input, OutputInterface $output): int
    {
        [$id] = $arguments;
        try {
            $client = $this->clientService->findById($id);
        } catch (UnexpectedValueException $e) {
            $output->writeln(sprintf('Client can\'t be loaded (%s).', $e->getMessage()));

            return self::RESPONSE_CODE_FAIL;
        }

        $output->writeln(sprintf('Client %s %s shipping address list:', $client->getFirstName(), $client->getLastName()));
        $this->outputClientShippingAddresses($client, $output);

        return self::RESPONSE_CODE_OK;
    }
}
