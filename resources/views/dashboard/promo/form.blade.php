@extends('layouts.dashboard')
@section('title', isset($promo) ? 'Edit Promo' : 'Buat Promo Baru')
@section('page-title', isset($promo) ? 'Edit Promo' : 'Buat Promo')
@section('breadcrumb', isset($promo) ? $promo->title : 'Form Penawaran Baru')

@section('content')
<div class="card-panel">
    <form method="POST" 
          action="{{ isset($promo) ? route('dashboard.promo.update', $promo->id) : route('dashboard.promo.store') }}" 
          enctype="multipart/form-data" 
          class="card-panel-body">
        @csrf
        @if(isset($promo))
            @method('PUT')
        @endif

        <div class="grid-sidebar-layout">
            
            {{-- Left Column: Main Settings --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Judul Promo*</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $promo->title ?? '') }}" required placeholder="Contoh: Diskon Gajian 25% Shem Ramen">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Promo</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Tulis rincian dan deskripsi penawaran... (bisa diklaim di seluruh outlet)">{{ old('description', $promo->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Syarat & Ketentuan (Terms)</label>
                    <textarea name="terms" class="form-control" rows="4" placeholder="1. Berlaku dine-in saja&#10;2. Minimal transaksi Rp 100rb&#10;3. Tidak dapat digabung dengan promo lain...">{{ old('terms', $promo->terms ?? '') }}</textarea>
                </div>

                <div class="grid-2-col" style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 24px;">
                    <div class="form-group">
                        <label class="form-label">Tipe Diskon*</label>
                        <select name="promo_type" class="form-control" required>
                            <option value="percentage" {{ old('promo_type', $promo->promo_type ?? '') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('promo_type', $promo->promo_type ?? '') === 'fixed' ? 'selected' : '' }}>Potongan Harga Tetap (Nominal Rp)</option>
                            <option value="free_item" {{ old('promo_type', $promo->promo_type ?? '') === 'free_item' ? 'selected' : '' }}>Free Item (Menu Gratis)</option>
                            <option value="buy_x_get_y" {{ old('promo_type', $promo->promo_type ?? '') === 'buy_x_get_y' ? 'selected' : '' }}>Buy 1 Get 1 / Buy X Get Y</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Diskon (Value)</label>
                        <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $promo->discount_value ?? '') }}" placeholder="Contoh: 25 (untuk 25%) atau 50000 (untuk Rp 50.000)">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Label Diskon (Badge Label)</label>
                    <input type="text" name="discount_label" class="form-control" value="{{ old('discount_label', $promo->discount_label ?? '') }}" placeholder="Contoh: Diskon 25% / Gratis Gyoza">
                </div>
            </div>

            {{-- Right Column: Media & Meta --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Banner Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if(isset($promo) && $promo->image)
                    <div style="margin-top: 12px;">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Banner Saat Ini:</div>
                        <img src="{{ $promo->image_url }}" style="width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--border-light);">
                    </div>
                    @endif
                </div>

                <div class="grid-2-col">
                    <div class="form-group">
                        <label class="form-label">Tanggal Muli</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ old('start_date', isset($promo) && $promo->start_date ? $promo->start_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ old('end_date', isset($promo) && $promo->end_date ? $promo->end_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Batas Maksimal Klaim (Max Claims)</label>
                    <input type="number" name="max_claims" class="form-control" value="{{ old('max_claims', $promo->max_claims ?? '') }}" placeholder="Contoh: 500 (kosongkan untuk unlimited)">
                </div>

                <div class="form-group">
                    <label class="form-label">Status Promo</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ old('status', $promo->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $promo->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        @if(isset($promo))
                        <option value="expired" {{ old('status', $promo->status ?? '') === 'expired' ? 'selected' : '' }}>Expired</option>
                        @endif
                    </select>
                </div>
            </div>

        </div>

        {{-- Form Buttons --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('dashboard.promo.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Simpan Promo
            </button>
        </div>

    </form>
</div>
@endsection
