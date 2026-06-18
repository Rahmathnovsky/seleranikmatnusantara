@extends('layouts.public')

@php
    $locale = session('locale', 'id');
    $heroBgType = $settings['hero_background_type'] ?? 'image';
    $heroBgUrl  = $settings['hero_background_url'] ?? null;
    $heroBgStyle = $heroBgUrl
        ? "background-image: url('" . asset('storage/' . $heroBgUrl) . "')"
        : "background: linear-gradient(135deg, #1a0f05 0%, #4b3217 40%, #634524 70%, #c5a059 100%)";
@endphp

@section('title', 'Selera Nikmat Nusantara — Authentic Indonesian F&B')
@section('meta_description', $locale === 'id'
    ? 'SNN Group – Menghadirkan cita rasa otentik Nusantara dengan pengalaman kuliner yang tak terlupakan.'
    : 'SNN Group – Authentic Indonesian culinary experience with unforgettable flavors.')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section class="hero" id="hero">
    @if($heroBgType === 'video' && $heroBgUrl)
        <video class="hero-bg" autoplay muted loop playsinline>
            <source src="{{ asset('storage/' . $heroBgUrl) }}" type="video/mp4">
        </video>
    @else
        <div class="hero-bg" style="{{ $heroBgStyle }}" id="heroBg"></div>
    @endif
    <div class="hero-overlay"></div>

    <div class="container" style="padding-top: var(--navbar-h);">
        <div class="hero-content">
            <div class="hero-eyebrow" data-aos="fade-up">
                <i class="fas fa-star" style="color: var(--accent);"></i>
                {{ $locale === 'id' ? '🇮🇩 Cita Rasa Autentik Nusantara' : '🇮🇩 Authentic Nusantara Flavors' }}
            </div>

            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
                {!! $locale === 'id'
                    ? ($settings['hero_title_id'] ?? '<span class="highlight">Selera Nikmat</span><br>Nusantara')
                    : ($settings['hero_title_en'] ?? '<span class="highlight">Exquisite Taste</span><br>of Nusantara') !!}
            </h1>

            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                {{ $locale === 'id'
                    ? ($settings['hero_subtitle_id'] ?? 'Dari Sabang sampai Merauke, kami hadir dengan koleksi brand F&B premium yang memanjakan lidah dan jiwa.')
                    : ($settings['hero_subtitle_en'] ?? 'From Sabang to Merauke, we present a collection of premium F&B brands that delight your palate and soul.') }}
            </p>

            <div class="hero-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ $settings['hero_cta_url'] ?? route('brands.index') }}" class="btn btn-accent btn-lg">
                    <i class="fas fa-utensils"></i>
                    {{ $locale === 'id' ? ($settings['hero_cta_text_id'] ?? 'Jelajahi Brand Kami') : ($settings['hero_cta_text_en'] ?? 'Explore Our Brands') }}
                </a>
                <a href="{{ route('promo.index') }}" class="btn btn-glass btn-lg">
                    <i class="fas fa-ticket-simple"></i>
                    {{ $locale === 'id' ? 'Lihat Promo' : 'See Promotions' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Floating Stats --}}
    <div class="hero-stats">
        <div class="stat-card" data-aos="fade-left" data-aos-delay="400">
            <div class="stat-num">{{ $brands->count() }}+</div>
            <div class="stat-label">{{ $locale === 'id' ? 'Brand' : 'Brands' }}</div>
        </div>
        <div class="stat-card" data-aos="fade-left" data-aos-delay="500">
            <div class="stat-num">{{ $brands->sum(fn($b) => $b->activeOutlets->count()) }}+</div>
            <div class="stat-label">{{ $locale === 'id' ? 'Outlet' : 'Outlets' }}</div>
        </div>
        <div class="stat-card" data-aos="fade-left" data-aos-delay="600">
            <div class="stat-num">{{ $promos->count() }}</div>
            <div class="stat-label">{{ $locale === 'id' ? 'Promo Aktif' : 'Active Promos' }}</div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="hero-scroll">
        <span>{{ $locale === 'id' ? 'Scroll' : 'Scroll' }}</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

{{-- ============================================================
     BRANDS SECTION
     ============================================================ --}}
