<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of menu categories
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Group categories by mass_id to display unique categories with translations
        $massIds = MenuCategory::select('mass_id')->distinct()->pluck('mass_id');
        $categories = [];
        
        foreach ($massIds as $massId) {
            // Get default language category or first available
            $defaultLanguage = Language::where('is_default', true)->first();
            $defaultCategory = MenuCategory::where('mass_id', $massId)
                ->where('language_id', $defaultLanguage->id)
                ->first();
                
            if (!$defaultCategory) {
                $defaultCategory = MenuCategory::where('mass_id', $massId)->first();
            }
            
            // Get all translations
            $translations = MenuCategory::where('mass_id', $massId)
                ->with('language')
                ->get();
                
            $categories[] = [
                'category' => $defaultCategory,
                'translations' => $translations
            ];
        }
        
        return view('admin.menu-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new menu category
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = Language::where('is_default', true)->first();
        
        // Generate next available mass_id
        $nextMassId = MenuCategory::max('mass_id') + 1;
        
        return view('admin.menu-categories.create', compact('languages', 'defaultLanguage', 'nextMassId'));
    }

    /**
     * Store a newly created menu category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|regex:/^[a-z0-9-]+$/|unique:menu_categories,slug,NULL,id,language_id,' . $request->language_id,
            'language_id' => 'required|exists:languages,id',
            'mass_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-categories.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'category_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            }
            
            // Create the menu category
            MenuCategory::create([
                'language_id' => $request->language_id,
                'mass_id' => $request->mass_id,
                'name' => $request->name,
                'slug' => $request->slug,
                'image' => $imagePath,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.menu-categories.index')
                ->with('success', 'Menu category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-categories.create')
                ->with('error', 'Failed to create menu category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a menu category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = MenuCategory::with('language')->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get translations (categories with same mass_id)
        $translations = MenuCategory::where('mass_id', $category->mass_id)
            ->where('id', '!=', $category->id)
            ->with('language')
            ->get();
        
        return view('admin.menu-categories.edit', compact('category', 'languages', 'translations'));
    }

    /**
     * Update the specified menu category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = MenuCategory::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|regex:/^[a-z0-9-]+$/|unique:menu_categories,slug,' . $id . ',id,language_id,' . $category->language_id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-categories.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($category->image && File::exists(public_path($category->image))) {
                    File::delete(public_path($category->image));
                }
                
                $image = $request->file('image');
                $imageName = 'category_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $category->image = 'uploads/' . $imageName;
            } elseif ($request->has('remove_image') && $request->remove_image) {
                // Remove existing image if requested
                if ($category->image && File::exists(public_path($category->image))) {
                    File::delete(public_path($category->image));
                }
                $category->image = null;
            }
            
            // Update the menu category
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->is_active = $request->has('is_active');
            $category->sort_order = $request->sort_order ?? 0;
            $category->save();
            
            DB::commit();
            
            return redirect()->route('admin.menu-categories.edit', $category->id)
                ->with('success', 'Menu category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-categories.edit', $id)
                ->with('error', 'Failed to update menu category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified menu category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = MenuCategory::findOrFail($id);
            
            // Check if there are menu items associated with this category
            $hasMenuItems = DB::table('menu_items')
                ->where('category_id', $category->id)
                ->exists();
                
            if ($hasMenuItems) {
                return redirect()->route('admin.menu-categories.index')
                    ->with('error', 'Cannot delete this category because it has associated menu items.');
            }
            
            // Delete image file if exists
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $category->delete();
            
            return redirect()->route('admin.menu-categories.index')
                ->with('success', 'Menu category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.menu-categories.index')
                ->with('error', 'Failed to delete menu category: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for creating a translation of a menu category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createTranslation($id)
    {
        $sourceCategory = MenuCategory::with('language')->findOrFail($id);
        
        // Get languages that don't have a translation for this category yet
        $existingLanguages = MenuCategory::where('mass_id', $sourceCategory->mass_id)
            ->pluck('language_id')
            ->toArray();
            
        $availableLanguages = Language::where('is_active', true)
            ->whereNotIn('id', $existingLanguages)
            ->get();
            
        if ($availableLanguages->isEmpty()) {
            return redirect()->route('admin.menu-categories.edit', $id)
                ->with('error', 'All languages already have translations for this category.');
        }
        
        return view('admin.menu-categories.create-translation', compact('sourceCategory', 'availableLanguages'));
    }
    
    /**
     * Store a new translation for a menu category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeTranslation(Request $request, $id)
    {
        $sourceCategory = MenuCategory::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|regex:/^[a-z0-9-]+$/|unique:menu_categories,slug,NULL,id,language_id,' . $request->language_id,
            'language_id' => 'required|exists:languages,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu-categories.create-translation', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Check if translation already exists
        $existingTranslation = MenuCategory::where('mass_id', $sourceCategory->mass_id)
            ->where('language_id', $request->language_id)
            ->first();
            
        if ($existingTranslation) {
            return redirect()->route('admin.menu-categories.create-translation', $id)
                ->with('error', 'A translation in this language already exists.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'category_' . time() . '_' . Str::slug(substr($request->name, 0, 20)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            } elseif ($request->has('use_source_image') && $sourceCategory->image) {
                // Use source category image if requested
                $imagePath = $sourceCategory->image;
            }
            
            // Create the translation
            $translation = MenuCategory::create([
                'language_id' => $request->language_id,
                'mass_id' => $sourceCategory->mass_id,
                'name' => $request->name,
                'slug' => $request->slug,
                'image' => $imagePath,
                'is_active' => $request->has('is_active'),
                'sort_order' => $sourceCategory->sort_order, // Use same sort order as source
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.menu-categories.edit', $translation->id)
                ->with('success', 'Translation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu-categories.create-translation', $id)
                ->with('error', 'Failed to create translation: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Format a string into a valid slug
     * 
     * @param string $text
     * @return string
     */
    private function formatSlug($text)
    {
        // Convert to lowercase
        $slug = strtolower($text);
        
        // Convert accented characters to ASCII equivalents
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        
        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $slug);
        
        // Remove all remaining non-alphanumeric characters (except hyphens)
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        
        // Replace multiple consecutive hyphens with a single one
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');
        
        return $slug;
    }
}