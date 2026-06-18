@php
    $locale = session('locale', 'id');
    $theme  = session('theme', 'light');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SNN Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>
<div class="dash-layout">

    {{-- Sidebar --}}
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-light.png') }}" alt="Selera Nikmat Nusantara" style="height: 38px; width: auto; object-fit: contain;">
            <div>
                <div style="font-weight: 800; font-size: 0.95rem;">Admin Panel</div>
                <div style="font-size: 0.65rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.05em;">Selera Nikmat Nusantara</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i> Dashboard
            </a>

            <div class="sidebar-section">Content</div>
            <a href="{{ route('dashboard.cms.index') }}" class="nav-item {{ request()->routeIs('dashboard.cms.*') ? 'active' : '' }}">
                <i class="fas fa-sliders"></i> {{ $locale === 'id' ? 'Pengaturan CMS' : 'CMS Settings' }}
            </a>
            <a href="{{ route('dashboard.blog.index') }}" class="nav-item {{ request()->routeIs('dashboard.blog.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Blog & Artikel
                @php $pendingComments = \App\Models\BlogComment::where('is_approved', false)->count() @endphp
                @if($pendingComments > 0)
                <span class="nav-badge">{{ $pendingComments }}</span>
                @endif
            </a>

            <div class="sidebar-section">Marketing</div>
            <a href="{{ route('dashboard.promo.index') }}" class="nav-item {{ request()->routeIs('dashboard.promo.*') ? 'active' : '' }}">
                <i class="fas fa-ticket"></i> Promo & Voucher
            </a>

            <div class="sidebar-section">Operations</div>
            <a href="{{ route('dashboard.brands.index') }}" class="nav-item {{ request()->routeIs('dashboard.brands.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Brand & Outlet
            </a>

            @if(auth()->user()->hasRole(['admin','hr']))
            <div class="sidebar-section">HR</div>
            <a href="{{ route('dashboard.career.index') }}" class="nav-item {{ request()->routeIs('dashboard.career.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> {{ $locale === 'id' ? 'Lowongan Kerja' : 'Career Jobs' }}
                @php $newApps = \App\Models\JobApplication::where('status','new')->count() @endphp
                @if($newApps > 0)
                <span class="nav-badge">{{ $newApps }}</span>
                @endif
            </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
            <div class="sidebar-section">Admin</div>
            <a href="{{ route('dashboard.users.index') }}" class="nav-item {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> {{ $locale === 'id' ? 'Manajemen User' : 'User Management' }}
            </a>
            @endif

            <div class="sidebar-section">Account</div>
            <a href="{{ route('home') }}" class="nav-item" target="_blank">
                <i class="fas fa-external-link"></i> {{ $locale === 'id' ? 'Lihat Website' : 'View Website' }}
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item" style="width: 100%; text-align: left; cursor: pointer; background: none; border: none; color: rgba(255,255,255,0.75);">
                    <i class="fas fa-right-from-bracket"></i>
                    {{ $locale === 'id' ? 'Keluar' : 'Logout' }}
                </button>
            </form>
        </nav>

        <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.72rem; opacity: 0.5; text-align: center;">
            SNN v1.0 © {{ date('Y') }}
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="dash-main">

        {{-- Header --}}
        <header class="dash-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="btn-icon" id="sidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem;">@yield('page-title', 'Dashboard')</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">@yield('breadcrumb', 'SNN Admin Panel')</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                {{-- Theme Toggle --}}
                <form method="POST" action="{{ route('theme.set', $theme === 'light' ? 'dark' : 'light') }}">
                    @csrf
                    <button type="submit" class="btn-icon" title="Toggle Theme">
                        <i class="fas {{ $theme === 'light' ? 'fa-moon' : 'fa-sun' }}"></i>
                    </button>
                </form>

                {{-- Lang Toggle --}}
                <form method="POST" action="{{ route('language.set', $locale === 'id' ? 'en' : 'id') }}">
                    @csrf
                    <button type="submit" class="btn-icon">
                        {{ $locale === 'id' ? '🇮🇩 ID' : '🇺🇸 EN' }}
                    </button>
                </form>

                {{-- User --}}
                <div style="display: flex; align-items: center; gap: 10px; padding-left: 12px; border-left: 1px solid var(--border);">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <div style="font-weight: 600; font-size: 0.825rem; line-height: 1.2;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: capitalize;">{{ auth()->user()->role }}</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="dash-alert dash-alert-success" id="dashAlert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div class="dash-alert dash-alert-error" id="dashAlert">
            <i class="fas fa-circle-exclamation"></i> {{ session('error') }}
            <button onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
        @endif

        {{-- Content --}}
        <div class="dash-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function toggleSidebar() {
    document.getElementById('dashSidebar').classList.toggle('open');
}
setTimeout(() => {
    const a = document.getElementById('dashAlert');
    if (a) { a.style.opacity = '0'; setTimeout(() => a?.remove(), 300); }
}, 4000);
</script>
@stack('scripts')
</body>
</html>
