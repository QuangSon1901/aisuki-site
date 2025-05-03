<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        // Group announcements by mass_id to display unique items with translations
        $massIds = Announcement::select('mass_id')->distinct()->pluck('mass_id');
        $announcements = [];
        
        foreach ($massIds as $massId) {
            // Get default language announcement or first available
            $defaultLanguage = Language::where('is_default', true)->first();
            $defaultAnnouncement = Announcement::where('mass_id', $massId)
                ->where('language_id', $defaultLanguage->id)
                ->first();
                
            if (!$defaultAnnouncement) {
                $defaultAnnouncement = Announcement::where('mass_id', $massId)->first();
            }
            
            // Get all translations
            $translations = Announcement::where('mass_id', $massId)
                ->with('language')
                ->get();
                
            $announcements[] = [
                'announcement' => $defaultAnnouncement,
                'translations' => $translations
            ];
        }
        
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = Language::where('is_default', true)->first();
        
        // Generate next available mass_id
        $nextMassId = Announcement::max('mass_id') + 1;
        
        return view('admin.announcements.create', compact('languages', 'defaultLanguage', 'nextMassId'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'mass_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.announcements.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create the announcement
            Announcement::create([
                'language_id' => $request->language_id,
                'mass_id' => $request->mass_id,
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->has('is_active'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_dismissible' => $request->has('is_dismissible'),
                'priority' => $request->priority ?? 0,
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.announcements.create')
                ->with('error', 'Failed to create announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit($id)
    {
        $announcement = Announcement::with('language')->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get translations (items with same mass_id)
        $translations = Announcement::where('mass_id', $announcement->mass_id)
            ->where('id', '!=', $announcement->id)
            ->with('language')
            ->get();
        
        return view('admin.announcements.edit', compact('announcement', 'languages', 'translations'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.announcements.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update the announcement
            $announcement->update([
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->has('is_active'),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_dismissible' => $request->has('is_dismissible'),
                'priority' => $request->priority ?? 0,
                'sort_order' => $request->sort_order ?? 0,
            ]);
            
            return redirect()->route('admin.announcements.edit', $announcement->id)
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.announcements.edit', $id)
                ->with('error', 'Failed to update announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();
            
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.announcements.index')
                ->with('error', 'Failed to delete announcement: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for creating a translation of an announcement
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createTranslation($id)
    {
        $sourceAnnouncement = Announcement::with('language')->findOrFail($id);
        
        // Get languages that don't have a translation for this announcement yet
        $existingLanguages = Announcement::where('mass_id', $sourceAnnouncement->mass_id)
            ->pluck('language_id')
            ->toArray();
            
        $availableLanguages = Language::where('is_active', true)
            ->whereNotIn('id', $existingLanguages)
            ->get();
            
        if ($availableLanguages->isEmpty()) {
            return redirect()->route('admin.announcements.edit', $id)
                ->with('error', 'All languages already have translations for this announcement.');
        }
        
        return view('admin.announcements.create-translation', compact('sourceAnnouncement', 'availableLanguages'));
    }
    
    /**
     * Store a new translation for an announcement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeTranslation(Request $request, $id)
    {
        $sourceAnnouncement = Announcement::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|exists:languages,id',
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.announcements.create-translation', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Check if translation already exists
        $existingTranslation = Announcement::where('mass_id', $sourceAnnouncement->mass_id)
            ->where('language_id', $request->language_id)
            ->first();
            
        if ($existingTranslation) {
            return redirect()->route('admin.announcements.create-translation', $id)
                ->with('error', 'A translation in this language already exists.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create the translation (inherit properties from source announcement)
            $translation = Announcement::create([
                'language_id' => $request->language_id,
                'mass_id' => $sourceAnnouncement->mass_id,
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? true : $sourceAnnouncement->is_active,
                'start_date' => $sourceAnnouncement->start_date,
                'end_date' => $sourceAnnouncement->end_date,
                'is_dismissible' => $sourceAnnouncement->is_dismissible,
                'priority' => $sourceAnnouncement->priority,
                'sort_order' => $sourceAnnouncement->sort_order,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.announcements.edit', $translation->id)
                ->with('success', 'Translation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.announcements.create-translation', $id)
                ->with('error', 'Failed to create translation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload an image from the Quill editor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'announcement_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            
            return response()->json([
                'success' => true,
                'url' => asset('uploads/' . $imageName)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No image provided'
        ], 400);
    }

    /**
     * Toggle active status for an announcement and all its translations
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($id)
    {
        try {
            // Find the announcement
            $announcement = Announcement::findOrFail($id);
            
            // Get the current active state (we'll toggle to the opposite)
            $newActiveState = !$announcement->is_active;
            
            // Update all announcements with the same mass_id
            Announcement::where('mass_id', $announcement->mass_id)
                ->update(['is_active' => $newActiveState]);
            
            $statusText = $newActiveState ? 'activated' : 'deactivated';
            return redirect()->route('admin.announcements.index')
                ->with('success', "Announcement '{$announcement->title}' and all its translations have been {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->route('admin.announcements.index')
                ->with('error', 'Failed to update announcement status: ' . $e->getMessage());
        }
    }
}