<?php

namespace App\Classes;
use App\Classes\Delivery\DeliveryProvider;
use App\Classes\Delivery\CanadaPostProvider;
use App\Classes\Delivery\UpsProvider;
use App\Classes\Delivery\InternalCourierProvider;

class DeliveryProviderFactory
{
    private array $providers = [
        'internal'=> InternalCourierProvider::class,
        'canadapost'=> CanadaPostProvider::class,
        'ups'=> UpsProvider::class,
    ];
    public function make(string $providerKey): DeliveryProvider
    {
        if (!array_key_exists($providerKey, $this->providers)) {
            throw new \InvalidArgumentException("Provider [{$providerKey}] is not correct");
        }

        $class = $this->providers[$providerKey];
        return new $class();
    }

    // return all provider
    public function available(): array
    {
        return array_keys($this->providers);
    }

}
