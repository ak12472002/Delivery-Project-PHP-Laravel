<?php

namespace App\Classes\Delivery;

use App\Models\Order;

abstract class DeliveryProvider
{
    //provider gives a price quote order
    abstract public function quote(Order $order): float;

    //provider creates shipment and return shipment data as array
    abstract public function createShipment(Order $order): array;

    //returns the provider name
    public function providerName(): string
    {
        return 'Unknown Provider';
    }
    //formats a shipment label from shipment array
    public function formatLabel(array $shipmentData): string
    {
        $tracking= $shipmentData['tracking_number']?? 'N/A';
        $provider= $shipmentData['provider']?? $this->providerName();
        $status= $shipmentData['status']?? 'pending';
        return "[{$provider}] Tracking: {$tracking} | Status: {$status}";
    }
}
