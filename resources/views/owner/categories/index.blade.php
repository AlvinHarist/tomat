<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - ToMaT Owner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <!-- Tambahan CSS untuk Tree View -->
    <style>
        .category-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px; }
        .panel { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        
        /* Form Styles */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
        .btn-save { width: 100%; padding: 10px; background: #21BD38; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-save:hover { background: #45a049; }

        /* Tree List Styles */
        .tree-list { list-style: none; padding-left: 0; }
        .tree-item { margin-bottom: 10px; border: 1px solid #f0f0f0; border-radius: 6px; overflow: hidden; }
        .tree-content { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: #fdfdfd; }
        .tree-info { flex: 1; }
        .cat-name { font-weight: bold; color: #333; }
        .cat-desc { font-size: 0.8rem; color: #888; }
        .tree-actions { display: flex; gap: 5px; }
        .btn-icon { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; color: white; }
        .btn-edit { background: #ffb74d; }
        .btn-delete { background: #e57373; }

        /* Child indentation */
        .tree-children { padding-left: 20px; border-left: 2px solid #eee; margin-left: 10px; margin-top: 5px; }
        
        .alert-success { background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>

    @include('owner.sidebar')

    <main class="main-content">
        <h1 class="page-title">Manajemen Kategori</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="category-grid">
            
            <!-- PANEL KIRI: Form Tambah -->
            <div class="panel">
                <h3 style="margin-top: 0; color: #21BD38;">Tambah Kategori</h3>
                <form action="{{ route('owner.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Elektronik">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Induk Kategori (Opsional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- Jadikan Kategori Utama --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                <!-- Tampilkan anak level 1 untuk dipilih jadi induk juga -->
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-save">Simpan Kategori</button>
                </form>
            </div>

            <!-- PANEL KANAN: Daftar Kategori -->
            <div class="panel">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #555;">Struktur Kategori</h3>
                
                <ul class="tree-list">
                    @foreach($categories as $category)
                        @include('owner.categories.category_item', ['category' => $category])
                    @endforeach
                </ul>
            </div>

        </div>
    </main>

</body>
</html>