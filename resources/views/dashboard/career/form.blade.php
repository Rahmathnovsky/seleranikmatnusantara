@extends('layouts.dashboard')
@section('title', isset($job) ? 'Edit Lowongan' : 'Tambah Lowongan Baru')
@section('page-title', isset($job) ? 'Edit Lowongan' : 'Pasang Lowongan')
@section('breadcrumb', isset($job) ? $job->title : 'Form Lowongan Kerja')

@section('content')
<div class="card-panel">
    <form method="POST" 
          action="{{ isset($job) ? route('dashboard.career.update', $job->id) : route('dashboard.career.store') }}" 
          class="card-panel-body">
        @csrf
        @if(isset($job))
            @method('PUT')
        @endif

        <div class="grid-sidebar-layout">
            
            {{-- Left column: General Position Details --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Nama Posisi Pekerjaan*</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $job->title ?? '') }}" required placeholder="Contoh: Barista, Sous Chef, Accounting Staff">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Pekerjaan (Job Description)*</label>
                    <textarea name="description" class="form-control" rows="8" required placeholder="Tulis tugas pokok dan tanggung jawab utama posisi ini...">{{ old('description', $job->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Persyaratan (Requirements)</label>
                    <textarea name="requirements" class="form-control" rows="6" placeholder="Tulis kualifikasi pelamar:&#10;1. Minimal pendidikan SMA/D3&#10;2. Pengalaman minimal 1 tahun&#10;3. Jujur dan komunikatif...">{{ old('requirements', $job->requirements ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Keuntungan & Fasilitas (Benefits)</label>
                    <textarea name="benefits" class="form-control" rows="4" placeholder="Tulis benefit kerja: Gaji pokok, BPJS, Bonus kinerja, makan siang gratis...">{{ old('benefits', $job->benefits ?? '') }}</textarea>
                </div>
            </div>

            {{-- Right column: Settings & Meta --}}
            <div>
                @if(!auth()->user()->brand_id)
                <div class="form-group">
                    <label class="form-label">Brand / Corporate Scope</label>
                    <select name="brand_id" class="form-control">
                        <option value="">Corporate (Head Office / HQ)</option>
                        @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ old('brand_id', $job->brand_id ?? '') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Bidang Pekerjaan (Category)</label>
                    <select name="job_category_id" class="form-control">
                        <option value="">Pilih Bidang...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('job_category_id', $job->job_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Pekerjaan (Job Type)*</label>
                    <select name="type" class="form-control" required>
                        <option value="fulltime" {{ old('type', $job->type ?? '') === 'fulltime' ? 'selected' : '' }}>Full Time</option>
                        <option value="parttime" {{ old('type', $job->type ?? '') === 'parttime' ? 'selected' : '' }}>Part Time</option>
                        <option value="internship" {{ old('type', $job->type ?? '') === 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="contract" {{ old('type', $job->type ?? '') === 'contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Penempatan*</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $job->location ?? '') }}" required placeholder="Contoh: Jakarta Pusat, Bandung">
                </div>

                <div class="form-group">
                    <label class="form-label">Rentang Gaji (Salary Range)</label>
                    <input type="text" name="salary_range" class="form-control" value="{{ old('salary_range', $job->salary_range ?? '') }}" placeholder="Contoh: Rp 4.5jt - 5.5jt / Bersaing">
                </div>

                <div class="form-group">
                    <label class="form-label">Batas Pendaftaran (Deadline)</label>
                    <input type="date" name="deadline" class="form-control" 
                           value="{{ old('deadline', isset($job) && $job->deadline ? $job->deadline->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Status Lowongan</label>
                    <select name="status" class="form-control">
                        <option value="open" {{ old('status', $job->status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $job->status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="draft" {{ old('status', $job->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div style="border-top: 1px solid var(--border-light); padding-top: 20px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('dashboard.career.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-floppy-disk"></i> Simpan Lowongan
            </button>
        </div>

    </form>
</div>
@endsection
