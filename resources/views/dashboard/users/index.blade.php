@extends('layouts.dashboard')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('breadcrumb', 'SNN Administrative Accounts')

@section('content')
<div class="card-panel">
    {{-- Header & Filters --}}
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px; justify-content: space-between;">
        <form method="GET" action="{{ route('dashboard.users.index') }}" style="display: flex; gap: 8px; flex: 1; min-width: 280px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama atau email..." style="max-width: 240px;">
            <select name="role" class="form-control form-control-sm" style="max-width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Peran (Role)</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                <option value="hr" {{ request('role') === 'hr' ? 'selected' : '' }}>HR (Recruitment)</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-magnifying-glass"></i></button>
        </form>
        
        <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    {{-- Users Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">Avatar</th>
                    <th>Nama Pengguna</th>
                    <th>Email Address</th>
                    <th>Peran (Role)</th>
                    <th>Tenant Scope (Brand)</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 120px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="text-align: center;">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-light); display: inline-block;">
                    </td>
                    <td>
                        <div style="font-weight: 700; font-size: 0.9rem;">{{ $user->name }}</div>
                        @if($user->phone)
                        <div style="font-size: 0.72rem; color: var(--text-muted);"><i class="fas fa-phone"></i> {{ $user->phone }}</div>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem;"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-danger">ADMIN</span>
                        @elseif($user->role === 'hr')
                            <span class="badge badge-warning">HR STAFF</span>
                        @elseif($user->role === 'editor')
                            <span class="badge badge-primary">EDITOR</span>
                        @else
                            <span class="badge badge-secondary">CUSTOMER</span>
                        @endif
                    </td>
                    <td>
                        @if($user->role === 'admin')
                            <span style="font-size: 0.75rem; font-weight: 600; color: var(--accent-dark);"><i class="fas fa-shield"></i> Full System Access</span>
                        @elseif($user->brand)
                            <span class="badge badge-primary" style="font-size: 0.7rem;">{{ $user->brand->name }}</span>
                        @elseif($user->role === 'editor' || $user->role === 'hr')
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">All Brands (Corporate)</span>
                        @else
                            <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-outline-sm" title="Edit User">
                                <i class="fas fa-user-gear"></i> Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('dashboard.users.destroy', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        User tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div style="padding: 20px 0;">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
