<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Style untuk multiple image preview agar tampil konsisten dengan gaya Owner */
        #preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
            background: #fff;
            padding: 5px;
            /* Tambahkan shadow tipis agar seperti card */
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
        }
        .preview-item img {
            width: 100%;
            height: 100px; 
            object-fit: cover;
            border-radius: 6px;
        }
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #e53e3e;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
            line-height: 1; /* Perbaiki centering icon */
        }
    </style>
</head>
<body>
    @include('seller.partials.sidebar')
    
    <main class="main-content">
        <div class="page-container">
            <div class="form-header" style="justify-content: flex-start;">
                <a href="{{ route('seller.products.index') }}" style="color: #999;">
                    <i class="fas fa-arrow-left" style="font-size: 1.5rem;"></i>
                </a>
                <div>
                    <h1>Tambah Produk Baru</h1>
                    <p>Lengkapi informasi produk Anda</p>
                </div>
            </div>
            
            <div>
                
                @if($errors->any())
                <div class="alert alert-danger">
                    <h3 style="font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">Terdapat kesalahan:</h3>
                    <ul style="list-style-type: disc; margin-left: 20px; font-size: 0.85rem;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="card">
                    @csrf
                    
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label class="form-label" for="name">Nama Produk <span class="text-required">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input" placeholder="Contoh: Baju Koko Premium" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="category_id">Kategori <span class="text-required">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi Produk <span class="text-required">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-textarea" placeholder="Jelaskan detail produk Anda..." required>{{ old('description') }}</textarea>
                            <p class="text-muted mt-1">Jelaskan spesifikasi, bahan, ukuran, dan keunggulan produk</p>
                        </div>
                        
                        <div class="form-row two-col">
                            <div class="form-group">
                                <label class="form-label" for="price">Harga (Rp) <span class="text-required">*</span></label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="1000"
                                         class="form-input" placeholder="50000" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="stock">Stok <span class="text-required">*</span></label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="1"
                                         class="form-input" placeholder="100" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Foto Produk (Maksimal 10) <span class="text-required">*</span></label>
                            
                            <label class="btn btn-outline" for="images-input">
                                <i class="fas fa-image" style="margin-right: 8px;"></i> Pilih Foto (Bisa lebih dari 1)
                            </label>
                            <input type="file" name="images[]" class="hidden" accept="image/*" multiple id="images-input" max="10">
                            <p class="text-muted mt-1">Format: JPG, PNG. Maksimal 2MB per file. Pilih 1-10 foto.</p>
                            
                            <div id="preview-container"></div>
                        </div>
                        
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Produk
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
    
    <script>
        // Multiple images preview
        const imagesInput = document.getElementById('images-input');
        const previewContainer = document.getElementById('preview-container');
        let selectedFiles = [];

        imagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Validate file count
            if (files.length > 10) {
                alert('Maksimal 10 foto!');
                e.target.value = '';
                displayPreviews([]);
                return;
            }
            
            // Validate file sizes
            for (let file of files) {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB per file.`);
                    e.target.value = '';
                    displayPreviews([]);
                    return;
                }
            }
            
            selectedFiles = files;
            displayPreviews(selectedFiles);
        });

        function displayPreviews(files) {
            previewContainer.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}">
                        <button type="button" onclick="removeImage(${index})" class="remove-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input (PENTING: menggunakan DataTransfer untuk memperbarui file input)
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imagesInput.files = dt.files;
            
            displayPreviews(selectedFiles);
        }
        
    </script>
</body>
</html>