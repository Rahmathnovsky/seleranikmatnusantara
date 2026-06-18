@extends('layouts.public')
@section('title', $locale === 'id' ? 'Voucher Saya' : 'My Vouchers')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <h1>{{ $locale === 'id' ? '🎫 Voucher Saya' : '🎫 My Vouchers' }}</h1>
        <p>{{ $locale === 'id' ? 'Koleksi promo yang sudah Anda klaim' : 'Your claimed promotions collection' }}</p>
    </div>
</div>
<section class="section">
    <div class="container-narrow">
        @forelse($claims as $claim)
        <div class="card" style="padding: 24px; margin-bottom: 20px; display: flex; gap: 24px; align-items: flex-start;" data-aos="fade-up">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span class="badge badge-{{ $claim->status === 'used' ? 'success' : ($claim->status === 'expired' ? 'danger' : 'accent') }}">
                        {{ ucfirst($claim->status) }}
                    </span>
                    @if($claim->promo?->end_date && !$claim->promo->end_date->isPast())
                    <span style="font-size: 0.72rem; color: var(--text-muted);">Expires {{ $claim->promo->end_date->format('d M Y') }}</span>
                    @endif
                </div>
                <h3 style="font-weight: 700; margin-bottom: 4px;">{{ $claim->promo?->title }}</h3>
                <p style="font-size: 0.875rem; color: var(--text-muted);">{{ $locale === 'id' ? 'Diklaim' : 'Claimed' }}: {{ $claim->claimed_at?->format('d M Y, H:i') }}</p>
            </div>
            <div style="text-align: center; flex-shrink: 0;">
                <div class="voucher-code" style="font-size: 1rem;">{{ $claim->claim_code }}</div>
                @if($claim->status === 'claimed')
                <a href="{{ route('promo.show', $claim->promo?->slug) }}" class="btn btn-primary btn-sm" style="margin-top: 10px; width: 100%; justify-content: center;">
                    {{ $locale === 'id' ? 'Lihat QR' : 'View QR' }}
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center" style="padding: 80px 0;">
            <i class="fas fa-ticket" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
            <h3 style="color: var(--text-muted);">{{ $locale === 'id' ? 'Belum ada voucher' : 'No vouchers yet' }}</h3>
            <a href="{{ route('promo.index') }}" class="btn btn-primary" style="margin-top: 20px;">{{ $locale === 'id' ? 'Lihat Promo' : 'Browse Promos' }}</a>
        </div>
        @endforelse
        {{ $claims->links() }}
    </div>
</section>
@endsection
