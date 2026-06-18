@extends('layouts.dashboard')
@section('title', 'Kelola Wilayah')
@section('page-title', 'Kelola Wilayah')
@section('breadcrumb', 'SNN Region Hierarchy')

@section('content')
<div class="grid-sidebar-layout">
    
    {{-- Left column: Regions Hierarchy List --}}
    <div class="card-panel" x-data="{ editingId: null, editingName: '', editingType: '' }">
        <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
            <h3 class="card-panel-title"><i class="fas fa-map-location-dot" style="color: var(--primary);"></i> Hierarki Wilayah</h3>
            <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Brand
            </a>
        </div>

        <div class="card-panel-body">
            @php
                $provinces = $regions->where('type', 'province');
            @endphp
            
            @forelse($provinces as $province)
            <div style="background: var(--bg); border: 1px solid var(--border-light); border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--border); padding-bottom: 8px; margin-bottom: 12px;">
                    <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                        <i class="fas fa-map"></i> Provinsi: {{ $province->name }}
                    </div>
                    <div style="display: flex; gap: 4px;">
                        <button class="btn btn-outline-sm" @click="editingId = {{ $province->id }}; editingName = '{{ $province->name }}'; editingType = 'province'" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form method="POST" action="{{ route('dashboard.brands.regions.destroy', $province->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus provinsi ini beserta semua kota di bawahnya?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Cities --}}
                <div style="padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                    @php
                        $cities = $regions->where('type', 'city')->where('parent_id', $province->id);
                    @endphp
                    @forelse($cities as $city)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--surface); padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-light);">
                        <span style="font-size: 0.85rem; font-weight: 600;"><i class="fas fa-city" style="color: var(--accent);"></i> {{ $city->name }}</span>
                        <div style="display: flex; gap: 4px;">
                            <button class="btn btn-outline-sm" @click="editingId = {{ $city->id }}; editingName = '{{ $city->name }}'; editingType = 'city'" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" action="{{ route('dashboard.brands.regions.destroy', $city->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kota ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-sm" style="color: var(--danger);" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div style="font-size: 0.8rem; color: var(--text-muted); font-style: italic; padding: 4px 12px;">Belum ada kota terdaftar</div>
                    @endforelse
                </div>
            </div>
            @empty
            <div style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i class="fas fa-map-location" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                Belum ada wilayah terdaftar
            </div>
            @endforelse
        </div>

        {{-- Region Edit Overlay / Panel --}}
        <template x-if="editingId !== null">
            <div style="margin-top: 20px; border-top: 1px solid var(--border); padding-top: 20px;">
                <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 12px; color: var(--primary);">Ubah Nama Wilayah</h4>
                <form method="POST" :action="`{{ url('/dashboard/brands/regions') }}/${editingId}`" style="display: flex; gap: 12px; align-items: flex-end;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" :value="editingType">
                    <div style="flex: 1;">
                        <label class="form-label">Nama Baru</label>
                        <input type="text" name="name" x-model="editingName" class="form-control" required>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn btn-outline" @click="editingId = null">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </template>
    </div>

    {{-- Right column: Add Region Form --}}
    <div class="card-panel" x-data="{ type: 'province' }">
        <div class="card-panel-header">
            <h3 class="card-panel-title"><i class="fas fa-plus-circle" style="color: var(--accent);"></i> Wilayah Baru</h3>
        </div>
        <form method="POST" action="{{ route('dashboard.brands.regions.store') }}" class="card-panel-body">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Tipe Wilayah*</label>
                <select name="type" x-model="type" class="form-control" required>
                    <option value="province">Provinsi</option>
                    <option value="city">Kota (Kabupaten)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Wilayah*</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: DKI Jakarta, Bandung">
            </div>

            <div class="form-group" x-show="type === 'city'" x-transition>
                <label class="form-label">Provinsi Induk (Parent)*</label>
                <select name="parent_id" class="form-control" :required="type === 'city'">
                    <option value="">Pilih Provinsi...</option>
                    @foreach($regions->where('type', 'province') as $prov)
                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 12px;">
                <i class="fas fa-plus"></i> Simpan Wilayah
            </button>
        </form>
    </div>

</div>
@endsection
