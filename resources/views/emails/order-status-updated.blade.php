@component('mail::message')
# Order Status Update

Your order #{{ $order->id }} status has been updated to: **{{ ucfirst($order->status) }}**

@if($order->status === 'completed')
Thank you for your order! It has been completed successfully.
@else
We're sorry to inform you that your order has been cancelled.
@endif

@component('mail::button', ['url' => route('orders.show', $order->id)])
View Order Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent