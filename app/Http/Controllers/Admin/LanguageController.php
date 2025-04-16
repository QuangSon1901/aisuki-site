<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created language
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:2|unique:languages,code',
            'name' => 'required|string|max:255',
            'native_name' => 'required|string|max:255',
            'flag' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.languages.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Check if setting as default
            $makeDefault = $request->has('is_default');
            
            // If this will be default, unset any existing default
            if ($makeDefault) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }
            
            // Create the language
            Language::create([
                'code' => strtolower($request->code),
                'name' => $request->name,
                'native_name' => $request->native_name,
                'flag' => $request->flag,
                'is_default' => $makeDefault,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.languages.index')
                ->with('success', 'Language created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.languages.create')
                ->with('error', 'Failed to create language: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified language
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $language = Language::findOrFail($id);
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified language
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'code' => [
                'required', 
                'string', 
                'size:2', 
                Rule::unique('languages')->ignore($id)
            ],
            'name' => 'required|string|max:255',
            'native_name' => 'required|string|max:255',
            'flag' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.languages.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Check if setting as default
            $makeDefault = $request->has('is_default');
            
            // If this will be default, unset any existing default
            if ($makeDefault && !$language->is_default) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }
            
            // If this was default but the checkbox is unchecked
            if ($language->is_default && !$makeDefault) {
                // Check if it's the only language
                $languageCount = Language::count();
                if ($languageCount === 1) {
                    return redirect()->route('admin.languages.edit', $id)
                        ->with('error', 'This is the only language and must remain default.')
                        ->withInput();
                }
                
                // Ensure at least one language is default
                $anotherLanguage = Language::where('id', '!=', $id)->first();
                if ($anotherLanguage) {
                    $anotherLanguage->is_default = true;
                    $anotherLanguage->save();
                }
            }
            
            // Update the language
            $language->update([
                'code' => strtolower($request->code),
                'name' => $request->name,
                'native_name' => $request->native_name,
                'flag' => $request->flag,
                'is_default' => $makeDefault,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.languages.index')
                ->with('success', 'Language updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.languages.edit', $id)
                ->with('error', 'Failed to update language: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified language if safe to do so
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->route('admin.languages.index')
                ->with('error', 'Failed to delete language');
    }
    
    /**
     * Set a language as default
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setDefault($id)
    {
        try {
            DB::beginTransaction();
            
            // Unset current default
            Language::where('is_default', true)->update(['is_default' => false]);
            
            // Set new default
            $language = Language::findOrFail($id);
            $language->is_default = true;
            $language->save();
            
            DB::commit();
            
            return redirect()->route('admin.languages.index')
                ->with('success', $language->name . ' has been set as the default language.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.languages.index')
                ->with('error', 'Failed to set default language: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle language active status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($id)
    {
        try {
            $language = Language::findOrFail($id);
            
            // Cannot deactivate default language
            if ($language->is_default && $language->is_active) {
                return redirect()->route('admin.languages.index')
                    ->with('error', 'Cannot deactivate the default language.');
            }
            
            $language->is_active = !$language->is_active;
            $language->save();
            
            $status = $language->is_active ? 'activated' : 'deactivated';
            
            return redirect()->route('admin.languages.index')
                ->with('success', $language->name . ' has been ' . $status . '.');
        } catch (\Exception $e) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Failed to update language status: ' . $e->getMessage());
        }
    }
}