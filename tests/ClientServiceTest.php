<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Model\Client;
use Model\ShippingAddress;
use Services\ClientService;
use Repository\ClientRepository;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @coversDefaultClass \Services\ClientService
 */
final class ClientServiceTest extends TestCase
{
    /** @var MockObject|ClientRepository */
    private               $clientRepository;
    private ClientService $clientService;

    public function setUp(): void
    {
        $this->clientRepository = $this->createMock(ClientRepository::class);
        $this->clientService = new ClientService($this->clientRepository);
    }

    /**
     * @dataProvider provideClientsForSuccessfulAddShippingAddressScenario
     * @covers ::addShippingAddress
     */
    public function testSuccessfulAddShippingAddressScenario(Client $client, ShippingAddress $shippingAddress, bool $willBeSaved): void
    {
        $this->clientRepository->expects($this->once())
            ->method('findById')
            ->willReturn($client);

        $this->clientRepository->expects($this->once())
            ->method('save');

        $isSaved = $this->clientService->addShippingAddress('test', $shippingAddress);
        $this->assertEquals($isSaved, $willBeSaved);
    }

    /**
     * @dataProvider provideClientsForSuccessfulUpdateShippingAddressScenario
     * @covers ::updateShippingAddress
     */
    public function testSuccessfulUpdateShippingAddressScenario(Client $client, int $addressIndex, bool $willBeUpdated): void
    {
        $shippingAddress = $client->getShippingAddresses()->offsetGet($addressIndex);

        $this->clientRepository->expects($this->once())
            ->method('findById')
            ->willReturn($client);

        $this->clientRepository->expects($this->once())
            ->method('save');

        $isSaved = $this->clientService->updateShippingAddress('test', $addressIndex, $shippingAddress);
        $this->assertEquals($isSaved, $willBeUpdated);
    }

    /**
     * @dataProvider provideClientsForUnsuccessfulUpdateShippingAddressScenario
     * @covers ::updateShippingAddress
     */
    public function testUnsuccessfulUpdateShippingAddressScenario(Client $client, int $addressIndex, bool $willBeUpdated): void
    {
        $shippingAddress = $client->getShippingAddresses()->offsetGet($addressIndex);

        $this->clientRepository->expects($this->once())
            ->method('findById')
            ->willReturn($client);

        $this->clientRepository->expects($this->once())
            ->method('save');

        $isSaved = $this->clientService->updateShippingAddress('test', $addressIndex, $shippingAddress);
        $this->assertEquals($isSaved, $willBeUpdated);
    }

    /**
     * @dataProvider provideClientsForExceptionOnRemoveShippingAddress
     * @covers ::removeShippingAddress
     */
    public function testExceptionOnRemoveShippingAddress(Client $client, int $deleteAddressIndex, string $exceptionMessage): void
    {
        $this->clientRepository->expects($this->once())
            ->method('findById')
            ->willReturn($client);

        $this->clientRepository->expects($this->never())
            ->method('save');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->clientService->removeShippingAddress('test', $deleteAddressIndex);
    }

    public function provideClientsForSuccessfulAddShippingAddressScenario(): array
    {
        return [
            [
                new Client('test', 'FirstName', 'LastName'),
                new ShippingAddress('LV','Riga','1000','Main str.',true),
                true
            ]
        ];
    }

    public function provideClientsForSuccessfulUpdateShippingAddressScenario(): array
    {
        return [
            [
                'client' => new Client('test', 'FirstName', 'LastName', [
                    0 => ['country' => 'LV', 'city' => 'Riga', 'zipcode' => '1000', 'street' => 'Main str.', 'default' => true],
                ]),
                'addressIndex' => 0,
                'willBeUpdated' => true
            ]
        ];
    }

    public function provideClientsForUnsuccessfulUpdateShippingAddressScenario(): array
    {
        return [
            [
                'client' => new Client('test', 'FirstName', 'LastName', [
                    0 => ['country' => 'LV', 'city' => 'Riga', 'zipcode' => '1000', 'street' => 'Main str.', 'default' => true],
                    1 => ['country' => 'LV', 'city' => 'Riga', 'zipcode' => '1000', 'street' => 'Main str.', 'default' => true],
                ]),
                'addressIndex' => 0,
                'willBeUpdated' => true
            ]
        ];
    }

    public function provideClientsForExceptionOnRemoveShippingAddress(): array
    {
        return [
            [
                'client' => new Client('test', 'FirstName', 'LastName', [
                    0 => ['country' => 'LV', 'city' => 'Riga', 'zipcode' => '1000', 'street' => 'Main str.', 'default' => true],
                    1 => ['country' => 'LV', 'city' => 'Riga', 'zipcode' => '1000', 'street' => 'Main str.', 'default' => true],
                ]),
                'deleteAddressIndex' => 0,
                'exceptionMessage' => 'Default shipping address can\'t be removed.'
            ]
        ];
    }
}