<section class="section" id="brands">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-eyebrow">{{ $locale === 'id' ? 'Brand Kami' : 'Our Brands' }}</div>
            <h2 class="section-title">
                {{ $locale === 'id' ? 'Koleksi Brand F&B Premium' : 'Premium F&B Brand Collection' }}
            </h2>
            <p class="section-subtitle">
                {{ $locale === 'id'
                    ? 'Setiap brand memiliki karakter unik, menghadirkan pengalaman kuliner yang berbeda namun tetap dalam satu visi — kepuasan Anda.'
                    : 'Each brand has a unique character, delivering diverse culinary experiences united by one vision — your satisfaction.' }}
            </p>
            <div class="section-divider"><i class="fas fa-utensils"></i></div>
        </div>

        @if($brands->count() > 0)
        <div class="brands-custom-grid">
            @foreach($brands as $brand)
                <div class="brand-custom-card" data-aos="fade-up">
                    <div class="brand-image-frame">
                        <a href="{{ route('brands.show', $brand->slug) }}">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy">
                        </a>
                    </div>
                    <h3 class="brand-custom-title">{{ $brand->name }}</h3>
                </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-store" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px;"></i>
            <p style="color: var(--text-muted);">{{ $locale === 'id' ? 'Belum ada brand tersedia.' : 'No brands available yet.' }}</p>
        </div>
        @endif

        <div class="text-center" style="margin-top: 48px;" data-aos="fade-up">
            <a href="{{ route('brands.index') }}" class="btn btn-outline">
                {{ $locale === 'id' ? 'Lihat Semua Brand' : 'View All Brands' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     ABOUT SECTION
     ============================================================ --}}
<section class="section about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image-col" data-aos="fade-right">
                @php $aboutImg = $settings['about_image'] ?? null; @endphp
                @if($aboutImg)
                <div class="about-img-wrapper">
                    <img src="{{ asset('storage/' . $aboutImg) }}" alt="About SNN">
                </div>
                @else
                <div class="about-img-placeholder">
                    <div class="about-icon-circle"><i class="fas fa-utensils"></i></div>
                    <div class="about-dots"></div>
                </div>
                @endif
            </div>
            <div class="about-content" data-aos="fade-left">
                <div class="section-eyebrow" style="display: inline-block;">
                    {{ $locale === 'id' ? 'Tentang Kami' : 'About Us' }}
                </div>
                <h2 class="section-title" style="text-align: left;">
                    {!! $locale === 'id'
                        ? ($settings['about_title_id'] ?? 'Kami Adalah <span style="color:var(--accent)">SNN Group</span>')
                        : ($settings['about_title_en'] ?? 'We Are <span style="color:var(--accent)">SNN Group</span>') !!}
                </h2>
                <p class="lead" style="margin-bottom: 24px;">
                    {{ $locale === 'id'
                        ? ($settings['about_text_id'] ?? 'Selera Nikmat Nusantara (SNN) adalah grup F&B yang berkomitmen menghadirkan cita rasa autentik Indonesia dengan standar kuliner premium.')
                        : ($settings['about_text_en'] ?? 'Selera Nikmat Nusantara (SNN) is an F&B group committed to delivering authentic Indonesian flavors with premium culinary standards.') }}
                </p>
                <div class="about-values">
                    <div class="about-value-item">
                        <div class="value-icon"><i class="fas fa-leaf"></i></div>
                        <div>
                            <div class="value-title">{{ $locale === 'id' ? 'Bahan Segar' : 'Fresh Ingredients' }}</div>
                            <div class="value-desc">{{ $locale === 'id' ? 'Pilihan bahan berkualitas setiap hari' : 'Quality ingredient selection daily' }}</div>
                        </div>
                    </div>
                    <div class="about-value-item">
                        <div class="value-icon"><i class="fas fa-award"></i></div>
                        <div>
                            <div class="value-title">{{ $locale === 'id' ? 'Resep Autentik' : 'Authentic Recipes' }}</div>
                            <div class="value-desc">{{ $locale === 'id' ? 'Warisan kuliner Nusantara yang terjaga' : 'Preserved Nusantara culinary heritage' }}</div>
                        </div>
                    </div>
                    <div class="about-value-item">
                        <div class="value-icon"><i class="fas fa-heart"></i></div>
                        <div>
                            <div class="value-title">{{ $locale === 'id' ? 'Pelayanan Terbaik' : 'Best Service' }}</div>
                            <div class="value-desc">{{ $locale === 'id' ? 'Kepuasan pelanggan adalah prioritas kami' : 'Customer satisfaction is our priority' }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('brands.index') }}" class="btn btn-primary" style="margin-top: 32px;">
                    {{ $locale === 'id' ? 'Kenali Brand Kami' : 'Meet Our Brands' }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     PROMO SECTION (toggleable)
     ============================================================ --}}
