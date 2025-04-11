@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Application Settings</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h5>Interface Preferences</h5>
                            
                            <div class="mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <select class="form-select @error('theme') is-invalid @enderror" id="theme" name="theme">
                                    <option value="light" {{ session('admin_theme', 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="dark" {{ session('admin_theme') == 'dark' ? 'selected' : '' }}>Dark</option>
                                </select>
                                @error('theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Notification Settings</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notifications" name="notifications" value="1"
                                       {{ session('admin_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notifications">Enable Notifications</label>
                                @error('notifications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection