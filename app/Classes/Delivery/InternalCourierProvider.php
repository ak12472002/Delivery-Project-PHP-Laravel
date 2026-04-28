<?php
namespace App\Classes\Delivery;

use App\Models\Order;

//no tracking available
class InternalCourierProvider extends DeliveryProvider
{
    public function providerName(): string
    {
        return 'Internal Courier';
    }
    public function quote(Order $order): float
    {
        $itemCount = $order->items()->count();
        $baseRate  = 5.00;
        return $baseRate + ($itemCount * 1.50);
    }
    public function createShipment(Order $order): array
    {
        return [
            'provider'=> $this->providerName(),
            'tracking_number'=> null,
            'status'=> 'processing',
            'priority'=> false,
            'provider_response'=> [
                'message'=> 'Shipment created via internal courier',
                'order_id'=> $order->id,

            ],
        ];
    }
}
