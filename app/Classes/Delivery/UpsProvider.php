<?php
namespace App\Classes\Delivery;

use App\Models\Order;
use App\Classes\Delivery\Contracts\Trackable;

class UpsProvider extends DeliveryProvider implements Trackable
{
    public function providerName(): string
    {
        return 'UPS';
    }
    //quote based on total amount percentage & base
    public function quote(Order $order): float
    {
        $baseRate   = 12.00;
        $percentage = $order->total_amount * 0.02;
        return $baseRate + $percentage;
    }

    //create shipment and return data
    public function createShipment(Order $order): array
    {
        $trackingNumber = '1Z' . strtoupper(uniqid());

        return [
            'provider'=>$this->providerName(),
            'tracking_number'=>$trackingNumber,
            'status'=>'label_created',
            'priority'=>false,
            'provider_response'=>[
                'message'=>'Shipment created via UPS',
                'tracking_number'=>$trackingNumber,
                'estimated_days'=>2,
            ],
        ];
    }
    public function track(string $trackingNumber): array
    {

        return [
            'tracking_number'=>$trackingNumber,
            'provider'=>$this->providerName(),
            'status'=>'out_for_delivery',
            'location'=>'Toronto, ON',
            'estimated_delivery'=>now()->addDays(1)->toDateString(),
        ];
    }
}
