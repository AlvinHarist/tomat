@php
    /** @var \App\Models\Category $category */
    $isActive    = $activeCategoryId === $category->id;
    $hasChildren = isset($groupedCategories[$category->id]) && $groupedCategories[$category->id]->isNotEmpty();

    // Indentasi per level (px)
    $indent = $level * 14;
@endphp

@if ($hasChildren)
    {{-- Kategori yang punya anak: bisa di-expand/collapse --}}
    <details class="group" {{ $isActive ? 'open' : '' }}>
        <summary class="flex items-center justify-between cursor-pointer px-2 py-1 rounded-lg hover:bg-gray-50">
            <a href="{{ route('search', array_merge(request()->query(), ['category' => $category->id])) }}"
               class="flex-1 flex items-center gap-2"
               style="margin-left: {{ $indent }}px">

                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-[11px] font-semibold text-gray-700">
                    {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                </span>

                <span class="font-medium {{ $isActive ? 'text-green-600' : 'text-gray-800' }}">
                    {{ $category->name }}
                </span>
            </a>

            {{-- Icon panah kecil kayak Tokopedia --}}
            <svg class="w-3 h-3 ml-2 transition-transform duration-200 group-open:rotate-180 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </summary>

        {{-- Anak-anak kategori --}}
        <div class="mt-1 space-y-1">
            @foreach ($groupedCategories[$category->id] as $child)
                @include('components.category-node', [
                    'category'          => $child,
                    'groupedCategories' => $groupedCategories,
                    'level'             => $level + 1,
                    'activeCategoryId'  => $activeCategoryId,
                ])
            @endforeach
        </div>
    </details>
@else
    {{-- Kategori tanpa anak: hanya link saja dengan sedikit indent --}}
    <a href="{{ route('search', array_merge(request()->query(), ['category' => $category->id])) }}"
       class="flex items-center justify-between px-2 py-1 rounded-lg hover:bg-gray-50"
       style="margin-left: {{ $indent }}px">

        <span class="flex items-center gap-2">
            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-[11px] font-semibold text-gray-700">
                {{ strtoupper(mb_substr($category->name, 0, 1)) }}
            </span>

            <span class="font-medium {{ $isActive ? 'text-green-600' : 'text-gray-800' }}">
                {{ $category->name }}
            </span>
        </span>

        @if(isset($category->products_count))
            <span class="text-[11px] text-gray-400">
                {{ $category->products_count }}
            </span>
        @endif
    </a>
@endif
