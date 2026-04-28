<?php
namespace App\Classes\Delivery;

use App\Models\Order;
use App\Classes\Delivery\Contracts\Trackable;

class CanadaPostProvider extends DeliveryProvider implements Trackable
{
    public function providerName(): string
    {
        return 'Canada Post';
    }

    //this gives estimate based on total weight of stuff
    public function quote(Order $order): float
    {
        $totalWeight = $order->items()->sum('weight_kg');
        $baseRate= 8.00;
        return $baseRate + ($totalWeight * 2.50);

    }
    public function createShipment(Order $order): array
    {
        $trackingNumber = 'CP' . strtoupper(uniqid());
        return [
            'provider'=> $this->providerName(),
            'tracking_number'=> $trackingNumber,
            'status'=> 'label_created',
            'priority'=> false,
            'provider_response'=> [
                'message'=> 'Shipment created with Canada Post',
                'tracking_number'=> $trackingNumber,
                'estimated_days'=> 5,
            ],
        ];
    }


    public function track(string $trackingNumber): array
    {
        return [
            'tracking_number'=> $trackingNumber,
            'provider'=> $this->providerName(),
            'status'=> 'in_transit',
            'location'=> 'Montreal, QC',
            'estimated_delivery'=> now()->addDays(3)->toDateString(),

        ];
    }
}