@if(($settings['show_promo_section'] ?? '1') !== '0' && $promos->count() > 0)
<section class="section promo-section" id="promos">
    <div class="promo-bg-pattern"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section-header" data-aos="fade-up">
            <div class="section-eyebrow">{{ $locale === 'id' ? 'Promo Spesial' : 'Special Promotions' }}</div>
            <h2 class="section-title">
                {{ $locale === 'id' ? 'Penawaran Terbaik Untuk Anda' : 'Best Deals Just For You' }}
            </h2>
        </div>

        <div class="grid-3" id="promoGrid">
            @foreach($promos->take(3) as $i => $promo)
            <div class="promo-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="promo-card-image">
                    <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" loading="lazy">
                    @if($promo->discount_label)
                    <div class="promo-badge">{{ $promo->discount_label }}</div>
                    @endif
                </div>
                <div class="promo-card-body">
                    <h3>{{ $promo->title }}</h3>
                    <p>{{ Str::limit($promo->description, 80) }}</p>
                    <div class="promo-card-footer">
                        @if($promo->end_date)
                        <div class="promo-deadline">
                            <i class="fas fa-clock"></i>
                            {{ $locale === 'id' ? 'Hingga' : 'Until' }} {{ $promo->end_date->format('d M Y') }}
                        </div>
                        @endif
                        <a href="{{ route('promo.show', $promo->slug) }}" class="btn btn-accent btn-sm">
                            {{ $locale === 'id' ? 'Klaim' : 'Claim' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center" style="margin-top: 40px;" data-aos="fade-up">
            <a href="{{ route('promo.index') }}" class="btn btn-primary">
                {{ $locale === 'id' ? 'Semua Promo' : 'All Promotions' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     BLOG SECTION (toggleable)
     ============================================================ --}}
@if(($settings['show_blog_section'] ?? '1') !== '0' && $posts->count() > 0)
<section class="section" id="blog">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-eyebrow">{{ $locale === 'id' ? 'Blog & Artikel' : 'Blog & Articles' }}</div>
            <h2 class="section-title">
                {{ $locale === 'id' ? 'Tips & Cerita Kuliner' : 'Culinary Tips & Stories' }}
            </h2>
            <div class="section-divider"><i class="fas fa-pen-nib"></i></div>
        </div>

        <div class="grid-3">
            @foreach($posts as $i => $post)
            <div class="blog-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="blog-card-image">
                    <a href="{{ route('blog.show', $post->slug) }}">
                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-body">
                    <div class="blog-card-meta">
                        @if($post->category)
                        <span class="category-tag">{{ $post->category->name }}</span>
                        @endif
                        <span class="date">{{ $post->published_at?->format('d M Y') }}</span>
                    </div>
                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                    <p>{{ Str::limit($post->excerpt ?? strip_tags($post->body), 110) }}</p>
                    <div class="blog-card-footer">
                        <div class="blog-author">
                            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}">
                            <span class="blog-author-name">{{ $post->author->name }}</span>
                        </div>
                        <span class="read-time"><i class="fas fa-clock"></i> {{ $post->reading_time }} min</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center" style="margin-top: 40px;" data-aos="fade-up">
            <a href="{{ route('blog.index') }}" class="btn btn-outline">
                {{ $locale === 'id' ? 'Baca Semua Artikel' : 'Read All Articles' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     CAREER SECTION (toggleable)
     ============================================================ --}}
@if(($settings['show_career_section'] ?? '1') !== '0' && $jobs->count() > 0)
<section class="section career-section" id="career">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-eyebrow">{{ $locale === 'id' ? 'Karir Bersama SNN' : 'Career at SNN' }}</div>
            <h2 class="section-title">
                {{ $locale === 'id' ? 'Bergabung dengan Tim Kami' : 'Join Our Team' }}
            </h2>
            <p class="section-subtitle">
                {{ $locale === 'id'
                    ? 'Jadilah bagian dari keluarga SNN dan bangun karir impianmu bersama kami.'
                    : 'Be part of the SNN family and build your dream career with us.' }}
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($jobs as $i => $job)
            <div class="job-card" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
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
                        @if($job->brand)
                        <span><i class="fas fa-tag"></i> {{ $job->brand->name }}</span>
                        @endif
                        <span><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                        @if($job->salary_range)
                        <span><i class="fas fa-money-bill"></i> {{ $job->salary_range }}</span>
                        @endif
                    </div>
                    <div class="job-tags">
                        <span class="badge badge-{{ $job->type_color }}">{{ $job->type_label }}</span>
                        @if($job->deadline)
                        <span class="badge badge-muted">
                            <i class="fas fa-calendar"></i>
                            {{ $locale === 'id' ? 'Deadline' : 'Deadline' }}: {{ $job->deadline->format('d M Y') }}
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('career.show', $job->slug) }}" class="btn btn-outline" style="flex-shrink: 0;">
                    {{ $locale === 'id' ? 'Lamar' : 'Apply' }}
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center" style="margin-top: 40px;" data-aos="fade-up">
            <a href="{{ route('career.index') }}" class="btn btn-primary">
                {{ $locale === 'id' ? 'Lihat Semua Lowongan' : 'View All Positions' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
/* Brands Section Custom Style */
#brands {
    background-color: #5c3c1e;
    color: #ffffff;
    position: relative;
}
#brands .section-title {
    color: #ffffff;
}
#brands .section-subtitle {
    color: #ebdcd0;
}
#brands .section-eyebrow {
    color: var(--accent-light);
    background: rgba(197,160,89,0.15);
    border-color: rgba(197,160,89,0.3);
}
#brands .section-divider i {
    color: var(--accent-light);
}
#brands .section-divider::before, #brands .section-divider::after {
    background: linear-gradient(to right, transparent, var(--accent-light));
}
#brands .section-divider::after {
    background: linear-gradient(to left, transparent, var(--accent-light));
}
#brands .btn-outline {
    color: #ffffff;
    border-color: #ffffff;
}
#brands .btn-outline:hover {
    background: #ffffff;
    color: #5c3c1e;
    border-color: #ffffff;
}

