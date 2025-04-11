@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">My Profile</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <form action="{{ route('admin.profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="profile-photo-form">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3 position-relative">
                                    <div class="profile-image-container" style="position: relative; display: inline-block;">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/'.$user->profile_photo) }}" 
                                                 class="img-fluid rounded-circle shadow" 
                                                 alt="{{ $user->name }}" 
                                                 style="width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4e73df&color=ffffff&size=200" 
                                                 class="img-fluid rounded-circle shadow" 
                                                 alt="{{ $user->name }}"
                                                 style="width: 200px; height: 200px; object-fit: cover;">
                                        @endif
                                        
                                        <div class="upload-overlay" style="position: absolute; bottom: 0; right: 0;">
                                            <label for="profile_photo" class="btn btn-sm btn-primary rounded-circle">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="submitPhotoForm(this)">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ ucfirst($user->role) }}</p>
                            <p><small>Member since {{ $user->created_at->format('M d, Y') }}</small></p>
                        </div>
                        
                        <div class="col-md-8">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <hr class="my-4">
                                <h5>Change Password</h5>
                                <p class="text-muted small">Leave blank if you don't want to change your password</p>
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitPhotoForm(input) {
        if (input.files && input.files[0]) {
            // Clear any existing success messages first
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => alert.remove());
            
            // Submit the form
            document.getElementById('profile-photo-form').submit();
        }
    }
    
    // Auto-close alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            });
        }, 5000);
    });
</script>
@endpush