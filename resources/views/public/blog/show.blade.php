@extends('layouts.public')
@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta')
    <meta name="keywords" content="{{ $post->meta_title ?? $post->title }}, selera nikmat nusantara, blog, kuliner">
    <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?? $post->excerpt }}">
    @if($post->cover_image)
    <meta property="og:image" content="{{ $post->cover_image_url }}">
    @endif
    <meta property="og:type" content="article">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero" style="text-align: left; padding-bottom: 80px;">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ $locale === 'id' ? 'Beranda' : 'Home' }}</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('blog.index') }}">Blog</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ Str::limit($post->title, 40) }}</span>
        </div>
        @if($post->category)
        <div style="margin-top: 24px;">
            <span class="section-eyebrow">{{ $post->category->name }}</span>
        </div>
        @endif
        <h1 style="margin-top: 12px; font-size: clamp(1.8rem, 4vw, 2.8rem);">{{ $post->title }}</h1>
        <div style="display: flex; align-items: center; gap: 24px; margin-top: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" style="width: 36px; height: 36px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.3);">
                <span style="font-size: 0.875rem; opacity: 0.9;">{{ $post->author->name }}</span>
            </div>
            <span style="font-size: 0.8rem; opacity: 0.7;"><i class="fas fa-calendar"></i> {{ $post->published_at?->format('d M Y') }}</span>
            <span style="font-size: 0.8rem; opacity: 0.7;"><i class="fas fa-clock"></i> {{ $post->reading_time }} min read</span>
            <span style="font-size: 0.8rem; opacity: 0.7;"><i class="fas fa-eye"></i> {{ number_format($post->views) }}</span>
        </div>
    </div>
</div>

