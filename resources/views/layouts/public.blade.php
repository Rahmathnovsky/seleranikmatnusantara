@php
    $locale = session('locale', 'id');
    $theme  = session('theme', 'light');
    app()->setLocale($locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Selera Nikmat Nusantara')</title>
    <meta name="description" content="@yield('meta_description', 'Selera Nikmat Nusantara — Authentic Indonesian F&B Experience')">
    <meta name="keywords" content="@yield('meta_keywords', 'selera nikmat nusantara, kuliner indonesia, premium f&b, shem ramen, bakoel bamboe')">
    @yield('meta')
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand" style="display: flex; align-items: center;">
                <img src="{{ asset('images/logo-light.png') }}" class="logo-light" alt="Selera Nikmat Nusantara" style="height: 50px; width: auto; object-fit: contain;">
                <img src="{{ asset('images/logo-dark.png') }}" class="logo-dark" alt="Selera Nikmat Nusantara" style="height: 50px; width: auto; object-fit: contain;">
            </a>

            <ul class="navbar-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ $locale === 'id' ? 'Beranda' : 'Home' }}
                </a></li>
                <li><a href="{{ route('brands.index') }}" class="{{ request()->routeIs('brands.*') ? 'active' : '' }}">
                    {{ $locale === 'id' ? 'Brand' : 'Brands' }}
                </a></li>
                <li><a href="{{ route('promo.index') }}" class="{{ request()->routeIs('promo.*') ? 'active' : '' }}">
                    {{ $locale === 'id' ? 'Promo' : 'Promotions' }}
                </a></li>
                <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
                    {{ $locale === 'id' ? 'Blog' : 'Blog' }}
                </a></li>
                <li><a href="{{ route('career.index') }}" class="{{ request()->routeIs('career.*') ? 'active' : '' }}">
                    {{ $locale === 'id' ? 'Karir' : 'Career' }}
                </a></li>
            </ul>

            <div class="navbar-actions">
                {{-- Language Toggle --}}
                <div class="lang-toggle">
                    <form method="POST" action="{{ route('language.set', $locale === 'id' ? 'en' : 'id') }}">
                        @csrf
                        <button type="submit" class="btn-icon" title="Switch Language" style="display: flex; align-items: center; justify-content: center; padding: 4px; border-radius: 4px;">
                            <span class="lang-flag" style="display: inline-flex; border-radius: 2px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.2); width: 22px; height: 14px;">
                                <img src="{{ $locale === 'id' ? 'https://flagcdn.com/w40/id.png' : 'https://flagcdn.com/w40/us.png' }}" alt="{{ $locale }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </span>
                        </button>
                    </form>
                </div>

                {{-- Theme Toggle --}}
                <div class="theme-toggle">
                    <form method="POST" action="{{ route('theme.set', $theme === 'light' ? 'dark' : 'light') }}">
                        @csrf
                        <button type="submit" class="btn-icon" id="themeToggle" title="Toggle Theme">
                            <i class="fas {{ $theme === 'light' ? 'fa-moon' : 'fa-sun' }}"></i>
                        </button>
                    </form>
                </div>

                {{-- Auth --}}
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-sm">
                        {{ $locale === 'id' ? 'Masuk' : 'Login' }}
                    </a>
                @else
                    <div class="user-menu" x-data="{ open: false }">
                        <button @click="open = !open" class="user-avatar-btn">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar-img">
                            <span class="user-name-short">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" x-show="open" @click.away="open = false" x-transition>
                            @if(auth()->user()->isBackOffice())
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-gauge-high"></i> Dashboard
                            </a>
                            @endif
                            <a href="{{ route('promo.my-vouchers') }}" class="dropdown-item">
                                <i class="fas fa-ticket"></i> {{ $locale === 'id' ? 'Voucher Saya' : 'My Vouchers' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item danger">
                                    <i class="fas fa-right-from-bracket"></i> {{ $locale === 'id' ? 'Keluar' : 'Logout' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

                {{-- Mobile Hamburger --}}
                <button class="hamburger" id="hamburger" onclick="toggleMenu()">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="toast-container">
        <div class="toast toast-success" id="flashToast">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast-container">
        <div class="toast toast-error" id="flashToast">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="footer-top">
            <div class="container footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo" style="display: flex; align-items: center; margin-bottom: 20px;">
                        <img src="{{ asset('images/logo-light.png') }}" class="logo-light" alt="Selera Nikmat Nusantara" style="height: 60px; width: auto; object-fit: contain;">
                        <img src="{{ asset('images/logo-dark.png') }}" class="logo-dark" alt="Selera Nikmat Nusantara" style="height: 60px; width: auto; object-fit: contain;">
                    </div>
                    <p class="footer-desc">
                        {{ $locale === 'id'
                            ? 'Menghadirkan cita rasa otentik Nusantara dengan pengalaman kuliner yang tak terlupakan.'
                            : 'Bringing authentic Nusantara flavors with unforgettable culinary experiences.' }}
                    </p>
                    <div class="footer-socials">
                        @if(\App\Models\SiteSetting::get('social_instagram'))
                        <a href="{{ \App\Models\SiteSetting::get('social_instagram') }}" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(\App\Models\SiteSetting::get('social_tiktok'))
                        <a href="{{ \App\Models\SiteSetting::get('social_tiktok') }}" target="_blank" class="social-link"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if(\App\Models\SiteSetting::get('social_facebook'))
                        <a href="{{ \App\Models\SiteSetting::get('social_facebook') }}" target="_blank" class="social-link"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if(\App\Models\SiteSetting::get('social_youtube'))
                        <a href="{{ \App\Models\SiteSetting::get('social_youtube') }}" target="_blank" class="social-link"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>

                <div class="footer-links">
                    <h4>{{ $locale === 'id' ? 'Navigasi' : 'Navigation' }}</h4>
                    <ul>
                        <li><a href="{{ route('brands.index') }}">{{ $locale === 'id' ? 'Brand Kami' : 'Our Brands' }}</a></li>
                        <li><a href="{{ route('promo.index') }}">{{ $locale === 'id' ? 'Promo & Diskon' : 'Promotions' }}</a></li>
                        <li><a href="{{ route('blog.index') }}">{{ $locale === 'id' ? 'Blog & Artikel' : 'Blog & Articles' }}</a></li>
                        <li><a href="{{ route('career.index') }}">{{ $locale === 'id' ? 'Lowongan Kerja' : 'Career' }}</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>{{ $locale === 'id' ? 'Hubungi Kami' : 'Contact Us' }}</h4>
                    <ul>
                        @if(\App\Models\SiteSetting::get('contact_phone'))
                        <li><a href="tel:{{ \App\Models\SiteSetting::get('contact_phone') }}">
                            <i class="fas fa-phone"></i> {{ \App\Models\SiteSetting::get('contact_phone') }}
                        </a></li>
                        @endif
                        @if(\App\Models\SiteSetting::get('contact_email'))
                        <li><a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}">
                            <i class="fas fa-envelope"></i> {{ \App\Models\SiteSetting::get('contact_email') }}
                        </a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} Selera Nikmat Nusantara. {{ $locale === 'id' ? 'Hak Cipta Dilindungi.' : 'All Rights Reserved.' }}</p>
            </div>
        </div>
    </footer>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('open');
            document.getElementById('hamburger').classList.toggle('active');
        }

        // Auto-dismiss toast
        setTimeout(() => {
            const toast = document.getElementById('flashToast');
            if (toast) toast.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => toast?.remove(), 300);
        }, 4000);
    </script>

    @stack('scripts')
</body>
</html>
