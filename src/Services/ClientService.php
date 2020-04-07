<?php declare(strict_types=1);

namespace Services;

use Repository\ClientRepository;
use Model\Client;
use Model\ShippingAddress;
use DomainException;
use UnexpectedValueException;

class ClientService
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function findById(string $id): Client
    {
        if (($client = $this->clientRepository->findById($id)) === null) {
            throw new UnexpectedValueException(sprintf('Client is not found (%s).', $id));
        }

        return $client;
    }

    /**
     * @throws DomainException
     */
    public function addShippingAddress(string $id, ShippingAddress $shippingAddress): bool
    {
        if (($client = $this->clientRepository->findById($id)) === null) {
            return false;
        }

        $client->getShippingAddresses()->add($shippingAddress);
        $this->clientRepository->save($client);

        return true;
    }

    /**
     * @throws DomainException
     * @throws UnexpectedValueException
     */
    public function updateShippingAddress(string $id, int $addressIndex, ShippingAddress $shippingAddress): bool
    {
        if (($client = $this->clientRepository->findById($id)) === null) {
            return false;
        }

        $client->getShippingAddresses()->updateByIndex($addressIndex, $shippingAddress);
        $this->clientRepository->save($client);

        return true;
    }

    /**
     * @throws DomainException
     */
    public function removeShippingAddress(string $id, int $addressIndex): bool
    {
        if (($client = $this->clientRepository->findById($id)) === null) {
            return false;
        }

        $client->getShippingAddresses()->removeByIndex($addressIndex);
        $this->clientRepository->save($client);

        return true;
    }
}
