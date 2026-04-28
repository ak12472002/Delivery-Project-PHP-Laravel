<?php

namespace App\Classes\Delivery\Contracts;

interface Trackable
{
    // any provider that implements has to  provide a tracking method
    public function track(string $trackingNumber): array;

}
