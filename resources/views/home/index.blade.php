@php
    use Illuminate\Support\Collection;

    $categories = collect([
        (object) ['id' => 1, 'name' => 'Elektronik', 'products_count' => 58],
        (object) ['id' => 2, 'name' => 'Fashion', 'products_count' => 45],
        (object) ['id' => 3, 'name' => 'Olahraga', 'products_count' => 22],
        (object) ['id' => 4, 'name' => 'Kecantikan', 'products_count' => 37],
        (object) ['id' => 5, 'name' => 'Rumah Tangga', 'products_count' => 19],
        (object) ['id' => 6, 'name' => 'Aksesoris', 'products_count' => 24],
    ]);
@endphp

@php
    // Contoh data banner (boleh diganti dari DB)
    $banners = [
        'https://picsum.photos/id/1015/1200/400',
        'https://picsum.photos/id/1025/1200/400',
        'https://picsum.photos/id/1035/1200/400',
    ];

    // Contoh data category spesifik (gambar di atas, teks di bawah)
    $specificCategories = [
        [
            'name' => 'Laptop',
            'image' => 'https://picsum.photos/id/180/400/250',
        ],
        [
            'name' => 'Smartphone',
            'image' => 'https://picsum.photos/id/250/400/250',
        ],
        [
            'name' => 'Camera',
            'image' => 'https://picsum.photos/id/301/400/250',
        ],
        [
            'name' => 'Camera',
            'image' => 'https://picsum.photos/id/301/400/250',
        ],
        [
            'name' => 'Camera',
            'image' => 'https://picsum.photos/id/301/400/250',
        ],
        [
            'name' => 'Camera',
            'image' => 'https://picsum.photos/id/301/400/250',
        ]
    ];
@endphp

@extends('layouts.app')

