@extends('layouts.dashboard')
@section('title', 'Brand & Outlet')
@section('page-title', 'Brand & Outlet')
@section('breadcrumb', 'Manage SnN Brands & Outlets')

@section('content')
<div class="card-panel">
    {{-- Header --}}
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px; justify-content: space-between;">
        <div>
            <span style="font-weight: 500; font-size: 0.9rem; color: var(--text-secondary);">
                Kelola brand F&B dalam Selera Nikmat Nusantara (SNN) Group dan kelola cabang outlet masing-masing.
            </span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('dashboard.brands.regions') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-map-location-dot"></i> Kelola Wilayah
            </a>
            @if(!auth()->user()->brand_id)
            <a href="{{ route('dashboard.brands.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Brand Baru
            </a>
            @endif
        </div>
    </div>

    {{-- Brand Grid / Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Logo</th>
                    <th>Nama Brand</th>
                    <th>Jenis Kuliner (Cuisine)</th>
                    <th style="width: 120px; text-align: center;">Jumlah Outlet</th>
                    <th style="width: 100px; text-align: center;">Urutan</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 160px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td style="text-align: center;">
                        <div style="width: 50px; height: 50px; border-radius: 8px; border: 1px solid var(--border-light); overflow: hidden; background: #fff; padding: 4px; display: inline-flex; align-items: center; justify-content: center;">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.95rem;">
                            <a href="{{ route('dashboard.brands.edit', $brand->id) }}" style="color: var(--text);">
                                {{ $brand->name }}
                            </a>
                        </div>
                        @if($brand->tagline)
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">"{{ $brand->tagline }}"</div>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem;">
                        <span class="badge badge-accent">{{ $brand->cuisine_type ?? 'Indonesian' }}</span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('dashboard.brands.outlets', $brand->id) }}" class="btn btn-outline-sm" style="font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-location-dot" style="color: var(--accent);"></i> {{ $brand->outlets_count }} Outlet
                        </a>
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                        {{ $brand->sort_order }}
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('dashboard.brands.outlets', $brand->id) }}" class="btn btn-outline-sm" title="Kelola Outlet">
                                <i class="fas fa-store"></i>
                            </a>
                            <a href="{{ route('dashboard.brands.edit', $brand->id) }}" class="btn btn-outline-sm" title="Edit Brand">
                                <i class="fas fa-pen"></i>
                            </a>
                            @if(!auth()->user()->brand_id)
                            <form method="POST" action="{{ route('dashboard.brands.destroy', $brand->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brand ini beserta semua data outlet dan karirnya?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-store" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada brand terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
