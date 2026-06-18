@extends('layouts.public')
@section('title', $locale === 'id' ? 'Promo & Diskon' : 'Promotions & Discounts')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <h1>{{ $locale === 'id' ? '🎁 Promo & Diskon' : '🎁 Promotions & Discounts' }}</h1>
        <p>{{ $locale === 'id' ? 'Klaim promo eksklusif dan nikmati penghematan terbaik' : 'Claim exclusive promos and enjoy the best savings' }}</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($promos->count() > 0)
        <div class="grid-3">
            @foreach($promos as $i => $promo)
            <div class="promo-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div class="promo-card-image">
                    <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" loading="lazy">
                    @if($promo->discount_label)
                    <div class="promo-badge">{{ $promo->discount_label }}</div>
                    @endif
                    @if($promo->end_date && $promo->end_date->diffInDays() <= 3)
                    <div style="position: absolute; top: 16px; right: 16px; background: var(--danger); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: var(--radius-full);">
                        {{ $locale === 'id' ? 'Hampir Habis!' : 'Ending Soon!' }}
                    </div>
                    @endif
                </div>
                <div class="promo-card-body">
                    <h3>{{ $promo->title }}</h3>
                    <p>{{ Str::limit($promo->description, 100) }}</p>
                    @if($promo->max_claims)
                    <div style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 6px;">
                            <span>{{ $locale === 'id' ? 'Klaim' : 'Claims' }}</span>
                            <span>{{ $promo->claimsCount() }}/{{ $promo->max_claims }}</span>
                        </div>
                        <div style="height: 4px; background: var(--border); border-radius: 2px; overflow: hidden;">
                            <div style="height: 100%; background: var(--accent); border-radius: 2px; width: {{ min(100, ($promo->claimsCount() / $promo->max_claims) * 100) }}%;"></div>
                        </div>
                    </div>
                    @endif
                    <div class="promo-card-footer">
                        @if($promo->end_date)
                        <div class="promo-deadline">
                            <i class="fas fa-clock"></i>
                            {{ $locale === 'id' ? 'Hingga' : 'Until' }} {{ $promo->end_date->format('d M Y') }}
                        </div>
                        @endif
                        <a href="{{ route('promo.show', $promo->slug) }}" class="btn btn-accent btn-sm">
                            {{ $locale === 'id' ? 'Lihat & Klaim' : 'View & Claim' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $promos->links() }}
        @else
        <div class="text-center" style="padding: 80px 0;">
            <i class="fas fa-ticket" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
            <h3 style="color: var(--text-muted); font-weight: 600;">{{ $locale === 'id' ? 'Belum ada promo aktif' : 'No active promotions' }}</h3>
            <p style="color: var(--text-muted); margin-top: 8px;">{{ $locale === 'id' ? 'Pantau terus untuk promo menarik selanjutnya!' : 'Stay tuned for upcoming promotions!' }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
