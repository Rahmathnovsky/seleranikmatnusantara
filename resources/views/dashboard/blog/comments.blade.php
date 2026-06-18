@extends('layouts.dashboard')
@section('title', 'Komentar Blog')
@section('page-title', 'Komentar Blog')
@section('breadcrumb', 'Manage Reader Comments')

@section('content')
<div class="card-panel">
    {{-- Tabs/Filters --}}
    <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 12px; gap: 8px;">
        <a href="{{ route('dashboard.blog.comments') }}" class="btn {{ request('approved') === null ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Semua Komentar
        </a>
        <a href="{{ route('dashboard.blog.comments', ['approved' => '0']) }}" class="btn {{ request('approved') === '0' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Pending Persetujuan
        </a>
        <a href="{{ route('dashboard.blog.comments', ['approved' => '1']) }}" class="btn {{ request('approved') === '1' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Sudah Disetujui
        </a>
    </div>

    {{-- Comments List Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 180px;">Penulis</th>
                    <th>Komentar</th>
                    <th>Artikel</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr x-data="{ showReplyForm: false }">
                    <td>
                        <div style="font-weight: 700; font-size: 0.85rem;">{{ $comment->author_name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $comment->author_email }}</div>
                        @if($comment->is_admin_reply)
                        <span class="badge badge-accent" style="font-size: 0.65rem; padding: 2px 6px;">Staff Reply</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $comment->body }}</div>
                        
                        {{-- Inline Reply form --}}
                        <div x-show="showReplyForm" x-transition style="margin-top: 12px; background: var(--bg); padding: 12px; border-radius: 8px; border: 1px solid var(--border-light);">
                            <form method="POST" action="{{ route('dashboard.blog.comments.reply', $comment->id) }}">
                                @csrf
                                <div class="form-group" style="margin-bottom: 8px;">
                                    <label class="form-label" style="font-size: 0.72rem;">Balas Komentar Ini:</label>
                                    <textarea name="body" class="form-control" rows="2" required placeholder="Tulis balasan resmi..." style="font-size: 0.825rem;"></textarea>
                                </div>
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <button type="button" class="btn btn-outline btn-sm" @click="showReplyForm = false">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Kirim Balasan</button>
                                </div>
                            </form>
                        </div>
                    </td>
                    <td style="font-size: 0.825rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        @if($comment->post)
                        <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" style="color: var(--primary); font-weight: 500;">
                            {{ $comment->post->title }}
                        </a>
                        @else
                        <span style="color: var(--text-muted);">Deleted Post</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $comment->is_approved ? 'badge-success' : 'badge-warning' }}">
                            {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td style="font-size: 0.75rem; color: var(--text-muted);">
                        {{ $comment->created_at->format('d M Y H:i') }}
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end;">
                            @if(!$comment->is_approved)
                            <form method="POST" action="{{ route('dashboard.blog.comments.approve', $comment->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if(!$comment->parent_id && !$comment->is_admin_reply)
                            <button class="btn btn-outline-sm" @click="showReplyForm = !showReplyForm" title="Balas">
                                <i class="fas fa-reply"></i>
                            </button>
                            @endif

                            <form method="POST" action="{{ route('dashboard.blog.comments.destroy', $comment->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')" style="display: inline;">
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
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-comments" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada komentar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($comments->hasPages())
    <div style="padding: 20px 0;">
        {{ $comments->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
