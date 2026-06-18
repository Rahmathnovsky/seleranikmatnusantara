@extends('layouts.public')
@section('title', $locale === 'id' ? 'Blog & Artikel' : 'Blog & Articles')
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero">
    <div class="page-hero-content container">
        <h1>{{ $locale === 'id' ? 'Blog & Artikel' : 'Blog & Articles' }}</h1>
        <p>{{ $locale === 'id' ? 'Tips kuliner, resep, dan berita terbaru dari SNN Group' : 'Culinary tips, recipes, and latest news from SNN Group' }}</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 280px; gap: 40px;">

            {{-- Posts Grid --}}
            <div>
                {{-- Search bar --}}
                <form method="GET" action="{{ route('blog.index') }}" style="margin-bottom: 32px;">
                    <div style="display: flex; gap: 8px;">
                        <div style="position: relative; flex: 1;">
                            <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $locale === 'id' ? 'Cari artikel...' : 'Search articles...' }}"
                                class="form-control" style="padding-left: 42px;">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ $locale === 'id' ? 'Cari' : 'Search' }}
                        </button>
                    </div>
                </form>

                @if($posts->count() > 0)
                <div class="grid-3">
                    @foreach($posts as $post)
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-card-image">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" loading="lazy">
                            </a>
                        </div>
                        <div class="blog-card-body">
                            <div class="blog-card-meta">
                                @if($post->category)
                                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="category-tag">{{ $post->category->name }}</a>
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

                {{-- Pagination --}}
                <div class="pagination">
                    {{ $posts->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
                @else
                <div class="text-center" style="padding: 80px 0;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
                    <p style="color: var(--text-muted);">{{ $locale === 'id' ? 'Belum ada artikel.' : 'No articles found.' }}</p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside>
                {{-- Categories --}}
                <div class="card" style="padding: 24px; margin-bottom: 24px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Kategori' : 'Categories' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('blog.index') }}" class="category-filter-link {{ !request('category') ? 'active' : '' }}">
                            <span>{{ $locale === 'id' ? 'Semua Artikel' : 'All Articles' }}</span>
                            <span class="count">{{ $posts->total() }}</span>
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="category-filter-link {{ request('category') === $cat->slug ? 'active' : '' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="count">{{ $cat->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Popular Posts --}}
                @if($featured->count() > 0)
                <div class="card" style="padding: 24px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Artikel Terpopuler' : 'Popular Articles' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($featured as $i => $fp)
                        <div style="display: flex; gap: 12px; align-items: flex-start;">
                            <div style="font-size: 1.5rem; font-weight: 800; color: var(--border); line-height: 1; min-width: 24px;">{{ $i + 1 }}</div>
                            <div>
                                <a href="{{ route('blog.show', $fp->slug) }}" style="font-size: 0.85rem; font-weight: 600; color: var(--text); line-height: 1.4; display: block; margin-bottom: 4px; transition: color var(--transition);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text)'">{{ $fp->title }}</a>
                                <span style="font-size: 0.72rem; color: var(--text-muted);"><i class="fas fa-eye"></i> {{ number_format($fp->views) }}</span>
                            </div>
                        </div>
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
.category-filter-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    border-radius: var(--radius);
    font-size: 0.875rem;
    color: var(--text-secondary);
    transition: all var(--transition);
    text-decoration: none;
}
.category-filter-link:hover, .category-filter-link.active {
    background: var(--primary-50);
    color: var(--primary);
}
.category-filter-link .count {
    font-size: 0.75rem;
    font-weight: 700;
    background: var(--border-light);
    padding: 2px 8px;
    border-radius: var(--radius-full);
}
.category-filter-link.active .count { background: var(--primary-100); color: var(--primary); }
@media (max-width: 768px) {
    section .container > div { grid-template-columns: 1fr; }
}
</style>
@endpush
