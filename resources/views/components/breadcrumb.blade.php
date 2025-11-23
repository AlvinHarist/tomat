<nav class="text-xs sm:text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1">
        @foreach ($items as $index => $item)
            @php
                $isLast = $index === count($items) - 1;
                $label  = $item['label'] ?? '';
                $url    = $item['url']  ?? null;
            @endphp

            {{-- separator › kecuali item pertama --}}
            @if ($index > 0)
                <span class="text-gray-400">›</span>
            @endif

            @if (! $isLast && $url)
                <li>
                    <a href="{{ $url }}" class="hover:text-green-600">
                        {{ $label }}
                    </a>
                </li>
            @else
                <li class="text-gray-800 font-medium">
                    {{ $label }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
