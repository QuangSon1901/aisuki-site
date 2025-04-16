<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'menu_items' => MenuItem::distinct('mass_id')->count(),
            'menu_categories' => MenuCategory::distinct('mass_id')->count(),
            'pages' => Page::distinct('mass_id')->count(),
            'users' => User::count(),
        ];
        
        return view('admin.dashboard.index', compact('stats'));
    }
}