@section('content')
    {{-- BANNER SLIDER --}}
    <div class="w-full flex justify-center mb-8 mt-12">
        <div class="relative w-full max-w-7xl overflow-hidden rounded-xl shadow" id="banner-slider">

            {{-- Track semua slide --}}
            <div class="flex transition-transform duration-500 ease-out" id="banner-track">
                @foreach ($banners as $banner)
                    <div class="w-full flex-shrink-0">
                        <img
                            src="{{ $banner }}"
                            alt="Banner {{ $loop->iteration }}"
                            class="w-full h-64 md:h-80 object-cover"
                        >
                    </div>
                @endforeach
            </div>

            {{-- Tombol Kiri --}}
            <button
                type="button"
                id="banner-prev"
                class="banner-nav-btn left-3"
                aria-label="Sebelumnya"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Tombol Kanan --}}
            <button
                type="button"
                id="banner-next"
                class="banner-nav-btn right-3"
                aria-label="Berikutnya"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- DOT INDICATORS --}}
            <div id="banner-dots" class="absolute bottom-3 left-3 flex gap-1.5 z-10">
                @foreach ($banners as $banner)
                    <span class="w-2.5 h-2.5 rounded-full bg-white/40 backdrop-blur-sm"></span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KATEGORI PILIHAN (ala Tokopedia) --}}
    <section class="max-w-7xl mx-auto mb-10">
        <div class="bg-white rounded-2xl shadow-md p-5 space-y-5">

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">
                    Selected Category
                </h2>
                <a href="#" class="text-xs sm:text-sm text-green-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @foreach ($specificCategories as $item)
                    <div
                        class="bg-white rounded-xl border border-gray-200
                              hover:shadow-sm flex flex-col items-center p-3 cursor-pointer
                              transition-all duration-200"
                    >
                        <div class="w-20 h-20 sm:w-24 sm:h-24 mb-2">
                            <img
                                src="{{ $item['image'] }}"
                                alt="{{ $item['name'] }}"
                                class="w-full h-full object-cover rounded-lg"
                            >
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-gray-800 text-center leading-tight">
                            {{ $item['name'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="pt-3">
              <div class="flex flex-wrap gap-2 sm:gap-3 justify-center">

                  {{-- Chip "Semua Kategori" --}}
                  <a
                      href="{{ route('home', [
                          'q'        => request('q'),
                          'province' => request('province'),
                      ]) }}"
                      class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm
                            border border-gray-200 bg-white text-gray-800 shadow-sm
                            hover:bg-gray-50 transition-all duration-200"
                  >
                      <span class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 text-gray-500">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h6" />
                          </svg>
                      </span>
                      <span class="font-medium whitespace-nowrap">Kategori</span>
                  </a>

                  {{-- Chip kategori dari DB --}}
                  @foreach ($categories as $category)
                      @php
                          $active = (int) request('category') === $category->id;
                      @endphp

                      <a
                          href="{{ route('home', [
                              'category' => $category->id,
                              'q'        => request('q'),
                              'province' => request('province'),
                          ]) }}"
                          class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm
                                shadow-sm transition-all duration-200

                                {{-- ACTIVE: Hijau premium (jika ingin putih juga bisa saya ubah) --}}
                                {{ $active
                                      ? 'bg-green-600 text-white border-green-600 hover:bg-green-700'
                                      : 'bg-white text-gray-800 border border-gray-200 hover:bg-gray-50' }}"
                      >

                          <span class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                              {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                          </span>

                          <span class="font-medium whitespace-nowrap">{{ $category->name }}</span>

                          @if(isset($category->products_count))
                              <span class="text-[11px] text-gray-400">({{ $category->products_count }})</span>
                          @endif

                      </a>
                  @endforeach

              </div>
            </div>

        </div>
    </section>

    {{-- PRODUCT SECTION --}}
    <section class="max-w-7xl mx-auto space-y-4 mb-2">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Product</h2>

                @if(request('q') || request('category') || request('province'))
                    <p class="text-xs text-gray-500 mt-1">
                        Filter:
                        @if(request('q'))
                            <span>cari "<strong>{{ request('q') }}</strong>"</span>
                        @endif
                        @if(request('category') && $categories->where('id', request('category'))->first())
                            @php
                                $activeCat = $categories->where('id', request('category'))->first();
                            @endphp
                            <span> • kategori "<strong>{{ $activeCat->name }}</strong>"</span>
                        @endif
                        @if(request('province'))
                            <span> • lokasi "<strong>{{ request('province') }}</strong>"</span>
                        @endif
                    </p>
                @endif
            </div>

            <a href="{{ route('home') }}" class="text-sm text-green-600 hover:underline">
                Reset filter
            </a>
        </div>

        {{-- Grid produk: max 6 kolom, tumbuh ke bawah --}}
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @if($products->count() > 0)
              @include('components.product-cards', ['products' => $products])
            @else
                <p class="text-gray-500 text-sm col-span-full">
                    Tidak ada produk yang cocok dengan filter.
                </p>
            @endif
        </div>

        @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasMorePages())
            <div class="flex justify-center mt-6">
                <button
                    id="load-more"
                    class="px-6 py-2 text-sm font-medium 
                          bg-white text-green-600 
                          border border-green-600 
                          rounded-full shadow-sm
                          hover:bg-green-50 hover:shadow-md 
                          transition"
                    data-next-page="{{ $products->currentPage() + 1 }}"
                >
                    Show more
                </button>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const track    = document.getElementById('banner-track');
    const prevBtn  = document.getElementById('banner-prev');
    const nextBtn  = document.getElementById('banner-next');
    const slides   = track.children;
    const total    = slides.length;

    const dotsContainer = document.getElementById('banner-dots');
    const dots = dotsContainer.children;

    let index = 0;
    let autoSlide = null;

    function updateSlide() {
        track.style.transform = `translateX(-${index * 100}%)`;
        updateDots();
    }

    function updateDots() {
        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.remove('bg-white');
            dots[i].classList.remove('opacity-100');

            dots[i].classList.add('bg-white/40'); // default
        }

        dots[index].classList.remove('bg-white/40');
        dots[index].classList.add('bg-white');     // dot aktif
        dots[index].classList.add('opacity-100');
    }

    function startAutoSlide() {
        if (total <= 1) return;

        if (autoSlide) clearInterval(autoSlide);

        autoSlide = setInterval(() => {
            index = (index + 1) % total;
            updateSlide();
        }, 5000);
    }

    if (total > 1) startAutoSlide();
    updateDots(); // inisialisasi dot pertama

    prevBtn.addEventListener('click', function () {
        index = (index - 1 + total) % total;
        updateSlide();
        startAutoSlide();
    });

    nextBtn.addEventListener('click', function () {
        index = (index + 1) % total;
        updateSlide();
        startAutoSlide();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
                index = (index - 1 + total) % total;
                updateSlide();
                startAutoSlide();
            } else if (e.key === 'ArrowRight') {
                index = (index + 1) % total;
                updateSlide();
                startAutoSlide();
            }
        });

    const loadMoreBtn = document.getElementById('load-more');
    const productGrid = document.getElementById('product-grid');

    if (loadMoreBtn && productGrid) {
        loadMoreBtn.addEventListener('click', function () {
            const button = this;
            const nextPage = button.dataset.nextPage;

            // optional: state loading
            button.disabled = true;
            button.textContent = 'Loading...';

            const url = new URL("{{ route('home') }}", window.location.origin);
            url.searchParams.set('page', nextPage);

            @if(request('q'))
                url.searchParams.set('q', "{{ request('q') }}");
            @endif
            @if(request('category'))
                url.searchParams.set('category', "{{ request('category') }}");
            @endif
            @if(request('province'))
                url.searchParams.set('province', "{{ request('province') }}");
            @endif

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                productGrid.insertAdjacentHTML('beforeend', data.html);

                if (data.has_more) {
                    button.dataset.nextPage = data.next_page;
                    button.disabled = false;
                    button.textContent = 'Show more';
                } else {
                    button.remove();
                }
            })
            .catch(err => {
                console.error(err);
                button.disabled = false;
                button.textContent = 'Show more';
            });
        });
    }
    });
  </script>
@endpush