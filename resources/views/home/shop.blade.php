@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <div class="mb-4">
                            <h6>Brands</h6>
                            <div class="form-group">
                                <select class="form-select" name="brand" onchange="this.form.submit()">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->brand_id }}" {{ request('brand') == $brand->brand_id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Price Range</h6>
                            <div class="form-group">
                                <select class="form-select" name="price_range" onchange="this.form.submit()">
                                    <option value="">All Prices</option>
                                    @foreach($priceRanges as $range)
                                        <option value="{{ $range->price_range_id }}" {{ request('price_range') == $range->price_range_id ? 'selected' : '' }}>
                                            {{ $range->range_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Sort By</h6>
                            <div class="form-group">
                                <select class="form-select" name="sort" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Products</h2>
                <span>Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
            </div>
            
            @if($products->isEmpty())
                <div class="alert alert-info">
                    No products found matching your criteria. Try adjusting your filters.
                </div>
            @else
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card product-card h-100">
                            <img src="{{ asset('storage/'.$product->image_path) }}" class="card-img-top product-img p-3" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                @if($product->brand)
                                <p class="card-text text-muted">{{ $product->brand->name }}</p>
                                @endif
                                <p class="card-text fw-bold">₱{{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('product.detail', $product->product_id) }}" class="btn btn-outline-primary">View Details</a>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection