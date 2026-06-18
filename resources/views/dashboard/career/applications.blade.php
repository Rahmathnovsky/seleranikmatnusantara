@extends('layouts.dashboard')
@section('title', 'Pelamar — ' . $job->title)
@section('page-title', 'Pelamar Kerja')
@section('breadcrumb', $job->title)

@section('content')
<div class="card-panel">
    {{-- Header Info --}}
    <div class="card-panel-header" style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Daftar Pelamar: {{ $job->title }}</h3>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                Tipe: <strong>{{ $job->type_label }}</strong> | Lokasi: <strong>{{ $job->location }}</strong> | Total Pelamar: <strong>{{ $applications->total() }}</strong>
            </div>
        </div>
        <a href="{{ route('dashboard.career.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Applications Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kandidat</th>
                    <th>No. Telepon</th>
                    <th>Pendidikan</th>
                    <th style="width: 100px; text-align: center;">Pengalaman</th>
                    <th style="width: 130px; text-align: center;">Status Rekrutmen</th>
                    <th style="width: 140px;">Tanggal Submit</th>
                    <th style="width: 100px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div style="font-weight: 700; font-size: 0.9rem;">{{ $app->full_name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $app->email }}</div>
                    </td>
                    <td style="font-size: 0.85rem;">
                        <a href="tel:{{ $app->phone }}" style="color: var(--primary); font-weight: 600;">{{ $app->phone }}</a>
                    </td>
                    <td style="font-size: 0.8rem;">
                        <div>{{ $app->last_education ?? '—' }}</div>
                        @if($app->major)
                        <div style="font-size: 0.7rem; color: var(--text-muted);">Jurusan: {{ $app->major }}</div>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: 600; font-size: 0.85rem;">
                        {{ $app->work_experience_years ?? 0 }} Tahun
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $app->status_color }}">
                            {{ strtoupper($app->status_label) }}
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                        {{ $app->applied_at->format('d M Y H:i') }}
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('dashboard.career.applications.show', $app->id) }}" class="btn btn-outline-sm" title="Detail Profil Pelamar">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-file-invoice" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada lamaran masuk untuk posisi ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($applications->hasPages())
    <div style="padding: 20px 0;">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection
