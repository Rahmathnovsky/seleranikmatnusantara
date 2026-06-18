@extends('layouts.public')
@section('title', $job->title . ' — Lowongan Kerja Selera Nikmat Nusantara')
@section('meta_description', Str::limit(strip_tags($job->description), 160))
@section('meta_keywords', $job->title . ', lowongan kerja, karir, ' . ($job->brand?->name ?? 'corporate') . ', f&b, selera nikmat nusantara')
@section('meta')
    <meta property="og:title" content="{{ $job->title }} — Lowongan Kerja Selera Nikmat Nusantara">
    <meta property="og:description" content="{{ Str::limit(strip_tags($job->description), 160) }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection
@php $locale = session('locale', 'id'); @endphp

@section('content')
<div class="page-hero" style="text-align: left; padding-bottom: 60px;">
    <div class="container page-hero-content">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ $locale === 'id' ? 'Beranda' : 'Home' }}</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('career.index') }}">{{ $locale === 'id' ? 'Karir' : 'Career' }}</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ Str::limit($job->title, 40) }}</span>
        </div>
        <div style="margin-top: 24px; display: flex; align-items: flex-start; gap: 20px;">
            <div style="width: 70px; height: 70px; border-radius: var(--radius-lg); background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3); overflow: hidden; flex-shrink: 0;">
                @if($job->brand)
                    <img src="{{ $job->brand->logo_url }}" alt="{{ $job->brand->name }}" style="width: 100%; height: 100%; object-fit: contain; padding: 8px;">
                @else
                    <img src="{{ asset('images/logo-light.png') }}" alt="Selera Nikmat Nusantara" style="width: 100%; height: 100%; object-fit: contain; padding: 8px;">
                @endif
            </div>
            <div>
                <h1 style="font-size: clamp(1.6rem, 4vw, 2.5rem);">{{ $job->title }}</h1>
                <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 12px; font-size: 0.85rem; opacity: 0.85;">
                    @if($job->brand)<span><i class="fas fa-tag"></i> {{ $job->brand->name }}</span>@endif
                    <span><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                    <span><i class="fas fa-briefcase"></i> {{ $job->type_label }}</span>
                    @if($job->salary_range)<span><i class="fas fa-money-bill"></i> {{ $job->salary_range }}</span>@endif
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 60px; align-items: start;">
            <div>
                {{-- Job Description --}}
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 style="font-weight: 700; margin-bottom: 20px; font-size: 1.1rem;">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                        {{ $locale === 'id' ? 'Deskripsi Pekerjaan' : 'Job Description' }}
                    </h2>
                    <div class="blog-article-body">{!! $job->description !!}</div>
                </div>

                @if($job->requirements)
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 style="font-weight: 700; margin-bottom: 20px; font-size: 1.1rem;">
                        <i class="fas fa-list-check" style="color: var(--accent);"></i>
                        {{ $locale === 'id' ? 'Persyaratan' : 'Requirements' }}
                    </h2>
                    <div class="blog-article-body">{!! $job->requirements !!}</div>
                </div>
                @endif

                @if($job->benefits)
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 style="font-weight: 700; margin-bottom: 20px; font-size: 1.1rem;">
                        <i class="fas fa-gift" style="color: var(--success);"></i>
                        {{ $locale === 'id' ? 'Keuntungan & Fasilitas' : 'Benefits & Perks' }}
                    </h2>
                    <div class="blog-article-body">{!! $job->benefits !!}</div>
                </div>
                @endif

                {{-- APPLICATION FORM --}}
                <div class="card" style="padding: 36px;" id="apply-form">
                    <h2 style="font-weight: 700; margin-bottom: 24px; font-size: 1.2rem;">
                        <i class="fas fa-paper-plane" style="color: var(--primary);"></i>
                        {{ $locale === 'id' ? 'Form Lamaran' : 'Application Form' }}
                    </h2>

                    @if(session('error'))<div class="dash-alert dash-alert-error" style="margin-bottom: 16px; border-radius: var(--radius);">{{ session('error') }}</div>@endif
                    @if($errors->any())<div class="dash-alert dash-alert-error" style="margin-bottom: 16px; border-radius: var(--radius);">{{ $errors->first() }}</div>@endif

                    <form method="POST" action="{{ route('career.apply', $job->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Nama Lengkap*' : 'Full Name*' }}</label>
                                <input type="text" name="full_name" required class="form-control" value="{{ old('full_name') }}" placeholder="John Doe">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Tanggal Lahir' : 'Date of Birth' }}</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                            </div>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Email*</label>
                                <input type="email" name="email" required class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'No. Telepon*' : 'Phone*' }}</label>
                                <input type="tel" name="phone" required class="form-control" value="{{ old('phone') }}" placeholder="+62 812...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ $locale === 'id' ? 'Alamat' : 'Address' }}</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="{{ $locale === 'id' ? 'Alamat lengkap...' : 'Full address...' }}">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Pendidikan Terakhir' : 'Last Education' }}</label>
                                <select name="last_education" class="form-control">
                                    <option value="">{{ $locale === 'id' ? 'Pilih...' : 'Select...' }}</option>
                                    <option value="SMA/SMK" {{ old('last_education') === 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('last_education') === 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('last_education') === 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('last_education') === 'S2' ? 'selected' : '' }}>S2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Jurusan' : 'Major' }}</label>
                                <input type="text" name="major" class="form-control" value="{{ old('major') }}" placeholder="Manajemen">
                            </div>
                            <div class="form-group">
                                <label class="form-label">IPK / GPA</label>
                                <input type="number" name="gpa" step="0.01" min="0" max="4" class="form-control" value="{{ old('gpa') }}" placeholder="3.50">
                            </div>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Universitas/Sekolah' : 'University/School' }}</label>
                                <input type="text" name="university" class="form-control" value="{{ old('university') }}" placeholder="Universitas Indonesia">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ $locale === 'id' ? 'Pengalaman Kerja (tahun)' : 'Work Experience (years)' }}</label>
                                <input type="number" name="work_experience_years" min="0" class="form-control" value="{{ old('work_experience_years', 0) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ $locale === 'id' ? 'Upload CV* (PDF/DOC, max 5MB)' : 'Upload CV* (PDF/DOC, max 5MB)' }}</label>
                            <input type="file" name="cv_file" required class="form-control" accept=".pdf,.doc,.docx" onchange="previewFileName(this)">
                            <div id="filePreview" style="font-size: 0.8rem; color: var(--success); margin-top: 6px;"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ $locale === 'id' ? 'Cover Letter / Surat Lamaran' : 'Cover Letter' }}</label>
                            <textarea name="cover_letter" class="form-control" rows="5" placeholder="{{ $locale === 'id' ? 'Ceritakan motivasi dan keunggulan Anda...' : 'Tell us about your motivation and strengths...' }}">{{ old('cover_letter') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ $locale === 'id' ? 'Link Portfolio (opsional)' : 'Portfolio URL (optional)' }}</label>
                            <input type="url" name="portfolio_url" class="form-control" value="{{ old('portfolio_url') }}" placeholder="https://portfolio.com/anda">
                        </div>

                        <div style="padding: 16px; background: var(--accent-50); border-radius: var(--radius); margin-bottom: 24px; font-size: 0.825rem; color: var(--text-secondary);">
                            <i class="fas fa-shield-check" style="color: var(--accent);"></i>
                            {{ $locale === 'id' ? 'Data Anda aman dan hanya digunakan untuk proses rekrutmen.' : 'Your data is secure and only used for recruitment purposes.' }}
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                            <i class="fas fa-paper-plane"></i>
                            {{ $locale === 'id' ? 'Kirim Lamaran' : 'Submit Application' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside style="position: sticky; top: calc(var(--navbar-h) + 20px);">
                <div class="card" style="padding: 24px; margin-bottom: 20px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Detail Posisi' : 'Position Details' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; gap: 12px;">
                            <i class="fas fa-briefcase" style="color: var(--accent); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">{{ $locale === 'id' ? 'Tipe' : 'Type' }}</div>
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $job->type_label }}</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 12px;">
                            <i class="fas fa-map-marker-alt" style="color: var(--accent); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">{{ $locale === 'id' ? 'Lokasi' : 'Location' }}</div>
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $job->location }}</div>
                            </div>
                        </div>
                        @if($job->salary_range)
                        <div style="display: flex; gap: 12px;">
                            <i class="fas fa-money-bill-wave" style="color: var(--accent); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">{{ $locale === 'id' ? 'Gaji' : 'Salary' }}</div>
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $job->salary_range }}</div>
                            </div>
                        </div>
                        @endif
                        @if($job->deadline)
                        <div style="display: flex; gap: 12px;">
                            <i class="fas fa-calendar" style="color: var(--danger); width: 16px; flex-shrink: 0; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Deadline</div>
                                <div style="font-weight: 600; font-size: 0.875rem; color: var(--danger);">{{ $job->deadline->format('d M Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <a href="#apply-form" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 20px;">
                        <i class="fas fa-paper-plane"></i>
                        {{ $locale === 'id' ? 'Lamar Sekarang' : 'Apply Now' }}
                    </a>
                </div>

                @if($related->count() > 0)
                <div class="card" style="padding: 24px;">
                    <h4 style="font-weight: 700; margin-bottom: 16px;">{{ $locale === 'id' ? 'Lowongan Serupa' : 'Similar Jobs' }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($related as $rj)
                        <a href="{{ route('career.show', $rj->slug) }}" style="display: flex; gap: 10px; text-decoration: none; color: inherit; padding: 8px; border-radius: var(--radius); transition: background var(--transition);" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                            <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--primary-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                @if($rj->brand)
                                    <img src="{{ $rj->brand->logo_url }}" style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
                                @else
                                    <img src="{{ asset('images/logo-dark.png') }}" alt="Selera Nikmat Nusantara" style="width: 100%; height: 100%; object-fit: contain; padding: 4px;">
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.825rem;">{{ $rj->title }}</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $rj->location }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-article-body { font-size: 0.95rem; line-height: 1.8; color: var(--text-secondary); }
.blog-article-body h2, .blog-article-body h3 { color: var(--text); font-weight: 700; margin: 20px 0 12px; }
.blog-article-body ul, .blog-article-body ol { padding-left: 20px; }
.blog-article-body li { margin-bottom: 6px; }
@media (max-width: 900px) {
    section .container > div { grid-template-columns: 1fr !important; }
    aside { position: static !important; }
}
</style>
@endpush

@push('scripts')
<script>
function previewFileName(input) {
    const preview = document.getElementById('filePreview');
    if (input.files[0]) {
        preview.innerHTML = `<i class="fas fa-check-circle"></i> ${input.files[0].name} (${(input.files[0].size/1024/1024).toFixed(2)} MB)`;
    }
}
</script>
@endpush
