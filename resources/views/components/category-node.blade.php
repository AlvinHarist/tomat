@php
    /** @var \App\Models\Category $category */
    $isActive    = $activeCategoryId === $category->id;
    $hasChildren = isset($groupedCategories[$category->id]) && $groupedCategories[$category->id]->isNotEmpty();

    // Indentasi per level (px)
    $indent = $level * 14;
@endphp

@if ($hasChildren)
    {{-- ================= PARENT CATEGORY (EXPANDABLE) ================= --}}
    <details class="group"
             {{ $isActive ? 'open' : '' }}
             style="margin-left: {{ $indent }}px">

        <summary
            class="flex items-center justify-between cursor-pointer px-2 py-1.5 rounded-lg
                   transition-all duration-200
                   text-gray-800 dark:text-slate-200
                   hover:bg-gray-100 dark:hover:bg-white/10
                   {{ $isActive ? 'bg-green-50 dark:bg-emerald-500/10' : '' }}"
        >
            <a href="{{ route('search', array_merge(request()->query(), ['category' => $category->id])) }}"
               class="flex items-center gap-2 flex-1">

                {{-- ICON BULAT --}}
                <span class="w-6 h-6 flex items-center justify-center rounded-full 
                             text-[11px] font-semibold
                             bg-gray-100 text-gray-700
                             dark:bg-white/10 dark:text-emerald-200
                             {{ $isActive ? 'bg-green-100 text-green-700 dark:bg-emerald-400/20 dark:text-emerald-300' : '' }}">
                    {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                </span>

                {{-- NAMA --}}
                <span class="font-medium text-sm
                             {{ $isActive ? 'text-green-600 dark:text-emerald-300' : 'text-gray-800 dark:text-slate-200' }}">
                    {{ $category->name }}
                </span>
            </a>

            {{-- ICON ARROW --}}
            <svg class="w-3.5 h-3.5 ml-2 shrink-0
                        transition-transform duration-300
                        text-gray-400 dark:text-slate-400
                        group-open:rotate-180"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </summary>

        {{-- CHILDREN --}}
        <div class="mt-1 space-y-1 border-l border-gray-200/60 dark:border-white/10 ml-3 pl-2">
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
    {{-- ================= LEAF CATEGORY (NO CHILD) ================= --}}
    <a href="{{ route('search', array_merge(request()->query(), ['category' => $category->id])) }}"
       class="flex items-center justify-between px-2 py-1.5 rounded-lg
              transition-all duration-200
              hover:bg-gray-100 dark:hover:bg-white/10
              {{ $isActive ? 'bg-green-50 dark:bg-emerald-500/10' : '' }}"
       style="margin-left: {{ $indent }}px"
    >
        <span class="flex items-center gap-2">

            {{-- ICON --}}
            <span class="w-6 h-6 flex items-center justify-center rounded-full
                         text-[11px] font-semibold
                         bg-gray-100 text-gray-700
                         dark:bg-white/10 dark:text-emerald-200
                         {{ $isActive ? 'bg-green-100 text-green-700 dark:bg-emerald-400/20 dark:text-emerald-300' : '' }}">
                {{ strtoupper(mb_substr($category->name, 0, 1)) }}
            </span>

            {{-- TEXT --}}
            <span class="font-medium text-sm 
                         {{ $isActive ? 'text-green-600 dark:text-emerald-300' : 'text-gray-800 dark:text-slate-200' }}">
                {{ $category->name }}
            </span>
        </span>

        {{-- COUNT PRODUK (opsional) --}}
        @if(isset($category->products_count))
            <span class="text-[11px]
                         text-gray-400 dark:text-slate-400">
                {{ $category->products_count }}
            </span>
        @endif
    </a>
@endif
