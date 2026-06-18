@extends('layouts.dashboard')
@section('title', isset($brand) ? 'Edit Brand' : 'Tambah Brand Baru')
@section('page-title', isset($brand) ? 'Edit Brand' : 'Tambah Brand')
@section('breadcrumb', isset($brand) ? $brand->name : 'Form Brand Baru')

@section('content')
<div class="card-panel">
    <form method="POST" 
          action="{{ isset($brand) ? route('dashboard.brands.update', $brand->id) : route('dashboard.brands.store') }}" 
          enctype="multipart/form-data" 
          class="card-panel-body">
        @csrf
        @if(isset($brand))
            @method('PUT')
        @endif

        <div class="grid-sidebar-layout">
            
            {{-- Left column: General Info --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Nama Brand*</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required placeholder="Contoh: Shem Ramen">
                </div>

                <div class="form-group">
                    <label class="form-label">Tagline Brand</label>
                    <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $brand->tagline ?? '') }}" placeholder="Contoh: Ramen Autentik Jepang Halal">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Brand</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Tulis deskripsi detail brand, sejarah singkat, atau menu unggulannya...">{{ old('description', $brand->description ?? '') }}</textarea>
                </div>

                <div class="grid-2-col" style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 24px;">
                    <div class="form-group">
                        <label class="form-label">Jenis Kuliner (Cuisine)*</label>
                        <input type="text" name="cuisine_type" class="form-control" value="{{ old('cuisine_type', $brand->cuisine_type ?? '') }}" placeholder="Contoh: Japanese, Indonesian" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Warna Utama Brand (Primary Hex Color)</label>
                        <input type="color" name="color_primary" class="form-control" value="{{ old('color_primary', $brand->color_primary ?? '#634524') }}" style="height: 44px; padding: 4px; cursor: pointer;">
                    </div>
                </div>

                <div class="grid-2-col">
                    <div class="form-group">
                        <label class="form-label">Website Resmi URL</label>
                        <input type="url" name="website_url" class="form-control" value="{{ old('website_url', $brand->website_url ?? '') }}" placeholder="https://shemramen.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instagram URL</label>
                        <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $brand->instagram_url ?? '') }}" placeholder="https://instagram.com/shemramen">
                    </div>
                </div>
            </div>

            {{-- Right column: Files & Ordering --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Logo Brand (PNG/JPG)</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if(isset($brand) && $brand->logo)
                    <div style="margin-top: 12px; text-align: center; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid var(--border-light);">
                        <img src="{{ $brand->logo_url }}" style="max-height: 80px; object-fit: contain;">
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Cover/Header Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                    @if(isset($brand) && $brand->cover_image)
                    <div style="margin-top: 12px;">
                        <img src="{{ $brand->cover_image_url }}" style="width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--border-light);">
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->sort_order ?? 1) }}" required>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; background: var(--bg); padding: 12px; border-radius: 8px; border: 1px solid var(--border-light);">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                        <label for="is_active" style="font-weight: 600; cursor: pointer; margin-bottom: 0;">Brand Aktif</label>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Simpan Brand
            </button>
        </div>

    </form>
</div>
@endsection
