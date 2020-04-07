<?php declare(strict_types=1);

namespace Model;

final class ShippingAddress
{
    private string $country;
    private string $city;
    private string $zipcode;
    private string $street;
    private bool $default;

    public function __construct(
        string $country,
        string $city,
        string $zipcode,
        string $street,
        bool $default
    ) {
        $this->country = $country;
        $this->city = $city;
        $this->zipcode = $zipcode;
        $this->street = $street;
        $this->default = $default;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $isDefault): void
    {
        $this->default = $isDefault;
    }

    public static function fromArray(array $address): self
    {
        return new self(
            $address['country'],
            $address['city'],
            $address['zipcode'],
            $address['street'],
            $address['default']
        );
    }
}
