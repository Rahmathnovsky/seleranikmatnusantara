<x-mail::message>
# Lamaran Baru Diterima

Ada lamaran baru masuk untuk posisi **{{ $application->job->title }}** di **{{ $application->job->brand->name ?? config('app.name') }}**.

Berikut adalah detail kandidat:

- **Nama Lengkap:** {{ $application->full_name }}
- **Email:** {{ $application->email }}
- **Telepon:** {{ $application->phone }}
- **Pendidikan Terakhir:** {{ $application->last_education }} (Jurusan: {{ $application->major }}, Universitas: {{ $application->university }})
- **IPK:** {{ $application->gpa }}
- **Pengalaman Kerja:** {{ $application->work_experience_years }} tahun

**Cover Letter:**
{{ $application->cover_letter ?? 'Tidak ada' }}

CV kandidat telah dilampirkan pada email ini.

<x-mail::button :url="route('dashboard.career.applications.show', $application->id)">
Lihat Detail Lamaran
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
