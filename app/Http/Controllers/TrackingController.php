<?php

namespace App\Http\Controllers;
use App\Models\Shipment;
use App\Models\Order;

use App\Classes\DeliveryProviderFactory;
use App\Classes\ShippingService;

class TrackingController extends Controller
{
    //track a shipment using its provider
    public function track(Order $order)
    {

        $trackingResult = null;
        if ($order->shipment) {
            $factory = new DeliveryProviderFactory();
            $shippingService = new ShippingService($factory);
            $trackingResult = $shippingService->track($order->shipment);
        }

        return view ('shipments.track', compact('order', 'trackingResult'));


    }
    //get track info
    public function apiTrack(Shipment $shipment)
    {
        $factory = new DeliveryProviderFactory();
        $shippingService = new ShippingService($factory);

        $trackingResult = $shippingService->track($shipment);

        return response()->json([
            'shipment_id'     => $shipment->id,
            'tracking_result' => $trackingResult,
        ]);
    }

}

