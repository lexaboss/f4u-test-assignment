<?php declare(strict_types=1);

namespace Model;

use ArrayIterator;
use DomainException;
use UnexpectedValueException;

final class ShippingAddressCollection extends ArrayIterator
{
    private const MAX_SHIPPING_ADDRESSES_COUNT = 3;

    public function __construct(array $collection = [])
    {
        if (!empty($collection)) {
        $collection = array_map(fn(array $address) => ShippingAddress::fromArray($address), $collection);}

        parent::__construct((array) $collection);
    }

    /** @throws DomainException */
    public function removeByIndex(int $shippingAddressIndex): void
    {
        if (!$this->offsetExists($shippingAddressIndex)) {
            throw new DomainException('Shipping address is not found.');
        }
        /** @var ShippingAddress $shippingAddress */
        $shippingAddress = $this->offsetGet($shippingAddressIndex);
        if ($shippingAddress->isDefault()) {
            throw new DomainException('Default shipping address can\'t be removed.');
        }

        $this->offsetUnset($shippingAddressIndex);
    }

    /**
     * @throws DomainException
     * @throws UnexpectedValueException
     */
    public function updateByIndex(int $shippingAddressIndex, ShippingAddress $updatedShippingAddress): void
    {
        /** @var ShippingAddress $oldShippingAddress */
        $oldShippingAddress = $this->offsetExists($shippingAddressIndex)
            ? $this->offsetGet($shippingAddressIndex)
            : null;
        if (null === $oldShippingAddress) {
            throw new DomainException('Shipping address is not found.');
        }

        if ($updatedShippingAddress->isDefault()) {
            $this->resetDefaultFlagForAllShippingAddress();
        } else {
            if (1 === $this->count()) {
                throw new UnexpectedValueException('Impossible to make not default the only shipping address.');
            } elseif ($oldShippingAddress->isDefault()) {
                $this->next();
                if (!$this->valid()) {
                    $this->rewind();
                }
                $this->current()->setDefault(true);
            }
        }
        $this->offsetSet((string) $shippingAddressIndex, $updatedShippingAddress);
    }

    /** @throws DomainException */
    public function add(ShippingAddress $shippingAddress): void
    {
        if (self::MAX_SHIPPING_ADDRESSES_COUNT <= $this->count()) {
            throw new DomainException('Max. shipping addresses count is exceeded.');
        }

        if (0 === $this->count()) {
            $shippingAddress->setDefault(true);
        }

        if ($shippingAddress->isDefault()) {
            $this->resetDefaultFlagForAllShippingAddress();
        }
        $this->append($shippingAddress);
    }

    private function resetDefaultFlagForAllShippingAddress(): void
    {
        $this->rewind();
        while ($this->valid())
        {
            $this->current()->setDefault(false);
            $this->next();
        }
    }
}
