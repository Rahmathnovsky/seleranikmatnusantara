@extends('layouts.dashboard')
@section('title', 'CMS Settings')
@section('page-title', 'CMS Settings')
@section('breadcrumb', 'Manage Homepage Settings')

@section('content')
<div class="card-panel" x-data="{ activeTab: 'hero' }">
    {{-- Tabs Navigation --}}
    <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 0;">
        <div style="display: flex; gap: 8px; margin-bottom: -1px;">
            <button @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'btn btn-primary' : 'btn btn-outline'" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <i class="fas fa-image"></i> Hero Section
            </button>
            <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'btn btn-primary' : 'btn btn-outline'" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <i class="fas fa-circle-info"></i> About Section
            </button>
            <button @click="activeTab = 'sections'" :class="activeTab === 'sections' ? 'btn btn-primary' : 'btn btn-outline'" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <i class="fas fa-eye"></i> Visibility
            </button>
            <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'btn btn-primary' : 'btn btn-outline'" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <i class="fas fa-address-book"></i> Contact & Footer
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('dashboard.cms.update') }}" enctype="multipart/form-data" class="card-panel-body" style="padding-top: 24px;">
        @csrf

        {{-- Group: Hero --}}
        <div x-show="activeTab === 'hero'" x-transition>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; color: var(--primary);">
                <i class="fas fa-photo-film"></i> Hero Section Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Hero Background Type</label>
                <select name="hero_background_type" class="form-control">
                    <option value="image" {{ ($settings['hero_background_type']->value ?? '') === 'image' ? 'selected' : '' }}>Image (Gambar)</option>
                    <option value="video" {{ ($settings['hero_background_type']->value ?? '') === 'video' ? 'selected' : '' }}>Video (MP4)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Hero Background Media (Upload New)</label>
                <input type="file" name="hero_background_url" class="form-control" accept="image/*,video/mp4">
                @if(isset($settings['hero_background_url']->value) && $settings['hero_background_url']->value)
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 4px;">Current Background:</div>
                    @if(Str::endsWith($settings['hero_background_url']->value, '.mp4'))
                        <video style="max-height: 120px; border-radius: 8px; border: 1px solid var(--border);" controls>
                            <source src="{{ asset('storage/' . $settings['hero_background_url']->value) }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ asset('storage/' . $settings['hero_background_url']->value) }}" style="max-height: 120px; border-radius: 8px; border: 1px solid var(--border);">
                    @endif
                </div>
                @endif
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">Hero Title (Indonesian)*</label>
                    <input type="text" name="hero_title_id" class="form-control" value="{{ $settings['hero_title_id']->value ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Title (English)*</label>
                    <input type="text" name="hero_title_en" class="form-control" value="{{ $settings['hero_title_en']->value ?? '' }}" required>
                </div>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">Hero Subtitle (Indonesian)</label>
                    <textarea name="hero_subtitle_id" class="form-control" rows="3">{{ $settings['hero_subtitle_id']->value ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Subtitle (English)</label>
                    <textarea name="hero_subtitle_en" class="form-control" rows="3">{{ $settings['hero_subtitle_en']->value ?? '' }}</textarea>
                </div>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">CTA Button Text (Indonesian)</label>
                    <input type="text" name="hero_cta_text_id" class="form-control" value="{{ $settings['hero_cta_text_id']->value ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">CTA Button Text (English)</label>
                    <input type="text" name="hero_cta_text_en" class="form-control" value="{{ $settings['hero_cta_text_en']->value ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">CTA Button Destination URL</label>
                <input type="text" name="hero_cta_url" class="form-control" value="{{ $settings['hero_cta_url']->value ?? '' }}" placeholder="/brands">
            </div>
        </div>

        {{-- Group: About --}}
        <div x-show="activeTab === 'about'" x-transition>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; color: var(--primary);">
                <i class="fas fa-circle-info"></i> About Section Settings
            </h3>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">About Title (Indonesian)*</label>
                    <input type="text" name="about_title_id" class="form-control" value="{{ $settings['about_title_id']->value ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">About Title (English)*</label>
                    <input type="text" name="about_title_en" class="form-control" value="{{ $settings['about_title_en']->value ?? '' }}" required>
                </div>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">About Text Description (Indonesian)</label>
                    <textarea name="about_text_id" class="form-control" rows="5">{{ $settings['about_text_id']->value ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">About Text Description (English)</label>
                    <textarea name="about_text_en" class="form-control" rows="5">{{ $settings['about_text_en']->value ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">About Image (Upload New)</label>
                <input type="file" name="about_image" class="form-control" accept="image/*">
                @if(isset($settings['about_image']->value) && $settings['about_image']->value)
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 4px;">Current Image:</div>
                    <img src="{{ asset('storage/' . $settings['about_image']->value) }}" style="max-height: 120px; border-radius: 8px; border: 1px solid var(--border);">
                </div>
                @endif
            </div>
        </div>

        {{-- Group: Sections --}}
        <div x-show="activeTab === 'sections'" x-transition>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; color: var(--primary);">
                <i class="fas fa-eye"></i> Section Visibility Settings
            </h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 24px;">
                Toggle home page sections on or off dynamically.
            </p>

            <div class="form-group" style="display: flex; align-items: center; gap: 12px; background: var(--bg); padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid var(--border-light);">
                <input type="checkbox" name="show_promo_section" id="show_promo_section" value="1" {{ ($settings['show_promo_section']->value ?? '1') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                <label for="show_promo_section" style="font-weight: 600; cursor: pointer;">Show Promo Section (Tampilkan Section Promo)</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 12px; background: var(--bg); padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid var(--border-light);">
                <input type="checkbox" name="show_blog_section" id="show_blog_section" value="1" {{ ($settings['show_blog_section']->value ?? '1') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                <label for="show_blog_section" style="font-weight: 600; cursor: pointer;">Show Blog Section (Tampilkan Section Blog & Tips)</label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 12px; background: var(--bg); padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid var(--border-light);">
                <input type="checkbox" name="show_career_section" id="show_career_section" value="1" {{ ($settings['show_career_section']->value ?? '1') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                <label for="show_career_section" style="font-weight: 600; cursor: pointer;">Show Career Section (Tampilkan Section Karir)</label>
            </div>
        </div>

        {{-- Group: Contact --}}
        <div x-show="activeTab === 'contact'" x-transition>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; color: var(--primary);">
                <i class="fas fa-address-book"></i> Contact & Footer Settings
            </h3>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">Contact Phone Number</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone']->value ?? '' }}" placeholder="+62 21 1234 5678">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Email Address</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email']->value ?? '' }}" placeholder="info@seleranikmatnusantara.test">
                </div>
            </div>

            <h4 style="font-size: 0.95rem; font-weight: 600; margin: 24px 0 12px; color: var(--text-secondary); border-bottom: 1px dashed var(--border-light); padding-bottom: 6px;">
                Social Media Links
            </h4>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label"><i class="fab fa-instagram" style="color: #e1306c;"></i> Instagram URL</label>
                    <input type="url" name="social_instagram" class="form-control" value="{{ $settings['social_instagram']->value ?? '' }}" placeholder="https://instagram.com/seleranikmatnusantara">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fab fa-tiktok" style="color: #000;"></i> TikTok URL</label>
                    <input type="url" name="social_tiktok" class="form-control" value="{{ $settings['social_tiktok']->value ?? '' }}" placeholder="https://tiktok.com/@seleranikmatnusantara">
                </div>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label"><i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook Page URL</label>
                    <input type="url" name="social_facebook" class="form-control" value="{{ $settings['social_facebook']->value ?? '' }}" placeholder="https://facebook.com/seleranikmatnusantara">
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fab fa-youtube" style="color: #ff0000;"></i> YouTube Channel URL</label>
                    <input type="url" name="social_youtube" class="form-control" value="{{ $settings['social_youtube']->value ?? '' }}" placeholder="https://youtube.com/c/seleranikmatnusantara">
                </div>
            </div>

            <h4 style="font-size: 0.95rem; font-weight: 600; margin: 24px 0 12px; color: var(--text-secondary); border-bottom: 1px dashed var(--border-light); padding-bottom: 6px;">
                Footer Narrative Description
            </h4>
            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">Footer Description (Indonesian)</label>
                    <textarea name="footer_text_id" class="form-control" rows="3">{{ $settings['footer_text_id']->value ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Footer Description (English)</label>
                    <textarea name="footer_text_en" class="form-control" rows="3">{{ $settings['footer_text_en']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn btn-outline" @click="window.location.reload()">Reset</button>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