<section style="padding: 60px 0 100px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 60px; align-items: start;">

            {{-- Article Content --}}
            <article>
                @if($post->cover_image)
                <div style="border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 40px; box-shadow: var(--shadow-xl);">
                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                @endif

                <div class="blog-article-body">
                    {!! $post->body !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->count() > 0)
                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Tags:</span>
                    @foreach($post->tags as $tag)
                    <span class="badge badge-muted">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Like & Share --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 32px; padding: 20px; background: var(--bg); border-radius: var(--radius-lg); flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @php
                            $liked = in_array($post->id, session('liked_posts', []));
                        @endphp
                        <button id="likeButton" onclick="likePost({{ $post->id }})" style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem; color: {{ $liked ? 'var(--danger)' : 'var(--text-secondary)' }}; cursor: pointer; border: none; background: none;">
                            <i class="{{ $liked ? 'fas' : 'far' }} fa-heart" id="likeIcon" style="font-size: 1.2rem; transition: transform var(--transition);"></i>
                            <span>{{ $locale === 'id' ? 'Suka' : 'Like' }}</span>
                            <span id="likeCount" style="background: rgba(99,69,36,0.08); padding: 2px 10px; border-radius: var(--radius-full); font-size: 0.8rem;">{{ $post->likes }}</span>
                        </button>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-weight: 600; font-size: 0.875rem;">{{ $locale === 'id' ? 'Bagikan:' : 'Share:' }}</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" style="color: #1877f2; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" style="color: #1da1f2; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" style="color: #25d366; font-size: 1.2rem;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                {{-- COMMENTS SECTION --}}
                <div style="margin-top: 60px;" id="comments">
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 32px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-comments" style="color: var(--primary);"></i>
                        {{ $locale === 'id' ? 'Komentar' : 'Comments' }}
                        <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">({{ $comments->count() }})</span>
                    </h3>

                    {{-- Comment Form --}}
                    <div class="card" style="padding: 28px; margin-bottom: 32px;">
                        <h4 style="font-weight: 700; margin-bottom: 20px;">{{ $locale === 'id' ? 'Tulis Komentar' : 'Write a Comment' }}</h4>
                        <form method="POST" action="{{ route('blog.comment.store', $post->id) }}">
                            @csrf
                            @if(!auth()->check())
                            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">{{ $locale === 'id' ? 'Nama*' : 'Name*' }}</label>
                                    <input type="text" name="guest_name" required class="form-control" value="{{ old('guest_name') }}">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}">
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Komentar*' : 'Comment*' }}</label>
                                <textarea name="body" required class="form-control" rows="4" placeholder="{{ $locale === 'id' ? 'Tulis komentar Anda...' : 'Write your comment...' }}">{{ old('body') }}</textarea>
                            </div>
                            @if(!auth()->check())
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px;">
                                {{ $locale === 'id' ? 'Komentar akan ditampilkan setelah disetujui admin.' : 'Comments will be shown after admin approval.' }}
                            </p>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                {{ $locale === 'id' ? 'Kirim Komentar' : 'Post Comment' }}
                            </button>
                        </form>
                    </div>

                    {{-- Comments List --}}
                    @forelse($comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <img src="{{ $comment->author_avatar }}" alt="{{ $comment->author_name }}" class="comment-avatar">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span class="comment-author">{{ $comment->author_name }}</span>
                                    @if($comment->is_admin_reply)
                                    <span class="admin-badge">Admin</span>
                                    @endif
                                </div>
                                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="comment-body">{{ $comment->body }}</div>
                        <div class="comment-actions">
                            <button class="comment-reply-btn" onclick="toggleReplyForm({{ $comment->id }})">
                                <i class="fas fa-reply"></i> {{ $locale === 'id' ? 'Balas' : 'Reply' }}
                            </button>
                        </div>

                        {{-- Reply form (inline) --}}
                        <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 16px;">
                            <form method="POST" action="{{ route('blog.comment.store', $post->id) }}">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                @if(!auth()->check())
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                    <input type="text" name="guest_name" required class="form-control" placeholder="{{ $locale === 'id' ? 'Nama*' : 'Name*' }}" style="font-size: 0.85rem; padding: 8px 12px;">
                                    <input type="email" name="guest_email" class="form-control" placeholder="Email" style="font-size: 0.85rem; padding: 8px 12px;">
                                </div>
                                @endif
                                <div style="display: flex; gap: 8px;">
                                    <textarea name="body" required class="form-control" rows="2" placeholder="{{ $locale === 'id' ? 'Tulis balasan...' : 'Write a reply...' }}" style="font-size: 0.85rem;"></textarea>
                                    <button type="submit" class="btn btn-primary btn-sm" style="align-self: flex-end; flex-shrink: 0;"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>

                        {{-- Replies --}}
                        @if($comment->replies->count() > 0)
                        <div style="margin-top: 16px;">
                            @foreach($comment->replies as $reply)
                            <div class="comment-card admin-reply">
                                <div class="comment-header">
                                    <img src="{{ $reply->author_avatar }}" alt="{{ $reply->author_name }}" class="comment-avatar">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span class="comment-author">{{ $reply->author_name }}</span>
                                            @if($reply->is_admin_reply)
                                            <span class="admin-badge">Admin</span>
                                            @endif
                                        </div>
                                        <span class="comment-date">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="comment-body">{{ $reply->body }}</div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-comment-slash" style="font-size: 2rem; margin-bottom: 12px; display: block;"></i>
                        {{ $locale === 'id' ? 'Belum ada komentar. Jadilah yang pertama!' : 'No comments yet. Be the first!' }}
                    </div>
                    @endforelse
                </div>
            </article>

            {{-- Sidebar --}}
            <aside style="position: sticky; top: calc(var(--navbar-h) + 20px);">
                @if($related->count() > 0)
                <div class="card" style="padding: 24px; margin-bottom: 24px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Artikel Terkait' : 'Related Articles' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($related as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" style="display: flex; gap: 12px; text-decoration: none; color: inherit;">
                            <img src="{{ $rel->cover_image_url }}" alt="{{ $rel->title }}" style="width: 70px; height: 55px; border-radius: var(--radius-sm); object-fit: cover; flex-shrink: 0;">
                            <div>
                                <div style="font-size: 0.825rem; font-weight: 600; line-height: 1.4; margin-bottom: 4px;">{{ Str::limit($rel->title, 60) }}</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $rel->published_at?->format('d M Y') }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Promo Banner --}}
                <div class="card" style="padding: 24px; background: linear-gradient(135deg, var(--primary-dark), var(--primary)); color: #fff; text-align: center;">
                    <i class="fas fa-ticket" style="font-size: 2rem; color: var(--accent-light); margin-bottom: 12px;"></i>
                    <h4 style="margin-bottom: 8px;">{{ $locale === 'id' ? 'Promo Spesial' : 'Special Promo' }}</h4>
                    <p style="font-size: 0.825rem; opacity: 0.85; margin-bottom: 16px;">{{ $locale === 'id' ? 'Dapatkan diskon eksklusif untuk pengalaman kuliner terbaik!' : 'Get exclusive discounts for the best culinary experience!' }}</p>
                    <a href="{{ route('promo.index') }}" class="btn btn-accent btn-sm" style="width: 100%; justify-content: center;">
                        {{ $locale === 'id' ? 'Lihat Promo' : 'See Promotions' }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- Related Posts (Bottom) --}}
