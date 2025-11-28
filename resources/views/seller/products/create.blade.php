<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Produk - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller/dashboard.css') }}">
    <style>
        .form-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .btn-submit { 
            background-color: #4CAF50; 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .btn-submit:hover { background-color: #45a049; }
        
        .file-upload-container { border: 2px dashed #ddd; padding: 20px; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.3s; }
        .file-upload-container:hover { border-color: #4CAF50; background: #f9f9f9; }
        .file-upload-container input[type="file"] { display: none; }
        
        .image-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 15px; }
        .image-preview { width: 100px; height: 100px; background-color: #eee; border-radius: 6px; overflow: hidden; position: relative; }
        .image-preview img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="user-profile">
            <div class="user-info">
                <div class="user-name">{{ Auth::guard('seller')->user()->pic_name ?? 'Seller' }}</div>
                <div class="user-role">Seller</div>
            </div>
            <div class="avatar"></div>
        </div>

        <div class="menu-title">MENU</div>
        <nav class="nav-links">
            <a href="{{ route('seller.dashboard') }}"><i class="fas fa-home"></i> Overview</a>
            <a href="{{ route('seller.products.create') }}" class="active"><i class="fas fa-upload"></i> Upload Product</a>
            <a href="{{ route('seller.products.index') }}"><i class="fas fa-cubes"></i> My Products</a>
            <a href="#"><i class="fas fa-chart-line"></i> Report</a>
        </nav>
        <div class="logo">ToMaT</div>
    </aside>

    <main class="main-content">
        <h1 class="page-title">Upload Produk Baru</h1>

        <div class="form-card">
            @if ($errors->any())
                <div style="background-color: #fdd; color: #a00; border: 1px solid #f99; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <strong>Terjadi Kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Produk</label>
                    <textarea id="description" name="description">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="images">Foto Produk (Maks. 5)</label>
                    <div class="file-upload-container" onclick="document.getElementById('image-upload').click()">
                        <i class="fas fa-image" style="font-size: 2rem; color: #ccc;"></i>
                        <p style="margin-top: 10px; color: #777;">Klik untuk memilih gambar</p>
                    </div>
                    <input type="file" id="image-upload" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                    
                    <div class="image-preview-grid" id="image-preview-container">
                        </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-cloud-upload-alt"></i> Unggah Produk
                </button>
            </form>
        </div>
    </main>
    
    <script>
        function previewImages(event) {
            const previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = ''; // Hapus preview lama
            const files = event.target.files;

            if (files.length > 5) {
                alert('Maksimal hanya boleh mengunggah 5 gambar.');
                event.target.value = ''; // Reset input file
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = (function(theFile) {
                        return function(e) {
                            const div = document.createElement('div');
                            div.className = 'image-preview';
                            div.innerHTML = '<img src="' + e.target.result + '" alt="' + theFile.name + '">';
                            previewContainer.appendChild(div);
                        };
                    })(file);

                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
</body>
</html>