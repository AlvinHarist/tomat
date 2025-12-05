@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 mt-12">
    <div class="max-w-6xl mx-auto mt-6">
        <x-breadcrumb :items="$breadcrumbs" />
    </div>

    {{-- CONTAINER 1: gambar + deskripsi + spesifikasi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- gambar (gallery) --}}
        @php
            // Normalisasi images: bisa datang sebagai array (JSON) atau string (legacy)
            $images = is_array($product->images)
                ? $product->images
                : ($product->images ? [$product->images] : []);

            $hasImages  = count($images) > 0;
            $firstImage = $hasImages ? $images[0] : null;
        @endphp

        <div class="md:col-span-1 bg-white rounded-xl shadow p-4">
            {{-- Gambar utama --}}
            <div class="w-full aspect-square flex items-center justify-center bg-gray-50 rounded-lg overflow-hidden">
                @if ($hasImages)
                    <img
                        id="mainProductImage"
                        src="{{ asset($firstImage) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover"
                    >
                @else
                    {{-- Fallback icon bila tidak ada gambar --}}
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" ry="2" />
                        <path d="M8 13l3-3 4 4 3-3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9" cy="9" r="1.5" />
                    </svg>
                @endif
            </div>

            {{-- Thumbnail scrollable --}}
            @if ($hasImages && count($images) > 1)
                <div class="flex gap-2 overflow-x-auto pb-2" id="thumbnailStrip">
                    @foreach ($images as $index => $img)
                        <button
                            type="button"
                            class="product-thumb-btn flex-shrink-0 w-16 h-16 rounded-md overflow-hidden border
                                @if($index === 0) ring-2 ring-green-500 border-green-500 @else border-gray-200 @endif
                                focus:outline-none focus:ring-2 focus:ring-green-500"
                            data-image="{{ asset($img) }}"
                        >
                            <img
                                src="{{ asset($img) }}"
                                alt="{{ $product->name }} thumbnail {{ $index + 1 }}"
                                class="w-full h-full object-cover"
                            >
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- deskripsi + spesifikasi --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow p-6 space-y-6">

            {{-- Nama + harga + rating + stock --}}
            <div>
                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>

                <div class="text-orange-600 font-bold text-xl mt-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                @php
                    // Gunakan avg_rating dari database (sudah disimpan)
                    $avgRating = $product->avg_rating;
                    $reviewCount = $product->review_count;

                    // rating yang sedang difilter (misal ?rating=5)
                    $selectedRating = request('rating');

                    // ulasan yang akan ditampilkan (filtered)
                    $filteredReviews = $selectedRating
                        ? $product->reviews->where('rating', (int) $selectedRating)
                        : $product->reviews;
                @endphp

                @if ($avgRating > 0)
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
                        @if($reviewCount > 0)
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">{{ $reviewCount }} ulasan</span>
                        @endif
                        <span class="text-gray-400">•</span>
                        <span>{{ optional($product->seller)->pic_province ?? 'Lokasi tidak diketahui' }}</span>
                    </div>
                @else
                    <div class="text-sm text-gray-500 mt-2">
                        Belum ada rating
                    </div>
                @endif

                {{-- Info stok --}}
                <div class="mt-3 flex items-center gap-3 text-sm">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                {{ $product->stock > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        Stok:
                        <span class="ml-1 font-semibold">
                            {{ $product->stock > 0 ? $product->stock . ' unit' : 'Habis' }}
                        </span>
                    </span>

                    @if($product->stock > 0 && $product->stock <= 5)
                        <span class="text-xs text-red-500 font-semibold">
                            Stok hampir habis!
                        </span>
                    @endif
                </div>
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
                <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">
                        Tertarik dengan produk ini?
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Hubungi penjual untuk tanya stok, nego harga, atau detail lainnya.
                    </p>

                    <div class="space-y-1 text-sm">
                        <div>
                            <a
                                href="https://wa.me/62{{ ltrim($product->seller->pic_phone, '0') }}"
                                target="_blank"
                                class="text-green-600 hover:underline"
                            >
                                Chat via WhatsApp
                            </a>
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

        <hr class="border border-gray-200">

        {{-- DAFTAR ULASAN (TERFILTER) --}}
        <div class="space-y-4">
            @forelse ($filteredReviews as $review)
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
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
    <div class="space-y-4 mt-6">
        <h2 class="text-xl font-semibold text-gray-800">Lainnya di toko ini</h2>

        <div id="recommendation-grid"
            class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
            @if($recommendations->count() > 0)
                @include('components.product-cards', ['products' => $recommendations])
            @else
                <p class="text-gray-500 text-sm col-span-full">
                    Belum ada produk lain di toko ini.
                </p>
            @endif
        </div>

        @if ($recommendations instanceof \Illuminate\Pagination\AbstractPaginator && $recommendations->hasMorePages())
            <div class="flex justify-center mt-4">
                <button
                    id="load-more-recommendations"
                    data-next-page="{{ $recommendations->currentPage() + 1 }}"
                    class="px-6 py-2 text-sm font-medium 
                        bg-white text-green-600 
                        border border-green-600 
                        rounded-full shadow-sm
                        hover:bg-green-50 hover:shadow-md 
                        transition"
                >
                    Tampilkan lebih banyak
                </button>
            </div>
        @endif
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

        // ============ GALLERY IMAGE ============

        const mainImage = document.getElementById('mainProductImage');
        const thumbButtons = document.querySelectorAll('.product-thumb-btn');

        thumbButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const newSrc = this.dataset.image;
                if (!mainImage || !newSrc) return;

                // Ganti src gambar utama
                mainImage.src = newSrc;

                // Update border highlight thumbnail aktif
                thumbButtons.forEach(other => {
                    other.classList.remove('ring-2', 'ring-green-500', 'border-green-500');
                    other.classList.add('border-gray-200');
                });

                this.classList.remove('border-gray-200');
                this.classList.add('ring-2', 'ring-green-500', 'border-green-500');
            });
        });

        // ============ RATING & MODAL ============

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

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // ============ LOAD MORE RECOMMENDATIONS ============

        const recGrid = document.getElementById('recommendation-grid');
        const loadMoreRecBtn = document.getElementById('load-more-recommendations');

        if (recGrid && loadMoreRecBtn) {
            loadMoreRecBtn.addEventListener('click', function () {
                const btn = this;
                const nextPage = btn.dataset.nextPage;

                btn.disabled = true;
                btn.textContent = 'Memuat...';

                const url = new URL("{{ route('product.show', $product) }}", window.location.origin);
                url.searchParams.set('load', 'recommendations');
                url.searchParams.set('rec_page', nextPage);

                // Pertahankan filter rating review kalau ada (optional)
                @if(request('rating'))
                    url.searchParams.set('rating', "{{ request('rating') }}");
                @endif

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    recGrid.insertAdjacentHTML('beforeend', data.html);

                    if (data.has_more) {
                        btn.dataset.nextPage = data.next_page;
                        btn.disabled = false;
                        btn.textContent = 'Tampilkan lebih banyak';
                    } else {
                        btn.remove();
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.disabled = false;
                    btn.textContent = 'Tampilkan lebih banyak';
                    alert('Gagal memuat produk rekomendasi.');
                });
            });
        }

        // ============ SWEETALERT ============

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
