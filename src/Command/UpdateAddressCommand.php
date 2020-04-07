<?php declare(strict_types=1);

namespace Command;

use Application\Console\InputInterface;
use Application\Console\OutputInterface;
use Application\DataProvider\DataProviderInterface;
use Repository\ClientRepository;
use Services\ClientService;
use UnexpectedValueException;

class UpdateAddressCommand extends AbstractCommand
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

        $this->outputClientShippingAddresses($client, $output);
        $output->write('Choose shipping address index to edit:');
        $shippingAddressIndex = $input->prompt(
            array_keys((array) $client->getShippingAddresses())
        );

        $shippingAddress = $this->promptAddressData($input, $output);
        $this->clientService->updateShippingAddress($id, (int) $shippingAddressIndex, $shippingAddress);

        return self::RESPONSE_CODE_OK;
    }
}
