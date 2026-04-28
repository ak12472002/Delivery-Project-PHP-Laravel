<?php

namespace App\Classes;
use App\Models\Order;
use App\Models\Shipment;
use App\Classes\Delivery\Contracts\Trackable;

class ShippingService
{
    public function __construct(private DeliveryProviderFactory $factory) {}

    //create shipment uses default params for overloading
    //forcing provider method use for auto-selecting
    //priority if true mark the shipment as priority
    public function createShipment(Order $order, ?string $forceProvider = null, bool $priority = false): Shipment
    {
        //decide which provider to use

        $providerKey = $forceProvider ?? $this->selectProvider($order);

        //get the provider instance
        $provider = $this->factory->make($providerKey);

        //gets the quoted price
        $quotedPrice = $provider->quote($order);

        //create the shipment data array
        $shipmentData = $provider->createShipment($order);

        //if priority flag is set override prio in data

        if ($priority) {
            $shipmentData['priority'] = true;
        }

        //save the shipment to the database
        $shipment = Shipment::query()->create([
            'order_id'=>$order->id,
            'provider'=>$providerKey,
            'tracking_number'=>$shipmentData['tracking_number'],
            'status'=>$shipmentData['status'],
            'quoted_price'=>$quotedPrice,
            'currency'=>$order->currency ?? 'CAD',
            'priority'=>$shipmentData['priority'],
            'provider_response'=> $shipmentData['provider_response'],

        ]);

        return $shipment;
    }

    //track a shipment
    public function track(Shipment $shipment): array
    {
        $provider = $this->factory->make($shipment->provider);

        //heck if this provider supports tracking
        if (!($provider instanceof Trackable)) {
            return [
                'error'=>true,
                'message'=>"Provider [{$provider->providerName()}] doesnt have tracking",

            ];
        }

        return $provider->track($shipment->tracking_number);
    }

    private function selectProvider(Order $order): string
    {
        //select the cheapest provider

        $bestProvider=null;
        $bestPrice=PHP_FLOAT_MAX;


        foreach ($this->factory->available() as $key) {

            $provider=$this->factory->make($key);
            $price=$provider->quote($order);


            if ($price < $bestPrice) {

                $bestPrice=$price;
                $bestProvider=$key;
            }
        }

        return $bestProvider;
    }
}
