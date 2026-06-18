@extends('layouts.dashboard')
@section('title', 'Detail Lamaran — ' . $application->full_name)
@section('page-title', 'Detail Lamaran')
@section('breadcrumb', $application->full_name)

@section('content')
<div class="grid-sidebar-layout">
    
    {{-- Left column: Candidate Profile & Resume --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        {{-- Profile Summary --}}
        <div class="card-panel">
            <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-50); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                        {{ strtoupper(substr($application->full_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text);">{{ $application->full_name }}</h3>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                            Melamar untuk posisi: <strong style="color: var(--primary);">{{ $application->job?->title }}</strong>
                        </div>
                    </div>
                </div>
                <a href="{{ route('dashboard.career.applications', $application->career_job_id) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke List
                </a>
            </div>
            
            <div class="card-panel-body grid-2-col" style="padding-top: 20px;">
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Email Address</div>
                    <div style="font-weight: 600; font-size: 0.9rem;"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Phone Number</div>
                    <div style="font-weight: 600; font-size: 0.9rem;"><a href="tel:{{ $application->phone }}">{{ $application->phone }}</a></div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Tanggal Lahir</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">
                        {{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('d M Y') : '—' }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Alamat</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $application->address ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Education & Experience details --}}
        <div class="card-panel">
            <div class="card-panel-header">
                <h4 class="card-panel-title"><i class="fas fa-graduation-cap" style="color: var(--primary);"></i> Latar Belakang & Pendidikan</h4>
            </div>
            <div class="card-panel-body grid-2-col">
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Pendidikan Terakhir</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $application->last_education ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Jurusan</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $application->major ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Universitas / Sekolah</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $application->university ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">IPK / GPA</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $application->gpa ? number_format($application->gpa, 2) : '—' }}</div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Pengalaman Kerja</div>
                    <div style="font-weight: 600; font-size: 0.9rem; color: var(--accent-dark);">{{ $application->work_experience_years ?? 0 }} Tahun</div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Link Portfolio</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">
                        @if($application->portfolio_url)
                        <a href="{{ $application->portfolio_url }}" target="_blank" style="color: var(--info); text-decoration: underline;"><i class="fas fa-external-link"></i> Buka Portfolio</a>
                        @else
                        —
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cover Letter Narrative --}}
        <div class="card-panel">
            <div class="card-panel-header">
                <h4 class="card-panel-title"><i class="fas fa-file-invoice" style="color: var(--accent);"></i> Cover Letter / Surat Lamaran</h4>
            </div>
            <div class="card-panel-body" style="font-size: 0.9rem; line-height: 1.7; white-space: pre-wrap; color: var(--text-secondary); background: var(--bg); padding: 20px; border-radius: 8px; border: 1px solid var(--border-light);">{{ $application->cover_letter ?? 'Kandidat tidak melampirkan cover letter.' }}</div>
        </div>

    </div>

    {{-- Right column: Actions & Status Management --}}
    <div style="position: sticky; top: 20px; display: flex; flex-direction: column; gap: 24px;">
        
        {{-- CV Download Panel --}}
        <div class="card-panel" style="text-align: center; padding: 28px;">
            <div style="font-size: 3rem; color: var(--accent); margin-bottom: 16px;"><i class="fas fa-file-pdf"></i></div>
            <h4 style="font-weight: 700; font-size: 1rem; margin-bottom: 8px;">Curriculum Vitae (CV)</h4>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 20px;">Unduh lampiran CV file yang di-submit oleh kandidat.</p>
            <a href="{{ Storage::disk('public')->url($application->cv_file) }}" target="_blank" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                <i class="fas fa-download"></i> Download CV File
            </a>
        </div>

        {{-- HR Evaluation & Status Form --}}
        <div class="card-panel">
            <div class="card-panel-header">
                <h4 class="card-panel-title"><i class="fas fa-sliders-h" style="color: var(--primary);"></i> Proses Evaluasi HR</h4>
            </div>
            <form method="POST" action="{{ route('dashboard.career.applications.status', $application->id) }}" class="card-panel-body">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Status Aplikasi*</label>
                    <select name="status" class="form-control" required>
                        <option value="new" {{ $application->status === 'new' ? 'selected' : '' }}>New (Baru)</option>
                        <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed (Ditinjau)</option>
                        <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted (Lolos Screening)</option>
                        <option value="interview" {{ $application->status === 'interview' ? 'selected' : '' }}>Interview (Wawancara)</option>
                        <option value="offered" {{ $application->status === 'offered' ? 'selected' : '' }}>Offered (Tawaran Kerja)</option>
                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Internal HR (Notes)</label>
                    <textarea name="hr_notes" class="form-control" rows="6" placeholder="Tulis hasil wawancara, nilai tes, atau alasan penerimaan/penolakan kandidat...">{{ old('hr_notes', $application->hr_notes ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 12px;">
                    <i class="fas fa-floppy-disk"></i> Update Status & Catatan
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
