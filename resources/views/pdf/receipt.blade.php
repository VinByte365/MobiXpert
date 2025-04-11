<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Receipt #{{ $order->order_id }}</h1>
        <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
    </div>
    
    <div class="details">
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderLines as $line)
                <tr>
                    <td>{{ $line->product->name }}</td>
                    <td>{{ $line->quantity }}</td>
                    <td>${{ number_format($line->price, 2) }}</td>
                    <td>${{ number_format($line->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="total">
        <p>Total: ${{ number_format($order->total_amount, 2) }}</p>
    </div>
</body>
</html>