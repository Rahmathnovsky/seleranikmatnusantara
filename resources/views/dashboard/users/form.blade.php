@extends('layouts.dashboard')
@section('title', isset($user) ? 'Edit User' : 'Tambah User Baru')
@section('page-title', isset($user) ? 'Edit User' : 'Tambah User')
@section('breadcrumb', isset($user) ? $user->name : 'Form User Baru')

@section('content')
<div class="card-panel">
    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="dash-alert dash-alert-error" style="margin: 20px; border-radius: 8px;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="fas fa-circle-exclamation"></i> Terjadi kesalahan validasi:</div>
        <ul style="margin: 0; padding-left: 20px; font-size: 0.85rem;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" 
          action="{{ isset($user) ? route('dashboard.users.update', $user->id) : route('dashboard.users.store') }}" 
          class="card-panel-body">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="grid-sidebar-layout">
            
            {{-- Left column: Account Credentials & Details --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap*</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required placeholder="Contoh: John Doe">
                </div>

                <div class="grid-2-col">
                    <div class="form-group">
                        <label class="form-label">Alamat Email*</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required placeholder="Contoh: user@seleranikmat.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border-light); padding-top: 24px; margin-top: 24px;">
                    <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 16px; color: var(--text-secondary);">
                        <i class="fas fa-key"></i> Kredensial Keamanan (Password)
                    </h3>
                    
                    @if(isset($user))
                        <div style="font-size: 0.8rem; color: var(--text-muted); background: var(--bg); padding: 10px 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid var(--border-light);">
                            <i class="fas fa-circle-info"></i> Biarkan kolom password kosong jika Anda tidak berniat mengubah password akun ini.
                        </div>
                    @endif

                    <div class="grid-2-col">
                        <div class="form-group">
                            <label class="form-label">Password{{ !isset($user) ? '*' : '' }}</label>
                            <input type="password" name="password" class="form-control" {{ !isset($user) ? 'required' : '' }} placeholder="Minimal 8 karakter...">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password{{ !isset($user) ? '*' : '' }}</label>
                            <input type="password" name="password_confirmation" class="form-control" {{ !isset($user) ? 'required' : '' }} placeholder="Ulangi password...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column: Roles & Scoping --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Peran Pengguna (Role)*</label>
                    <select name="role" id="roleSelector" class="form-control" required onchange="toggleBrandScoping()">
                        <option value="customer" {{ old('role', $user->role ?? '') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                        <option value="editor" {{ old('role', $user->role ?? '') === 'editor' ? 'selected' : '' }}>Editor (Konten & Promo)</option>
                        <option value="hr" {{ old('role', $user->role ?? '') === 'hr' ? 'selected' : '' }}>HR Staff (Lowongan Kerja)</option>
                        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin (Full System Access)</option>
                    </select>
                </div>

                {{-- Tenant Scoping (Dynamic Brand Selector) --}}
                <div class="form-group" id="brandScopingContainer" style="display: none;">
                    <label class="form-label">Tenant Brand Scope</label>
                    <select name="brand_id" class="form-control">
                        <option value="">Semua Brand (Corporate / Multi-tenant)</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $user->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                    <span style="font-size: 0.7rem; color: var(--text-muted); display: block; margin-top: 4px;">
                        Pilih brand spesifik untuk membatasi akses pengeditan editor / HR ini.
                    </span>
                </div>

                {{-- Account Status --}}
                <div class="form-group" style="margin-top: 24px;">
                    <div style="display: flex; align-items: center; gap: 8px; background: var(--bg); padding: 12px; border-radius: 8px; border: 1px solid var(--border-light);">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                        <label for="is_active" style="font-weight: 600; cursor: pointer; margin-bottom: 0;">User Aktif (Dapat Login)</label>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Simpan User
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function toggleBrandScoping() {
        const role = document.getElementById('roleSelector').value;
        const brandContainer = document.getElementById('brandScopingContainer');
        
        // Show brand selector only for editor and hr roles
        if (role === 'editor' || role === 'hr') {
            brandContainer.style.display = 'block';
        } else {
            brandContainer.style.display = 'none';
        }
    }
    
    // Run initial check on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleBrandScoping();
    });
</script>
@endpush
