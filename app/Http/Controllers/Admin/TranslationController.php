<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    /**
     * Display a listing of translation groups
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all unique groups
        $groups = Translation::select('group')
            ->distinct()
            ->orderBy('group')
            ->get()
            ->pluck('group');
            
        // Get translation statistics for each group
        $groupStats = [];
        $languages = Language::where('is_active', true)->get();
        
        foreach ($groups as $group) {
            $stats = [];
            $totalKeys = Translation::where('group', $group)
                ->distinct('key')
                ->count('key');
                
            foreach ($languages as $language) {
                $translatedCount = Translation::where('group', $group)
                    ->where('language_code', $language->code)
                    ->whereNotNull('value')
                    ->where('value', '!=', '')
                    ->count();
                    
                $percentage = $totalKeys > 0 ? round(($translatedCount / $totalKeys) * 100) : 0;
                
                $stats[$language->code] = [
                    'translated' => $translatedCount,
                    'total' => $totalKeys,
                    'percentage' => $percentage
                ];
            }
            
            $groupStats[$group] = $stats;
        }
        
        return view('admin.translations.index', compact('groups', 'groupStats', 'languages'));
    }
    
    /**
     * Show translations for a specific group
     *
     * @param  string  $group
     * @return \Illuminate\Http\Response
     */
    public function group($group)
    {
        // Get all active languages
        $languages = Language::where('is_active', true)->get();
        
        // Get all unique keys for this group
        $keys = Translation::where('group', $group)
            ->select('key')
            ->distinct()
            ->orderBy('key')
            ->pluck('key');
            
        // Get all translations for this group
        $translations = [];
        
        foreach ($keys as $key) {
            $translations[$key] = [];
            
            foreach ($languages as $language) {
                $translation = Translation::where('group', $group)
                    ->where('key', $key)
                    ->where('language_code', $language->code)
                    ->first();
                    
                $translations[$key][$language->code] = $translation ? $translation->value : null;
            }
        }
        
        return view('admin.translations.group', compact('group', 'languages', 'translations'));
    }
    
    /**
     * Update translations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string',
            'translations' => 'required|array',
            'translations.*' => 'array',
            'translations.*.*' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $group = $request->input('group');
        $translations = $request->input('translations');
        
        try {
            DB::beginTransaction();
            
            foreach ($translations as $key => $languageValues) {
                foreach ($languageValues as $languageCode => $value) {
                    // Find or create translation
                    $translation = Translation::firstOrNew([
                        'group' => $group,
                        'key' => $key,
                        'language_code' => $languageCode,
                    ]);
                    
                    $translation->value = $value;
                    $translation->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.translations.group', $group)
                ->with('success', 'Translations updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.translations.group', $group)
                ->with('error', 'Failed to update translations: ' . $e->getMessage());
        }
    }
    
    /**
     * Import translations from language files
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        try {
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            // Get all language files
            $langPath = resource_path('lang');
            
            if (!File::isDirectory($langPath)) {
                return redirect()->route('admin.translations.index')
                    ->with('error', 'Language directory not found.');
            }
            
            $imported = 0;
            
            // Process each language
            foreach ($languages as $language) {
                $langDir = $langPath . '/' . $language->code;
                
                if (!File::isDirectory($langDir)) {
                    continue;
                }
                
                // Process each file in the language directory
                foreach (File::files($langDir) as $file) {
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $group = $filename;
                    
                    // Load the language file
                    $translations = include($file);
                    
                    if (is_array($translations)) {
                        // Import each translation
                        foreach ($translations as $key => $value) {
                            $this->importTranslation($group, $key, $value, $language->code);
                            $imported++;
                        }
                    }
                }
            }
            
            return redirect()->route('admin.translations.index')
                ->with('success', "Successfully imported {$imported} translations.");
        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Failed to import translations: ' . $e->getMessage());
        }
    }
    
    /**
     * Import nested translations recursively
     *
     * @param  string  $group
     * @param  string  $key
     * @param  mixed   $value
     * @param  string  $languageCode
     * @param  string  $prefix
     * @return void
     */
    private function importTranslation($group, $key, $value, $languageCode, $prefix = '')
    {
        // If value is an array, process it recursively
        if (is_array($value)) {
            foreach ($value as $nestedKey => $nestedValue) {
                $newPrefix = $prefix ? $prefix . '.' . $key : $key;
                $this->importTranslation($group, $nestedKey, $nestedValue, $languageCode, $newPrefix);
            }
        } else {
            // It's a leaf node, save the translation
            $fullKey = $prefix ? $prefix . '.' . $key : $key;
            
            $translation = Translation::firstOrNew([
                'group' => $group,
                'key' => $fullKey,
                'language_code' => $languageCode,
            ]);
            
            $translation->value = $value;
            $translation->save();
        }
    }
    
    /**
     * Export translations to language files
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        try {
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            // Base path for language files
            $langPath = resource_path('lang');
            
            if (!File::isDirectory($langPath)) {
                File::makeDirectory($langPath, 0755, true);
            }
            
            $exported = 0;
            
            // Process each language
            foreach ($languages as $language) {
                $langDir = $langPath . '/' . $language->code;
                
                if (!File::isDirectory($langDir)) {
                    File::makeDirectory($langDir, 0755, true);
                }
                
                // Get all groups for this language
                $groups = Translation::where('language_code', $language->code)
                    ->select('group')
                    ->distinct()
                    ->pluck('group');
                
                // Process each group
                foreach ($groups as $group) {
                    $translations = Translation::where('group', $group)
                        ->where('language_code', $language->code)
                        ->get();
                    
                    // Skip empty groups
                    if ($translations->isEmpty()) {
                        continue;
                    }
                    
                    // Organize translations
                    $phpArray = [];
                    
                    foreach ($translations as $translation) {
                        // Handle nested keys (with dots)
                        $keys = explode('.', $translation->key);
                        $this->buildNestedArray($phpArray, $keys, $translation->value);
                        $exported++;
                    }
                    
                    // Generate PHP file
                    $filePath = $langDir . '/' . $group . '.php';
                    $content = "<?php\n\nreturn " . $this->varExport($phpArray, true) . ";\n";
                    
                    File::put($filePath, $content);
                }
            }
            
            return redirect()->route('admin.translations.index')
                ->with('success', "Successfully exported {$exported} translations.");
        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Failed to export translations: ' . $e->getMessage());
        }
    }
    
    /**
     * Build a nested array from keys and value
     *
     * @param  array   &$array
     * @param  array   $keys
     * @param  string  $value
     * @return void
     */
    private function buildNestedArray(&$array, $keys, $value)
    {
        $key = array_shift($keys);
        
        if (empty($keys)) {
            $array[$key] = $value;
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            
            $this->buildNestedArray($array[$key], $keys, $value);
        }
    }
    
    /**
     * Better implementation of var_export
     *
     * @param  mixed  $expression
     * @param  bool   $return
     * @return string|void
     */
    private function varExport($expression, $return = false)
    {
        $export = var_export($expression, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = implode(PHP_EOL, array_filter(["["] + $array));
        
        if ($return) {
            return $export;
        } else {
            echo $export;
        }
    }
    
    /**
     * Add a new translation key
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string',
            'key' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $group = $request->input('group');
        $key = $request->input('key');
        
        // Check if key already exists
        $exists = Translation::where('group', $group)
            ->where('key', $key)
            ->exists();
            
        if ($exists) {
            return redirect()->route('admin.translations.group', $group)
                ->with('error', "Translation key '{$key}' already exists in group '{$group}'.");
        }
        
        try {
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            // Create empty translations for all languages
            foreach ($languages as $language) {
                Translation::create([
                    'group' => $group,
                    'key' => $key,
                    'value' => null,
                    'language_code' => $language->code,
                ]);
            }
            
            return redirect()->route('admin.translations.group', $group)
                ->with('success', "Translation key '{$key}' added successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.translations.group', $group)
                ->with('error', 'Failed to add translation key: ' . $e->getMessage());
        }
    }
    
    /**
     * Create a new translation group
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|regex:/^[a-zA-Z0-9_]+$/',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('admin.translations.index')
                ->withErrors($validator)
                ->withInput();
        }
        
        $group = $request->input('group_name');
        
        // Check if group already exists
        $exists = Translation::where('group', $group)->exists();
        
        if ($exists) {
            return redirect()->route('admin.translations.index')
                ->with('error', "Translation group '{$group}' already exists.");
        }
        
        try {
            // Get all active languages
            $languages = Language::where('is_active', true)->get();
            
            // Create a sample translation for this group
            foreach ($languages as $language) {
                Translation::create([
                    'group' => $group,
                    'key' => 'sample',
                    'value' => 'Sample Translation',
                    'language_code' => $language->code,
                ]);
            }
            
            return redirect()->route('admin.translations.group', $group)
                ->with('success', "Translation group '{$group}' created successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Failed to create translation group: ' . $e->getMessage());
        }
    }
}