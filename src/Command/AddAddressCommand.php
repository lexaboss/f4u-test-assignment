<?php declare(strict_types=1);

namespace Command;

use Application\Console\InputInterface;
use Application\Console\OutputInterface;
use Application\DataProvider\DataProviderInterface;
use Repository\ClientRepository;
use Services\ClientService;

class AddAddressCommand extends AbstractCommand
{
    private ClientService $clientService;

    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->clientService = new ClientService(new ClientRepository($dataProvider));
    }

    public function execute(array $arguments, InputInterface $input, OutputInterface $output): int
    {
        [$id] = $arguments;

        $shippingAddress = $this->promptAddressData($input, $output);
        $this->clientService->addShippingAddress($id, $shippingAddress);

        return self::RESPONSE_CODE_OK;
    }
}
