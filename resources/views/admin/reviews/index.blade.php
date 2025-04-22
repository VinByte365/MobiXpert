@extends('admin.layouts.app')

@section('title', 'Product Reviews')

@section('content')
<div class="card">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Product Reviews</h1>
            <div class="d-flex">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="me-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Review ID</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->review_id }}</td>
                        <td>{{ $review->product->name ?? 'N/A' }}</td>
                        <td>{{ $review->user->name ?? 'N/A' }}</td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            ({{ $review->rating }})
                        </td>
                        <td>{{ Str::limit($review->comment, 50) }}</td>
                        <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.reviews.destroy', $review->review_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No reviews found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection