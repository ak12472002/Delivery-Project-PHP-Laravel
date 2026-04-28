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

<h1>Track Shipment</h1>
<a href="{{route('orders.show', $order->id)}}">Back to Order</a>
<br> <br>

@if(!$order->shipment)
    <p style="color: red;">This order has no shipment yet.</p>
@else
    <p><strong>Order ID:</strong> {{$order->id}}</p>
    <p><strong>Tracking Number:</strong> {{$order->shipment->tracking_number ?? 'N/A'}}</p>

    <br>

    @if($trackingResult)
        <h3>Tracking Result</h3>
        <p><strong>Provider:</strong> {{$trackingResult['provider']}}</p>
        <p><strong>Status:</strong> {{$trackingResult['status']}}</p>
        <p><strong>Location:</strong> {{$trackingResult['location'] ?? 'N/A'}}</p>
        <p><strong>Estimated Delivery:</strong> {{$trackingResult['estimated_delivery'] ?? 'N/A'}}</p>
    @else
        <p style="color: red;">Tracking is not available for this provider.</p>
    @endif

@endif

</body>

</html>
