<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuItemController extends Controller
{
    public function index()
    {
        $defaultLanguage = Language::where('is_default', true)->first();
        $menuItems = MenuItem::with('category')
            ->where('language_id', $defaultLanguage->id)
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $defaultLanguage = Language::where('is_default', true)->first();
        $categories = MenuCategory::where('language_id', $defaultLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $languages = Language::where('is_active', true)->get();
        
        return view('admin.menu-items.create', compact('categories', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            }

            // Generate a new mass_id
            $massId = MenuItem::max('mass_id') + 1;
            
            // Get the selected category and its translations
            $category = MenuCategory::findOrFail($request->category_id);
            $categoryTranslations = MenuCategory::where('mass_id', $category->mass_id)
                ->where('id', '!=', $category->id)
                ->get()
                ->keyBy('language_id');
            
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            foreach ($languages as $language) {
                $nameField = 'name_' . $language->code;
                $descriptionField = 'description_' . $language->code;
                
                $name = $request->has($nameField) ? $request->$nameField : $request->name;
                $description = $request->has($descriptionField) ? $request->$descriptionField : $request->description;
                
                // Find the appropriate category_id for this language
                $categoryId = $category->language_id == $language->id 
                    ? $category->id 
                    : (isset($categoryTranslations[$language->id]) 
                        ? $categoryTranslations[$language->id]->id 
                        : $category->id);
                
                MenuItem::create([
                    'language_id' => $language->id,
                    'mass_id' => $massId,
                    'category_id' => $categoryId,
                    'code' => $request->code,
                    'name' => $name,
                    'description' => $description,
                    'price' => $request->price,
                    'image' => $imagePath,
                    'sort_order' => $request->sort_order,
                    'is_active' => $request->has('is_active'),
                    'is_featured' => $request->has('is_featured'),
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating menu item: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        
        $language = Language::findOrFail($menuItem->language_id);
        $defaultLanguage = Language::where('is_default', true)->first();
        
        $categories = MenuCategory::where('language_id', $menuItem->language_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($categories->isEmpty()) {
            $categories = MenuCategory::where('language_id', $defaultLanguage->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        
        $languages = Language::where('is_active', true)->get();
        $translations = MenuItem::where('mass_id', $menuItem->mass_id)
            ->where('id', '!=', $menuItem->id)
            ->get()
            ->keyBy('language_id');
            
        return view('admin.menu-items.edit', compact(
            'menuItem', 'categories', 'languages', 'translations'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $menuItem = MenuItem::findOrFail($id);
            
            // Handle image upload
            $imagePath = $menuItem->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                    unlink(public_path($menuItem->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            }
            
            // Update the current menu item
            $menuItem->update([
                'category_id' => $request->category_id,
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'sort_order' => $request->sort_order,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
            ]);
            
            // Update translations
            $languages = Language::where('is_active', true)->get();
            foreach ($languages as $language) {
                if ($language->id == $menuItem->language_id) continue;
                
                $nameField = 'name_' . $language->code;
                $descriptionField = 'description_' . $language->code;
                
                if ($request->has($nameField)) {
                    // Find category for this language
                    $category = MenuCategory::find($request->category_id);
                    $categoryTranslation = MenuCategory::where('mass_id', $category->mass_id)
                        ->where('language_id', $language->id)
                        ->first();
                    
                    $categoryId = $categoryTranslation ? $categoryTranslation->id : $request->category_id;
                    
                    $translation = MenuItem::firstOrNew([
                        'mass_id' => $menuItem->mass_id,
                        'language_id' => $language->id,
                    ]);
                    
                    $translation->category_id = $categoryId;
                    $translation->code = $request->code;
                    $translation->name = $request->$nameField;
                    $translation->description = $request->has($descriptionField) ? $request->$descriptionField : '';
                    $translation->price = $request->price;
                    $translation->image = $imagePath;
                    $translation->sort_order = $request->sort_order;
                    $translation->is_active = $request->has('is_active');
                    $translation->is_featured = $request->has('is_featured');
                    $translation->save();
                }
            }
            
            DB::commit();
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating menu item: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);
            $massId = $menuItem->mass_id;
            
            // Delete all translations (all items with same mass_id)
            $items = MenuItem::where('mass_id', $massId)->get();
            
            foreach ($items as $item) {
                $item->delete();
            }
            
            // Delete image if exists
            if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                unlink(public_path($menuItem->image));
            }
            
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting menu item: ' . $e->getMessage());
        }
    }
}