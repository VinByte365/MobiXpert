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
        <h1>Product Reviews</h1>
    </div>
    
    <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete button handler
    $(document).on('click', '.delete-review', function() {
        var id = $(this).data('id');
        var url = $(this).data('url');
        
        if (confirm('Are you sure you want to delete this review?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.LaravelDataTables['reviews-table'].ajax.reload();
                    alert(response.message || 'Review deleted successfully');
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Could not delete review'));
                }
            });
        }
    });
});
</script>
{!! $dataTable->scripts() !!}
@endpush