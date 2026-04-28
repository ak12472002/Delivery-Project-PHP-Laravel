<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;

use App\Classes\CurrencyService;


class OrderController extends Controller
{
    //get all orders so far
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    //get create orders
    public function create()
    {
        return view('orders.create');
    }


    // create a new customer and order and order items
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'=>'required|string',
            'customer_email'=>'required|email',
            'customer_phone'=>'nullable|string',
            'customer_address'=>'required|string',
            'customer_city'=>'required|string',
            'customer_province'=>'required|string',
            'customer_postal_code'=>'required|string',
            'customer_country'=>'required|string',
            'notes'=>'nullable|string',

            'item_name.*'=>'required|string',
            'item_quantity.*'=>'required|integer|min:1',
            'item_unit_price.*'=>'required|numeric|min:0',
            'item_weight_kg.*'=>'required|numeric|min:0',
        ]);

        //find or/and create the customer by email adderess
        $customer = Customer::firstOrCreate(
            ['email' => $request->customer_email],
            [
                'name'=>$request->customer_name,
                'phone'=>$request->customer_phone,
                'address'=>$request->customer_address,
                'city'=>$request->customer_city,
                'province'=>$request->customer_province,
                'postal_code'=>$request->customer_postal_code,
                'country'=>$request->customer_country,
            ]
        );

        //total amount from items
        $totalAmount = 0;
        foreach ($request->item_name as $i => $name) {
            $totalAmount += $request->item_quantity[$i] * $request->item_unit_price[$i];
        }

        $order = Order::create([
            'customer_id'=>$customer->id,
            'status' =>'pending',
            'total_amount'=>$totalAmount,
            'currency'=>'CAD',
            'notes'=>$request->notes,
        ]);

        foreach ($request->item_name as $i=>$name) {
            OrderItem::create([
                'order_id'=>$order->id,
                'name'=>$name,
                'quantity'=>$request->item_quantity[$i],
                'unit_price'=>$request->item_unit_price[$i],
                'weight_kg'=>$request->item_weight_kg[$i],
            ]);
        }

        return redirect()->route('orders.index', $order->id);
    }

    //GET show order details
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    //post api orders
    public function apiStore(Request $request)
    {
        $request->validate([
            'customer_name'=>'required|string',
            'customer_email' => 'required|email',
            'customer_phone'=>'nullable|string',
            'customer_address'=> 'required|string',
            'customer_city'=>'required|string',
            'customer_province'=> 'required|string',
            'customer_postal_code'=> 'required|string',
            'customer_country'=> 'required|string',
            'currency'=> 'nullable|string',
            'notes'=> 'nullable|string',
            'items'=> 'required|array|min:1',
            'items.*.name'=> 'required|string',
            'items.*.quantity'=> 'required|integer|min:1',
            'items.*.unit_price'=> 'required|numeric|min:0',
            'items.*.weight_kg'=> 'required|numeric|min:0',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => $request->customer_email],
            [
                'name'=>$request->customer_name,
                'phone'=>$request->customer_phone,
                'address'=>$request->customer_address,
                'city'=>$request->customer_city,
                'province'=>$request->customer_province,
                'postal_code'=>$request->customer_postal_code,
                'country'=>$request->customer_country,
            ]
        );

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity']* $item['unit_price'];
        }

        $order = Order::create([
            'customer_id'=>$customer->id,
            'status'=>'pending',
            'total_amount'=>$totalAmount,
            'currency'=>$request->currency ?? 'CAD',
            'notes'=>$request->notes,
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id'=>$order->id,
                'name'=>$item['name'],
                'quantity'=>$item['quantity'],
                'unit_price'=>$item['unit_price'],
                'weight_kg'=>$item['weight_kg'],
            ]);
        }

        $order->load('customer', 'items');

        return response()->json([
            'message'=>'Order created successfully',
            'order'=>$order,
        ], 201);
    }


    public function apiShow(Order $order)
    {
        $order->load('customer', 'items', 'shipment');

        $currencyService = new CurrencyService();
        $conversion = $currencyService->convert($order->total_amount, $order->currency ?? 'CAD', 'USD');

        return response()->json([
            'order'=>$order,
            'conversion'=>$conversion,
        ]);
    }

}
