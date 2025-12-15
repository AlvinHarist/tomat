@extends('layouts.app')

@push('styles')
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">

    {{-- ===================== SIDEBAR FILTER ===================== --}}
    <aside class="rounded-2xl border border-gray-200/70 bg-white shadow-sm p-5 space-y-6 h-fit
                  dark:border-white/10 dark:bg-white/5 dark:backdrop-blur-xl dark:shadow-2xl">

        {{-- ========== FILTER KATEGORI ========== --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Kategori</h2>

            <div class="max-h-64 overflow-y-auto pr-1 text-xs sm:text-sm space-y-1">
                @php
                    $groupedCategories = $categories->groupBy('parent_id');
                    $activeCategoryId  = (int) request('category');
                @endphp

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

        {{-- ========== FILTER HARGA ========== --}}
        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Harga</h2>

            <form action="{{ route('search') }}" method="GET" class="space-y-3">
                {{-- Pertahankan query --}}
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="province" value="{{ request('province') }}">
                <input type="hidden" name="rating" value="{{ request('rating') }}">

                <div class="flex items-center gap-2">
                    <input
                        type="number" name="min_price"
                        placeholder="Min"
                        value="{{ request('min_price') }}"
                        class="w-full rounded-lg px-3 py-2 text-xs sm:text-sm
                               border border-gray-300 bg-white
                               focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300
                               dark:bg-white/5 dark:border-white/15 dark:text-white">
                    <span class="text-gray-400 dark:text-slate-300">-</span>

                    <input
                        type="number" name="max_price"
                        placeholder="Max"
                        value="{{ request('max_price') }}"
                        class="w-full rounded-lg px-3 py-2 text-xs sm:text-sm
                               border border-gray-300 bg-white
                               focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300
                               dark:bg-white/5 dark:border-white/15 dark:text-white">
                </div>

                <button type="submit"
                        class="w-full py-2 rounded-lg text-xs sm:text-sm font-medium
                               bg-emerald-600 text-white hover:bg-emerald-700 transition">
                    Terapkan
                </button>
            </form>
        </div>

        {{-- ========== FILTER LOKASI ========== --}}
        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Lokasi Toko</h2>

            <form action="{{ route('search') }}" method="GET" class="space-y-2 text-xs sm:text-sm">
                {{-- Pertahankan filter lain --}}
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                <input type="hidden" name="rating" value="{{ request('rating') }}">

                <select
                    name="province"
                    onchange="this.form.submit()"
                    class="w-full rounded-lg px-3 py-2
                        border border-gray-300 bg-white
                        focus:ring-2 focus:ring-emerald-300
                        dark:bg-white/5 dark:border-white/15 dark:text-white"
                >
                    <option value="" {{ request('province') ? '' : 'selected' }}>
                        Semua lokasi
                    </option>

                    {{-- 38 Provinsi (manual) --}}
                    <option value="Aceh" {{ request('province') === 'Aceh' ? 'selected' : '' }}>Aceh</option>
                    <option value="Sumatera Utara" {{ request('province') === 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                    <option value="Sumatera Barat" {{ request('province') === 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                    <option value="Riau" {{ request('province') === 'Riau' ? 'selected' : '' }}>Riau</option>
                    <option value="Jambi" {{ request('province') === 'Jambi' ? 'selected' : '' }}>Jambi</option>
                    <option value="Sumatera Selatan" {{ request('province') === 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                    <option value="Bengkulu" {{ request('province') === 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                    <option value="Lampung" {{ request('province') === 'Lampung' ? 'selected' : '' }}>Lampung</option>
                    <option value="Kepulauan Bangka Belitung" {{ request('province') === 'Kepulauan Bangka Belitung' ? 'selected' : '' }}>Kepulauan Bangka Belitung</option>
                    <option value="Kepulauan Riau" {{ request('province') === 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>

                    <option value="DKI Jakarta" {{ request('province') === 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                    <option value="Jawa Barat" {{ request('province') === 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                    <option value="Jawa Tengah" {{ request('province') === 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                    <option value="D.I. Yogyakarta" {{ request('province') === 'D.I. Yogyakarta' ? 'selected' : '' }}>D.I. Yogyakarta</option>
                    <option value="Jawa Timur" {{ request('province') === 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    <option value="Banten" {{ request('province') === 'Banten' ? 'selected' : '' }}>Banten</option>

                    <option value="Bali" {{ request('province') === 'Bali' ? 'selected' : '' }}>Bali</option>
                    <option value="Nusa Tenggara Barat" {{ request('province') === 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                    <option value="Nusa Tenggara Timur" {{ request('province') === 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>

                    <option value="Kalimantan Barat" {{ request('province') === 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                    <option value="Kalimantan Tengah" {{ request('province') === 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                    <option value="Kalimantan Selatan" {{ request('province') === 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                    <option value="Kalimantan Timur" {{ request('province') === 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                    <option value="Kalimantan Utara" {{ request('province') === 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>

                    <option value="Sulawesi Utara" {{ request('province') === 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                    <option value="Sulawesi Tengah" {{ request('province') === 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                    <option value="Sulawesi Selatan" {{ request('province') === 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                    <option value="Sulawesi Tenggara" {{ request('province') === 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                    <option value="Gorontalo" {{ request('province') === 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                    <option value="Sulawesi Barat" {{ request('province') === 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>

                    <option value="Maluku" {{ request('province') === 'Maluku' ? 'selected' : '' }}>Maluku</option>
                    <option value="Maluku Utara" {{ request('province') === 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>

                    {{-- Papua & pemekaran --}}
                    <option value="Papua" {{ request('province') === 'Papua' ? 'selected' : '' }}>Papua</option>
                    <option value="Papua Barat" {{ request('province') === 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                    <option value="Papua Tengah" {{ request('province') === 'Papua Tengah' ? 'selected' : '' }}>Papua Tengah</option>
                    <option value="Papua Pegunungan" {{ request('province') === 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                    <option value="Papua Selatan" {{ request('province') === 'Papua Selatan' ? 'selected' : '' }}>Papua Selatan</option>
                    <option value="Papua Barat Daya" {{ request('province') === 'Papua Barat Daya' ? 'selected' : '' }}>Papua Barat Daya</option>
                </select>
            </form>
        </div>

        {{-- ========== FILTER RATING ========== --}}
        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Rating</h2>

            <div class="flex flex-wrap gap-2">
                {{-- Semua --}}
                <a href="{{ route('search', array_merge(request()->query(), ['rating' => null])) }}"
                    class="px-3 py-1.5 rounded-full text-xs sm:text-sm border transition
                           {{ request('rating')
                                ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100 dark:bg-white/5 dark:text-white dark:border-white/15'
                                : 'bg-emerald-600 text-white border-emerald-600' }}">
                    Semua
                </a>

                {{-- 5 -> 1 bintang --}}
                @for($star = 5; $star >= 1; $star--)
                    <a href="{{ route('search', array_merge(request()->query(), ['rating' => $star])) }}"
                       class="px-3 py-1.5 rounded-full text-xs sm:text-sm border inline-flex items-center gap-1 transition
                              {{ (int)request('rating') === $star
                                    ? 'bg-emerald-600 text-white border-emerald-600'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100 dark:bg-white/5 dark:border-white/15 dark:text-white' }}">
                        <span>{{ $star }}</span>
                        <span class="text-yellow-400">★</span>
                    </a>
                @endfor
            </div>
        </div>

        {{-- ========== RESET FILTER (DIPINDAH KE SINI) ========== --}}
        <div class="pt-4 border-t border-gray-200/60 dark:border-white/10">
            <a href="{{ route('search') }}"
                class="block w-full text-center px-4 py-2 rounded-lg
                       text-xs sm:text-sm font-semibold
                       text-red-600 border border-red-300
                       hover:bg-red-50 transition
                       dark:text-red-300 dark:border-red-400/40 dark:hover:bg-red-900/20">
                Reset Semua Filter
            </a>
        </div>

    </aside>

    {{-- ===================== HASIL SEARCH ===================== --}}
    <section class="md:col-span-3 space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Pencarian</h2>

            {{-- RINGKASAN FILTER --}}
            <p class="text-sm text-gray-500 dark:text-slate-300 mt-1">
                @if(request('q'))
                    Kata kunci: <strong class="text-gray-700 dark:text-white">"{{ request('q') }}"</strong>
                @endif

                @if(request('category') && $categories->where('id', request('category'))->first())
                    @php $activeCat = $categories->where('id', request('category'))->first(); @endphp
                    • Kategori: <strong class="text-gray-700 dark:text-white">{{ $activeCat->name }}</strong>
                @endif

                @if(request('province'))
                    • Lokasi: <strong class="text-gray-700 dark:text-white">{{ request('province') }}</strong>
                @endif

                @if(request('min_price') || request('max_price'))
                    • Harga:
                    <strong class="text-gray-700 dark:text-white">
                        {{ request('min_price') ? 'Rp '.number_format(request('min_price'),0,',','.') : '0' }}
                        -
                        {{ request('max_price') ? 'Rp '.number_format(request('max_price'),0,',','.') : '∞' }}
                    </strong>
                @endif

                @if(request('rating'))
                    • Rating minimal:
                    <strong class="text-gray-700 dark:text-white">{{ request('rating') }} ★</strong>
                @endif
            </p>
        </div>

        {{-- PRODUK GRID --}}
        <div id="product-grid"
             class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @if($products->count() > 0)
                @include('components.product-cards', ['products' => $products])
            @else
                <p class="text-gray-500 dark:text-slate-300 text-sm col-span-full">
                    Tidak ada produk yang cocok.
                </p>
            @endif
        </div>

        {{-- LOAD MORE --}}
        @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasMorePages())
            <div class="mt-6 flex justify-center">
                <button id="load-more-btn"
                        data-next-page="{{ $products->currentPage() + 1 }}"
                        class="px-6 py-3 rounded-lg
                               bg-emerald-600 text-white font-semibold
                               hover:bg-emerald-700 transition
                               flex items-center gap-2">
                    Muat Lebih Banyak
                </button>
            </div>

            <div id="loading-spinner" class="mt-6 flex justify-center hidden">
                <div class="flex items-center gap-2">
                    <div class="animate-spin">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <span class="text-gray-600 dark:text-gray-300">Memuat...</span>
                </div>
            </div>
        @endif
    </section>
</div>

{{-- AJAX LOAD MORE --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('load-more-btn');
    const spinner = document.getElementById('loading-spinner');
    const grid = document.getElementById('product-grid');

    if (!btn) return;

    btn.addEventListener('click', () => {
        const nextPage = btn.dataset.nextPage;
        const qs = window.location.search;

        btn.classList.add('hidden');
        spinner.classList.remove('hidden');

        fetch(`{{ route('search') }}?page=${nextPage}${qs ? '&' + qs.substring(1) : ''}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            grid.insertAdjacentHTML('beforeend', data.html);

            if (data.has_more) {
                btn.dataset.nextPage = data.next_page;
                btn.classList.remove('hidden');
            }

            spinner.classList.add('hidden');
        })
        .catch(() => {
            spinner.classList.add('hidden');
            btn.classList.remove('hidden');
        });
    });
});
</script>
@endsection
