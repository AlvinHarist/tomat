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
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="stock" value="{{ old('stock') }}" min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="100" required>
                            </div>
                        </div>
                        
                        <!-- Product Images (Multiple) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Produk (Maksimal 10) <span class="text-red-500">*</span>
                            </label>
                            <div>
                                <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Pilih Foto (Bisa lebih dari 1)
                                    <input type="file" name="images[]" class="hidden" accept="image/*" multiple required id="images-input" max="10">
                                </label>
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB per file. Pilih 1-10 foto.</p>
                                
                                <!-- Preview Container -->
                                <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
                            </div>
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
            displayPreviews();
        });

        function displayPreviews() {
            previewContainer.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-300">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        </div>
                        <button type="button" onclick="removeImage(${index})" 
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <p class="text-xs text-gray-600 mt-1 text-center truncate">${file.name}</p>
                    `;
                    previewContainer.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imagesInput.files = dt.files;
            
            displayPreviews();
        }
    </script>
</body>
</html>