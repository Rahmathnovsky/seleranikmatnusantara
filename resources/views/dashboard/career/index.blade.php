@extends('layouts.dashboard')
@section('title', 'Lowongan Kerja')
@section('page-title', 'Lowongan Kerja')
@section('breadcrumb', 'Manage Recuritments & Vacancies')

@section('content')
<div class="card-panel">
    {{-- Header --}}
    <div class="card-panel-header" style="flex-wrap: wrap; gap: 16px; justify-content: space-between;">
        <div>
            <span style="font-weight: 500; font-size: 0.9rem; color: var(--text-secondary);">
                Kelola lowongan pekerjaan untuk brand F&B maupun Head Office (Corporate), dan proses lamaran kandidat.
            </span>
        </div>
        <a href="{{ route('dashboard.career.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Lowongan Baru
        </a>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Posisi Pekerjaan</th>
                    <th>Brand / Corporate</th>
                    <th>Tipe / Lokasi</th>
                    <th>Bidang (Category)</th>
                    <th style="width: 120px; text-align: center;">Pelamar (Apps)</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th>Deadline</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td>
                        <div style="font-weight: 700; font-size: 0.95rem;">
                            <a href="{{ route('dashboard.career.edit', $job->id) }}" style="color: var(--text);">
                                {{ $job->title }}
                            </a>
                        </div>
                    </td>
                    <td>
                        @if($job->brand)
                        <span class="badge badge-primary" style="font-size: 0.7rem;">{{ $job->brand->name }}</span>
                        @else
                        <span class="badge badge-secondary" style="font-size: 0.7rem;">Corporate (HQ)</span>
                        @endif
                    </td>
                    <td style="font-size: 0.825rem;">
                        <span class="badge badge-{{ $job->type_color }}" style="font-size: 0.65rem;">{{ $job->type_label }}</span>
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;"><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</div>
                    </td>
                    <td style="font-size: 0.85rem;">{{ $job->category?->name ?? '—' }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('dashboard.career.applications', $job->id) }}" class="btn btn-outline-sm" style="font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fas fa-users" style="color: var(--primary);"></i> {{ $job->applications_count }} Pelamar
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $job->status === 'open' ? 'badge-success' : ($job->status === 'closed' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td style="font-size: 0.75rem; color: var(--text-muted);">
                        {{ $job->deadline ? $job->deadline->format('d M Y') : 'No Limit' }}
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('dashboard.career.applications', $job->id) }}" class="btn btn-outline-sm" title="Lihat Pelamar">
                                <i class="fas fa-id-card"></i>
                            </a>
                            <a href="{{ route('dashboard.career.edit', $job->id) }}" class="btn btn-outline-sm" title="Edit Lowongan">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('dashboard.career.destroy', $job->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')" style="display: inline;">
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
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                        <i class="fas fa-briefcase" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                        Belum ada lowongan terbit
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($jobs->hasPages())
    <div style="padding: 20px 0;">
        {{ $jobs->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