/* Brands Showcase Grid */
.brands-custom-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 48px 32px;
    justify-content: center;
    margin-top: 40px;
}

.brand-custom-card {
    width: calc(33.333% - 22px);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.brand-image-frame {
    width: 100%;
    aspect-ratio: 1.4;
    border-radius: 60px;
    border: 3px solid #ffffff;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    background: #ffffff;
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    transition: transform var(--transition), box-shadow var(--transition);
}

.brand-image-frame:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}

.brand-image-frame img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.brand-image-frame:hover img {
    transform: scale(1.05);
}

.brand-custom-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 12px;
    font-family: var(--font-sans);
}

.brand-region-slider {
    display: flex;
    align-items: center;
    gap: 8px;
}

.slider-arrow {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all var(--transition);
}

.slider-arrow:hover:not(:disabled) {
    background: #ffffff;
    color: #5c3c1e;
    border-color: #ffffff;
}

.slider-arrow:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.slider-pill {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 20px;
    border-radius: var(--radius-full);
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 600;
    min-width: 120px;
    text-align: center;
}

@media (max-width: 992px) {
    .brand-custom-card {
        width: calc(50% - 16px);
    }
}
@media (max-width: 600px) {
    .brand-custom-card {
        width: 100%;
    }
    .brand-image-frame {
        border-radius: 40px;
    }
}

/* About Section */
.about-section { background: var(--surface); }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.about-img-wrapper { border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl); }
.about-img-wrapper img { width: 100%; height: 500px; object-fit: cover; }
.about-img-placeholder {
    width: 100%;
    height: 500px;
    border-radius: var(--radius-xl);
    background: linear-gradient(135deg, var(--primary-50), var(--accent-50));
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border);
}
.about-icon-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2.5rem;
}
.about-values { display: flex; flex-direction: column; gap: 20px; }
.about-value-item { display: flex; align-items: flex-start; gap: 16px; }
.value-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius);
    background: var(--accent-50);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-dark);
    font-size: 1rem;
    flex-shrink: 0;
    border: 1px solid rgba(197,160,89,0.2);
}
.value-title { font-weight: 700; font-size: 0.9rem; margin-bottom: 4px; }
.value-desc { font-size: 0.825rem; color: var(--text-muted); }

/* Promo Section */
.promo-section { background: var(--bg-secondary); position: relative; overflow: hidden; }
.promo-bg-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(197,160,89,0.1) 0%, transparent 60%),
                      radial-gradient(circle at 80% 50%, rgba(99,69,36,0.08) 0%, transparent 60%);
}

/* Career Section */
.career-section { background: var(--surface); }

@media (max-width: 768px) {
    .about-grid { grid-template-columns: 1fr; gap: 40px; }
    .about-img-wrapper { height: 300px; }
    .about-img-wrapper img { height: 300px; }
}
</style>
@endpush

@push('scripts')
<script>
// Parallax on hero
window.addEventListener('scroll', () => {
    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        heroBg.style.transform = `translateY(${window.scrollY * 0.3}px)`;
    }
});

// Simple AOS (Animate on Scroll)
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const el = entry.target;
            const delay = el.dataset.aosDelay || 0;
            setTimeout(() => el.classList.add('aos-animate'), parseInt(delay));
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
</script>
@endpush
