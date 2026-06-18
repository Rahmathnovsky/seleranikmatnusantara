@extends('layouts.dashboard')
@section('title', 'Klaim Promo — ' . $promo->title)
@section('page-title', 'Klaim Promo')
@section('breadcrumb', $promo->title)

@section('content')
<div class="card-panel">
    {{-- Header Info --}}
    <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
        <div style="display: flex; gap: 20px; align-items: center;">
            <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" style="width: 120px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-light);">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">{{ $promo->title }}</h3>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                    Total Penukaran: <strong>{{ $claims->total() }}</strong> dari kuota <strong>{{ $promo->max_claims ?? 'Tak Terbatas' }}</strong>
                </div>
            </div>
        </div>
        <a href="{{ route('dashboard.promo.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Claims Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 200px;">User / Customer</th>
                    <th>Kode Voucher</th>
                    <th style="width: 160px; text-align: center;">Status</th>
                    <th style="width: 180px;">Waktu Klaim</th>
                    <th style="width: 180px;">Waktu Digunakan</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                <tr>
                    <td>
                        @if($claim->user)
                        <div style="font-weight: 700; font-size: 0.875rem;">{{ $claim->user->name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $claim->user->email }}</div>
                        @else
                        <span style="color: var(--text-muted); font-size: 0.85rem;">Anonymous / Guest</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <code style="font-size: 0.9rem; font-family: monospace; background: var(--bg); padding: 4px 10px; border-radius: 4px; border: 1px solid var(--border-light); font-weight: 700; color: var(--text-secondary);">
                                {{ $claim->claim_code }}
                            </code>
                            <button class="btn btn-outline-sm" onclick="navigator.clipboard.writeText('{{ $claim->claim_code }}'); alert('Kode disalin!');" title="Salin Kode" style="padding: 4px 6px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $claim->status === 'used' ? 'badge-success' : ($claim->status === 'expired' ? 'badge-danger' : 'badge-warning') }}">
                            {{ strtoupper($claim->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                        {{ $claim->claimed_at->format('d M Y H:i') }}
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                        @if($claim->used_at)
                            <div>{{ $claim->used_at->format('d M Y H:i') }}</div>
                            @if($claim->outlet)
                            <div style="font-size: 0.7rem; font-weight: 600; color: var(--primary);">di: {{ $claim->outlet->name }}</div>
                            @endif
                        @else
                            <span style="color: var(--text-muted); font-style: italic;">Belum digunakan</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($claim->status === 'claimed')
                        <form method="POST" action="{{ route('dashboard.promo.claims.mark-used', $claim->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menandai kupon ini sebagai SUDAH DIGUNAKAN?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check-double"></i> Redeem
                            </button>
                        </form>
                        @else
                        <span style="font-size: 0.8rem; color: var(--text-muted);">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-ticket-simple" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada klaim voucher untuk promo ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($claims->hasPages())
    <div style="padding: 20px 0;">
        {{ $claims->links() }}
    </div>
    @endif
</div>
@endsection
