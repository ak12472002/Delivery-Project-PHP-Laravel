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
<h1>Create New Order</h1>
<a href="{{route('orders.index')}}">Back to Orders</a>
<br>
<br>

@if($errors->any())
    @foreach($errors->all() as $error)
        <p style="color: red;">{{$error}}</p>
    @endforeach
@endif

<form method="POST" action="{{route('orders.store')}}">
    @csrf

    <h3>Customer Info</h3>

    <label>Name:</label><br>
    <input type="text" name="customer_name" value="{{old('customer_name')}}">
    <br>
    <br>

    <label>Email:</label><br>
    <input type="email" name="customer_email" value="{{old('customer_email')}}">
    <br>
    <br>

    <label>Phone:</label><br>
    <input type="text" name="customer_phone" value="{{old('customer_phone')}}">
    <br>
    <br>

    <label>Address:</label><br>
    <input type="text" name="customer_address" value="{{old('customer_address')}}">
    <br>
    <br>

    <label>City:</label><br>
    <input type="text" name="customer_city" value="{{old('customer_city')}}">
    <br>
    <br>

    <label>Province:</label><br>
    <input type="text" name="customer_province" value="{{old('customer_province')}}">
    <br><br>

    <label>Postal Code:</label><br>
    <input type="text" name="customer_postal_code" value="{{old('customer_postal_code')}}">
    <br>
    <br>

    <label>Country:</label><br>
    <input type="text" name="customer_country" value="{{old('customer_country', 'Canada')}}">
    <br>
    <br>

    <label>Notes:</label><br>
    <textarea name="notes">{{old('notes')}}</textarea>
    <br>
    <br>

    <h3>Order Items</h3>
    <table border="1" cellpadding="10" id="items-table">
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit Price (CAD)</th>
            <th>Weight (kg)</th>
        </tr>
        <tr>
            <td><input type="text" name="item_name[]"></td>
            <td><input type="number" name="item_quantity[]" value="1" min="1"></td>
            <td><input type="number" name="item_unit_price[]" step="0.01" min="0"></td>
            <td><input type="number" name="item_weight_kg[]" step="0.01" min="0"></td>
        </tr>
    </table>
    <br>

    <!--t0 add more items -->
    <button type="button" onclick="addItem()">Add Item</button>
    <br><br>
    <button type="submit">Create Order</button>
</form>

<script>
    function addItem() {

        var tbody = document.getElementById('items-table');
        var row = tbody.insertRow();
        row.innerHTML = '<td><input type="text" name="item_name[]"></td>' +
            '<td><input type="number" name="item_quantity[]" value="1" min="1"></td>' +
            '<td><input type="number" name="item_unit_price[]" step="0.01" min="0"></td>' +
            '<td><input type="number" name="item_weight_kg[]" step="0.01" min="0"></td>';

    }
</script>

</body>
</html>