@if($related->count() > 0)
<section style="padding: 60px 0; background: var(--bg-secondary);">
    <div class="container">
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 28px;">{{ $locale === 'id' ? 'Artikel Lainnya' : 'More Articles' }}</h3>
        <div class="grid-3">
            @foreach($related as $rel)
            <div class="blog-card" data-aos="fade-up">
                <div class="blog-card-image">
                    <a href="{{ route('blog.show', $rel->slug) }}">
                        <img src="{{ $rel->cover_image_url }}" alt="{{ $rel->title }}" loading="lazy">
                    </a>
                </div>
                <div class="blog-card-body">
                    <h3><a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a></h3>
                    <p>{{ Str::limit($rel->excerpt ?? strip_tags($rel->body), 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
.blog-article-body { font-size: 1rem; line-height: 1.85; color: var(--text-secondary); }
.blog-article-body h1, .blog-article-body h2, .blog-article-body h3 { color: var(--text); font-weight: 700; margin: 32px 0 16px; }
.blog-article-body h2 { font-size: 1.5rem; }
.blog-article-body h3 { font-size: 1.2rem; }
.blog-article-body p { margin-bottom: 20px; }
.blog-article-body ul, .blog-article-body ol { padding-left: 24px; margin-bottom: 20px; }
.blog-article-body li { margin-bottom: 8px; }
.blog-article-body img { border-radius: var(--radius-lg); margin: 24px 0; box-shadow: var(--shadow-md); }
.blog-article-body blockquote {
    border-left: 4px solid var(--accent);
    padding: 16px 24px;
    margin: 24px 0;
    background: var(--accent-50);
    border-radius: 0 var(--radius) var(--radius) 0;
    font-style: italic;
    color: var(--text);
}
@media (max-width: 900px) {
    section .container > div { grid-template-columns: 1fr !important; }
    aside { position: static !important; }
}
</style>
@endpush

@push('scripts')
<script>
function likePost(postId) {
    const btn = document.getElementById('likeButton');
    const icon = document.getElementById('likeIcon');
    const count = document.getElementById('likeCount');

    icon.style.transform = 'scale(1.3)';
    setTimeout(() => icon.style.transform = 'scale(1)', 150);

    fetch(`/blog/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            count.innerText = data.likes;
            if (data.status === 'liked') {
                icon.className = 'fas fa-heart';
                btn.style.color = 'var(--danger)';
            } else {
                icon.className = 'far fa-heart';
                btn.style.color = 'var(--text-secondary)';
            }
        }
    })
    .catch(err => console.error(err));
}

function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
document.querySelectorAll('[data-aos]').forEach(el => {
    new IntersectionObserver((entries) => {
        entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('aos-animate'); });
    }, { threshold: 0.1 }).observe(el);
});
</script>
@endpush
