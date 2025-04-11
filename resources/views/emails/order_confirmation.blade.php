<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4a90e2; color: white; padding: 25px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 25px; background-color: #f9f9f9; border-radius: 0 0 5px 5px; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .attachment-card { background: #f0f7ff; padding: 15px; border-left: 4px solid #4a90e2; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .button { display: inline-block; padding: 12px 24px; background-color: #4a90e2; color: white; 
                 text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">Order Confirmation #{{ $order->order_id }}</h1>
        </div>
        
        <div class="content">
            <p>Dear Valued Customer,</p>
            <p>Thank you for shopping with MobiXpert! We're preparing your order and will notify you once it's shipped.</p>
            
            <div class="order-details">
                <h3 style="margin-top:0; color:#4a90e2;">Order Summary</h3>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Order Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Status:</strong> <span style="color:#4a90e2; font-weight:bold;">{{ ucfirst($order->status) }}</span></p>
            </div>

            <div class="attachment-card">
                <h4 style="margin-top:0;">📄 Your Order Receipt</h4>
                <p>We've attached a detailed PDF receipt for your records. This document includes:</p>
                <ul style="margin-bottom:0;">
                    <li>Complete itemized list of your purchase</li>
                    <li>Order totals and payment details</li>
                    <li>Order reference number for your records</li>
                </ul>
                <p style="margin-bottom:0;"><small>Can't view the attachment? <a href="{{ route('orders.show', $order->order_id) }}" style="color:#4a90e2;">View your order online</a></small></p>
            </div>
            
            <p>If you have any questions about your order, our support team is happy to help.</p>
            
            <a href="{{ route('home') }}" class="button">Continue Shopping</a>
            
            <p>Warm regards,<br>
            <strong>The MobiXpert Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} MobiXpert. All rights reserved.<br>
            <small>MobiXpert, 123 Business Ave, Your City</small></p>
        </div>
    </div>
</body>
</html>