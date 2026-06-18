@extends('layouts.dashboard')
@section('title', 'Outlet — ' . $brand->name)
@section('page-title', 'Outlet Cabang')
@section('breadcrumb', $brand->name)

@section('content')
<div class="grid-sidebar-layout">
    
    {{-- Left column: Outlets List --}}
    <div class="card-panel">
        <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid var(--border-light); overflow: hidden; background: #fff; padding: 4px; display: inline-flex; align-items: center; justify-content: center;">
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Daftar Cabang {{ $brand->name }}</h3>
                </div>
            </div>
            <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Brand
            </a>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Cabang</th>
                        <th>Wilayah (Region)</th>
                        <th>Alamat & Kontak</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 100px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outlets as $outlet)
                    <tr>
                        <td>
                            @if($outlet->photo)
                            <img src="{{ $outlet->photo_url }}" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                            @else
                            <div style="width: 50px; height: 35px; border-radius: 4px; background: var(--bg-secondary); border: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center;" title="No photo">
                                <i class="fas fa-image" style="color: var(--text-muted); font-size: 0.8rem;"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; font-size: 0.9rem; color: var(--primary);">{{ $outlet->name }}</div>
                        </td>
                        <td style="font-size: 0.825rem;">
                            {{ $outlet->region?->name ?? '—' }}
                            @if($outlet->region?->parent)
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Provinsi: {{ $outlet->region->parent->name }}</div>
                            @endif
                        </td>
                        <td style="font-size: 0.78rem;">
                            @if($outlet->address)
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $outlet->address }}">{{ $outlet->address }}</div>
                            @endif
                            <div style="display: flex; gap: 8px; margin-top: 4px; flex-wrap: wrap;">
                                @if($outlet->phone)<span><i class="fas fa-phone"></i> {{ $outlet->phone }}</span>@endif
                                @if($outlet->whatsapp)<span><i class="fab fa-whatsapp" style="color: var(--success);"></i> {{ $outlet->whatsapp }}</span>@endif
                                @if($outlet->gmaps_url)<a href="{{ $outlet->gmaps_url }}" target="_blank" style="color: var(--info);"><i class="fas fa-map-location-dot"></i> Maps</a>@endif
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <form method="POST" action="{{ route('dashboard.brands.outlets.update', [$brand->id, $outlet->id]) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $outlet->name }}">
                                <input type="hidden" name="is_active" value="{{ $outlet->is_active ? 0 : 1 }}">
                                <button type="submit" class="badge {{ $outlet->is_active ? 'badge-success' : 'badge-warning' }}" style="border: none; cursor: pointer;" title="Klik untuk ubah status">
                                    {{ $outlet->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" action="{{ route('dashboard.brands.outlets.destroy', [$brand->id, $outlet->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus outlet cabang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus Cabang">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fas fa-map-pin" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                            Belum ada cabang terdaftar untuk brand ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($outlets->hasPages())
        <div style="padding: 20px 0;">
            {{ $outlets->links() }}
        </div>
        @endif
    </div>

    {{-- Right column: Add Outlet Form --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title"><i class="fas fa-plus-circle" style="color: var(--accent);"></i> Cabang Baru</h3>
        </div>
        <form method="POST" action="{{ route('dashboard.brands.outlets.store', $brand->id) }}" enctype="multipart/form-data" class="card-panel-body">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Cabang / Outlet*</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Shem Ramen Kelapa Gading">
            </div>

            <div class="form-group">
                <label class="form-label">Wilayah (Kota)*</label>
                <select name="region_id" class="form-control" required>
                    <option value="">Pilih Kota...</option>
                    @foreach($regions as $r)
                        @if($r->type === 'city')
                        <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->parent?->name ?? 'Provinsi' }})</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Ruko Boulevard Blok TB No. 12..."></textarea>
            </div>

            <div class="grid-2-col">
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" placeholder="021-123456">
                </div>
                <div class="form-group">
                    <label class="form-label">Whatsapp</label>
                    <input type="text" name="whatsapp" class="form-control" placeholder="0812345678">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Google Maps Link</label>
                <input type="url" name="gmaps_url" class="form-control" placeholder="https://maps.google.com/...">
            </div>

            <div class="form-group">
                <label class="form-label">Foto Outlet</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 16px;">
                <i class="fas fa-plus"></i> Simpan Cabang
            </button>
        </form>
    </div>

</div>
@endsection
