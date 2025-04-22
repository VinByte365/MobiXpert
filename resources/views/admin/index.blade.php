@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
<style>
    .stat-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .recent-item {
        transition: background-color 0.2s ease;
    }
    .recent-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    .dashboard-chart {
        height: 250px !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Dashboard</h1>
            <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 stat-card bg-primary bg-gradient text-white h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Products</div>
                            <div class="h3 mb-0 font-weight-bold">{{ \App\Models\Product::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.products') }}" class="text-white small stretched-link">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 stat-card bg-success bg-gradient text-white h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                            <div class="h3 mb-0 font-weight-bold">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.users') }}" class="text-white small stretched-link">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 stat-card bg-info bg-gradient text-white h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Reviews</div>
                            <div class="h3 mb-0 font-weight-bold">{{ \App\Models\Review::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.reviews.index') }}" class="text-white small stretched-link">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 stat-card bg-warning bg-gradient text-white h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Avg Rating</div>
                            <div class="h3 mb-0 font-weight-bold">
                                {{ number_format(\App\Models\Review::avg('rating') ?? 0, 1) }}
                                <small>/5</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star-half-alt fa-2x stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.reviews.index') }}" class="text-white small stretched-link">View Details</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Products Added (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="productsChart" class="dashboard-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Top Brands</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="brandsPieChart" class="dashboard-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Recent Products</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach(\App\Models\Product::latest()->take(5)->get() as $product)
                        <a href="{{ route('admin.products.edit', $product->product_id) }}" class="list-group-item list-group-item-action recent-item">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">Price: ${{ number_format($product->price, 2) }} | Stock: {{ $product->stock_quantity }}</small>
                                </div>
                                <small>{{ $product->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.products') }}" class="btn btn-sm btn-primary">View All Products</a>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Recent Reviews</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach(\App\Models\Review::with(['user', 'product'])->latest()->take(5)->get() as $review)
                        <div class="list-group-item recent-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $review->product->name }}</h6>
                                <small>{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="mb-1">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</p>
                            <small class="text-muted">By: {{ $review->user->name }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-primary">View All Reviews</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Management Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Recent Orders</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="orderFilterDropdown" data-bs-toggle="dropdown">
                            Filter by Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-status="all">All Orders</a></li>
                            <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
                            <li><a class="dropdown-item" href="#" data-status="completed">Completed</a></li>
                            <li><a class="dropdown-item" href="#" data-status="cancelled">Cancelled</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Order::with('user')->latest()->take(10)->get() as $order)
                                <tr>
                                    <td>#{{ $order->order_id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <form action="{{ route('admin.orders.update', $order->order_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary view-order" data-order-id="{{ $order->order_id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-primary">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Products Chart - Last 6 months
    const months = [];
    const productCounts = [];
    
    @php
    $lastSixMonths = collect(range(0, 5))->map(function($i) {
        return now()->subMonths($i)->format('Y-m');
    })->reverse();
    
    $productsByMonth = \App\Models\Product::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
        ->whereIn(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $lastSixMonths)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();
    @endphp
    
    @foreach($lastSixMonths as $month)
    months.push('{{ \Carbon\Carbon::createFromFormat("Y-m", $month)->format("M Y") }}');
    productCounts.push({{ $productsByMonth[$month] ?? 0 }});
    @endforeach
    
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Products Added',
                data: productCounts,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    precision: 0,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Brands Pie Chart
    @php
    $topBrands = \App\Models\Product::selectRaw('COUNT(*) as count, brands.name')
        ->join('brands', 'products.brand_id', '=', 'brands.brand_id')
        ->groupBy('brands.name')
        ->orderBy('count', 'desc')
        ->take(5)
        ->get();
    @endphp
    
    const brandLabels = [];
    const brandData = [];
    const brandColors = [
        'rgba(78, 115, 223, 0.8)',
        'rgba(28, 200, 138, 0.8)',
        'rgba(54, 185, 204, 0.8)',
        'rgba(246, 194, 62, 0.8)',
        'rgba(231, 74, 59, 0.8)'
    ];
    
    @foreach($topBrands as $index => $brand)
    brandLabels.push('{{ $brand->name }}');
    brandData.push({{ $brand->count }});
    @endforeach
    
    const brandsCtx = document.getElementById('brandsPieChart').getContext('2d');
    new Chart(brandsCtx, {
        type: 'doughnut',
        data: {
            labels: brandLabels,
            datasets: [{
                data: brandData,
                backgroundColor: brandColors,
                hoverBackgroundColor: brandColors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endpush