<?php declare(strict_types=1);

namespace Command;

use Application\Console\InputInterface;
use Application\Console\OutputInterface;
use Model\Client;
use Model\ShippingAddress;

abstract class AbstractCommand implements CommandInterface
{
    protected function outputClientShippingAddresses(Client $client, OutputInterface $output): void
    {
        if (0 === $client->getShippingAddresses()->count()) {
            $output->writeln('--- empty ---');

            return;
        }

        $shippingAddressCollection = $client->getShippingAddresses();
        $shippingAddressCollection->rewind();
        while ($shippingAddressCollection->valid())
        {
            $shippingAddress = $shippingAddressCollection->current();

            $output->writeln(sprintf(
                '[%s] %s, %s, %s, %s %s',
                $shippingAddressCollection->key(),
                $shippingAddress->getStreet(),
                $shippingAddress->getCity(),
                $shippingAddress->getCountry(),
                $shippingAddress->getZipcode(),
                true === $shippingAddress->isDefault() ? ' - is default' : ''
            ));
            $shippingAddressCollection->next();
        }
    }

    protected function promptAddressData(InputInterface $input, OutputInterface $output): ShippingAddress
    {
        $output->write('Input city:');
        $city = $input->prompt();

        $output->write('Input country:');
        $country = $input->prompt();

        $output->write('Input zipcode:');
        $zipcode = $input->prompt();

        $output->write('Input street:');
        $street = $input->prompt();

        $output->write('Is default? (y,n)');
        $isDefault = $input->prompt(['y','n']) === 'y';

        return new ShippingAddress(
            $city,
            $country,
            $zipcode,
            $street,
            $isDefault
        );
    }
}
