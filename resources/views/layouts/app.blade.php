<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Script tema (localStorage + preferensi OS) --}}
    <script>
        (function () {
            const html = document.documentElement;
            const stored = localStorage.getItem("theme");

            if (stored === "dark") {
                html.classList.add("dark");
            } else if (stored === "light") {
                html.classList.remove("dark");
            } else {
                if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                    html.classList.add("dark");
                }
            }

            // Toggle + animasi
            window.toggleTheme = function () {
                const html = document.documentElement;

                // aktifkan animasi transisi warna sementara
                html.classList.add("theme-transition");
                setTimeout(() => html.classList.remove("theme-transition"), 300);

                const isDark = html.classList.toggle("dark");
                localStorage.setItem("theme", isDark ? "dark" : "light");
            }
        })();
    </script>
</head>

<body
    class="min-h-screen theme-transition
           bg-gray-50 text-gray-900
           dark:bg-gradient-to-b dark:from-slate-950 dark:via-slate-900 dark:to-emerald-950/90
           dark:text-slate-50"
>
    <!-- HEADER -->
    <header
        class="theme-transition sticky top-0 z-40
               bg-white/95 border-b border-gray-200 shadow-sm
               dark:bg-slate-950/60 dark:border-white/10 dark:shadow-xl
               backdrop-blur-xl"
    >
        <div class="max-w-7xl mx-auto px-4 py-3 
                    flex items-center justify-between gap-6">

            <!-- LEFT: LOGO -->
            <div class="flex-shrink-0 font-pacifico text-2xl font-semibold 
                        text-green-600 dark:text-emerald-300 drop-shadow">
                <a href="{{ route('home') }}">
                    {{ config('app.name', 'MyApp') }}
                </a>
            </div>

            <!-- MIDDLE: SEARCH BAR (SEMBUNYI DI HALAMAN HOME) -->
            @if (!request()->routeIs('home'))
                <div class="flex-1 flex justify-center">
                    <form action="{{ route('search') }}" method="GET" class="w-full max-w-xl">
                        <div class="relative">
                            <input
                                type="text"
                                name="q"
                                placeholder="Cari produk, jasa, atau layanan..."
                                value="{{ request('q') }}"
                                class="w-full rounded-full pl-12 pr-4 py-2.5 text-sm theme-transition
                                    bg-white border border-gray-300
                                    text-gray-800 placeholder:text-gray-400
                                    focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500
                                    transition

                                    dark:bg-white/5 dark:border-white/15
                                    dark:text-slate-100 dark:placeholder:text-slate-400
                                    dark:focus:ring-emerald-400 dark:focus:border-emerald-400"
                            >

                            <!-- icon search -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 
                                    w-5 h-5 text-gray-400 dark:text-emerald-300/80"
                                fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61Z"/>
                            </svg>
                        </div>

                        {{-- Kirim semua filter aktif --}}
                        @foreach (['category','province','min_price','max_price','rating'] as $filter)
                            @if (request($filter))
                                <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                            @endif
                        @endforeach
                    </form>
                </div>
            @else
                {{-- Supaya header tetap seimbang di /home, kasih spacer kecil --}}
                <div class="flex-1"></div>
            @endif

            <!-- RIGHT: TOGGLE + AUTH BUTTONS -->
            <div class="flex items-center gap-3 flex-shrink-0">

                <!-- THEME SWITCH — MORPHING SUN <-> MOON -->
                <button onclick="toggleTheme()"
                    class="relative w-14 h-8 flex items-center rounded-full
                           bg-gray-300 dark:bg-slate-800
                           border border-gray-400/60 dark:border-emerald-400/40
                           transition-all duration-500 ease-out overflow-hidden">

                    <!-- BACKGROUND RAYS for sun -->
                    <span class="sun-rays absolute inset-0 flex justify-center items-center
                                pointer-events-none transition-all duration-500
                                opacity-100 dark:opacity-0">
                        <span class="w-10 h-10 bg-yellow-400/40 rounded-full blur-xl"></span>
                    </span>

                    <!-- MORPHING HANDLE (DIPINDAH SEDIKIT DARI BORDER KIRI) -->
                    <span class="morph-icon absolute left-1 top-1/2 -translate-y-1/2
                                w-6 h-6 bg-yellow-400 rounded-full shadow-md
                                flex items-center justify-center
                                transition-all duration-500 ease-out
                                
                                dark:bg-slate-200 
                                dark:translate-x-6
                                dark:rounded-[50%_50%_40%_60%/40%_60%_50%_50%]  {{-- moon shape --}}
                                dark:shadow-[0_0_12px_rgba(16,185,129,0.5)]
                                dark:rotate-12">
                        
                        <!-- VISIBLE SUN DOT -->
                        <span class="sun-dot block w-2 h-2 bg-yellow-200 rounded-full
                                    transition-all duration-500 dark:scale-0"></span>

                        <!-- VISIBLE MOON CUTOUT -->
                        <span class="moon-cut absolute block w-4 h-4 bg-slate-800 dark:bg-transparent
                                    rounded-full -right-1 -top-1 opacity-0
                                    transition-all duration-500
                                    dark:opacity-100"></span>
                    </span>

                </button>

                <a href="{{ route('seller.login') }}"
                   class="theme-transition px-5 py-2 text-sm font-medium rounded-full
                          border border-green-500 text-green-600 
                          bg-white hover:bg-green-50
                          transition

                          dark:border-emerald-300/80 dark:text-emerald-100 
                          dark:bg-white/5 dark:hover:bg-emerald-500/10 dark:hover:border-emerald-300
                          dark:backdrop-blur-xl">
                    Masuk
                </a>

                <a href="{{ route('register') }}"
                   class="theme-transition px-5 py-2 text-sm font-medium rounded-full
                          bg-green-500 text-white hover:bg-green-600
                          transition

                          dark:bg-emerald-500 dark:hover:bg-emerald-600
                          dark:shadow-[0_0_25px_rgba(16,185,129,0.5)]
                          dark:hover:shadow-[0_0_35px_rgba(16,185,129,0.7)]">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT WITH LOCATION DROPDOWN -->
    <main class="w-full pt-4 pb-10 min-h-[60vh] relative">
        @php
            $provinces = [
                'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi',
                'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kep. Bangka Belitung',
                'Kep. Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta',
                'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat',
                'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
                'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
                'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan',
                'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku',
                'Maluku Utara', 'Papua', 'Papua Barat'
            ];

            $currentProvince = request('province', 'All Location');
        @endphp

        <!-- DROPDOWN LOKASI (FLOATING CHIP) -->
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-end">
                <div class="relative" x-data="{ open: false }">
                    <!-- BUTTON DROPDOWN -->
                    <button 
                        @click="open = !open"
                        class="theme-transition inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                               bg-white border border-gray-300
                               text-xs sm:text-sm text-gray-800
                               hover:bg-gray-50
                               shadow-sm transition whitespace-nowrap

                               dark:bg-white/5 dark:border-white/20
                               dark:text-slate-100
                               dark:hover:bg-white/10 dark:hover:border-emerald-300/70
                               dark:backdrop-blur-xl"
                    >
                        <svg class="w-4 h-4 text-gray-500 dark:text-emerald-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm0 9.5s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                        </svg>

                        <span class="max-w-[140px] overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ $currentProvince }}
                        </span>

                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- DROPDOWN LIST -->
                    <div 
                        x-show="open" 
                        @click.outside="open = false"
                        x-transition
                        class="mt-2 w-52 max-h-64 overflow-y-auto 
                               bg-white border border-gray-200 
                               rounded-xl shadow-xl absolute right-0 z-20

                               dark:bg-slate-950/95 dark:border-white/15 
                               dark:backdrop-blur-xl"
                    >
                        <a
                            href="{{ route('search', Arr::except(request()->query(), ['province'])) }}"
                            class="block px-4 py-2.5 text-xs sm:text-sm theme-transition
                                   hover:bg-gray-100
                                   {{ request('province') ? '' : 'bg-gray-100 font-semibold' }}
                                   dark:hover:bg-emerald-500/15 dark:text-slate-100
                                   {{ request('province') ? '' : 'dark:bg-emerald-500/20 dark:font-semibold dark:text-emerald-100' }}"
                        >
                            All Location
                        </a>

                        @foreach ($provinces as $prov)
                            <a 
                                href="{{ route('search', array_merge(request()->query(), ['province' => $prov])) }}"
                                class="block px-4 py-2.5 text-xs sm:text-sm theme-transition
                                       hover:bg-gray-100
                                       {{ request('province') === $prov ? 'bg-gray-100 font-semibold' : '' }}
                                       dark:hover:bg-emerald-500/15 dark:text-slate-100
                                       {{ request('province') === $prov ? 'dark:bg-emerald-500/20 dark:font-semibold dark:text-emerald-100' : '' }}"
                            >
                                {{ $prov }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT SLOT -->
        <div class="-mt-16">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer
        class="theme-transition py-4 border-t bg-white border-gray-200
               text-gray-600 text-xs sm:text-sm
               dark:bg-slate-950/70 dark:border-white/10 dark:text-slate-400 dark:backdrop-blur-xl"
    >
        <p class="text-center">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.
        </p>
    </footer>

    @stack('scripts')
</body>
</html>
