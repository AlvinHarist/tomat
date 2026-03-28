<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Tambahkan style untuk multiple image preview agar tampil konsisten dengan gaya Owner */
        #images-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
        }
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
            background: #fff;
            padding: 5px;
            text-align: center;
        }
        .preview-item img {
            width: 100%;
            height: 100px; /* Tinggi tetap untuk preview */
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
        }
        .preview-item.deleted {
            opacity: 0.5;
            border-color: #e53e3e;
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
                    <h1>Edit Produk</h1>
                    <p>Perbarui informasi produk Anda</p>
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
                
                <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label class="form-label" for="name">Nama Produk <span class="text-required">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-input" placeholder="Contoh: Baju Koko Premium" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="category_id">Kategori <span class="text-required">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi Produk <span class="text-required">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-textarea" placeholder="Jelaskan detail produk Anda..." required>{{ old('description', $product->description) }}</textarea>
                            <p class="text-muted mt-1">Jelaskan spesifikasi, bahan, ukuran, dan keunggulan produk</p>
                        </div>
                        
                        <div class="form-row two-col">
                            <div class="form-group">
                                <label class="form-label" for="price">Harga (Rp) <span class="text-required">*</span></label>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" min="0" step="1000"
                                        class="form-input" placeholder="50000" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="stock">Stok <span class="text-required">*</span></label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                        class="form-input" placeholder="100" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Foto Produk (Maksimal 10)</label>
                            
                            @if($product->images && is_array($product->images) && count($product->images) > 0)
                            <div style="margin-bottom: 15px;">
                                <p class="text-muted" style="margin-bottom: 8px;">Foto saat ini (klik untuk hapus):</p>
                                <div id="images-preview-grid">
                                    @foreach($product->images as $image)
                                    <div class="preview-item existing-image" data-path="{{ $image }}">
                                        <img src="{{ asset('storage/' . $image) }}">
                                        <button type="button" onclick="markForDeletion('{{ $image }}', this)" class="remove-btn" title="Hapus foto ini">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <p class="text-muted" style="font-size: 0.75rem; margin-top: 5px;">Existing</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <label class="btn btn-outline" for="images-input">
                                <i class="fas fa-image" style="margin-right: 8px;"></i> Tambah Foto Baru
                            </label>
                            <input type="file" name="images[]" class="hidden" accept="image/*" multiple id="images-input">
                            <p class="text-muted mt-1">Format: JPG, PNG. Maksimal 2MB per file. Total maksimal 10 foto.</p>
                            
                            <div id="new-preview-container" style="margin-top: 15px;">
                                <div id="images-preview-grid"></div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Produk
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
    
    <script>
        const imagesInput = document.getElementById('images-input');
        const newPreviewContainer = document.getElementById('new-preview-container').querySelector('#images-preview-grid');
        const existingImagesGrid = document.getElementById('images-preview-grid');
        let selectedFiles = [];

        // Fungsi untuk menghitung total gambar
        function countTotalImages() {
            const existingCount = existingImagesGrid ? existingImagesGrid.querySelectorAll('.existing-image:not(.deleted)').length : 0;
            const newCount = selectedFiles.length;
            return existingCount + newCount;
        }

        // --- Penanganan Gambar Baru ---
        imagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Validate file count
            if (countTotalImages() + files.length > 10) {
                alert('Total maksimal 10 foto!');
                e.target.value = '';
                return;
            }
            
            // Validate file sizes
            for (let file of files) {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB per file.`);
                    e.target.value = '';
                    return;
                }
            }
            
            selectedFiles = [...selectedFiles, ...files]; // Tambahkan file baru ke array yang sudah ada
            displayNewPreviews();
        });

        function displayNewPreviews() {
            newPreviewContainer.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item new-image';
                    div.innerHTML = `
                        <img src="${e.target.result}">
                        <button type="button" onclick="removeNewImage(${index})" class="remove-btn" title="Hapus foto baru">
                            <i class="fas fa-times"></i>
                        </button>
                        <p class="text-muted" style="font-size: 0.75rem; margin-top: 5px;">Baru</p>
                    `;
                    newPreviewContainer.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
            // Update input files agar sesuai dengan selectedFiles
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imagesInput.files = dt.files;
        }

        function removeNewImage(index) {
            selectedFiles.splice(index, 1);
            displayNewPreviews();
        }

        // --- Penanganan Gambar Lama (Hapus) ---
        function markForDeletion(imagePath, button) {
            const parentDiv = button.closest('.preview-item');
            
            if (parentDiv.classList.contains('deleted')) {
                // Unmark for deletion
                parentDiv.classList.remove('deleted');
                button.innerHTML = `<i class="fas fa-times"></i>`;
                button.style.backgroundColor = '#e53e3e';
                
                // Hapus hidden input
                document.querySelector(`input[name="delete_images[]"][value="${imagePath}"]`)?.remove();
            } else {
                // Mark for deletion
                if (countTotalImages() - 1 < 1) { // Minimal harus ada 1 gambar tersisa
                    alert('Produk harus memiliki minimal satu gambar!');
                    return;
                }
                
                parentDiv.classList.add('deleted');
                button.innerHTML = `<i class="fas fa-undo-alt"></i>`;
                button.style.backgroundColor = '#4CAF50';
                
                // Tambahkan hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imagePath;
                document.querySelector('form').appendChild(input);
            }
        }
        
        
        window.markForDeletion = markForDeletion;
    </script>
</body>
</html>