@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Statistics Overview</h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h5 class="stat-card-title">Menu Items</h5>
                                <div class="stat-card-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                            <p class="stat-card-value">{{ $stats['menu_items'] }}</p>
                            <p class="stat-card-info">Total items across all languages</p>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h5 class="stat-card-title">Categories</h5>
                                <div class="stat-card-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                            <p class="stat-card-value">{{ $stats['menu_categories'] }}</p>
                            <p class="stat-card-info">Total menu categories</p>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h5 class="stat-card-title">Pages</h5>
                                <div class="stat-card-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                            <p class="stat-card-value">{{ $stats['pages'] }}</p>
                            <p class="stat-card-info">Published content pages</p>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <h5 class="stat-card-title">Users</h5>
                                <div class="stat-card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <p class="stat-card-value">{{ $stats['users'] }}</p>
                            <p class="stat-card-info">Total registered users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Quick Actions Section -->
        <div class="col-lg-8 col-md-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <a href="{{ route('admin.menu-items.create') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h5 class="action-title">Add Menu Item</h5>
                            <p class="action-description">Create a new menu item for your restaurant</p>
                        </a>
                        
                        <a href="{{ route('admin.menu-categories.create') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <h5 class="action-title">New Category</h5>
                            <p class="action-description">Create a new menu category</p>
                        </a>
                        
                        <a href="{{ route('admin.pages.create') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5 class="action-title">Create Page</h5>
                            <p class="action-description">Add a new content page</p>
                        </a>
                        
                        <a href="{{ route('admin.settings.index') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h5 class="action-title">Settings</h5>
                            <p class="action-description">Configure website settings</p>
                        </a>
                        
                        <a href="{{ route('admin.translations.index') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-language"></i>
                            </div>
                            <h5 class="action-title">Translations</h5>
                            <p class="action-description">Manage website translations</p>
                        </a>
                        
                        <a href="{{ route('admin.users.index') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <h5 class="action-title">Users</h5>
                            <p class="action-description">Manage user accounts</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Info Section -->
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5>System Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>PHP Version</strong></td>
                                <td>{{ phpversion() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Laravel Version</strong></td>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Environment</strong></td>
                                <td>{{ ucfirst(app()->environment()) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Default Language</strong></td>
                                <td>{{ \App\Models\Language::where('is_default', true)->first()->name ?? 'Not Set' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Time Zone</strong></td>
                                <td>{{ config('app.timezone') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Time</strong></td>
                                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection