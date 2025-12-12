@php
    // Parent (general category)
    $parentCategories  = $categories->whereNull('parent_id');
    $parentIds         = $categories->pluck('parent_id')->filter()->unique();
    $leafCategories    = $categories->whereNotIn('id', $parentIds);

    $generalCategories = $parentCategories->random(min(5, $parentCategories->count()));
    $specificCategories = $leafCategories->random(min(5, $leafCategories->count()));

    $groupedCategories = $categories->groupBy('parent_id');
@endphp

@extends('layouts.app')

@section('content')
    {{-- WRAPPER HALAMAN: light = gray lembut, dark = gradient --}}
    <div class="min-h-screen w-full  
                bg-gray-50 
                dark:bg-gradient-to-b dark:from-slate-950 dark:via-slate-900 dark:to-emerald-950/90">

        {{-- GENERAL CATEGORY NAV (PARENT) - TAB STYLE --}}
        <section class="max-w-7xl mx-auto pt-10 px-4 lg:px-0">
            <div class="border-b border-gray-200 dark:border-slate-700 pb-2 overflow-x-auto">
                <div class="flex items-center gap-4 min-w-max">
                    @foreach ($generalCategories as $category)
                        @php
                            $active = (int) request('category') === $category->id;
                        @endphp

                        <a
                            href="{{ route('search', [
                                'category' => $category->id,
                                'q'        => request('q'),
                                'province' => request('province'),
                            ]) }}"
                            class="text-sm pb-2 border-b-2 transition-all duration-200
                                   {{ $active
                                        ? 'font-semibold text-emerald-600 border-emerald-500 dark:text-emerald-300 dark:border-emerald-300'
                                        : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-gray-300 dark:text-slate-300 dark:hover:text-emerald-200 dark:hover:border-emerald-200' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- HERO: PREMIUM FADE SLIDER + SEARCH BAR + SPECIFIC CATEGORY --}}
        <section class="max-w-7xl mx-auto mt-4 px-4 lg:px-0">
            <div
                class="relative w-full rounded-3xl overflow-hidden
                       border border-gray-200/50 bg-white shadow-sm
                       dark:border-white/10 dark:bg-gradient-to-br dark:from-white/10 dark:via-white/5 dark:to-emerald-500/10 
                       dark:shadow-2xl dark:backdrop-blur-2xl"
            >
                {{-- SLIDER BACKGROUND (FADE + KEN BURNS) --}}
                <div class="relative w-full h-64 md:h-80 lg:h-96">
                    <div id="hero-slides" class="absolute inset-0">
                        @foreach ($banners as $banner)
                            <div class="hero-slide absolute inset-0 opacity-0">
                                <img
                                    src="{{ $banner }}"
                                    alt="Banner {{ $loop->iteration }}"
                                    class="w-full h-full object-cover hero-slide-image"
                                >
                            </div>
                        @endforeach
                    </div>

                    {{-- overlay gradient agar text & search terbaca --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/10
                                dark:from-slate-950/85 dark:via-slate-950/40 dark:to-transparent"></div>

                    {{-- subtle top light in light mode --}}
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-white/30 to-transparent dark:from-transparent"></div>

                    {{-- HERO CONTENT --}}
                    <div class="relative z-10 h-full flex flex-col items-center justify-center px-4">
                        <div class="max-w-2xl text-center space-y-3">
                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white drop-shadow-sm">
                                Change your wardrobe. Find exciting goods.
                            </h1>
                            <p class="text-sm md:text-base text-gray-100/90">
                                Cari produk, jasa, atau hobi baru yang bikin harimu lebih seru.
                            </p>

                            {{-- SEARCH BAR --}}
                            <form action="{{ route('search') }}" method="GET" class="mt-4">
                                <div class="relative max-w-xl mx-auto">
                                    <input
                                        type="text"
                                        name="q"
                                        placeholder="What are you looking for?"
                                        value="{{ request('q') }}"
                                        class="w-full rounded-full pl-5 md:pl-12 pr-14 py-2.5 md:py-3.5 text-sm md:text-base
                                               bg-white/95 text-gray-900 placeholder:text-gray-400
                                               border border-gray-200/80
                                               shadow-md
                                               focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"
                                    >

                                    {{-- ICON SEARCH --}}
                                    <svg 
                                        class="hidden md:block absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-emerald-500" 
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 10.61 10.61Z"/>
                                    </svg>

                                    {{-- BUTTON ARROW --}}
                                    <button type="submit"
                                        class="absolute right-2 top-1/2 -translate-y-1/2
                                               w-9 h-9 rounded-full bg-emerald-500 text-white
                                               flex items-center justify-center
                                               shadow-md hover:bg-emerald-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M5 12h14M13 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    @if(request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    @if(request('province'))
                                        <input type="hidden" name="province" value="{{ request('province') }}">
                                    @endif
                                </div>
                            </form>

                            {{-- SPECIFIC CATEGORIES + LIHAT SEMUA --}}
                            <div class="mt-4 flex flex-wrap justify-center gap-2">
                                @foreach ($specificCategories as $item)
                                    <a href="{{ route('search', [
                                                'category' => $item->id,
                                                'q'        => request('q'),
                                                'province' => request('province'),
                                            ]) }}"
                                       class="px-4 py-1.5 rounded-full text-xs md:text-sm
                                              bg-white/90 text-gray-800 border border-gray-200/80
                                              hover:bg-emerald-50 hover:border-emerald-400
                                              transition">
                                        {{ $item->name }}
                                    </a>
                                @endforeach

                                {{-- TOMBOL LIHAT SEMUA KATEGORI --}}
                                <button
                                    type="button"
                                    id="open-category-modal"
                                    class="px-4 py-1.5 rounded-full text-xs md:text-sm
                                           bg-transparent border border-white/80 text-white
                                           hover:bg-white/10 hover:border-emerald-300
                                           transition">
                                    Lihat semua kategori
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MODAL SEMUA KATEGORI (STYLED) --}}
        <div 
            id="category-modal"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4
                   dark:bg-slate-950/60"
        >
            <div class="w-full max-w-5xl max-h-[80vh] overflow-y-auto relative rounded-3xl shadow-2xl border
                        bg-white p-6 md:p-7 border-gray-200
                        dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-950 dark:to-emerald-950/90 
                        dark:border-white/10">

                {{-- Close button --}}
                <button 
                    id="close-category-modal"
                    class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center hover:bg-gray-200 transition
                           dark:bg-white/5 dark:border-white/20 dark:hover:bg-white/15"
                >
                    <span class="text-gray-600 dark:text-gray-200 text-sm">✕</span>
                </button>

                {{-- Title --}}
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Semua Kategori</h2>
                        <p class="text-xs md:text-sm text-gray-500 mt-1 dark:text-emerald-100/80">
                            Jelajahi kategori berdasarkan jenis dan subkategori.
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Level 0: Parent (root, parent_id = null) --}}
                    @foreach ($groupedCategories[null] ?? [] as $parent)
                        @php
                            $children = $groupedCategories[$parent->id] ?? collect();
                            $subCount = $children->count();
                        @endphp

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 md:p-5 hover:shadow-md transition
                                    dark:bg-white/5 dark:border-white/15 dark:hover:shadow-xl dark:hover:border-emerald-300/50 dark:backdrop-blur-2xl">

                            {{-- Parent header --}}
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-semibold text-emerald-700 border border-emerald-200
                                                dark:bg-emerald-500/20 dark:text-emerald-200 dark:border-emerald-300/50">
                                        {{ strtoupper(mb_substr($parent->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('search', ['category' => $parent->id]) }}"
                                          class="font-semibold text-gray-900 hover:text-emerald-600
                                                 dark:text-white dark:hover:text-emerald-300">
                                            {{ $parent->name }}
                                        </a>
                                        @if($subCount > 0)
                                            <p class="text-[11px] text-gray-500 dark:text-emerald-100/70">
                                                {{ $subCount }} subkategori
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Level 1 & 2 --}}
                            @if ($children->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($children as $child)
                                        @php
                                            $grandChildren = $groupedCategories[$child->id] ?? collect();
                                        @endphp

                                        <div class="bg-white rounded-xl border border-gray-200 p-3
                                                    dark:bg-slate-900/60 dark:border-white/10 dark:backdrop-blur-xl">
                                            {{-- Child --}}
                                            <a href="{{ route('search', ['category' => $child->id]) }}"
                                              class="text-sm font-medium text-gray-800 hover:text-emerald-600 flex items-center gap-2
                                                     dark:text-emerald-100 dark:hover:text-emerald-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                                {{ $child->name }}
                                            </a>

                                            {{-- Grandchild (max depth 3) --}}
                                            @if ($grandChildren->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-1.5 ml-4">
                                                    @foreach ($grandChildren as $grand)
                                                        <a href="{{ route('search', ['category' => $grand->id]) }}"
                                                          class="px-2.5 py-0.5 rounded-full border border-gray-200 bg-gray-50
                                                                 text-[11px] text-gray-700 hover:border-emerald-500 hover:text-emerald-700 transition

                                                                 dark:border-white/15 dark:bg-white/5
                                                                 dark:text-emerald-50 dark:hover:border-emerald-400 dark:hover:text-emerald-200">
                                                            {{ $grand->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-500 italic dark:text-emerald-100/70">
                                    Belum ada subkategori.
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- PRODUCT SECTION --}}
        <section class="max-w-7xl mx-auto space-y-4 mt-10 px-4 lg:px-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Product</h2>

                    @if(request('q') || request('category') || request('province'))
                        <p class="text-xs text-gray-600 mt-1 dark:text-emerald-100/80">
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
            </div>

            <div class="rounded-3xl border border-gray-200/40 bg-white shadow-sm p-4 sm:p-5
                        dark:border-white/10 dark:bg-white/5 dark:backdrop-blur-2xl dark:shadow-2xl">
                <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
                    @if($products->count() > 0)
                        @include('components.product-cards', ['products' => $products])
                    @else
                        <p class="text-gray-500 dark:text-emerald-50/90 text-sm col-span-full">
                            Tidak ada produk.
                        </p>
                    @endif
                </div>

                @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasMorePages())
                    <div class="flex justify-center mt-6">
                        <button
                            id="load-more"
                            data-next-page="{{ $products->currentPage() + 1 }}"
                            class="px-6 py-2 text-sm font-medium 
                                   bg-white text-emerald-600 
                                   border border-emerald-600 
                                   rounded-full shadow-sm
                                   hover:bg-emerald-50 hover:shadow-md 
                                   transition

                                   dark:bg-white/10 dark:text-emerald-100 
                                   dark:border-emerald-400/70 
                                   dark:hover:bg-emerald-500/90 dark:hover:text-white dark:hover:shadow-xl 
                                   dark:backdrop-blur-xl"
                        >
                            Show more
                        </button>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('styles')
<style>
    /* Premium hero fade + Ken Burns */
    .hero-slide {
        opacity: 0;
        transition: opacity 800ms ease-out;
    }

    .hero-slide-active {
        opacity: 1;
    }

    .hero-slide-image {
        transform-origin: center;
    }

    .hero-slide-active .hero-slide-image {
        animation: hero-kenburns 10s ease-out forwards;
    }

    @keyframes hero-kenburns {
        from {
            transform: scale(1.02);
        }
        to {
            transform: scale(1.08);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-slide,
        .hero-slide-image {
            transition: none !important;
            animation: none !important;
            transform: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // PREMIUM HERO BACKGROUND SLIDER (FADE + KEN BURNS)
    const slides = Array.from(document.querySelectorAll('.hero-slide'));
    let index = 0;

    function setActiveSlide(i) {
        slides.forEach((slide, idx) => {
            slide.classList.toggle('hero-slide-active', idx === i);
        });
    }

    if (slides.length > 0) {
        setActiveSlide(0);

        if (slides.length > 1) {
            setInterval(() => {
                index = (index + 1) % slides.length;
                setActiveSlide(index);
            }, 8000); // 8 detik per slide
        }
    }

    // LOAD MORE PRODUCT
    const loadMoreBtn = document.getElementById('load-more');
    const productGrid = document.getElementById('product-grid');

    if (loadMoreBtn && productGrid) {
        loadMoreBtn.addEventListener('click', function () {
            const button = this;
            const nextPage = button.dataset.nextPage;

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

    // CATEGORY MODAL
    const modal    = document.getElementById('category-modal');
    const openBtn  = document.getElementById('open-category-modal');
    const closeBtn = document.getElementById('close-category-modal');

    if (modal && openBtn && closeBtn) {
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
