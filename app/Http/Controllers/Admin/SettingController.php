<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Translation;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        // Changed variable names to avoid conflicts with global variables
        $generalSettingItems = Setting::where('group', 'general')->get()->keyBy('key');
        $contactSettingItems = Setting::where('group', 'contact')->get()->keyBy('key');
        $socialSettingItems = Setting::where('group', 'social')->get()->keyBy('key');
        $seoSettingItems = Setting::where('group', 'seo')->get()->keyBy('key');
        $orderSettingItems = Setting::where('group', 'order')->get()->keyBy('key');
        $mailSettingItems = Setting::where('group', 'mail')->get()->keyBy('key');
        
        // Get all active languages for translatable settings
        $languages = Language::where('is_active', true)->get();
        
        // Get translations for translatable settings
        $translations = [];
        foreach ($languages as $language) {
            $translations[$language->code] = Translation::where('group', 'settings')
                ->where('language_code', $language->code)
                ->get()
                ->keyBy('key');
        }
        
        return view('admin.settings.index', compact(
            'generalSettingItems',
            'contactSettingItems',
            'socialSettingItems',
            'seoSettingItems',
            'orderSettingItems',
            'mailSettingItems',
            'languages',
            'translations'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
            'translations' => 'nullable|array',
            'site_logo' => 'nullable|image|max:1024', // 1MB max
            'favicon' => 'nullable|image|max:512', // 512KB max
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->value = $value;
                $setting->save();
            }
        }

        if ($request->hasFile('site_logo')) {
            $setting = Setting::where('key', 'site_logo')->first();
            if ($setting) {
                $file = $request->file('site_logo');
                $fileName = 'site_logo' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $value = 'uploads/' . $fileName;

                $setting->value = $value;
                $setting->save();
            }
        }
        
        // Process and save translations for translatable settings
        if ($request->has('translations')) {
            foreach ($request->translations as $langCode => $settings) {
                foreach ($settings as $key => $value) {
                    Translation::updateOrCreate(
                        [
                            'group' => 'settings',
                            'key' => $key,
                            'language_code' => $langCode,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }
        }
        
        // Clear settings cache
        clear_settings_cache();
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver' => 'required|string',
            'host' => 'required_if:driver,smtp|string',
            'port' => 'required_if:driver,smtp|numeric',
            'username' => 'required_if:driver,smtp|string',
            'password' => 'required_if:driver,smtp|string',
            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $validator->errors()->first()
            ], 422);
        }

        $mailService = new MailService();
        $testEmail = $request->input('test_email', $request->input('from_address'));
        
        $result = $mailService->sendTestEmail($request->all(), $testEmail);
        
        return response()->json($result);
    }
}