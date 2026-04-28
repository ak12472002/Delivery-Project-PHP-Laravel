<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delivery Hub</title>
</head>
<body>

<h1>Order Details</h1>
<a href="{{route('orders.index')}}">Back to Orders</a>
<br><br>

@if(session('success'))
    <p style="color: green;">{{session('success')}}</p>
@endif
@if(session('error'))
    <p style="color: red;">{{session('error')}}</p>
@endif

<p><strong>ID:</strong> {{$order->id}}</p>
<p><strong>Status:</strong> {{$order->status}}</p>
<p><strong>Total:</strong> ${{number_format($order->total_amount, 2)}} {{$order->currency}}</p>
<p><strong>Notes:</strong> {{$order->notes ?? 'N/A'}}</p>
<p><strong>Date:</strong> {{$order->created_at->format('Y-m-d H:i')}}</p>

<br>

<h3>Customer</h3>
<p><strong>Name:</strong> {{$order->customer->name}}</p>
<p><strong>Email:</strong> {{$order->customer->email}}</p>
<p><strong>Phone:</strong> {{$order->customer->phone ?? 'N/A'}}</p>
<p><strong>Address:</strong> {{$order->customer->address}}, {{$order->customer->city}}, {{$order->customer->province}} {{$order->customer->postal_code}}, {{$order->customer->country}}</p>

<br>

<h3>Order Items</h3>
<table border="1" cellpadding="10">
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Weight (kg)</th>
        <th>Subtotal</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->quantity}}</td>
            <td>${{number_format($item->unit_price, 2)}}</td>
            <td>{{$item->weight_kg}}</td>
            <td>${{number_format($item->quantity * $item->unit_price, 2)}}</td>
        </tr>
    @endforeach
</table>

<br>

<h3>Shipment</h3>
@if($order->shipment)
    <p><strong>Provider:</strong> {{$order->shipment->provider}}</p>
    <p><strong>Tracking Number:</strong> {{$order->shipment->tracking_number ?? 'N/A'}}</p>
    <p><strong>Status:</strong> {{$order->shipment->status}}</p>
    <p><strong>Quoted Price:</strong> ${{number_format($order->shipment->quoted_price, 2)}} {{$order->shipment->currency}}</p>
    <p><strong>Priority:</strong> {{$order->shipment->priority ? 'Yes' : 'No'}}</p>
    <br>
    <!--track shipping link-->
    <a href="{{route('orders.track', $order->id)}}">Track Shipment</a>
@else
    <p>No shipment yet.</p>

    @if($order->status === 'pending')
        <h4>Ship this Order</h4>
        <form method="POST" action="{{route('orders.ship', $order->id)}}">
            @csrf

            <label>Provider:</label><br>
            <select name="provider">
                <option value="internal">Internal Courier</option>
                <option value="canadapost">Canada Post</option>
                <option value="ups">UPS</option>
            </select>
            <br>
            <br>

            <label>Priority:</label><br>
            <select name="priority">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <br>
            <br>

            <button type="submit">Ship Order</button>
        </form>

    @endif
@endif

</body>
</html>
