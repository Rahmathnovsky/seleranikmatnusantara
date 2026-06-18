@extends('layouts.public')
@section('title', $locale === 'id' ? 'Brand Kami' : 'Our Brands')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="container page-hero-content">
        <h1>{{ $locale === 'id' ? 'Brand Kami' : 'Our Brands' }}</h1>
        <p>{{ $locale === 'id' ? 'Koleksi brand F&B premium dari SNN Group' : 'Premium F&B brand collection from SNN Group' }}</p>
    </div>
</div>
<section class="section">
    <div class="container">
        @if($brands->count() > 0)
        <div class="grid-3">
            @foreach($brands as $i => $brand)
            <a href="{{ route('brands.show', $brand->slug) }}" class="brand-list-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div style="aspect-ratio: 16/10; overflow: hidden; position: relative;">
                    <img src="{{ $brand->cover_image_url }}" alt="{{ $brand->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(18,10,2,0.8) 0%, transparent 60%);"></div>
                    <div style="position: absolute; bottom: 16px; left: 16px; right: 16px;">
                        <h3 style="color: #fff; font-weight: 700; font-size: 1.1rem;">{{ $brand->name }}</h3>
                        @if($brand->cuisine_type)
                        <span style="font-size: 0.75rem; color: var(--accent-light);">{{ $brand->cuisine_type }}</span>
                        @endif
                    </div>
                </div>
                <div style="padding: 20px;">
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 12px;">{{ Str::limit($brand->description, 100) }}</p>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">
                            <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
                            {{ $brand->outlets_count }} {{ $locale === 'id' ? 'Outlet' : 'Outlets' }}
                        </span>
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--primary);">
                            {{ $locale === 'id' ? 'Lihat Detail' : 'View Detail' }} →
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center" style="padding: 80px 0;">
            <p style="color: var(--text-muted);">{{ $locale === 'id' ? 'Belum ada brand.' : 'No brands available.' }}</p>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.brand-list-card {
    background: var(--surface);
    border-radius: var(--radius-xl);
    overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    transition: all var(--transition);
    text-decoration: none;
    color: inherit;
    display: block;
}
.brand-list-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-xl); }
.brand-list-card:hover img { transform: scale(1.05); }
</style>
@endpush
