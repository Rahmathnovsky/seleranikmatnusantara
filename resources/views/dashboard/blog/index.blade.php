@extends('layouts.dashboard')
@section('title', 'Blog posts')
@section('page-title', 'Blog posts')
@section('breadcrumb', 'Manage Articles & News')

@section('content')
<div class="card-panel">
    {{-- Header & Filters --}}
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px;">
        <form method="GET" action="{{ route('dashboard.blog.index') }}" style="display: flex; gap: 8px; flex: 1; min-width: 280px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari judul..." style="max-width: 240px;">
            <select name="status" class="form-control form-control-sm" style="max-width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-magnifying-glass"></i></button>
        </form>
        
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('dashboard.blog.categories') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a href="{{ route('dashboard.blog.comments') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-comments"></i> Komentar
            </a>
            <a href="{{ route('dashboard.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Artikel Baru
            </a>
        </div>
    </div>

    {{-- Table Grid --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Cover</th>
                    <th>Judul</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 110px; text-align: center;">Stats</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 100px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.9rem;">
                            <a href="{{ route('dashboard.blog.edit', $post->id) }}" style="color: var(--text);">
                                {{ $post->title }}
                            </a>
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Oleh: {{ $post->author?->name ?? 'Guest' }}</div>
                    </td>
                    <td>
                        @if($post->brand)
                        <span class="badge badge-primary" style="font-size: 0.7rem;">{{ $post->brand->name }}</span>
                        @else
                        <span class="badge badge-secondary" style="font-size: 0.7rem;">Corporate</span>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem;">{{ $post->category?->name ?? '—' }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $post->status === 'published' ? 'badge-success' : ($post->status === 'draft' ? 'badge-warning' : 'badge-danger') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 0.75rem; color: var(--text-muted);">
                        <div title="Views"><i class="fas fa-eye"></i> {{ number_format($post->views) }}</div>
                        <div title="Likes"><i class="fas fa-heart" style="color: var(--danger);"></i> {{ number_format($post->likes) }}</div>
                    </td>
                    <td style="font-size: 0.75rem; color: var(--text-muted);">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : 'Not Published' }}
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('dashboard.blog.edit', $post->id) }}" class="btn btn-outline-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('dashboard.blog.destroy', $post->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-newspaper" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada artikel
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($posts->hasPages())
    <div style="padding: 20px 0;">
        {{ $posts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
