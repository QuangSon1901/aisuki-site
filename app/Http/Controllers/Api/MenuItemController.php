<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Get addons for a specific menu item
     *
     * @param int $id Menu item ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddons($id)
    {
        $menuItem = MenuItem::with('addons')->find($id);
        
        if (!$menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found',
                'addons' => []
            ], 404);
        }
        
        // Get only necessary addon data
        $addons = $menuItem->addons->map(function($addon) {
            return [
                'id' => $addon->id,
                'name' => $addon->name,
                'price' => $addon->price
            ];
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Addons retrieved successfully',
            'addons' => $addons
        ]);
    }
}