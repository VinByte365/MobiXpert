<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .logo { max-width: 150px; height: auto; margin-bottom: 10px; }
        .company-info { margin-bottom: 5px; font-size: 14px; }
        .receipt-title { color: #2c3e50; margin: 10px 0; }
        .section { margin-bottom: 25px; }
        .section-title { border-bottom: 1px solid #eee; padding-bottom: 5px; color: #2c3e50; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .detail-box { padding: 10px; background-color: #f9f9f9; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f2f2f2; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; border-top: 2px solid #ddd; padding-top: 10px; }
        .total-row { font-weight: bold; font-size: 16px; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .thank-you { font-size: 18px; text-align: center; margin: 30px 0; color: #2c3e50; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="receipt-title">MobiXpert</h1>
        <p class="company-info">Phone: (123) 456-7890 | Email: support@mobixpert.com</p>
        <h2 class="receipt-title">RECEIPT #{{ $order->order_id }}</h2>
        <p>Date: {{ $order->created_at->format('F d, Y h:i A') }}</p>
    </div>
    
    <div class="details-grid">
        <div class="detail-box">
            <h3 class="section-title">Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->user->name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
        </div>
        
        <div class="detail-box">
            <h3 class="section-title">Order Information</h3>
            <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst($order->status ?? 'N/A') }}</p>
        </div>
    </div>
    
    <div class="section">
        <h3 class="section-title">Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderLines as $line)
                <tr>
                    <td>{{ $line->product->name }}</td>
                    <td>{{ $line->quantity }}</td>
                    <td class="text-right">PHP{{ number_format($line->price, 2) }}</td>
                    <td class="text-right">PHP{{ number_format($line->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="total-section">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">PHP{{ number_format($order->total_amount - ($order->shipping_fee ?? 0) - ($order->tax_amount ?? 0), 2) }}</td>
            </tr>
            @if(isset($order->shipping_fee) && $order->shipping_fee > 0)
            <tr>
                <td>Shipping Fee:</td>
                <td class="text-right">PHP{{ number_format($order->shipping_fee, 2) }}</td>
            </tr>
            @endif
            @if(isset($order->tax_amount) && $order->tax_amount > 0)
            <tr>
                <td>Tax ({{ $order->tax_rate ?? '12' }}%):</td>
                <td class="text-right">PHP{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td class="text-right">PHP{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <h3 class="section-title">Payment Information</h3>
        <p><strong>Payment Status:</strong> {{ ucfirst($order->status ?? 'N/A') }}</p>
        <p><strong>Transaction ID:</strong> {{ $order->order_id ?? 'N/A' }}</p>
    </div>
    
    <div class="thank-you">
        Thank you for shopping with MobiXpert!
    </div>
    
    <div class="footer">
        <p>This is an official receipt. For questions or concerns, please contact our customer support.</p>
        <p>© {{ date('Y') }} MobiXpert. All rights reserved.</p>
    </div>
</body>
</html>