<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $defaultLanguage = Language::where('is_default', true)->first();
        $categories = MenuCategory::where('language_id', $defaultLanguage->id)
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.menu-categories.index', compact('categories'));
    }

    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        return view('admin.menu-categories.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
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
            $massId = MenuCategory::max('mass_id') + 1;
            
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            foreach ($languages as $language) {
                $nameField = 'name_' . $language->code;
                $slugField = 'slug_' . $language->code;
                
                $name = $request->has($nameField) ? $request->$nameField : $request->name;
                $slug = $request->has($slugField) && !empty($request->$slugField) 
                       ? $request->$slugField 
                       : Str::slug($name);
                
                MenuCategory::create([
                    'language_id' => $language->id,
                    'mass_id' => $massId,
                    'name' => $name,
                    'slug' => $slug,
                    'image' => $imagePath,
                    'sort_order' => $request->sort_order,
                    'is_active' => $request->has('is_active'),
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.menu-categories.index')
                ->with('success', 'Menu category created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating menu category: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $category = MenuCategory::findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        $translations = MenuCategory::where('mass_id', $category->mass_id)
            ->where('id', '!=', $category->id)
            ->get()
            ->keyBy('language_id');
            
        return view('admin.menu-categories.edit', compact('category', 'languages', 'translations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $category = MenuCategory::findOrFail($id);
            
            // Handle image upload
            $imagePath = $category->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            }
            
            // Update the current category
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'image' => $imagePath,
                'sort_order' => $request->sort_order,
                'is_active' => $request->has('is_active'),
            ]);
            
            // Update translations
            $languages = Language::where('is_active', true)->get();
            foreach ($languages as $language) {
                if ($language->id == $category->language_id) continue;
                
                $nameField = 'name_' . $language->code;
                $slugField = 'slug_' . $language->code;
                
                if ($request->has($nameField)) {
                    $translation = MenuCategory::firstOrNew([
                        'mass_id' => $category->mass_id,
                        'language_id' => $language->id,
                    ]);
                    
                    $translation->name = $request->$nameField;
                    $translation->slug = $request->has($slugField) && !empty($request->$slugField)
                                      ? $request->$slugField
                                      : Str::slug($request->$nameField);
                    $translation->image = $imagePath;
                    $translation->sort_order = $request->sort_order;
                    $translation->is_active = $request->has('is_active');
                    $translation->save();
                }
            }
            
            DB::commit();
            return redirect()->route('admin.menu-categories.index')
                ->with('success', 'Menu category updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating menu category: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = MenuCategory::findOrFail($id);
            $massId = $category->mass_id;
            
            // Delete all translations (all categories with same mass_id)
            $categories = MenuCategory::where('mass_id', $massId)->get();
            
            foreach ($categories as $cat) {
                $cat->delete();
            }
            
            return redirect()->route('admin.menu-categories.index')
                ->with('success', 'Menu category deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting menu category: ' . $e->getMessage());
        }
    }
}