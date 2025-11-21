<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')

    <!-- AlpineJS untuk dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between gap-4">

            <!-- Nama App -->
            <div class="text-xl font-semibold text-gray-800">
                {{ config('app.name', 'MyApp') }}
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-md">
                <form action="{{ route('home.index') }}" method="GET" class="max-w-5xl mx-auto">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari produk..."
                            value="{{ request('q') }}"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-300"
                        >
                        @if(request('category'))
                            {{-- pertahankan kategori kalau sudah dipilih --}}
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700"
                        >
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-2">
                <a href="#" 
                   class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Login
                </a>

                <a href="{{ route('register') }}" 
                   class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Register
                </a>
            </div>

        </div>
    </header>

    <!-- CONTENT WITH LOCATION DROPDOWN -->
    <main class="container mx-auto px-4 pt-6 pb-10 min-h-[60vh] relative">
        <div class="absolute right-4 top-2" x-data="{ open: false, province: 'Jawa Barat' }">
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

                // nilai default lokasi
                $currentProvince = request('province', 'All Location');
            @endphp

            <div class="absolute right-4 top-2" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="flex items-center gap-2 bg-white border px-3 py-1.5 rounded-lg shadow-sm hover:shadow-md transition text-sm"
                >
                    <span class="text-red-500">📍</span>
                    <span class="font-medium">{{ $currentProvince }}</span>
                    <span class="text-gray-500 text-xs">▼</span>
                </button>

                <div 
                    x-show="open" 
                    @click.outside="open = false"
                    x-transition
                    class="mt-2 w-48 max-h-64 overflow-y-auto bg-white border rounded-lg shadow absolute right-0 z-20"
                >
                    {{-- ALL LOCATION --}}
                    <a
                        href="{{ route('home.index', Arr::except(request()->query(), ['province'])) }}"
                        class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm
                            {{ request('province') ? '' : 'bg-gray-100 font-semibold' }}"
                    >
                        All Location
                    </a>

                    {{-- DAFTAR PROVINSI --}}
                    @foreach ($provinces as $prov)
                        <a 
                            href="{{ route('home.index', array_merge(request()->query(), ['province' => $prov])) }}"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm
                                {{ request('province') === $prov ? 'bg-gray-100 font-semibold' : '' }}"
                        >
                            {{ $prov }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- CONTENT SLOT -->
        <div>
          @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 bg-white border-t">
        <p class="text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.
        </p>
    </footer>

    @stack('scripts')
</body>
</html>
