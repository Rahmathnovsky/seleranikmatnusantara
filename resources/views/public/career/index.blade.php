@extends('layouts.public')
@section('title', $locale === 'id' ? 'Karir Bersama SNN' : 'Career at SNN')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <h1>{{ $locale === 'id' ? '💼 Karir Bersama SNN' : '💼 Career at SNN' }}</h1>
        <p>{{ $locale === 'id' ? 'Bergabung bersama tim profesional dan bangun karir impianmu' : 'Join our professional team and build your dream career' }}</p>
    </div>
</div>
<section class="section">
    <div class="container">
        {{-- Filters --}}
        <form method="GET" action="{{ route('career.index') }}" style="background: var(--surface); padding: 20px 24px; border-radius: var(--radius-xl); box-shadow: var(--shadow); margin-bottom: 36px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ $locale === 'id' ? 'Cari posisi...' : 'Search position...' }}" style="padding-left: 14px;">
            </div>
            <div>
                <select name="type" class="form-control">
                    <option value="">{{ $locale === 'id' ? 'Semua Tipe' : 'All Types' }}</option>
                    <option value="fulltime" {{ request('type') === 'fulltime' ? 'selected' : '' }}>Full Time</option>
                    <option value="parttime" {{ request('type') === 'parttime' ? 'selected' : '' }}>Part Time</option>
                    <option value="internship" {{ request('type') === 'internship' ? 'selected' : '' }}>Internship</option>
                    <option value="contract" {{ request('type') === 'contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </div>
            <div>
                <select name="category" class="form-control">
                    <option value="">{{ $locale === 'id' ? 'Semua Bidang' : 'All Fields' }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="brand" class="form-control">
                    <option value="">{{ $locale === 'id' ? 'Semua Brand' : 'All Brands' }}</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> {{ $locale === 'id' ? 'Filter' : 'Filter' }}
            </button>
        </form>

        @forelse($jobs as $job)
        <div class="job-card" data-aos="fade-up">
            <div class="job-brand-logo">
                @if($job->brand)
                <img src="{{ $job->brand->logo_url }}" alt="{{ $job->brand->name }}">
                @else
                <img src="{{ asset('images/logo-dark.png') }}" alt="Selera Nikmat Nusantara">
                @endif
            </div>
            <div class="job-info">
                <div class="job-title">
                    <a href="{{ route('career.show', $job->slug) }}">{{ $job->title }}</a>
                </div>
                <div class="job-meta">
                    @if($job->brand)<span><i class="fas fa-tag"></i> {{ $job->brand->name }}</span>@endif
                    <span><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                    @if($job->salary_range)<span><i class="fas fa-money-bill"></i> {{ $job->salary_range }}</span>@endif
                    <span><i class="fas fa-layer-group"></i> {{ $job->category?->name ?? ($locale === 'id' ? 'Umum' : 'General') }}</span>
                </div>
                <div class="job-tags">
                    <span class="badge badge-{{ $job->type_color }}">{{ $job->type_label }}</span>
                    @if($job->deadline)
                    <span class="badge badge-muted"><i class="fas fa-calendar"></i> Deadline: {{ $job->deadline->format('d M Y') }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('career.show', $job->slug) }}" class="btn btn-primary" style="flex-shrink: 0;">
                {{ $locale === 'id' ? 'Lamar Sekarang' : 'Apply Now' }}
            </a>
        </div>
        @empty
        <div class="text-center" style="padding: 80px 0;">
            <i class="fas fa-briefcase" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
            <h3 style="color: var(--text-muted);">{{ $locale === 'id' ? 'Belum ada lowongan tersedia' : 'No positions available' }}</h3>
            <p style="color: var(--text-muted); margin-top: 8px;">{{ $locale === 'id' ? 'Pantau terus halaman ini!' : 'Check back soon!' }}</p>
        </div>
        @endforelse

        {{ $jobs->withQueryString()->links() }}
    </div>
</section>
@endsection
