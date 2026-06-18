@extends('layouts.public')
@section('title', $brand->name . ' — Selera Nikmat Nusantara')
@section('meta_description', Str::limit($brand->description, 160))
@section('meta_keywords', $brand->name . ', ' . $brand->cuisine_type . ', kuliner, outlet, selera nikmat nusantara')
@section('meta')
    <meta property="og:title" content="{{ $brand->name }} — Selera Nikmat Nusantara">
    <meta property="og:description" content="{{ Str::limit($brand->description, 160) }}">
    <meta property="og:image" content="{{ $brand->cover_image_url }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@php $locale = session('locale', 'id'); @endphp

@section('content')
{{-- Brand Hero --}}
<div style="position: relative; height: 50vh; min-height: 400px; overflow: hidden;">
    <img src="{{ $brand->cover_image_url }}" alt="{{ $brand->name }}" style="width: 100%; height: 100%; object-fit: cover;">
    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(18,10,2,0.9) 0%, rgba(18,10,2,0.4) 60%, transparent 100%);"></div>
    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 40px;" class="container">
        <div style="display: flex; align-items: flex-end; gap: 24px;">
            <div style="width: 90px; height: 90px; border-radius: var(--radius-lg); background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.3); overflow: hidden; flex-shrink: 0;">
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="width: 100%; height: 100%; object-fit: contain; padding: 8px;">
            </div>
            <div>
                <h1 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 700; margin-bottom: 6px; font-family: var(--font-display);">{{ $brand->name }}</h1>
                @if($brand->tagline)<p style="color: var(--accent-light); font-size: 1rem;">{{ $brand->tagline }}</p>@endif
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 60px; align-items: start;">

            {{-- Outlets by Region --}}
            <div>
                <h2 style="font-weight: 700; font-size: 1.3rem; margin-bottom: 8px;">
                    {{ $locale === 'id' ? 'Lokasi Outlet' : 'Outlet Locations' }}
                </h2>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 32px;">
                    {{ $regions->sum(fn($r) => $r->outlets->count()) }} {{ $locale === 'id' ? 'outlet tersedia' : 'outlets available' }}
                </p>

                @forelse($regions as $region)
                <div style="margin-bottom: 32px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid var(--border);">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-pin" style="color: #fff; font-size: 0.75rem;"></i>
                        </div>
                        <h3 style="font-weight: 700; font-size: 1.05rem;">{{ $region->name }}</h3>
                        <span class="badge badge-muted">{{ $region->outlets->count() }} outlet</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
                        @foreach($region->outlets as $outlet)
                        <div class="card" style="padding: 20px;">
                            @if($outlet->photo)
                            <div style="aspect-ratio: 16/9; overflow: hidden; border-radius: var(--radius); margin-bottom: 14px;">
                                <img src="{{ $outlet->photo_url }}" alt="{{ $outlet->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            @endif
                            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 8px;">{{ $outlet->name }}</h4>
                            @if($outlet->address)
                            <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; gap: 6px; margin-bottom: 6px;">
                                <i class="fas fa-map-marker-alt" style="margin-top: 2px; color: var(--accent); flex-shrink: 0;"></i>
                                <span>{{ $outlet->address }}</span>
                            </div>
                            @endif
                            @if($outlet->phone)
                            <a href="tel:{{ $outlet->phone }}" style="font-size: 0.8rem; color: var(--primary); display: flex; gap: 6px; align-items: center; margin-bottom: 8px; text-decoration: none;">
                                <i class="fas fa-phone"></i> {{ $outlet->phone }}
                            </a>
                            @endif
                            @if($outlet->today_hours)
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                <i class="fas fa-clock"></i> {{ $outlet->today_hours }}
                            </div>
                            @endif
                            @if($outlet->gmaps_url)
                            <a href="{{ $outlet->gmaps_url }}" target="_blank" class="btn btn-outline btn-sm" style="margin-top: 12px; width: 100%; justify-content: center;">
                                <i class="fab fa-google"></i> Google Maps
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center" style="padding: 60px 0; color: var(--text-muted);">
                    <i class="fas fa-map-marker-slash" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                    {{ $locale === 'id' ? 'Belum ada outlet untuk brand ini.' : 'No outlets available for this brand.' }}
                </div>
                @endforelse
            </div>

            {{-- Brand Info Sidebar --}}
            <aside style="position: sticky; top: calc(var(--navbar-h) + 20px);">
                <div class="card" style="padding: 28px; margin-bottom: 24px;">
                    <h3 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Tentang Brand' : 'About Brand' }}</h3>
                    <p style="font-size: 0.9rem; line-height: 1.7; color: var(--text-secondary);">{{ $brand->description }}</p>

                    @if($brand->cuisine_type)
                    <div style="margin-top: 16px; display: flex; gap: 8px; align-items: center;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">{{ $locale === 'id' ? 'Jenis' : 'Type' }}:</span>
                        <span class="badge badge-accent">{{ $brand->cuisine_type }}</span>
                    </div>
                    @endif

                    <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
                        @if($brand->website_url)
                        <a href="{{ $brand->website_url }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-globe"></i> Website</a>
                        @endif
                        @if($brand->instagram_url)
                        <a href="{{ $brand->instagram_url }}" target="_blank" class="btn btn-outline btn-sm"><i class="fab fa-instagram"></i> Instagram</a>
                        @endif
                    </div>
                </div>

                {{-- Other Brands --}}
                @if($otherBrands->count() > 0)
                <div class="card" style="padding: 24px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Brand Lainnya' : 'Other Brands' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($otherBrands as $ob)
                        <a href="{{ route('brands.show', $ob->slug) }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit; padding: 8px; border-radius: var(--radius); transition: background var(--transition);" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                            <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0; border: 1px solid var(--border);">
                                <img src="{{ $ob->logo_url }}" alt="{{ $ob->name }}" style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $ob->name }}</div>
                                @if($ob->cuisine_type)
                                <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $ob->cuisine_type }}</div>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
@media (max-width: 900px) {
    section .container > div { grid-template-columns: 1fr !important; }
    aside { position: static !important; }
}
</style>
@endpush
