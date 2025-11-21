@extends('layouts.simple-template')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    {{-- CONTAINER 1: gambar + deskripsi + spesifikasi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- gambar --}}
        <div class="md:col-span-1 bg-white rounded-xl shadow p-4 flex items-center justify-center">
            <img src="{{ asset($product->main_image) }}"
                 alt="{{ $product->name }}"
                 class="rounded-lg object-cover w-full">
        </div>

        {{-- deskripsi + spesifikasi --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow p-6 space-y-6">

            {{-- Nama + harga + “rating” --}}
            <div>
                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>

                <div class="text-orange-600 font-bold text-xl mt-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                @php
                    $avgRating = $product->reviews->avg('rating');
                @endphp

                @if ($avgRating)
                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                        <div class="flex">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <span class="text-yellow-400">★</span>
                                @else
                                    <span class="text-gray-300">★</span>
                                @endif
                            @endfor
                        </div>

                        <span>{{ number_format($avgRating, 1) }}</span>
                        <span class="text-gray-400">•</span>
                        <span>{{ optional($product->store)->province ?? 'Lokasi tidak diketahui' }}</span>
                    </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            <div>
                <h2 class="text-lg font-semibold mb-2">Deskripsi Produk</h2>
                <p class="text-gray-700 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            {{-- Di sini kalau kamu punya tabel spesifikasi sendiri,
                 bisa loop dari relasi. Untuk sekarang, placeholder saja. --}}
            {{-- <div>
                <h2 class="text-lg font-semibold mb-2">Spesifikasi</h2>
                ...
            </div> --}}
        </div>
    </div>

    {{-- CONTAINER 2: REVIEW & RATING --}}
    <div class="bg-white rounded-xl shadow p-6 space-y-6">
        <h2 class="text-xl font-semibold">Ulasan & Rating</h2>

        @if ($avgRating)
            <div class="flex items-center gap-4">
                <div class="text-4xl font-bold text-yellow-500">
                    {{ number_format($avgRating, 1) }}
                </div>

                <div>
                    <div class="flex">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <span class="text-yellow-400 text-xl">★</span>
                            @else
                                <span class="text-gray-300 text-xl">★</span>
                            @endif
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500">
                        Dari {{ $product->reviews->count() }} ulasan
                    </p>
                </div>
            </div>
        @else
            <p class="text-gray-500">Belum ada rating.</p>
        @endif

        <hr>

        <div class="space-y-4">
            @forelse ($product->reviews as $review)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <strong>{{ $review->user->name ?? 'User' }}</strong>
                        <span class="text-xs text-gray-500">
                            {{ $review->created_at->format('Y-m-d') }}
                        </span>
                    </div>

                    <div class="text-yellow-400 text-sm mt-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                ★
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>

                    <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada ulasan.</p>
            @endforelse
        </div>
    </div>

    {{-- CONTAINER 3: PRODUK REKOMENDASI --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Produk Rekomendasi</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @foreach ($recommendations as $item)
                <a href="{{ route('products.show', $item->id) }}"
                   class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-3 block">
                    <img src="{{ asset($item->main_image) }}"
                         class="w-full h-32 object-cover rounded-lg"
                         alt="{{ $item->name }}">

                    <div class="mt-2 space-y-1">
                        <h3 class="text-sm font-semibold line-clamp-2">{{ $item->name }}</h3>

                        <div class="text-sm font-bold text-orange-600">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
