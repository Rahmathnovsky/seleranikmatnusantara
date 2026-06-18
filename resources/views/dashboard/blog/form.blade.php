@extends('layouts.dashboard')
@section('title', isset($post) ? 'Edit Artikel' : 'Tulis Artikel')
@section('page-title', isset($post) ? 'Edit Artikel' : 'Tulis Artikel')
@section('breadcrumb', isset($post) ? $post->title : 'Buat Artikel Baru')

@section('content')
<div class="card-panel">
    <form method="POST" 
          action="{{ isset($post) ? route('dashboard.blog.update', $post->id) : route('dashboard.blog.store') }}" 
          enctype="multipart/form-data" 
          class="card-panel-body">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif

        <div class="grid-sidebar-layout">
            
            {{-- Left column: Main Content --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Judul Artikel*</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required placeholder="Contoh: 5 Makanan Nusantara Terfavorit di Shem Ramen">
                </div>

                <div class="form-group">
                    <label class="form-label">Kutipan / Ringkasan Singkat</label>
                    <textarea name="excerpt" class="form-control" rows="3" placeholder="Tulis deskripsi singkat artikel (maksimal 500 karakter)...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Isi Artikel / Konten Utama*</label>
                    <textarea name="body" class="form-control" rows="16" required placeholder="Tulis isi artikel lengkap disini..." style="font-family: inherit;">{{ old('body', $post->body ?? '') }}</textarea>
                </div>

                {{-- SEO Fields --}}
                <div style="border-top: 1px solid var(--border-light); padding-top: 24px; margin-top: 32px;">
                    <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 16px; color: var(--text-secondary);">
                        <i class="fas fa-search"></i> SEO Meta Configuration (Optional)
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Meta Title (SEO)</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title ?? '') }}" placeholder="Optimized title for search engines">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description (SEO)</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Optimized description for search snippets...">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right column: Sidebar Settings --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="blog_category_id" class="form-control">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('blog_category_id', $post->blog_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                    @if(isset($post) && $post->cover_image)
                    <div style="margin-top: 12px;">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Cover Saat Ini:</div>
                        <img src="{{ $post->cover_image_url }}" style="width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--border-light);">
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Status Penerbitan</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ old('status', $post->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        @if(isset($post))
                        <option value="archived" {{ old('status', $post->status ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Terbit</label>
                    <input type="datetime-local" name="published_at" class="form-control" 
                           value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                    <span style="font-size: 0.7rem; color: var(--text-muted); display: block; margin-top: 4px;">
                        Biarkan kosong jika ingin langsung diterbitkan saat disimpan.
                    </span>
                </div>

                @if(isset($post))
                <div class="form-group" style="background: var(--bg); padding: 16px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.75rem; color: var(--text-secondary);">
                    <div><i class="fas fa-eye"></i> Dilihat: {{ number_format($post->views) }} kali</div>
                    <div style="margin-top: 6px;"><i class="fas fa-heart" style="color: var(--danger);"></i> Disukai: {{ number_format($post->likes) }} orang</div>
                    <div style="margin-top: 6px;"><i class="fas fa-comments"></i> Komentar: {{ $post->comments()->count() }}</div>
                </div>
                @endif
            </div>

        </div>

        {{-- Form Buttons --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('dashboard.blog.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Simpan Artikel
            </button>
        </div>

    </form>
</div>
@endsection
