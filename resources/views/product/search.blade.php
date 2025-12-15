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

    <aside class="rounded-2xl border border-gray-200/70 bg-white shadow-sm p-5 space-y-6 h-fit
                  dark:border-white/10 dark:bg-white/5 dark:backdrop-blur-xl dark:shadow-2xl">

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

        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Price</h2>

            <form action="{{ route('search') }}" method="GET" class="space-y-3">
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
                    Apply
                </button>
            </form>
        </div>

        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Store location</h2>

            @php
                $currentProvince = request('province') ?: 'All Location';
                $provinces = [
                    'Aceh','Sumatera Utara','Sumatera Barat','Riau','Jambi','Sumatera Selatan','Bengkulu','Lampung',
                    'Kepulauan Bangka Belitung','Kepulauan Riau','DKI Jakarta','Jawa Barat','Jawa Tengah','D.I. Yogyakarta',
                    'Jawa Timur','Banten','Bali','Nusa Tenggara Barat','Nusa Tenggara Timur','Kalimantan Barat','Kalimantan Tengah',
                    'Kalimantan Selatan','Kalimantan Timur','Kalimantan Utara','Sulawesi Utara','Sulawesi Tengah','Sulawesi Selatan',
                    'Sulawesi Tenggara','Gorontalo','Sulawesi Barat','Maluku','Maluku Utara','Papua','Papua Barat','Papua Tengah',
                    'Papua Pegunungan','Papua Selatan','Papua Barat Daya'
                ];
            @endphp

            <div class="relative" x-data="{ open:false }">
                <form x-ref="form" action="{{ route('search') }}" method="GET" class="space-y-2 text-xs sm:text-sm">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    <input type="hidden" name="rating" value="{{ request('rating') }}">

                    <input type="hidden" name="province" value="{{ request('province') }}" x-ref="province">

                    <button
                        type="button"
                        @click="open = !open"
                        class="theme-transition w-full inline-flex items-center justify-between gap-2 px-3 py-2 rounded-lg
                            bg-white border border-gray-300
                            text-xs sm:text-sm text-gray-800
                            hover:bg-gray-50 shadow-sm transition

                            dark:bg-white/5 dark:border-white/20
                            dark:text-slate-100
                            dark:hover:bg-white/10 dark:hover:border-emerald-300/70
                            dark:backdrop-blur-xl"
                    >
                        <span class="max-w-[220px] overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ $currentProvince }}
                        </span>

                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="mt-2 w-full max-h-64 overflow-y-auto
                            bg-white border border-gray-200
                            rounded-xl shadow-xl absolute left-0 z-20

                            dark:bg-slate-950/95 dark:border-white/15
                            dark:backdrop-blur-xl"
                    >
                        <button
                            type="button"
                            @click="$refs.province.value=''; $refs.form.submit()"
                            class="w-full text-left block px-4 py-2.5 text-xs sm:text-sm theme-transition
                                hover:bg-gray-100
                                {{ request('province') ? '' : 'bg-gray-100 font-semibold' }}
                                dark:text-slate-100 dark:hover:bg-emerald-500/15
                                {{ request('province') ? '' : 'dark:bg-emerald-500/20 dark:font-semibold dark:text-emerald-100' }}"
                        >
                            All Location
                        </button>

                        @foreach ($provinces as $prov)
                            <button
                                type="button"
                                @click="$refs.province.value='{{ $prov }}'; $refs.form.submit()"
                                class="w-full text-left block px-4 py-2.5 text-xs sm:text-sm theme-transition
                                    hover:bg-gray-100
                                    {{ request('province') === $prov ? 'bg-gray-100 font-semibold' : '' }}
                                    dark:text-slate-100 dark:hover:bg-emerald-500/15
                                    {{ request('province') === $prov ? 'dark:bg-emerald-500/20 dark:font-semibold dark:text-emerald-100' : '' }}"
                            >
                                {{ $prov }}
                            </button>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <div class="pt-2 border-t border-gray-200/60 dark:border-white/10">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 mt-3">Rating</h2>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('search', array_merge(request()->query(), ['rating' => null])) }}"
                    class="px-3 py-1.5 rounded-full text-xs sm:text-sm border transition
                           {{ request('rating')
                                ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100 dark:bg-white/5 dark:text-white dark:border-white/15'
                                : 'bg-emerald-600 text-white border-emerald-600' }}">
                    All
                </a>

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

        <div class="pt-4 border-t border-gray-200/60 dark:border-white/10">
            <a href="{{ route('search') }}"
                class="block w-full text-center px-4 py-2 rounded-lg
                       text-xs sm:text-sm font-semibold
                       text-red-600 border border-red-300
                       hover:bg-red-50 transition
                       dark:text-red-300 dark:border-red-400/40 dark:hover:bg-red-900/20">
                Reset Filter
            </a>
        </div>

    </aside>

    <section class="md:col-span-3 space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Search Result</h2>

            <p class="text-sm text-gray-500 dark:text-slate-300 mt-1">
                @if(request('q'))
                    Keyword: <strong class="text-gray-700 dark:text-white">"{{ request('q') }}"</strong>
                @endif

                @if(request('category') && $categories->where('id', request('category'))->first())
                    @php $activeCat = $categories->where('id', request('category'))->first(); @endphp
                    • Category: <strong class="text-gray-700 dark:text-white">{{ $activeCat->name }}</strong>
                @endif

                @if(request('province'))
                    • Location: <strong class="text-gray-700 dark:text-white">{{ request('province') }}</strong>
                @endif

                @if(request('min_price') || request('max_price'))
                    • Price:
                    <strong class="text-gray-700 dark:text-white">
                        {{ request('min_price') ? 'Rp '.number_format(request('min_price'),0,',','.') : '0' }}
                        -
                        {{ request('max_price') ? 'Rp '.number_format(request('max_price'),0,',','.') : '∞' }}
                    </strong>
                @endif

                @if(request('rating'))
                    • Minimum rating:
                    <strong class="text-gray-700 dark:text-white">{{ request('rating') }} ★</strong>
                @endif
            </p>
        </div>

        <div id="product-grid"
             class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @if($products->count() > 0)
                @include('components.product-cards', ['products' => $products])
            @else
                <p class="text-gray-500 dark:text-slate-300 text-sm col-span-full">
                    No matching products found.
                </p>
            @endif
        </div>

        @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasMorePages())
            <div class="mt-6 flex justify-center">
                <button id="load-more-btn"
                        data-next-page="{{ $products->currentPage() + 1 }}"
                        class="px-6 py-3 rounded-lg
                               bg-emerald-600 text-white font-semibold
                               hover:bg-emerald-700 transition
                               flex items-center gap-2">
                    Load more
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
                    <span class="text-gray-600 dark:text-gray-300">Load...</span>
                </div>
            </div>
        @endif
    </section>
</div>

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
