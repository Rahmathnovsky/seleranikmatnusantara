@extends('layouts.dashboard')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'SNN Admin Panel')

@section('content')
<div class="dash-stat-grid">
    <div class="stat-widget">
        <div class="stat-widget-icon primary"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-widget-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-widget-label">Total Customer</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon accent"><i class="fas fa-newspaper"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['total_posts'] }}</div>
            <div class="stat-widget-label">Artikel Published</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon success"><i class="fas fa-ticket"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['active_promos'] }}</div>
            <div class="stat-widget-label">Promo Aktif</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon warning"><i class="fas fa-hand-holding-heart"></i></div>
        <div>
            <div class="stat-widget-value">{{ number_format($stats['total_claims']) }}</div>
            <div class="stat-widget-label">Total Klaim Promo</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon info"><i class="fas fa-store"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['total_brands'] }}</div>
            <div class="stat-widget-label">Brand Aktif</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon primary"><i class="fas fa-map-marker-alt"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['total_outlets'] }}</div>
            <div class="stat-widget-label">Total Outlet</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon accent"><i class="fas fa-briefcase"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['open_jobs'] }}</div>
            <div class="stat-widget-label">Lowongan Terbuka</div>
        </div>
    </div>
    <div class="stat-widget">
        <div class="stat-widget-icon danger"><i class="fas fa-file-circle-question"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['new_applications'] }}</div>
            <div class="stat-widget-label">Lamaran Baru</div>
        </div>
    </div>
    @if($stats['pending_comments'] > 0)
    <div class="stat-widget">
        <div class="stat-widget-icon warning"><i class="fas fa-comment-dots"></i></div>
        <div>
            <div class="stat-widget-value">{{ $stats['pending_comments'] }}</div>
            <div class="stat-widget-label">Komentar Pending</div>
        </div>
    </div>
    @endif
</div>

{{-- Quick Actions --}}
<div class="card-panel" style="margin-bottom: 24px;">
    <div class="card-panel-header">
        <div class="card-panel-title"><i class="fas fa-bolt" style="color: var(--accent);"></i> Aksi Cepat</div>
    </div>
    <div class="card-panel-body">
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="{{ route('dashboard.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tulis Artikel
            </a>
            <a href="{{ route('dashboard.promo.create') }}" class="btn btn-accent btn-sm">
                <i class="fas fa-plus"></i> Buat Promo
            </a>
            <a href="{{ route('dashboard.brands.create') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-plus"></i> Tambah Brand
            </a>
            <a href="{{ route('dashboard.career.create') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-plus"></i> Pasang Lowongan
            </a>
            <a href="{{ route('dashboard.cms.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-sliders"></i> Ubah Tampilan Home
            </a>
        </div>
    </div>
</div>

<div class="grid-2-col">

    {{-- Recent Applications --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <div class="card-panel-title"><i class="fas fa-file-lines" style="color: var(--primary);"></i> Lamaran Terbaru</div>
            <a href="{{ route('dashboard.career.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentApplications as $app)
                    <tr>
                        <td>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $app->full_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $app->email }}</div>
                        </td>
                        <td style="font-size: 0.825rem;">{{ $app->job?->title }}</td>
                        <td><span class="badge badge-{{ $app->status_color }}">{{ $app->status_label }}</span></td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ $app->applied_at->format('d M') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada lamaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Promo Claims --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <div class="card-panel-title"><i class="fas fa-ticket" style="color: var(--accent);"></i> Klaim Promo Terbaru</div>
            <a href="{{ route('dashboard.promo.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Promo</th>
                        <th>Kode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentClaims as $claim)
                    <tr>
                        <td style="font-size: 0.825rem;">{{ $claim->user?->name }}</td>
                        <td style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($claim->promo?->title, 25) }}</td>
                        <td>
                            <code style="font-size: 0.75rem; background: var(--bg); padding: 2px 6px; border-radius: 4px;">
                                {{ $claim->claim_code }}
                            </code>
                        </td>
                        <td>
                            <span class="badge {{ $claim->status === 'used' ? 'badge-success' : ($claim->status === 'expired' ? 'badge-danger' : 'badge-warning') }}">
                                {{ $claim->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada klaim</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Pending Comments --}}
@if($recentComments->where('is_approved', false)->count() > 0)
<div class="card-panel" style="margin-top: 20px;">
    <div class="card-panel-header">
        <div class="card-panel-title"><i class="fas fa-comment-dots" style="color: var(--warning);"></i> Komentar Perlu Persetujuan</div>
        <a href="{{ route('dashboard.blog.comments', ['approved' => '0']) }}" class="btn btn-outline btn-sm">Kelola Semua</a>
    </div>
    <div class="card-panel-body">
        @foreach($recentComments->where('is_approved', false)->take(3) as $comment)
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid var(--border-light);">
            <div>
                <div style="font-weight: 600; font-size: 0.85rem;">{{ $comment->author_name }}</div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">pada: {{ $comment->post?->title }}</div>
                <div style="font-size: 0.875rem; margin-top: 6px;">{{ Str::limit($comment->body, 100) }}</div>
            </div>
            <form method="POST" action="{{ route('dashboard.blog.comments.approve', $comment->id) }}" style="flex-shrink: 0; margin-left: 16px;">
                @csrf
                <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Setujui</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
