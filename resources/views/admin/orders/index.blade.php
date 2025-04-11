@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Orders List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->order_id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $order->orderLines->sum('quantity') }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status == 'completed') badge-success
                                @elseif($order->status == 'cancelled') badge-danger
                                @else badge-warning @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.orders.update', $order->order_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection