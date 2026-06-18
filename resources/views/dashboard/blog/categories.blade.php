@extends('layouts.dashboard')
@section('title', 'Kategori Artikel')
@section('page-title', 'Kategori Artikel')
@section('breadcrumb', 'Manage Blog Categories')

@section('content')
<div class="grid-sidebar-layout">
    
    {{-- Left column: Categories List --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title"><i class="fas fa-tags" style="color: var(--primary);"></i> Daftar Kategori</h3>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th style="width: 100px; text-align: center;">Jumlah Post</th>
                        <th style="width: 120px; text-align: center;">Status</th>
                        <th style="width: 100px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="font-weight: 600;">{{ $cat->id }}</td>
                        <td>
                            <div style="font-weight: 700; font-size: 0.9rem;">{{ $cat->name }}</div>
                            @if($cat->description)
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $cat->description }}</div>
                            @endif
                        </td>
                        <td style="font-size: 0.825rem; font-family: monospace;">{{ $cat->slug }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $cat->posts_count }}</td>
                        <td style="text-align: center;">
                            <form method="POST" action="{{ route('dashboard.blog.categories.update', $cat->id) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $cat->name }}">
                                <input type="hidden" name="is_active" value="{{ $cat->is_active ? 0 : 1 }}">
                                <button type="submit" class="badge {{ $cat->is_active ? 'badge-success' : 'badge-warning' }}" style="border: none; cursor: pointer;" title="Klik untuk ubah status">
                                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" action="{{ route('dashboard.blog.categories.destroy', $cat->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua post dalam kategori ini akan kehilangan kategorinya.')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column: Add Category Form --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title"><i class="fas fa-plus-circle" style="color: var(--accent);"></i> Kategori Baru</h3>
        </div>
        <form method="POST" action="{{ route('dashboard.blog.categories.store') }}" class="card-panel-body">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Kategori*</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Info Kuliner, Event">
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat kategori..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 12px;">
                <i class="fas fa-plus"></i> Simpan Kategori
            </button>
        </form>
    </div>

</div>
@endsection
