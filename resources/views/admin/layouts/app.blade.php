<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - AISUKI Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/logo.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/admin.css'])

    <!-- Page specific CSS -->
    @stack('styles')
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <img src="{{ asset('uploads/logo.png') }}" alt="AISUKI Logo" class="logo">
                    <span class="logo-text">AISUKI</span>
                </div>
                <button id="sidebarClose" class="d-block d-md-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="sidebar-content">
                <ul class="nav-menu">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-heading">Menu Management</li>

                    <li class="nav-item {{ request()->routeIs('admin.menu-categories.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.menu-categories.index') }}" class="nav-link">
                            <i class="fas fa-list"></i>
                            <span class="nav-text">Categories</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.menu-items.index') }}" class="nav-link">
                            <i class="fas fa-utensils"></i>
                            <span class="nav-text">Menu Items</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.addon-items.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.addon-items.index') }}" class="nav-link">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-text">Add-ons</span>
                        </a>
                    </li>

                    <li class="nav-heading">Orders</li>

                    <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="nav-text">Orders</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.notifications.index') }}" class="nav-link">
                            <i class="fas fa-bell"></i>
                            <span class="nav-text" style="flex: 1;">Notifications</span>
                            @php
                                $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                            @endphp
                            <!-- Thêm class notification-badge -->
                            <span class="badge bg-danger notification-badge {{ $unreadCount > 0 ? '' : 'd-none' }}">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-heading">Content</li>

                    <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pages.index') }}" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span class="nav-text">Pages</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                            <i class="fas fa-bullhorn"></i>
                            <span class="nav-text">Announcements</span>
                        </a>
                    </li>

                    <li class="nav-heading">Localization</li>

                    <li class="nav-item {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.translations.index') }}" class="nav-link">
                            <i class="fas fa-language"></i>
                            <span class="nav-text">Translations</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.languages.index') }}" class="nav-link">
                            <i class="fas fa-globe"></i>
                            <span class="nav-text">Languages</span>
                        </a>
                    </li>

                    <li class="nav-heading">System</li>

                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main content -->
        <main class="main-content">
            <!-- Top navbar -->
            <nav class="top-navbar">
                <div class="navbar-left">
                    <button id="sidebarToggle" class="toggle-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="page-title d-none d-sm-block">@yield('title', 'Dashboard')</h4>
                </div>

                <div class="navbar-right">
                    <!-- Notification dropdown -->
                    <div class="dropdown me-3">
                        <button class="position-relative btn btn-link text-dark notification-button" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @php
                                $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                            @endphp
                            
                            <!-- Thêm class notification-badge -->
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge {{ $unreadCount > 0 ? '' : 'd-none' }}">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Thông báo</span>
                                <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none small">Xem tất cả</a>
                            </div>
                            <div class="dropdown-divider"></div>
                            
                            <!-- Thêm class notification-list -->
                            <div class="notification-list">
                                @php
                                    $recentNotifications = \App\Models\Notification::orderBy('is_read', 'asc')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @if($recentNotifications->isEmpty())
                                    <div class="dropdown-item text-center text-muted py-3">
                                        <i class="fas fa-bell-slash me-1"></i> Không có thông báo
                                    </div>
                                @else
                                    @foreach($recentNotifications as $notification)
                                        <a href="{{ route('admin.notifications.show', $notification) }}" class="dropdown-item d-flex align-items-center {{ $notification->is_read ? '' : 'bg-light' }}">
                                            <div class="flex-shrink-0">
                                                <div class="bg-{{ $notification->getColorClass() }} text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas {{ $notification->getIconClass() }}"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <div class="small {{ $notification->is_read ? '' : 'fw-bold' }}">{{ Str::limit($notification->title, 40) }}</div>
                                                <div class="text-muted smaller">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-center">
                                    <i class="fas fa-check-double me-1"></i> Đánh dấu đã đọc tất cả
                                </button>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" class="site-link" target="_blank" title="View Website">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="d-none d-md-inline">View Site</span>
                    </a>

                    <div class="dropdown">
                        <button class="user-dropdown" type="button" data-bs-toggle="dropdown">
                            <div class="avatar">
                                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">{{ Auth::user()->name }}</li>
                            <li>
                                <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.profile.change-password') }}" class="dropdown-item">
                                    <i class="fas fa-key"></i> Change Password
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </nav>

            <!-- Content -->
            <div class="content-wrapper">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="admin-footer">
                <div>© {{ date('Y') }} AISUKI Restaurant. All rights reserved.</div>
                <div>Admin Panel v1.0</div>
            </footer>
        </main>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin JS -->
    @vite(['resources/js/admin.js'])

    <!-- Page specific JS -->
    @stack('scripts')

    <script>
        window.routes = {
            'admin.api.notifications.check': '{{ route("admin.api.notifications.check") }}'
        };
    </script>
</body>

</html>