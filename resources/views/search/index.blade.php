@extends('layouts.app')

@push('styles')
<style>
/* Hilangkan spinner di Chrome, Safari, Edge */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Hilangkan spinner di Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">

    {{-- ================= SIDEBAR FILTER (KIRI) ================= --}}
    <aside class="bg-white rounded-2xl shadow-md p-5 space-y-6 h-fit">

        {{-- FILTER KATEGORI --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Kategori</h2>
            <div class="mt-3 max-h-64 overflow-y-auto pr-1 text-xs sm:text-sm">
              @php
                  // Group semua kategori berdasarkan parent_id
                  $groupedCategories = $categories->groupBy('parent_id');
                  $activeCategoryId  = (int) request('category');
              @endphp

              {{-- Root category = parent_id = null --}}
              <div class="space-y-1">
                  @foreach ($groupedCategories[null] ?? [] as $rootCategory)
                      @include('components.category-node', [
                          'category'         => $rootCategory,
                          'groupedCategories'=> $groupedCategories,
                          'level'            => 0,
                          'activeCategoryId' => $activeCategoryId,
                      ])
                  @endforeach
              </div>
          </div>
        </div>

        {{-- FILTER HARGA --}}
        <div class="pt-2 border-t border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-3 mt-3">Harga</h2>

            <form action="{{ route('search') }}" method="GET" class="space-y-3">
                {{-- Pertahankan query lain --}}
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="province" value="{{ request('province') }}">
                <input type="hidden" name="rating" value="{{ request('rating') }}">

                <div class="flex items-center gap-2">
                    <input
                        type="number"
                        name="min_price"
                        placeholder="Min"
                        value="{{ request('min_price') }}"
                        class="w-full border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring focus:ring-green-200"
                    >
                    <span class="text-gray-400">-</span>
                    <input
                        type="number"
                        name="max_price"
                        placeholder="Max"
                        value="{{ request('max_price') }}"
                        class="w-full border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring focus:ring-green-200"
                    >
                </div>

                <button type="submit"
                        class="w-full py-2 text-xs sm:text-sm font-medium rounded-lg
                               bg-green-600 text-white hover:bg-green-700 transition">
                    Terapkan
                </button>
            </form>
        </div>

        {{-- FILTER LOKASI TOKO --}}
        <div class="pt-2 border-t border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-3 mt-3">Lokasi Toko</h2>

            <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1 text-xs sm:text-sm">
                {{-- Semua lokasi --}}
                <a href="{{ route('search', array_merge(request()->query(), ['province' => null])) }}"
                   class="block px-2 py-1 rounded hover:bg-gray-50
                          {{ request('province') ? 'text-gray-700' : 'font-semibold text-green-600 bg-green-50' }}">
                    Semua lokasi
                </a>

                @foreach ($locations ?? [] as $loc)
                    <a href="{{ route('search', array_merge(request()->query(), ['province' => $loc])) }}"
                       class="block px-2 py-1 rounded hover:bg-gray-50
                              {{ request('province') === $loc ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">
                        {{ $loc }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- FILTER RATING --}}
        <div class="pt-2 border-t border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-3 mt-3">Rating</h2>

            <div class="flex flex-wrap gap-2">
                {{-- Semua --}}
                <a href="{{ route('search', array_merge(request()->query(), ['rating' => null])) }}"
                   class="px-3 py-1.5 rounded-full text-xs sm:text-sm border
                          {{ request('rating') ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                               : 'bg-green-600 text-white border-green-600' }}">
                    Semua
                </a>

                {{-- 5 -> 1 bintang --}}
                @for($star = 5; $star >= 1; $star--)
                    <a href="{{ route('search', array_merge(request()->query(), ['rating' => $star])) }}"
                       class="px-3 py-1.5 rounded-full text-xs sm:text-sm border inline-flex items-center gap-1
                              {{ (int)request('rating') === $star
                                    ? 'bg-green-600 text-white border-green-600'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                        <span>{{ $star }}</span>
                        <span class="text-yellow-400">★</span>
                    </a>
                @endfor
            </div>
        </div>

    </aside>

    {{-- ================= HASIL PENCARIAN (KANAN) ================= --}}
    <section class="md:col-span-3 space-y-4">

        {{-- Header & Ringkasan filter --}}
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Pencarian</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    @if(request('q'))
                        Kata kunci: <strong>"{{ request('q') }}"</strong>
                    @endif

                    @if(request('category') && $categories->where('id', request('category'))->first())
                        @php $activeCat = $categories->where('id', request('category'))->first(); @endphp
                        • Kategori: <strong>{{ $activeCat->name }}</strong>
                    @endif

                    @if(request('province'))
                        • Lokasi: <strong>{{ request('province') }}</strong>
                    @endif

                    @if(request('min_price') || request('max_price'))
                        • Harga:
                        <strong>
                            {{ request('min_price') ? 'Rp '.number_format(request('min_price'),0,',','.') : '0' }}
                            -
                            {{ request('max_price') ? 'Rp '.number_format(request('max_price'),0,',','.') : '∞' }}
                        </strong>
                    @endif

                    @if(request('rating'))
                        • Rating min: <strong>{{ request('rating') }} ★</strong>
                    @endif
                </p>
            </div>

            <a href="{{ route('search') }}" class="text-xs sm:text-sm text-green-600 hover:underline">
                Reset semua filter
            </a>
        </div>

        {{-- Grid produk --}}
        <div id="product-grid"
             class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-4 gap-4">
            @if($products->count() > 0)
                @include('components.product-cards', ['products' => $products])
            @else
                <p class="text-gray-500 text-sm col-span-full">
                    Tidak ada produk yang cocok dengan filter.
                </p>
            @endif
        </div>

        {{-- Load More Button (AJAX Pagination) --}}
        @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasMorePages())
            <div class="mt-8 flex justify-center">
                <button id="load-more-btn"
                        data-next-page="{{ $products->currentPage() + 1 }}"
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-200
                               flex items-center gap-2 justify-center">
                    <span>Muat Lebih Banyak</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>
            </div>

            {{-- Loading Spinner --}}
            <div id="loading-spinner" class="mt-8 flex justify-center hidden">
                <div class="flex items-center gap-2">
                    <div class="animate-spin">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="text-gray-600 font-medium">Memuat produk...</span>
                </div>
            </div>
        @endif
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const productGrid = document.getElementById('product-grid');

    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', function() {
        const nextPage = loadMoreBtn.getAttribute('data-next-page');
        const queryString = window.location.search;

        // Show spinner, hide button
        loadMoreBtn.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

        // Fetch AJAX
        fetch(`{{ route('search') }}?page=${nextPage}${queryString ? '&' + queryString.substring(1) : ''}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Append produk baru ke grid
            productGrid.insertAdjacentHTML('beforeend', data.html);

            // Update next page
            loadMoreBtn.setAttribute('data-next-page', data.next_page);

            // Jika tidak ada halaman lanjutan, hide button
            if (!data.has_more) {
                loadMoreBtn.classList.add('hidden');
            } else {
                loadMoreBtn.classList.remove('hidden');
            }

            // Hide spinner
            loadingSpinner.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadMoreBtn.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            alert('Gagal memuat produk. Silakan coba lagi.');
        });
    });
});
</script>
@endsection
