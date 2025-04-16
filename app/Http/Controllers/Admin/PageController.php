<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Group pages by mass_id to display unique pages with translations
        $massIds = Page::select('mass_id')->distinct()->pluck('mass_id');
        $pages = [];
        
        foreach ($massIds as $massId) {
            // Get default language page or first available
            $defaultLanguage = Language::where('is_default', true)->first();
            $defaultPage = Page::where('mass_id', $massId)
                ->where('language_id', $defaultLanguage->id)
                ->first();
                
            if (!$defaultPage) {
                $defaultPage = Page::where('mass_id', $massId)->first();
            }
            
            // Get all translations
            $translations = Page::where('mass_id', $massId)
                ->with('language')
                ->get();
                
            $pages[] = [
                'page' => $defaultPage,
                'translations' => $translations
            ];
        }
        
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::with('language')->findOrFail($id);
        $languages = Language::where('is_active', true)->get();
        
        // Get translations (pages with same mass_id)
        $translations = Page::where('mass_id', $page->mass_id)
            ->with('language')
            ->get();
        
        return view('admin.pages.edit', compact('page', 'languages', 'translations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        
        // Format the slug before validation
        $request->merge(['slug' => $this->formatSlug($request->slug)]);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/', // Only allow lowercase letters, numbers, and hyphens
                'unique:pages,slug,' . $id . ',id,language_id,' . $page->language_id,
            ],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists and is not a URL
            if ($page->featured_image && 
                !Str::startsWith($page->featured_image, 'http') && 
                File::exists(public_path($page->featured_image))) {
                File::delete(public_path($page->featured_image));
            }
            
            $image = $request->file('featured_image');
            $imageName = 'page_' . time() . '_' . Str::slug(substr($page->title, 0, 20)) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $page->featured_image = 'uploads/' . $imageName;
        }
        
        // Update page
        $page->title = $request->title;
        $page->content = $request->content;
        $page->slug = $request->slug;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->is_active = $request->has('is_active');
        $page->sort_order = $request->sort_order ?? 0;
        $page->save();
        
        return redirect()->route('admin.pages.edit', $page->id)
            ->with('success', 'Page updated successfully');
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'editor_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
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
        
        // Replace any non alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');
        
        return $slug;
    }
}