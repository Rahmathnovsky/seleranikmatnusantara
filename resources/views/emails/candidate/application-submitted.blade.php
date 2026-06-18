<x-mail::message>
# Halo {{ $application->full_name }},

Terima kasih telah melamar pekerjaan untuk posisi **{{ $application->job->title }}** di **{{ $application->job->brand->name ?? config('app.name') }}**.

Kami telah menerima lamaran Anda dan tim HR kami akan segera meninjaunya. Jika kualifikasi Anda sesuai dengan kebutuhan kami, kami akan menghubungi Anda untuk tahap wawancara selanjutnya.

<x-mail::button :url="route('career.show', $application->job->slug)">
Lihat Lowongan Kerja
</x-mail::button>

Salam hangat,<br>
Tim HR {{ $application->job->brand->name ?? config('app.name') }}
</x-mail::message>
