<li class="tree-item">
    <div class="tree-content">
        <div class="tree-info">
            <div class="cat-name">{{ $category->name }}</div>
            <div class="cat-desc">{{ $category->description ?? '-' }}</div>
        </div>
        <div class="tree-actions">
            <!-- Tombol Hapus -->
            <form action="{{ route('owner.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Semua sub-kategori juga akan terhapus.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>

    <!-- Jika punya anak, panggil diri sendiri (Rekursif) -->
    @if($category->children->count() > 0)
        <ul class="tree-list tree-children">
            @foreach($category->children as $child)
                @include('owner.categories.category_item', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>