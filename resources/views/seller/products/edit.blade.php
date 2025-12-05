<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - ToMaT</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('seller.partials.sidebar')
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <a href="{{ route('seller.products.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Edit Produk</h1>
                        <p class="text-gray-600 mt-2">Perbarui informasi produk Anda</p>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div>
                
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Form -->
                <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        
                        <!-- Product Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Contoh: Baju Koko Premium" required>
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Produk <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                      placeholder="Jelaskan detail produk Anda..." required>{{ old('description', $product->description) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Jelaskan spesifikasi, bahan, ukuran, dan keunggulan produk</p>
                        </div>
                        
                        <!-- Price & Stock -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="50000" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="100" required>
                            </div>
                        </div>
                        
                        <!-- Product Images (Multiple) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Produk (Maksimal 10)
                            </label>
                            
                            <!-- Existing Images -->
                            @if($product->images && is_array($product->images) && count($product->images) > 0)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4" id="existing-images">
                                    @foreach($product->images as $index => $image)
                                    <div class="relative group">
                                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-300">
                                            <img src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover">
                                        </div>
                                        <button type="button" onclick="markForDeletion('{{ $image }}', this)" 
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Add New Images -->
                            <div>
                                <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Tambah Foto Baru
                                    <input type="file" name="images[]" class="hidden" accept="image/*" multiple id="images-input">
                                </label>
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB per file. Total maksimal 10 foto.</p>
                                
                                <!-- New Images Preview -->
                                <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex items-center justify-end space-x-3">
                        <a href="{{ route('seller.products.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            Update Produk
                        </button>
                    </div>
                    
                </form>
                
            </div>
        </main>
    </div>
    
    <script>
        // Multiple images preview
        const imagesInput = document.getElementById('images-input');
        const previewContainer = document.getElementById('preview-container');
        let selectedFiles = [];
        let imagesToDelete = [];

        imagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const existingCount = document.querySelectorAll('#existing-images > div:not(.deleted)').length;
            const totalCount = existingCount + files.length - imagesToDelete.length;
            
            // Validate total file count
            if (totalCount > 10) {
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
                        <button type="button" onclick="removeNewImage(${index})" 
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

        function removeNewImage(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            imagesInput.files = dt.files;
            
            displayPreviews();
        }

        function markForDeletion(imagePath, button) {
            const parentDiv = button.closest('.group');
            
            if (parentDiv.classList.contains('deleted')) {
                // Unmark for deletion
                parentDiv.classList.remove('deleted', 'opacity-50');
                button.classList.remove('bg-green-500');
                button.classList.add('bg-red-500');
                button.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>`;
                
                imagesToDelete = imagesToDelete.filter(img => img !== imagePath);
                
                // Remove hidden input
                document.querySelector(`input[name="delete_images[]"][value="${imagePath}"]`)?.remove();
            } else {
                // Mark for deletion
                parentDiv.classList.add('deleted', 'opacity-50');
                button.classList.remove('bg-red-500');
                button.classList.add('bg-green-500');
                button.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12l4 4m0 0l8-8m-8 8H2"/>
                </svg>`;
                
                imagesToDelete.push(imagePath);
                
                // Add hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imagePath;
                document.querySelector('form').appendChild(input);
            }
        }
    </script>
        </div>
    </div>
</body>
</html>
