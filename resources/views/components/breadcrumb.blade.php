<nav class="theme-transition mb-5 select-none" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1 text-xs sm:text-sm">

        @foreach ($items as $index => $item)
            @php
                $isLast = $index === count($items) - 1;
                $label  = $item['label'] ?? '';
                $url    = $item['url']  ?? null;
            @endphp

            {{-- Separator: jangan tampil di item pertama --}}
            @if ($index > 0)
                <span class="text-gray-400 dark:text-slate-500 px-1">/</span>
            @endif

            {{-- ITEM BUKAN TERAKHIR (LINK) --}}
            @if (! $isLast && $url)
                <li>
                    <a href="{{ $url }}"
                       class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md 
                              text-gray-600 dark:text-slate-300
                              hover:text-green-600 dark:hover:text-emerald-300
                              hover:bg-gray-100 dark:hover:bg-white/10
                              transition-colors duration-200">

                        {{ $label }}
                    </a>
                </li>

            {{-- ITEM TERAKHIR (ACTIVE STATE) --}}
            @else
                <li class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md
                           font-semibold
                           text-gray-800 dark:text-white
                           bg-gray-200/40 dark:bg-white/10">
                    {{ $label }}
                </li>
            @endif

        @endforeach

    </ol>
</nav>
