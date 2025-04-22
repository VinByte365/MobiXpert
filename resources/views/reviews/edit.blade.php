@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.detail', $product->product_id) }}">{{ $product->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Review</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Your Review for {{ $product->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.update', $review->review_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="order_id" value="{{ $review->order_id }}">

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Select a rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} - {{ ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][$i-1] }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Review</label>
                            <textarea name="comment" id="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('product.detail', $product->product_id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection