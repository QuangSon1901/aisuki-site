<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddonItemController extends Controller
{
    /**
     * Display a listing of addon items
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Group items by mass_id to display unique items with translations
        $massIds = AddonItem::select('mass_id')->distinct()->pluck('mass_id');
        $addonItems = [];
        
        foreach ($massIds as $massId) {
            // Get default language item or first available
            $defaultLanguage = Language::where('is_default', true)->first();
            $defaultItem = AddonItem::where('mass_id', $massId)
                ->where('language_id', $defaultLanguage->id)
                ->first();
                
            if (!$defaultItem) {
                $defaultItem = AddonItem::where('mass_id', $massId)->first();
            }
            
            // Get all translations
            $translations = AddonItem::where('mass_id', $massId)
                ->with('language')
                ->get();
                
            $addonItems[] = [
                'item' => $defaultItem,
                'translations' => $translations
            ];
        }
        
        return view('admin.addon-items.index', compact('addonItems'));
    }

    /**
     * Show the form for creating a new addon item
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = Language::where('is_default', true)->first();
        
        // Generate next available mass_id
        $nextMassId = AddonItem::max('mass_id') + 1;
        
        return view('admin.addon-items.create', compact('languages', 'defaultLanguage', 'nextMassId'));
    }

    /**
     * Store a newly created addon item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'mass_id' => 'required|integer',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('addon_items')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->language_id);
                }),
            ],
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.addon-items.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create the addon item
            AddonItem::create([
                'language_id' => $request->language_id,
                'mass_id' => $request->mass_id,
                'name' => $request->name,
                'price' => $request->price,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.addon-items.index')
                ->with('success', 'Addon item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.addon-items.create')
                ->with('error', 'Failed to create addon item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified addon item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $addonItem = AddonItem::with('language')->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get translations (items with same mass_id)
        $translations = AddonItem::where('mass_id', $addonItem->mass_id)
            ->where('id', '!=', $addonItem->id)
            ->with('language')
            ->get();
        
        return view('admin.addon-items.edit', compact('addonItem', 'languages', 'translations'));
    }

    /**
     * Update the specified addon item
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $addonItem = AddonItem::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('addon_items')->where(function ($query) use ($request, $id) {
                    return $query->where('language_id', $request->language_id)
                                 ->where('id', '!=', $id);
                }),
            ],
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.addon-items.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update the addon item
            $addonItem->name = $request->name;
            $addonItem->price = $request->price;
            $addonItem->is_active = $request->has('is_active');
            $addonItem->sort_order = $request->sort_order ?? 0;
            $addonItem->save();
            
            return redirect()->route('admin.addon-items.edit', $addonItem->id)
                ->with('success', 'Addon item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.addon-items.edit', $id)
                ->with('error', 'Failed to update addon item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified addon item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $addonItem = AddonItem::findOrFail($id);
            $addonItem->delete();
            
            return redirect()->route('admin.addon-items.index')
                ->with('success', 'Addon item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.addon-items.index')
                ->with('error', 'Failed to delete addon item: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for creating a translation of an addon item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createTranslation($id)
    {
        $sourceItem = AddonItem::with('language')->findOrFail($id);
        
        // Get languages that don't have a translation for this addon item yet
        $existingLanguages = AddonItem::where('mass_id', $sourceItem->mass_id)
            ->pluck('language_id')
            ->toArray();
            
        $availableLanguages = Language::where('is_active', true)
            ->whereNotIn('id', $existingLanguages)
            ->get();
            
        if ($availableLanguages->isEmpty()) {
            return redirect()->route('admin.addon-items.edit', $id)
                ->with('error', 'All languages already have translations for this addon item.');
        }
        
        return view('admin.addon-items.create-translation', compact('sourceItem', 'availableLanguages'));
    }
    
    /**
     * Store a new translation for an addon item
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeTranslation(Request $request, $id)
    {
        $sourceItem = AddonItem::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('addon_items')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->language_id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.addon-items.create-translation', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Check if translation already exists
        $existingTranslation = AddonItem::where('mass_id', $sourceItem->mass_id)
            ->where('language_id', $request->language_id)
            ->first();
            
        if ($existingTranslation) {
            return redirect()->route('admin.addon-items.create-translation', $id)
                ->with('error', 'A translation in this language already exists.')
                ->withInput();
        }

        try {
            // Create the translation
            $translation = AddonItem::create([
                'language_id' => $request->language_id,
                'mass_id' => $sourceItem->mass_id,
                'name' => $request->name,
                'price' => $request->has('use_source_price') ? $sourceItem->price : $request->price,
                'is_active' => $request->has('is_active'),
                'sort_order' => $sourceItem->sort_order, // Use same sort order as source
            ]);
            
            return redirect()->route('admin.addon-items.edit', $translation->id)
                ->with('success', 'Translation created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.addon-items.create-translation', $id)
                ->with('error', 'Failed to create translation: ' . $e->getMessage())
                ->withInput();
        }
    }
}