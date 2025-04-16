<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\AddonItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    /**
     * Display a listing of menu items
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Group items by mass_id to display unique items with translations
        $massIds = MenuItem::select('mass_id')->distinct()->pluck('mass_id');
        $menuItems = [];
        
        foreach ($massIds as $massId) {
            // Get default language item or first available
            $defaultLanguage = Language::where('is_default', true)->first();
            $defaultItem = MenuItem::where('mass_id', $massId)
                ->where('language_id', $defaultLanguage->id)
                ->with(['category', 'addons'])
                ->first();
                
            if (!$defaultItem) {
                $defaultItem = MenuItem::where('mass_id', $massId)
                    ->with(['category', 'addons'])
                    ->first();
            }
            
            // Get all translations
            $translations = MenuItem::where('mass_id', $massId)
                ->with(['language', 'category'])
                ->get();
                
            $menuItems[] = [
                'item' => $defaultItem,
                'translations' => $translations
            ];
        }
        
        return view('admin.menu-items.index', compact('menuItems'));
    }

    /**
     * Show the form for creating a new menu item
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = Language::where('is_default', true)->first();
        
        // Get categories for the default language
        $categories = [];
        if ($defaultLanguage) {
            $categories = MenuCategory::where('language_id', $defaultLanguage->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        
        // Get all available addon items for the default language
        $addons = [];
        if ($defaultLanguage) {
            $addons = AddonItem::where('language_id', $defaultLanguage->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        
        // Generate next available mass_id
        $nextMassId = MenuItem::max('mass_id') + 1;
        
        // Generate a unique item code
        $lastItem = MenuItem::orderBy('id', 'desc')->first();
        $nextCode = 'ITEM001';
        
        if ($lastItem) {
            // Extract numeric part and increment
            $codeBase = preg_replace('/[^0-9]/', '', $lastItem->code);
            $codeNumber = (int)$codeBase + 1;
            $nextCode = 'ITEM' . str_pad($codeNumber, 3, '0', STR_PAD_LEFT);
        }
        
        return view('admin.menu-items.create', compact('languages', 'defaultLanguage', 'categories', 'addons', 'nextMassId', 'nextCode'));
    }

    /**
     * Store a newly created menu item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'category_id' => 'required|exists:menu_categories,id',
            'mass_id' => 'required|integer',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_items')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->language_id);
                }),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addon_items,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-items.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'menu_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            }
            
            // Create the menu item
            $menuItem = MenuItem::create([
                'language_id' => $request->language_id,
                'mass_id' => $request->mass_id,
                'category_id' => $request->category_id,
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            // Attach addon items if any
            if ($request->has('addon_ids') && is_array($request->addon_ids)) {
                $menuItem->addons()->attach($request->addon_ids);
            }
            
            DB::commit();
            
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-items.create')
                ->with('error', 'Failed to create menu item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a menu item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItem = MenuItem::with(['language', 'category', 'addons'])->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get categories for the menu item's language
        $categories = MenuCategory::where('language_id', $menuItem->language_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Get all available addon items for the menu item's language
        $addons = AddonItem::where('language_id', $menuItem->language_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Get translations (items with same mass_id)
        $translations = MenuItem::where('mass_id', $menuItem->mass_id)
            ->where('id', '!=', $menuItem->id)
            ->with(['language', 'category'])
            ->get();
        
        // Get current addon IDs for the menu item
        $selectedAddonIds = $menuItem->addons->pluck('id')->toArray();
        
        return view('admin.menu-items.edit', compact('menuItem', 'languages', 'categories', 'addons', 'translations', 'selectedAddonIds'));
    }

    /**
     * Update the specified menu item
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:menu_categories,id',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_items')->where(function ($query) use ($menuItem, $id) {
                    return $query->where('language_id', $menuItem->language_id)
                                 ->where('id', '!=', $id);
                }),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addon_items,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-items.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($menuItem->image && File::exists(public_path($menuItem->image))) {
                    File::delete(public_path($menuItem->image));
                }
                
                $image = $request->file('image');
                $imageName = 'menu_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $menuItem->image = 'uploads/' . $imageName;
            } elseif ($request->has('remove_image') && $request->remove_image) {
                // Remove existing image if requested
                if ($menuItem->image && File::exists(public_path($menuItem->image))) {
                    File::delete(public_path($menuItem->image));
                }
                $menuItem->image = null;
            }
            
            // Update the menu item
            $menuItem->category_id = $request->category_id;
            $menuItem->code = $request->code;
            $menuItem->name = $request->name;
            $menuItem->description = $request->description;
            $menuItem->price = $request->price;
            $menuItem->is_active = $request->has('is_active');
            $menuItem->is_featured = $request->has('is_featured');
            $menuItem->sort_order = $request->sort_order ?? 0;
            $menuItem->save();
            
            // Sync addon items
            $addonIds = $request->has('addon_ids') ? $request->addon_ids : [];
            $menuItem->addons()->sync($addonIds);
            
            // Sync addon items with translations if requested
            if ($request->has('sync_addons_to_translations')) {
                $translations = MenuItem::where('mass_id', $menuItem->mass_id)
                    ->where('id', '!=', $menuItem->id)
                    ->get();
                
                foreach ($translations as $translation) {
                    // Get addon mass_ids from the current language
                    $addonMassIds = [];
                    if (!empty($addonIds)) {
                        $addonMassIds = AddonItem::whereIn('id', $addonIds)
                            ->pluck('mass_id')
                            ->toArray();
                    }
                    
                    // Get corresponding addon IDs in the translation language
                    $translationAddonIds = [];
                    if (!empty($addonMassIds)) {
                        $translationAddonIds = AddonItem::where('language_id', $translation->language_id)
                            ->whereIn('mass_id', $addonMassIds)
                            ->pluck('id')
                            ->toArray();
                    }
                    
                    // Sync addon items for the translation
                    $translation->addons()->sync($translationAddonIds);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.menu-items.edit', $menuItem->id)
                ->with('success', 'Menu item updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-items.edit', $id)
                ->with('error', 'Failed to update menu item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified menu item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);
            
            // Delete image file if exists
            if ($menuItem->image && File::exists(public_path($menuItem->image))) {
                File::delete(public_path($menuItem->image));
            }
            
            $menuItem->delete();
            
            return redirect()->route('admin.menu-items.index')
                ->with('success', 'Menu item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.menu-items.index')
                ->with('error', 'Failed to delete menu item: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for creating a translation of a menu item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createTranslation($id)
    {
        $sourceItem = MenuItem::with(['language', 'category', 'addons'])->findOrFail($id);
        
        // Get languages that don't have a translation for this menu item yet
        $existingLanguages = MenuItem::where('mass_id', $sourceItem->mass_id)
            ->pluck('language_id')
            ->toArray();
            
        $availableLanguages = Language::where('is_active', true)
            ->whereNotIn('id', $existingLanguages)
            ->get();
            
        if ($availableLanguages->isEmpty()) {
            return redirect()->route('admin.menu-items.edit', $id)
                ->with('error', 'All languages already have translations for this menu item.');
        }
        
        // Get categories for each available language
        $categoriesByLanguage = [];
        $addonsByLanguage = [];
        
        foreach ($availableLanguages as $language) {
            $categoriesByLanguage[$language->id] = MenuCategory::where('language_id', $language->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
                
            $addonsByLanguage[$language->id] = AddonItem::where('language_id', $language->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }
        
        // Get source item addon IDs
        $sourceAddonIds = $sourceItem->addons->pluck('id')->toArray();
        
        return view('admin.menu-items.create-translation', 
            compact('sourceItem', 'availableLanguages', 'categoriesByLanguage', 'addonsByLanguage', 'sourceAddonIds'));
    }
    
    /**
     * Store a new translation for a menu item
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeTranslation(Request $request, $id)
    {
        $sourceItem = MenuItem::with('addons')->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'category_id' => 'required|exists:menu_categories,id',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menu_items')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->language_id);
                }),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addon_items,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-items.create-translation', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Check if translation already exists
        $existingTranslation = MenuItem::where('mass_id', $sourceItem->mass_id)
            ->where('language_id', $request->language_id)
            ->first();
            
        if ($existingTranslation) {
            return redirect()->route('admin.menu-items.create-translation', $id)
                ->with('error', 'A translation in this language already exists.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'menu_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            } elseif ($request->has('use_source_image') && $sourceItem->image) {
                // Use source item image if requested
                $imagePath = $sourceItem->image;
            }
            
            // Create the translation
            $translation = MenuItem::create([
                'language_id' => $request->language_id,
                'mass_id' => $sourceItem->mass_id,
                'category_id' => $request->category_id,
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $sourceItem->price, // Use same price as source
                'image' => $imagePath,
                'is_active' => $request->has('is_active'),
                'is_featured' => $sourceItem->is_featured, // Use same featured status as source
                'sort_order' => $sourceItem->sort_order, // Use same sort order as source
            ]);
            
            // Handle addon synchronization
            if ($request->has('use_source_addons')) {
                // Use source item addons
                $addonMassIds = $sourceItem->addons->pluck('mass_id')->toArray();
                
                // Get corresponding addon IDs in the new language
                $translationAddonIds = AddonItem::where('language_id', $request->language_id)
                    ->whereIn('mass_id', $addonMassIds)
                    ->pluck('id')
                    ->toArray();
                
                // Attach addon items
                $translation->addons()->attach($translationAddonIds);
            } elseif ($request->has('addon_ids')) {
                // Use selected addon items
                $translation->addons()->attach($request->addon_ids);
            }
            
            DB::commit();
            
            return redirect()->route('admin.menu-items.edit', $translation->id)
                ->with('success', 'Translation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-items.create-translation', $id)
                ->with('error', 'Failed to create translation: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Get menu categories for a specific language (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesByLanguage(Request $request)
    {
        $languageId = $request->language_id;
        
        if (!$languageId) {
            return response()->json(['error' => 'Language ID is required'], 400);
        }
        
        $categories = MenuCategory::where('language_id', $languageId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name']);
            
        return response()->json(['categories' => $categories]);
    }
    
    /**
     * Get addon items for a specific language (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAddonsByLanguage(Request $request)
    {
        $languageId = $request->language_id;
        
        if (!$languageId) {
            return response()->json(['error' => 'Language ID is required'], 400);
        }
        
        $addons = AddonItem::where('language_id', $languageId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'price']);
            
        return response()->json(['addons' => $addons]);
    }
}