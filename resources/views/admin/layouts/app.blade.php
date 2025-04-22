<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | MobiXpert Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #343a40;
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            background: #212529;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            color: #fff;
            background: #2c3136;
            border-left-color: #007bff;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .admin-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        /* Navbar */
        .admin-navbar {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        /* Footer */
        .admin-footer {
            background: #fff;
            padding: 15px 0;
            text-align: center;
            margin-top: 30px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .admin-sidebar.active {
                margin-left: 0;
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .admin-content.active {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.index') }}" class="sidebar-brand">
                <i class="fas fa-mobile-alt me-2"></i> MobiXpert
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i> Reviews
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profile
                </a>
            </li>
            
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Main Content -->
    <div class="admin-content">
        <!-- Navbar -->
        <nav class="navbar admin-navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user-cog me-2"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
        
        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container-fluid">
                <p class="mb-0">&copy; {{ date('Y') }} MobiXpert. All rights reserved.</p>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const content = document.querySelector('.admin-content');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    content.classList.toggle('active');
                });
            }
            
            // Auto-close alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>