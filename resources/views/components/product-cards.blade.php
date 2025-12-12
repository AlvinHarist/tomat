@foreach ($products as $product)
<a href="{{ route('product.show', $product) }}"
   class="group relative flex flex-col overflow-hidden rounded-2xl theme-transition
          border border-gray-200/50 bg-white shadow-sm
          hover:shadow-md hover:-translate-y-0.5 transition-all duration-200
          dark:border-white/10 dark:bg-white/5 dark:backdrop-blur-xl
          dark:hover:bg-white/10 dark:hover:border-emerald-300/70
          dark:shadow-sm dark:hover:shadow-2xl">

    @php
        $hasImage = is_array($product->images) && !empty($product->images);
        $firstImage = $hasImage ? $product->images[0] : null;
    @endphp

    {{-- Gambar produk --}}
    <div class="relative w-full aspect-[4/3] overflow-hidden">
        @if ($hasImage)
            <img
                src="{{ asset($firstImage) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover
                       transition-transform duration-300 
                       group-hover:scale-105"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-slate-900/60">
                <svg class="w-10 h-10 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 5a2 2 0 012-2h3l2 2h6a2 2 0 012 2v3m0 4v3a2 2 0 01-2 2h-3l-2-2H5a2 2 0 01-2-2v-3m0-4V7m0 0h18m-9 4l3 3m0 0l3-3m-3 3V3" />
                </svg>
            </div>
        @endif

        <div class="pointer-events-none absolute inset-0 
                    bg-gradient-to-t from-black/25 via-transparent to-transparent
                    dark:from-slate-950/60"></div>
    </div>

    {{-- Detail produk --}}
    <div class="p-3 flex flex-col flex-1">
        <h3 class="text-sm font-semibold leading-tight line-clamp-2
                   text-gray-900 dark:text-slate-50">
            {{ $product->name }}
        </h3>

        <div class="text-sm font-bold mt-1
                    text-emerald-600 dark:text-emerald-300">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </div>

        <div class="flex items-center gap-2 mt-2">
            @if($product->avg_rating > 0)
                <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold
                                 text-gray-800 dark:text-slate-100">
                        {{ number_format($product->avg_rating, 1) }}
                    </span>
                    <span class="text-xs text-amber-400">★</span>
                </div>
                @if($product->review_count > 0)
                    <span class="text-[11px]
                                 text-gray-500 dark:text-slate-300">
                        ({{ $product->review_count }})
                    </span>
                @endif
            @else
                <span class="text-[11px]
                             text-gray-400 dark:text-slate-400">
                    Belum ada rating
                </span>
            @endif
        </div>

        <div class="flex-1"></div>

        <div class="flex justify-end mt-2">
            <button type="button"
                    class="p-1.5 rounded-full 
                           hover:bg-gray-100 
                           dark:hover:bg-white/10 transition">
                <svg class="w-4 h-4 text-gray-400 dark:text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="5" cy="12" r="2"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                    <circle cx="19" cy="12" r="2"></circle>
                </svg>
            </button>
        </div>
    </div>

</a>
@endforeach
