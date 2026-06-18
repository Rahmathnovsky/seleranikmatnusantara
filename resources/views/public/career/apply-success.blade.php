@extends('layouts.public')
@section('title', $locale === 'id' ? 'Lamaran Terkirim!' : 'Application Submitted!')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<section style="min-height: 80vh; display: flex; align-items: center; padding: 120px 0 80px;">
    <div class="container text-center">
        <div style="max-width: 560px; margin: 0 auto;">
            <div style="width: 100px; height: 100px; background: var(--success-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 32px; font-size: 3rem;">
                ✅
            </div>
            <h1 style="font-family: var(--font-display); font-size: 2.5rem; font-weight: 700; margin-bottom: 16px; color: var(--text);">
                {{ $locale === 'id' ? 'Lamaran Terkirim!' : 'Application Submitted!' }}
            </h1>
            <p class="lead" style="margin-bottom: 8px;">
                {{ $locale === 'id'
                    ? "Terima kasih telah melamar posisi <strong>{$job->title}</strong> di SNN Group."
                    : "Thank you for applying for the <strong>{$job->title}</strong> position at SNN Group." }}
            </p>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 40px;">
                {{ $locale === 'id'
                    ? 'Tim HR kami akan meninjau lamaran Anda dan menghubungi dalam 3-5 hari kerja jika Anda lolos seleksi awal.'
                    : 'Our HR team will review your application and contact you within 3-5 business days if you pass the initial screening.' }}
            </p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('career.index') }}" class="btn btn-primary">
                    <i class="fas fa-briefcase"></i>
                    {{ $locale === 'id' ? 'Lihat Lowongan Lain' : 'See More Jobs' }}
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    {{ $locale === 'id' ? 'Kembali ke Beranda' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
