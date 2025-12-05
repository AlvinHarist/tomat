<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi ToMaT</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-5xl w-full flex flex-col md:flex-row">
        
        <!-- Left Panel (Image) -->
        <div class="hidden md:block md:w-1/2 bg-green-50 relative">
            <img src="{{ asset('images/store.png') }}" alt="Shop Illustration" class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-8">
                <div class="text-white">
                    <h2 class="text-3xl font-bold mb-2">Bergabung dengan ToMaT</h2>
                    <p class="text-lg opacity-90">Kelola toko Anda dengan mudah dan jangkau lebih banyak pelanggan.</p>
                </div>
            </div>
        </div>

        <!-- Right Panel (Form) -->
        <div class="w-full md:w-1/2 p-8 md:p-12 overflow-y-auto max-h-screen">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-green-600 mb-2">ToMaT</h1>
                <h2 class="text-2xl font-semibold text-gray-800">Registrasi Toko Baru</h2>
                <p class="text-gray-600 mt-2">Sudah punya akun? <a href="{{ route('seller.login') }}" class="text-green-600 hover:underline font-medium">Masuk</a></p>
            </div>
            
            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="space-y-5" id="registerForm" novalidate>                
                @csrf 
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada form:</h3>
                                <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Nama Toko -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                           placeholder="Masukkan nama toko" required>
                    <p class="text-red-500 text-xs mt-1 hidden error-msg">Nama toko wajib diisi.</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                              placeholder="Ceritakan sedikit tentang toko Anda">{{ old('description') }}</textarea>
                </div>
                
                <!-- Nama PIC -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                           placeholder="Nama Penanggung Jawab" required>
                    <p class="text-red-500 text-xs mt-1 hidden error-msg">Nama PIC wajib diisi.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nomor Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="08xxxxxxxxxx" required pattern="[0-9]+">
                        <p class="text-red-500 text-xs mt-1 hidden error-msg" data-default="Nomor telepon wajib diisi.">Nomor telepon wajib diisi.</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="email@contoh.com" required>
                        <p class="text-red-500 text-xs mt-1 hidden error-msg" data-default="Email wajib diisi.">Email wajib diisi.</p>
                    </div>
                </div>

                <!-- Alamat Section -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Alamat Lengkap</h3>
                    
                    <div class="space-y-4">
            
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                            <select name="provinsi" id="provinsi" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->code }}" {{ old('provinsi') == $province->code ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-red-500 text-xs mt-1 hidden error-msg">Provinsi wajib dipilih.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="kabupatenkota" id="kabupatenkota" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required disabled>
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                            <p class="text-red-500 text-xs mt-1 hidden error-msg">Kabupaten/Kota wajib dipilih.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="kecamatan" id="kecamatan" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <p class="text-red-500 text-xs mt-1 hidden error-msg">Kecamatan wajib dipilih.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan <span class="text-red-500">*</span></label>
                            <select name="kelurahan" id="kelurahan" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required disabled>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <p class="text-red-500 text-xs mt-1 hidden error-msg">Kelurahan wajib dipilih.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RT <span class="text-red-500">*</span></label>
                                <input type="text" name="rt" value="{{ old('rt') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="001" required pattern="[0-9]+">
                                <p class="text-red-500 text-xs mt-1 hidden error-msg" data-default="RT wajib diisi.">RT wajib diisi.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RW <span class="text-red-500">*</span></label>
                                <input type="text" name="rw" value="{{ old('rw') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="002" required pattern="[0-9]+">
                                <p class="text-red-500 text-xs mt-1 hidden error-msg" data-default="RW wajib diisi.">RW wajib diisi.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jalan <span class="text-red-500">*</span></label>
                            <input type="text" name="jalan" value="{{ old('jalan') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Nama Jalan, No. Rumah" required>
                            <p class="text-red-500 text-xs mt-1 hidden error-msg">Alamat jalan wajib diisi.</p>
                        </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Keamanan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors pr-10"
                                       placeholder="Minimal 8 karakter" required minlength="8">
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    👁️
                                </button>
                            </div>
                            
                            <!-- Password Requirements -->
                            <div id="password-feedback" class="mt-2 p-3 bg-gray-50 rounded-lg text-xs hidden">
                                <p class="font-medium text-gray-700 mb-1">Syarat Password:</p>
                                <ul class="space-y-1">
                                    <li id="length" class="flex items-center text-gray-500">
                                        <span class="mr-2">⚪</span> Minimal 8 karakter
                                    </li>
                                    <li id="uppercase" class="flex items-center text-gray-500">
                                        <span class="mr-2">⚪</span> Huruf besar (A-Z)
                                    </li>
                                    <li id="lowercase" class="flex items-center text-gray-500">
                                        <span class="mr-2">⚪</span> Huruf kecil (a-z)
                                    </li>
                                    <li id="number" class="flex items-center text-gray-500">
                                        <span class="mr-2">⚪</span> Angka (0-9)
                                    </li>
                                    <li id="symbol" class="flex items-center text-gray-500">
                                        <span class="mr-2">⚪</span> Simbol (@$!%*?&)
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors pr-10"
                                       placeholder="Ulangi password" required>
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    👁️
                                </button>
                            </div>
                            <p class="text-red-500 text-xs mt-1 hidden" id="error-password-confirm">Password tidak cocok.</p>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Section -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Dokumen Pendukung</h3>
                    
                    <div class="space-y-6">
                        <!-- KTP Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTP PIC <span class="text-red-500">*</span></label>
                            <input type="text" name="ktp_number" value="{{ old('ktp_number') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="16 digit angka" required pattern="[0-9]{16}" maxlength="16">
                            <p class="text-red-500 text-xs mt-1 hidden error-msg" data-default="Nomor KTP wajib diisi.">Nomor KTP wajib diisi.</p>
                        </div>

                        <!-- Foto PIC -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto PIC <span class="text-red-500">*</span></label>
                            <div class="flex items-start space-x-4">
                                <div class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                                    <img id="preview-photo" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                    <span class="text-gray-400 text-xs text-center p-1" id="placeholder-photo">Preview Foto</span>
                                </div>
                                <div class="flex-1">
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        <span class="mr-2">📂</span> Pilih Foto
                                        <input type="file" name="photo" class="hidden file-input" accept="image/*" required data-preview="preview-photo" data-placeholder="placeholder-photo">
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                                    <p class="text-sm text-gray-700 mt-1 file-name truncate">Belum ada file dipilih</p>
                                    <p class="text-red-500 text-xs mt-1 hidden error-msg">Foto PIC wajib diunggah.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Foto KTP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto KTP <span class="text-red-500">*</span></label>
                            <div class="flex items-start space-x-4">
                                <div class="w-40 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                                    <img id="preview-ktp" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                    <span class="text-gray-400 text-xs text-center p-1" id="placeholder-ktp">Preview KTP</span>
                                </div>
                                <div class="flex-1">
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        <span class="mr-2">📂</span> Pilih KTP
                                        <input type="file" name="ktp_file" class="hidden file-input" accept="image/*" required data-preview="preview-ktp" data-placeholder="placeholder-ktp">
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                                    <p class="text-sm text-gray-700 mt-1 file-name truncate">Belum ada file dipilih</p>
                                    <p class="text-red-500 text-xs mt-1 hidden error-msg">Foto KTP wajib diunggah.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" id="submit-button" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Daftar Sekarang
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
            
            // --- 1. Validation Logic (Touched & Dirty) ---
            inputs.forEach(input => {
                // Initial state: not touched
                input.dataset.touched = 'false';

                // Blur event: Mark as touched and validate
                input.addEventListener('blur', function() {
                    this.dataset.touched = 'true';
                    validateField(this);
                });

                // Input/Change event: Validate immediately if already touched
                const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
                input.addEventListener(eventType, function() {
                    if (this.dataset.touched === 'true') {
                        validateField(this);
                    }
                    // Special case for password match
                    if (this.name === 'password' || this.name === 'password_confirmation') {
                        checkPasswordMatch();
                    }
                    // Special case for password complexity
                    if (this.name === 'password') {
                        checkPasswordComplexity(this.value);
                    }
                });
            });

            function validateField(input) {
                let errorMsg = input.parentElement.querySelector('.error-msg');
                
                // Fix for file inputs where error-msg is not a direct sibling
                if (!errorMsg) {
                    const container = input.closest('.flex-1'); // For file inputs structure
                    if (container) {
                        errorMsg = container.querySelector('.error-msg');
                    }
                }

                if (!errorMsg) return true; // If no error container found, assume valid to avoid blocking submit

                let isValid = true;
                let message = errorMsg.dataset.default || errorMsg.textContent;

                // Required check
                if (input.hasAttribute('required') && !input.value.trim()) {
                    isValid = false;
                    message = input.getAttribute('placeholder') ? input.getAttribute('placeholder') + " wajib diisi." : "Field ini wajib diisi.";
                    // Reset to default message if available
                    if (errorMsg.dataset.default) message = errorMsg.dataset.default;
                } 
                // Pattern check (Numbers only)
                else if (input.hasAttribute('pattern')) {
                    const pattern = new RegExp('^' + input.getAttribute('pattern') + '$');
                    if (!pattern.test(input.value)) {
                        isValid = false;
                        if (input.name === 'phone') message = "Nomor telepon hanya boleh angka.";
                        if (input.name === 'rt' || input.name === 'rw') message = "Hanya boleh angka.";
                        if (input.name === 'ktp_number') message = "Nomor KTP harus 16 digit angka.";
                    }
                }
                // Email check
                else if (input.type === 'email' && input.value) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(input.value)) {
                        isValid = false;
                        message = "Format email tidak valid.";
                    }
                }

                if (!isValid) {
                    errorMsg.textContent = message;
                    errorMsg.classList.remove('hidden');
                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.remove('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
                } else {
                    errorMsg.classList.add('hidden');
                    input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.add('border-gray-300', 'focus:border-green-500', 'focus:ring-green-500');
                }
                
                return isValid;
            }

            // --- 2. Password Logic ---
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const confirmError = document.getElementById('error-password-confirm');
            const feedbackBox = document.getElementById('password-feedback');
            
            // Toggle Visibility
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '🙈';
                });
            });

            // Complexity Check
            function checkPasswordComplexity(value) {
                feedbackBox.classList.remove('hidden');
                
                const rules = {
                    length: value.length >= 8,
                    uppercase: /[A-Z]/.test(value),
                    lowercase: /[a-z]/.test(value),
                    number: /\d/.test(value),
                    symbol: /[@$!%*?&]/.test(value)
                };

                for (const [key, valid] of Object.entries(rules)) {
                    const item = document.getElementById(key);
                    if (valid) {
                        item.classList.remove('text-gray-500');
                        item.classList.add('text-green-600');
                        item.querySelector('span').textContent = '✅';
                    } else {
                        item.classList.remove('text-green-600');
                        item.classList.add('text-gray-500');
                        item.querySelector('span').textContent = '⚪';
                    }
                }

                return Object.values(rules).every(Boolean);
            }

            // Match Check
            function checkPasswordMatch() {
                if (confirmInput.value && passwordInput.value !== confirmInput.value) {
                    confirmError.classList.remove('hidden');
                    confirmInput.classList.add('border-red-500');
                } else {
                    confirmError.classList.add('hidden');
                    confirmInput.classList.remove('border-red-500');
                }
            }

            passwordInput.addEventListener('focus', () => feedbackBox.classList.remove('hidden'));
            // passwordInput.addEventListener('blur', () => feedbackBox.classList.add('hidden')); // Optional: hide on blur

            // --- 3. File Upload & Preview ---
            const fileInputs = document.querySelectorAll('.file-input');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    const fileNameDisplay = this.closest('.flex-1').querySelector('.file-name');
                    const previewId = this.dataset.preview;
                    const placeholderId = this.dataset.placeholder;
                    const previewImg = document.getElementById(previewId);
                    const placeholder = document.getElementById(placeholderId);
                    const errorMsg = this.closest('.flex-1').querySelector('.error-msg');

                    if (file) {
                        // Size check (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar! Maksimal 2MB.');
                            this.value = ''; // Reset
                            fileNameDisplay.textContent = 'Belum ada file dipilih';
                            previewImg.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                            return;
                        }

                        fileNameDisplay.textContent = file.name;
                        
                        // Preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewImg.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                        
                        // Hide error
                        if (errorMsg) errorMsg.classList.add('hidden');

                    } else {
                        fileNameDisplay.textContent = 'Belum ada file dipilih';
                        previewImg.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                });
            });

            // --- 4. Form Submit ---
            form.addEventListener('submit', function(e) {
                let hasError = false;
                
                // Validate all inputs
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        hasError = true;
                        input.dataset.touched = 'true'; // Force show error
                    }
                });

                // Validate Password Complexity
                if (!checkPasswordComplexity(passwordInput.value)) {
                    hasError = true;
                    passwordInput.focus();
                    alert('Password belum memenuhi syarat.');
                }

                // Validate Password Match
                if (passwordInput.value !== confirmInput.value) {
                    hasError = true;
                    confirmInput.focus();
                }

                // Validate Region Dropdowns (make sure all are selected)
                const provinsiSelect = document.getElementById('provinsi');
                const kabupatenSelect = document.getElementById('kabupatenkota');
                const kecamatanSelect = document.getElementById('kecamatan');
                const kelurahanSelect = document.getElementById('kelurahan');

                if (!provinsiSelect.value) {
                    hasError = true;
                    provinsiSelect.focus();
                    alert('Provinsi wajib dipilih.');
                } else if (!kabupatenSelect.value) {
                    hasError = true;
                    kabupatenSelect.focus();
                    alert('Kabupaten/Kota wajib dipilih.');
                } else if (!kecamatanSelect.value) {
                    hasError = true;
                    kecamatanSelect.focus();
                    alert('Kecamatan wajib dipilih.');
                } else if (!kelurahanSelect.value) {
                    hasError = true;
                    kelurahanSelect.focus();
                    alert('Kelurahan wajib dipilih.');
                }

                if (hasError) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = document.querySelector('.border-red-500');
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            // --- 5. Dependent Dropdown for Indonesian Regions ---
            const provinsiSelect = document.getElementById('provinsi');
            const kabupatenSelect = document.getElementById('kabupatenkota');
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');

            // Province change
            provinsiSelect.addEventListener('change', function() {
                const provinceCode = this.value;
                
                // Reset dependent dropdowns
                kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                
                kabupatenSelect.value = '';
                kecamatanSelect.value = '';
                kelurahanSelect.value = '';
                
                kabupatenSelect.disabled = true;
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;

                if (provinceCode) {
                    // Fetch cities
                    fetch(`/api/cities/${provinceCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.code;
                                option.textContent = city.name;
                                kabupatenSelect.appendChild(option);
                            });
                            kabupatenSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            alert('Gagal memuat data Kabupaten/Kota. Silakan refresh halaman.');
                        });
                }
            });

            // City change
            kabupatenSelect.addEventListener('change', function() {
                const cityCode = this.value;
                
                // Reset dependent dropdowns
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                
                kecamatanSelect.value = '';
                kelurahanSelect.value = '';
                
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;

                if (cityCode) {
                    // Fetch districts
                    fetch(`/api/districts/${cityCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.code;
                                option.textContent = district.name;
                                kecamatanSelect.appendChild(option);
                            });
                            kecamatanSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching districts:', error);
                            alert('Gagal memuat data Kecamatan. Silakan refresh halaman.');
                        });
                }
            });

            // District change
            kecamatanSelect.addEventListener('change', function() {
                const districtCode = this.value;
                
                // Reset dependent dropdown
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kelurahanSelect.value = '';
                kelurahanSelect.disabled = true;

                if (districtCode) {
                    // Fetch villages
                    fetch(`/api/villages/${districtCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(village => {
                                const option = document.createElement('option');
                                option.value = village.code;
                                option.textContent = village.name;
                                kelurahanSelect.appendChild(option);
                            });
                            kelurahanSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching villages:', error);
                            alert('Gagal memuat data Kelurahan. Silakan refresh halaman.');
                        });
                }
            });
        });
    </script>
</body>
</html>