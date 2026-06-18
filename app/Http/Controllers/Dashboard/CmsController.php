<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    protected array $settingsMeta = [
        // Hero Section
        'hero_background_type'  => ['type' => 'text',    'group' => 'hero',    'label' => 'Hero Background Type (image/video)'],
        'hero_background_url'   => ['type' => 'image',   'group' => 'hero',    'label' => 'Hero Background Media'],
        'hero_title_id'         => ['type' => 'text',    'group' => 'hero',    'label' => 'Hero Title (ID)'],
        'hero_title_en'         => ['type' => 'text',    'group' => 'hero',    'label' => 'Hero Title (EN)'],
        'hero_subtitle_id'      => ['type' => 'textarea','group' => 'hero',    'label' => 'Hero Subtitle (ID)'],
        'hero_subtitle_en'      => ['type' => 'textarea','group' => 'hero',    'label' => 'Hero Subtitle (EN)'],
        'hero_cta_text_id'      => ['type' => 'text',    'group' => 'hero',    'label' => 'CTA Button Text (ID)'],
        'hero_cta_text_en'      => ['type' => 'text',    'group' => 'hero',    'label' => 'CTA Button Text (EN)'],
        'hero_cta_url'          => ['type' => 'url',     'group' => 'hero',    'label' => 'CTA Button URL'],
        // About Section
        'about_title_id'        => ['type' => 'text',    'group' => 'about',   'label' => 'About Title (ID)'],
        'about_title_en'        => ['type' => 'text',    'group' => 'about',   'label' => 'About Title (EN)'],
        'about_text_id'         => ['type' => 'textarea','group' => 'about',   'label' => 'About Text (ID)'],
        'about_text_en'         => ['type' => 'textarea','group' => 'about',   'label' => 'About Text (EN)'],
        'about_image'           => ['type' => 'image',   'group' => 'about',   'label' => 'About Image'],
        // Sections Visibility
        'show_promo_section'    => ['type' => 'boolean', 'group' => 'sections','label' => 'Show Promo Section'],
        'show_blog_section'     => ['type' => 'boolean', 'group' => 'sections','label' => 'Show Blog Section'],
        'show_career_section'   => ['type' => 'boolean', 'group' => 'sections','label' => 'Show Career Section'],
        // Contact
        'contact_phone'         => ['type' => 'text',    'group' => 'contact', 'label' => 'Phone'],
        'contact_email'         => ['type' => 'text',    'group' => 'contact', 'label' => 'Email'],
        'social_instagram'      => ['type' => 'url',     'group' => 'contact', 'label' => 'Instagram URL'],
        'social_tiktok'         => ['type' => 'url',     'group' => 'contact', 'label' => 'TikTok URL'],
        'social_facebook'       => ['type' => 'url',     'group' => 'contact', 'label' => 'Facebook URL'],
        'social_youtube'        => ['type' => 'url',     'group' => 'contact', 'label' => 'YouTube URL'],
        'footer_text_id'        => ['type' => 'textarea','group' => 'contact', 'label' => 'Footer Text (ID)'],
        'footer_text_en'        => ['type' => 'textarea','group' => 'contact', 'label' => 'Footer Text (EN)'],
    ];

    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        $meta = $this->settingsMeta;

        $groups = collect($meta)->groupBy(fn($item) => $item['group'])
            ->map(fn($items, $group) => $items->keys());

        return view('dashboard.cms.index', compact('settings', 'meta', 'groups'));
    }

    public function update(Request $request)
    {
        foreach ($this->settingsMeta as $key => $meta) {
            if ($meta['type'] === 'boolean') {
                SiteSetting::updateOrCreate(['key' => $key], [
                    'value' => $request->has($key) ? '1' : '0',
                    'type' => 'boolean', 'group' => $meta['group'], 'label' => $meta['label'],
                ]);
            } elseif ($meta['type'] === 'image' && $request->hasFile($key)) {
                $old = SiteSetting::where('key', $key)->value('value');
                if ($old) Storage::disk('public')->delete($old);
                $path = $request->file($key)->store('cms', 'public');
                SiteSetting::updateOrCreate(['key' => $key], [
                    'value' => $path, 'type' => 'image', 'group' => $meta['group'], 'label' => $meta['label'],
                ]);
            } elseif ($request->has($key)) {
                SiteSetting::updateOrCreate(['key' => $key], [
                    'value' => $request->input($key),
                    'type' => $meta['type'], 'group' => $meta['group'], 'label' => $meta['label'],
                ]);
            }
        }

        return back()->with('success', __('Settings saved successfully!'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        $path = $request->file('image')->store('cms', 'public');
        return response()->json(['url' => Storage::disk('public')->url($path), 'path' => $path]);
    }
}
