@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 mt-12">
    <div class="max-w-6xl mx-auto mt-6">
        <x-breadcrumb :items="$breadcrumbs" />
    </div>

    {{-- CONTAINER 1: gambar + deskripsi + spesifikasi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- gambar --}}
        <div class="md:col-span-1 bg-white rounded-xl shadow p-4 flex items-center justify-center">
              <img src="{{ asset('storage/' . ($product->images[0] ?? 'images/store.png')) }}"
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
                    // rata-rata dari semua ulasan
                    $avgRating = $product->reviews->avg('rating');

                    // rating yang sedang difilter (misal ?rating=5)
                    $selectedRating = request('rating');

                    // ulasan yang akan ditampilkan (filtered)
                    $filteredReviews = $selectedRating
                        ? $product->reviews->where('rating', (int) $selectedRating)
                        : $product->reviews;
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
                        <span>{{ optional($product->seller)->pic_province ?? 'Lokasi tidak diketahui' }}</span>
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

            {{-- Info penjual: kontak jika tertarik --}}
            @if ($product->seller)
                <div class="mt-4 p-4 border rounded-lg bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">
                        Tertarik dengan produk ini?
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Hubungi penjual untuk tanya stok, nego harga, atau detail lainnya.
                    </p>

                    <div class="space-y-1 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">No. HP / WhatsApp:</span>
                            <span class="ml-1 text-gray-800">
                                {{ $product->seller->pic_phone }}
                            </span>
                            {{-- Kalau mau langsung ke WhatsApp, bisa pakai link ini --}}
                            {{-- 
                            <a
                                href="https://wa.me/62{{ ltrim($product->seller->pic_phone, '0') }}"
                                target="_blank"
                                class="ml-2 text-green-600 hover:underline text-xs"
                            >
                                Chat via WhatsApp
                            </a>
                            --}}
                        </div>

                        <div>
                            <span class="font-medium text-gray-700">Email:</span>
                            <a
                                href="mailto:{{ $product->seller->pic_email }}"
                                class="ml-1 text-blue-600 hover:underline"
                            >
                                {{ $product->seller->pic_email }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-4 text-xs text-gray-400">
                    Informasi penjual tidak tersedia.
                </div>
            @endif

        </div>
    </div>

    {{-- CONTAINER 2: REVIEW & RATING --}}
    <div class="bg-white rounded-xl shadow p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold">Ulasan & Rating</h2>

            {{-- Tombol buka modal tambah ulasan --}}
            <button
                id="openReviewModal"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition"
            >
                + Tulis Ulasan
            </button>
        </div>

        @if ($avgRating)
            <div class="flex items-center justify-between flex-wrap gap-4">
                {{-- Rata-rata rating --}}
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
                            @if($selectedRating)
                                <span class="text-gray-400">
                                    • Menampilkan hanya bintang {{ $selectedRating }}
                                    ({{ $filteredReviews->count() }} ulasan)
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- FILTER BERDASARKAN BINTANG --}}
                <div class="flex items-center gap-2">
                    {{-- Tombol "Semua" --}}
                    <a href="{{ route('product.show', $product->id) }}"
                      class="px-3 py-1.5 text-xs sm:text-sm rounded-full border
                              {{ $selectedRating ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' : 'bg-green-600 text-white border-green-600' }}">
                        Semua
                    </a>

                    {{-- Tombol 5 → 1 bintang --}}
                    @for ($star = 5; $star >= 1; $star--)
                        <a href="{{ request()->fullUrlWithQuery(['rating' => $star]) }}"
                          class="px-3 py-1.5 text-xs sm:text-sm rounded-full border inline-flex items-center gap-1
                                  {{ (int)$selectedRating === $star
                                        ? 'bg-green-600 text-white border-green-600'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                            <span>{{ $star }}</span>
                            <span class="text-yellow-400">★</span>
                        </a>
                    @endfor
                </div>
            </div>
        @else
            <p class="text-gray-500">Belum ada rating.</p>
        @endif

        <hr>

        {{-- DAFTAR ULASAN (TERFILTER) --}}
        <div class="space-y-4">
            @forelse ($filteredReviews as $review)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <strong>{{ $review->name ?? 'User' }}</strong>
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
                <p class="text-gray-500">
                    @if($selectedRating)
                        Belum ada ulasan dengan rating {{ $selectedRating }} bintang.
                    @else
                        Belum ada ulasan.
                    @endif
                </p>
            @endforelse
        </div>
    </div>

    {{-- MODAL TAMBAH ULASAN --}}
    <div
        id="reviewModal"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden"
    >
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 relative">
            {{-- Tombol close --}}
            <button
                type="button"
                id="closeReviewModal"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
            >
                ✕
            </button>

            <div class="p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Tulis Ulasan untuk {{ $product->name }}
                </h3>

                <form
                    action="{{ route('review.store') }}"
                    method="POST"
                    class="space-y-4"
                    autocomplete="off"
                >
                    @csrf

                    {{-- hidden product id --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            No. Handphone / WhatsApp
                        </label>
                        <input
                            type="text"
                            name="phone"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi
                        </label>

                        <select
                            name="province"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                        >
                            <option value="" disabled selected>Pilih provinsi</option>

                            {{-- 34 Provinsi Indonesia --}}
                            <option value="Aceh">Aceh</option>
                            <option value="Sumatera Utara">Sumatera Utara</option>
                            <option value="Sumatera Barat">Sumatera Barat</option>
                            <option value="Riau">Riau</option>
                            <option value="Jambi">Jambi</option>
                            <option value="Sumatera Selatan">Sumatera Selatan</option>
                            <option value="Bengkulu">Bengkulu</option>
                            <option value="Lampung">Lampung</option>
                            <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                            <option value="Kepulauan Riau">Kepulauan Riau</option>

                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <option value="D.I. Yogyakarta">D.I. Yogyakarta</option>
                            <option value="Jawa Timur">Jawa Timur</option>
                            
                            <option value="Banten">Banten</option>
                            <option value="Bali">Bali</option>
                            <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                            <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>

                            <option value="Kalimantan Barat">Kalimantan Barat</option>
                            <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                            <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                            <option value="Kalimantan Timur">Kalimantan Timur</option>
                            <option value="Kalimantan Utara">Kalimantan Utara</option>

                            <option value="Sulawesi Utara">Sulawesi Utara</option>
                            <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                            <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                            <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                            <option value="Gorontalo">Gorontalo</option>
                            <option value="Sulawesi Barat">Sulawesi Barat</option>

                            <option value="Maluku">Maluku</option>
                            <option value="Maluku Utara">Maluku Utara</option>
                            <option value="Papua">Papua</option>
                            <option value="Papua Barat">Papua Barat</option>
                            <option value="Papua Tengah">Papua Tengah</option>
                            <option value="Papua Pegunungan">Papua Pegunungan</option>
                            <option value="Papua Selatan">Papua Selatan</option>
                            <option value="Papua Barat Daya">Papua Barat Daya</option>
                        </select>
                    </div>

                    {{-- RATING: BINTANG KLIKABLE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Rating
                        </label>

                        {{-- input hidden yang dikirim ke server --}}
                        <input type="hidden" name="rating" id="ratingInput">

                        <div id="ratingStars" class="flex items-center gap-1 text-2xl">
                            @for ($i = 1; $i <= 5; $i++)
                                <button
                                    type="button"
                                    class="rating-star text-gray-300 hover:text-yellow-400 transition
                                        focus:outline-none"
                                    data-value="{{ $i }}"
                                >
                                    ★
                                </button>
                            @endfor
                        </div>

                        <p class="text-xs text-gray-500 mt-1" id="ratingHint">
                            Klik jumlah bintang untuk memberi rating.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Komentar
                        </label>
                        <textarea
                            name="comment"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Ceritakan pengalamanmu dengan produk ini..."
                            autocomplete="off"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button
                            type="button"
                            id="cancelReviewModal"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:bg-gray-50"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700"
                        >
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- CONTAINER 3: PRODUK REKOMENDASI --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Lainnya di toko ini</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @if($recommendations->count() > 0)
              @include('components.product-cards', ['products' => $recommendations])
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn   = document.getElementById('openReviewModal');
        const closeBtn  = document.getElementById('closeReviewModal');
        const cancelBtn = document.getElementById('cancelReviewModal');
        const modal     = document.getElementById('reviewModal');
        const form      = modal ? modal.querySelector('form') : null;

        // rating
        const ratingInput     = document.getElementById('ratingInput');
        const ratingStarsWrap = document.getElementById('ratingStars');
        const ratingHint      = document.getElementById('ratingHint');
        const stars           = ratingStarsWrap
            ? ratingStarsWrap.querySelectorAll('.rating-star')
            : [];

        function setRating(value) {
            if (!ratingInput) return;

            ratingInput.value = value;

            stars.forEach(star => {
                const starValue = parseInt(star.dataset.value, 10);
                if (starValue <= value) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                }
            });

            if (ratingHint) {
                ratingHint.textContent = value
                    ? `Kamu memberi rating ${value} bintang.`
                    : 'Klik jumlah bintang untuk memberi rating.';
            }
        }

        // event klik bintang
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.dataset.value, 10);
                setRating(value);
            });
        });

        function openModal() {
            if (!modal) return;
            modal.classList.remove('hidden');
        }

        function clearForm() {
            if (form) {
                form.reset();
            }
            // reset rating visual & value
            setRating(0);
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            clearForm();
        }

        if (openBtn)   openBtn.addEventListener('click', openModal);
        if (closeBtn)  closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        // Tutup modal kalau klik di area overlay
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // SWEETALERT lokal (window.Swal dari app.js)

        // sukses
        @if (session('success'))
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        }
        @endif

        // error server
        @if (session('error'))
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            }).then(() => {
                openModal();
            });
        }
        @endif

        // error validasi (ambil pesan pertama)
        @if ($errors->any())
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Form tidak valid',
                text: '{{ $errors->first() }}',
            }).then(() => {
                openModal();
            });
        }
        @endif
    });
</script>
@endpush