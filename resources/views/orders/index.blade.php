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
<h1>Orders List</h1>
<a href="{{route('orders.create')}}">Create New Order</a>
<br>
<br>

@if(session('success'))
    <p style="color: green;">{{session('success')}}</p>
@endif
@if(session('error'))
    <p style="color: red;">{{session('error')}}</p>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Total (CAD)</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    @foreach($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->customer->name}}</td>
            <td>{{$order->status}}</td>
            <td>${{number_format($order->total_amount, 2)}}</td>
            <td>{{$order->created_at->format('Y-m-d')}}</td>
            <td>
                <a href="{{route('orders.show', $order->id)}}">View</a>
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
