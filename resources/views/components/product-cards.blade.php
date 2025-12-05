
@foreach ($products as $product)
<a href="{{ route('product.show', $product) }}"
class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
    @php
        $hasImage = is_array($product->images) && !empty($product->images);
        $firstImage = $hasImage ? $product->images[0] : null;
    @endphp

    {{-- Gambar produk --}}
    @if ($hasImage)
        {{-- Jika punya gambar --}}
        <img
            src="{{ asset('storage/' . $firstImage) }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover rounded-lg"
        >
    @else
        {{-- Fallback icon --}}
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 5a2 2 0 012-2h3l2 2h6a2 2 0 012 2v3m0 4v3a2 2 0 01-2 2h-3l-2-2H5a2 2 0 01-2-2v-3m0-4V7m0 0h18m-9 4l3 3m0 0l3-3m-3 3V3" />
        </svg>
    @endif

  {{-- Detail produk --}}
  <div class="p-3 flex flex-col flex-1">

      {{-- Nama --}}
      <h3 class="text-sm font-semibold text-gray-900 leading-tight line-clamp-2">
          {{ $product->name }}
      </h3>

      {{-- Harga --}}
      <div class="text-sm font-bold text-orange-600 mt-1">
          Rp {{ number_format($product->price, 0, ',', '.') }}
      </div>

      {{-- Rating & terjual --}}
      @if(isset($product->rating) || isset($product->sold))
          <div class="flex items-center text-xs text-gray-600 mt-1 gap-1.5">
              @if(isset($product->rating))
                  <span class="text-yellow-500">★</span>
                  <span class="font-semibold">{{ number_format($product->rating, 1) }}</span>
              @endif

              @if(isset($product->sold))
                  <span>•</span>
                  <span>{{ $product->sold }} terjual</span>
              @endif
          </div>
      @endif

      {{-- Lokasi --}}
      @if(isset($product->location))
          <div class="flex items-center text-xs text-gray-500 mt-1 gap-1.5">
              {{ $product->location }}
          </div>
      @endif

      {{-- Menu titik tiga --}}
      <div class="flex justify-end mt-2">
          <button type="button" class="p-1 rounded-full hover:bg-gray-100">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                  <circle cx="5" cy="12" r="2"></circle>
                  <circle cx="12" cy="12" r="2"></circle>
                  <circle cx="19" cy="12" r="2"></circle>
              </svg>
          </button>
      </div>

  </div>

</a>
@endforeach