<?php declare(strict_types=1);

namespace Model;

class Client implements SerializableEntityInterface
{
    private string $id;
    private string $firstName;
    private string $lastName;

    /**
     * @var ShippingAddress[]|ShippingAddressCollection
     */
    private ShippingAddressCollection $shippingAddresses;

    public function __construct(string $id, string $firstName, string $lastName, array $shippingAddresses = [])
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->shippingAddresses = new ShippingAddressCollection($shippingAddresses);
    }

    /**
     * @return  ShippingAddress[]|ShippingAddressCollection
     */
    public function getShippingAddresses()
    {
        return $this->shippingAddresses;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
