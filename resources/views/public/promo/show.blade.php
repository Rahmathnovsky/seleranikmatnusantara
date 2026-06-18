@extends('layouts.public')
@section('title', $promo->title . ' — Promo Selera Nikmat Nusantara')
@section('meta_description', Str::limit(strip_tags($promo->description), 160))
@section('meta_keywords', $promo->title . ', promo, diskon, voucher, ' . ($promo->brand?->name ?? 'corporate') . ', kuliner, selera nikmat nusantara')
@section('meta')
    <meta property="og:title" content="{{ $promo->title }} — Promo Selera Nikmat Nusantara">
    <meta property="og:description" content="{{ Str::limit(strip_tags($promo->description), 160) }}">
    <meta property="og:image" content="{{ $promo->image_url }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ $locale === 'id' ? 'Beranda' : 'Home' }}</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('promo.index') }}">Promo</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ Str::limit($promo->title, 30) }}</span>
        </div>
        <h1 style="margin-top: 16px;">{{ $promo->title }}</h1>
        @if($promo->discount_label)
        <div style="margin-top: 12px;"><span class="section-eyebrow">{{ $promo->discount_label }}</span></div>
        @endif
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 60px; align-items: start;">

            {{-- Promo Details --}}
            <div>
                @if($promo->banner_image ?? $promo->image)
                <div style="border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 32px; box-shadow: var(--shadow-xl);">
                    <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                </div>
                @endif

                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Detail Promo' : 'Promo Details' }}</h2>
                    <div style="font-size: 0.95rem; line-height: 1.75; color: var(--text-secondary);">{!! nl2br(e($promo->description)) !!}</div>
                </div>

                @if($promo->terms)
                <div class="card" style="padding: 28px;">
                    <h3 style="font-weight: 700; margin-bottom: 16px; font-size: 1rem;">
                        <i class="fas fa-file-contract" style="color: var(--accent);"></i>
                        {{ $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions' }}
                    </h3>
                    <div style="font-size: 0.875rem; line-height: 1.7; color: var(--text-secondary);">{!! nl2br(e($promo->terms)) !!}</div>
                </div>
                @endif
            </div>

            {{-- Claim Sidebar --}}
            <div style="position: sticky; top: calc(var(--navbar-h) + 20px);">
                @if($userClaim)
                {{-- Already Claimed --}}
                <div class="qr-voucher">
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--accent); margin-bottom: 16px;">
                        ✅ {{ $locale === 'id' ? 'Voucher Anda' : 'Your Voucher' }}
                    </div>
                    <h3 style="font-weight: 700; margin-bottom: 8px;">{{ $promo->title }}</h3>

                    {{-- QR Code --}}
                    <div class="qr-code-wrapper">
                        <div id="qrcode"></div>
                    </div>

                    <div class="voucher-code">{{ $userClaim->claim_code }}</div>

                    <div style="margin-top: 16px; font-size: 0.8rem; color: var(--text-muted);">
                        {{ $locale === 'id' ? 'Tunjukkan kode ini kepada kasir' : 'Show this code to the cashier' }}
                    </div>

                    <span class="badge badge-{{ $userClaim->status === 'used' ? 'success' : ($userClaim->status === 'expired' ? 'danger' : 'accent') }}" style="margin-top: 12px;">
                        {{ ucfirst($userClaim->status) }}
                    </span>
                </div>

                <a href="{{ route('promo.my-vouchers') }}" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 12px;">
                    <i class="fas fa-ticket"></i>
                    {{ $locale === 'id' ? 'Semua Voucher Saya' : 'All My Vouchers' }}
                </a>

                @elseif($promo->isActive())
                {{-- Claim Form --}}
                <div class="card" style="padding: 32px; text-align: center;">
                    <div style="width: 72px; height: 72px; background: var(--accent-50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem;">🎁</div>
                    <h3 style="font-weight: 700; margin-bottom: 8px;">{{ $locale === 'id' ? 'Klaim Promo Ini!' : 'Claim This Promo!' }}</h3>

                    @if($promo->max_claims)
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px;">
                        {{ $promo->claimsCount() }}/{{ $promo->max_claims }} {{ $locale === 'id' ? 'sudah diklaim' : 'claimed' }}
                    </div>
                    <div style="height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 20px;">
                        <div style="height: 100%; background: linear-gradient(to right, var(--accent), var(--primary)); border-radius: 3px; width: {{ min(100, ($promo->claimsCount() / $promo->max_claims) * 100) }}%;"></div>
                    </div>
                    @endif

                    @if($promo->end_date)
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 20px;">
                        <i class="fas fa-clock"></i> {{ $locale === 'id' ? 'Berakhir' : 'Expires' }}: <strong>{{ $promo->end_date->format('d M Y') }}</strong>
                    </div>
                    @endif

                    @auth
                    <form method="POST" action="{{ route('promo.claim', $promo->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-accent btn-lg" style="width: 100%; justify-content: center;">
                            <i class="fas fa-hand-holding-heart"></i>
                            {{ $locale === 'id' ? 'Klaim Sekarang' : 'Claim Now' }}
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                        <i class="fas fa-lock"></i>
                        {{ $locale === 'id' ? 'Login untuk Klaim' : 'Login to Claim' }}
                    </a>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 10px;">{{ $locale === 'id' ? 'Perlu akun untuk klaim promo' : 'Account required to claim promo' }}</p>
                    @endauth
                </div>
                @else
                <div class="card" style="padding: 32px; text-align: center;">
                    <i class="fas fa-ban" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px;"></i>
                    <h3 style="color: var(--text-muted);">{{ $locale === 'id' ? 'Promo Tidak Tersedia' : 'Promo Unavailable' }}</h3>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 8px;">{{ $locale === 'id' ? 'Promo ini sudah berakhir atau penuh.' : 'This promo has ended or is fully claimed.' }}</p>
                </div>
                @endif

                {{-- Manual Code Input --}}
                <div class="card" style="padding: 20px; margin-top: 16px;">
                    <h4 style="font-weight: 700; font-size: 0.875rem; margin-bottom: 12px;">
                        <i class="fas fa-keyboard" style="color: var(--accent);"></i>
                        {{ $locale === 'id' ? 'Punya Kode Promo?' : 'Have a Promo Code?' }}
                    </h4>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="promoCodeInput" class="form-control" placeholder="XXXX-XXXX-XXXX" style="font-family: monospace; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.05em;">
                        <button onclick="verifyCode()" class="btn btn-primary btn-sm" style="flex-shrink: 0;">
                            {{ $locale === 'id' ? 'Cek' : 'Check' }}
                        </button>
                    </div>
                    <div id="codeResult" style="margin-top: 10px; font-size: 0.8rem;"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
@if($userClaim)
new QRCode(document.getElementById("qrcode"), {
    text: "{{ $userClaim->claim_code }}",
    width: 180,
    height: 180,
    colorDark: "#634524",
    colorLight: "#fffcf8",
    correctLevel: QRCode.CorrectLevel.H
});
@endif

async function verifyCode() {
    const code = document.getElementById('promoCodeInput').value.trim().toUpperCase();
    if (!code) return;
    const result = document.getElementById('codeResult');
    result.innerHTML = '<span style="color: var(--text-muted);">Mengecek...</span>';
    const res = await fetch("{{ route('promo.verify-code') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ code })
    });
    const data = await res.json();
    if (data.valid) {
        result.innerHTML = `<div style="color: var(--success); font-weight: 600;">✅ ${data.promo?.title ?? 'Valid'}</div>`;
    } else {
        result.innerHTML = `<div style="color: var(--danger);">❌ ${data.message}</div>`;
    }
}
document.getElementById('promoCodeInput')?.addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endpush
