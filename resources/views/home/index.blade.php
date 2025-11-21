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
    ];
@endphp

@extends('layouts.simple-template')

@section('content')
    {{-- BANNER SLIDER --}}
    <div class="w-full flex justify-center mb-8">
        <div class="relative w-full max-w-5xl overflow-hidden rounded-xl shadow" id="banner-slider">

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
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow-md backdrop-blur flex items-center justify-center"
                aria-label="Sebelumnya"
            >
                ‹
            </button>

            {{-- Tombol Kanan --}}
            <button
                type="button"
                id="banner-next"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow-md backdrop-blur flex items-center justify-center"
                aria-label="Berikutnya"
            >
                ›
            </button>
        </div>
    </div>

    {{-- CATEGORY SECTION --}}
    <section class="max-w-5xl mx-auto space-y-6 mb-10">
        {{-- Baris 1: Title "Category" --}}
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Category</h2>
        </div>

        {{-- Baris 2: Card category spesifik (dummy, gambar di atas, teks di bawah) --}}
        <div class="space-y-3">
            <p class="text-sm text-gray-500">Category spesifik</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($specificCategories as $category)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <img
                            src="{{ $category['image'] }}"
                            alt="{{ $category['name'] }}"
                            class="w-full h-28 sm:h-32 object-cover"
                        >
                        <div class="p-3 text-center">
                            <span class="text-sm font-medium text-gray-800">
                                {{ $category['name'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Baris 3: Card category general (dari DB, icon kiri, teks kanan, rounded) --}}
        <div class="space-y-3">
            <p class="text-sm text-gray-500">Category general</p>

            <div class="flex flex-wrap gap-3">
                {{-- Tombol "Semua Kategori" --}}
                <a
                    href="{{ route('home.index', [
                        'q'        => request('q'),
                        'province' => request('province'),
                    ]) }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-full text-sm shadow-sm
                        {{ request('category') ? 'bg-white text-gray-800' : 'bg-blue-600 text-white' }}"
                >
                    <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100">
                        🔄
                    </span>
                    <span>Semua Kategori</span>
                </a>

                {{-- Daftar kategori dari DB --}}
                @foreach ($categories as $category)
                    @php
                        $active = (int) request('category') === $category->id;
                    @endphp

                    <a
                        href="{{ route('home.index', [
                            'category' => $category->id,
                            'q'        => request('q'),
                            'province' => request('province'),
                        ]) }}"
                        class="flex items-center gap-2 px-4 py-2 rounded-full text-sm shadow-sm
                            {{ $active ? 'bg-blue-600 text-white' : 'bg-white text-gray-800 hover:bg-gray-50' }}"
                    >
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-xs font-semibold">
                            {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                        </span>
                        <span>{{ $category->name }}</span>

                        @if(isset($category->products_count))
                            <span class="text-[11px] text-gray-400">
                                ({{ $category->products_count }})
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRODUCT SECTION --}}
    <section class="max-w-5xl mx-auto space-y-4 mb-12">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Produk</h2>

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

            <a href="{{ route('home.index') }}" class="text-sm text-blue-600 hover:underline">
                Reset filter
            </a>
        </div>

        {{-- Grid produk: max 6 kolom, tumbuh ke bawah --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @forelse ($products as $product)
                <a href="{{ route('products.show', $product) }}"
                   class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                    {{-- Gambar produk --}}
                    <div class="w-full h-32 sm:h-36 md:h-40 overflow-hidden">
                        <img
                            src="{{ asset($product->main_image) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    {{-- Detail produk --}}
                    <div class="p-3 flex flex-col gap-1 flex-1">
                        {{-- Nama --}}
                        <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        {{-- Harga --}}
                        <div class="text-sm font-bold text-orange-600 mt-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        {{-- (opsional) Rating & lokasi bisa ditambahkan di sini --}}
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-sm col-span-full">
                    Tidak ada produk yang cocok dengan filter.
                </p>
            @endforelse
        </div>

        <div>
            {{ $products->links() }}
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('banner-track');
        const prevBtn = document.getElementById('banner-prev');
        const nextBtn = document.getElementById('banner-next');
        const slides = track.children;
        const total = slides.length;

        let index = 0;

        function updateSlide() {
            track.style.transform = `translateX(-${index * 100}%)`;
        }

        if (total <= 1) {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
        }

        prevBtn.addEventListener('click', function () {
            index = (index - 1 + total) % total;
            updateSlide();
        });

        nextBtn.addEventListener('click', function () {
            index = (index + 1) % total;
            updateSlide();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                index = (index - 1 + total) % total;
                updateSlide();
            } else if (e.key === 'ArrowRight') {
                index = (index + 1) % total;
                updateSlide();
            }
        });
    });
</script>
@endpush
