<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- AlpineJS untuk dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
</head>

<body class="bg-gray-100">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between gap-4">

            <!-- Nama App -->
            <div class="font-pacifico text-2xl font-semibold text-green-600">
                <a href="{{ route('home') }}">
                    {{ config('app.name', 'MyApp') }}
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl">
                <form action="{{ route('search') }}" method="GET" class="w-full">
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari produk..."
                            value="{{ request('q') }}"
                            class="w-full border border-gray-300 rounded-full pl-12 pr-4 py-2.5 text-sm
                                  text-gray-700
                                  focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500
                                  transition"
                        >

                        <!-- SVG Icon (Kaca Pembesar) -->
                        <svg 
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="2" 
                            viewBox="0 0 24 24"
                        >
                            <path 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61Z"
                            />
                        </svg>
                    </div>

                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </form>
            </div>


            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
              <a href="#"
                class="px-6 py-2 text-sm font-medium rounded-full
                        border border-green-500 text-green-500 bg-white
                        hover:bg-green-50">
                  Login
              </a>

              <a href="{{ route('register') }}"
                class="px-6 py-2 text-sm font-medium rounded-full
                        bg-green-500 text-white
                        hover:bg-green-600">
                  Register
              </a>
          </div>

        </div>
    </header>

    <!-- CONTENT WITH LOCATION DROPDOWN -->
    <main class="w-full px-4 pt-6 pb-10 min-h-[60vh] relative">
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
              <!-- BUTTON DROPDOWN -->
              <button 
                  @click="open = !open"
                  class="inline-flex items-center gap-2 px-2 py-1 bg-transparent text-sm text-gray-700 hover:text-gray-900 whitespace-nowrap"
              >
                  <!-- ICON LOKASI (ABU-ABU) -->
                  <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm0 9.5s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                  </svg>

                  <!-- TEKS PROVINSI (1 BARIS, ELLIPSIS JIKA KEPANJANGAN) -->
                  <span class="max-w-[140px] overflow-hidden text-ellipsis whitespace-nowrap">
                      {{ $currentProvince }}
                  </span>

                  <!-- ICON PANAH (ABU-ABU) -->
                  <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
              </button>

              <!-- DROPDOWN LIST TETAP SAMA -->
              <div 
                  x-show="open" 
                  @click.outside="open = false"
                  x-transition
                  class="mt-2 w-48 max-h-64 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow absolute right-0 z-20"
              >
                  <a
                      href="{{ route('search', Arr::except(request()->query(), ['province'])) }}"
                      class="block px-4 py-2 text-sm hover:bg-gray-100
                          {{ request('province') ? '' : 'bg-gray-100 font-semibold' }}"
                  >
                      All Location
                  </a>

                  @foreach ($provinces as $prov)
                      <a 
                          href="{{ route('search', array_merge(request()->query(), ['province' => $prov])) }}"
                          class="block px-4 py-2 text-sm hover:bg-gray-100
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
    <footer class="py-4 bg-white border-t border-gray-200">
        <p class="text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.
        </p>
    </footer>

    @stack('scripts')
</body>
</html>