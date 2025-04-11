@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Edit Product: {{ $product->name }}</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name', $product->name) }}" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="3" required>{{ old('description', $product->description) }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" 
                       value="{{ old('price', $product->price) }}" step="0.01" required>
            </div>
            
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
            </div>
            
            <div class="mb-3">
                <label for="brand_id" class="form-label">Brand</label>
                <select class="form-select" id="brand_id" name="brand_id" required>
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->brand_id }}" 
                            {{ $product->brand_id == $brand->brand_id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="price_range_id" class="form-label">Price Range</label>
                <select class="form-select" id="price_range_id" name="price_range_id" required>
                    <option value="">Select Price Range</option>
                    @foreach($priceRanges as $priceRange)
                        <option value="{{ $priceRange->price_range_id }}" 
                            {{ $product->price_range_id == $priceRange->price_range_id ? 'selected' : '' }}>
                            {{ $priceRange->range_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                @if($product->image_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$product->image_path) }}" alt="Current Image" width="100">
                        <p class="text-muted">Current Image</p>
                    </div>
                @endif
            </div>
            
            <div class="modal-footer">
                <a href="{{ route('admin.products') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection