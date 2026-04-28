<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Classes\DeliveryProviderFactory;
use App\Classes\ShippingService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function ship(Request $request, Order $order)
    {

        $request->validate([
            'provider'=>'nullable|string|in:internal,canadapost,ups',
            'priority'=>'nullable|boolean',
        ]);

        //make sure this order does not already have a shipment
        if ($order->shipment) {
            return response()->json([
                'message'=>'This order already has a shipment',
            ], 422);
        }

        //make sure the order is in pending status
        if ($order->status !== 'pending') {
            return response()->json([
                'message'=>'Only pending orders can be shipped',
            ], 422);
        }

        //load order items so providers can calculate quotes
//        $order->load('items','shipment');

        // create the shipping service with the factory
        $factory = new DeliveryProviderFactory();
        $shippingService = new ShippingService($factory);

        $shippingService->createShipment(

            $order,
            $request->provider,
            $request->boolean('priority', false)
        );

        // update the order status to shipped
        $order->update(['status'=>'shipped']);

        return redirect()->route('orders.show', $order->id)->with(['success'=>'Shipment created successfully']);

    }

    //post api orders methods
    public function apiShip(Request $request, Order $order)
    {
        $request->validate([
            'provider'=>'nullable|string|in:internal,canadapost,ups',
            'priority'=>'nullable|boolean',
        ]);

        if ($order->shipment) {
            return response()->json(['message'=>' Order already has a shipment'], 422);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be shipped'], 422);
        }

        $order->load('items');

        $factory = new DeliveryProviderFactory();
        $shippingService = new ShippingService($factory);

        $shipment = $shippingService->createShipment(
            $order,
            $request->provider,
            $request->boolean('priority', false)
        );
        $order->update(['status' => 'shipped']);

        return response()->json([
            'message'=>'Shipment created successfully',
            'shipment'=>$shipment,
        ], 201);
    }
}
