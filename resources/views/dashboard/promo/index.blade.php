@extends('layouts.dashboard')
@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')
@section('breadcrumb', 'Manage Special Offers & Claims')

@section('content')
<div class="card-panel">
    {{-- Header & Filters --}}
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px; justify-content: space-between;">
        <div>
            <span style="font-weight: 500; font-size: 0.9rem; color: var(--text-secondary);">
                Kelola promosi, kupon diskon, dan lihat data penukaran voucher digital.
            </span>
        </div>
        <a href="{{ route('dashboard.promo.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Promo Baru
        </a>
    </div>

    {{-- Table Grid --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 100px;">Banner</th>
                    <th>Judul Promo</th>
                    <th>BrandScope</th>
                    <th>Diskon</th>
                    <th style="width: 130px; text-align: center;">Claims (Klaim)</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th>Periode</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                <tr>
                    <td>
                        <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.9rem;">
                            <a href="{{ route('dashboard.promo.edit', $promo->id) }}" style="color: var(--text);">
                                {{ $promo->title }}
                            </a>
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Tipe: {{ strtoupper(str_replace('_', ' ', $promo->promo_type)) }}</div>
                    </td>
                    <td>
                        @if($promo->brand)
                        <span class="badge badge-primary" style="font-size: 0.7rem;">{{ $promo->brand->name }}</span>
                        @else
                        <span class="badge badge-secondary" style="font-size: 0.7rem;">Corporate</span>
                        @endif
                    </td>
                    <td>
                        @if($promo->discount_label)
                        <div style="font-weight: 600; font-size: 0.85rem; color: var(--accent-dark);">{{ $promo->discount_label }}</div>
                        @endif
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            @if($promo->promo_type === 'percentage')
                                {{ $promo->discount_value }}% Off
                            @elseif($promo->promo_type === 'fixed')
                                Rp {{ number_format($promo->discount_value) }} Off
                            @else
                                Free Item / Buy X Get Y
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('dashboard.promo.claims', $promo->id) }}" style="font-weight: 700; color: var(--primary); text-decoration: underline;">
                            {{ $promo->claims_count }}
                        </a>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">
                            / {{ $promo->max_claims ?? '∞' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $promo->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($promo->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.75rem; color: var(--text-muted);">
                        <div>Mulai: {{ $promo->start_date ? $promo->start_date->format('d M Y') : '—' }}</div>
                        <div>Selesai: {{ $promo->end_date ? $promo->end_date->format('d M Y') : '—' }}</div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('dashboard.promo.claims', $promo->id) }}" class="btn btn-outline-sm" title="Lihat Klaim">
                                <i class="fas fa-ticket"></i>
                            </a>
                            <a href="{{ route('dashboard.promo.edit', $promo->id) }}" class="btn btn-outline-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('dashboard.promo.destroy', $promo->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-ticket" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada promo
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($promos->hasPages())
    <div style="padding: 20px 0;">
        {{ $promos->links() }}
    </div>
    @endif
</div>
@endsection
