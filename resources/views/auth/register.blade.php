<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register ToMaT</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

    </head>
<body>
    <div class="container">
        
        <div class="left-panel">
            <img src="{{ asset('images/store.png') }}" alt="Shop Illustration" style="max-width: 100%;">
        </div>

        <div class="right-panel">
            <div class="logo">ToMaT</div>
            <h2>Register</h2>
            <p>Already have ToMaT account? <br> <a href="#">Login</a></p>
            
            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">                
                @csrf @if ($errors->any())
                    <div style="color: red; margin-bottom: 15px; background: #ffebee; padding: 10px; border-radius: 5px;">
                        <strong>Whoops! Something went wrong.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label>Shop name</label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Enter shop name"
                           required> <span class="error-message">Nama toko wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Enter shop description">{{ old('description') }}</textarea>
                </div>

                <input type="hidden" name="province" value="Jawa Barat">
                <input type="hidden" name="city" value="Bandung">

                <div class="form-group">
                    <label>District</label>
                    <input type="text" name="district" value="{{ old('district') }}" placeholder="District"
                           required> <span class="error-message">Distrik wajib diisi.</span>
                </div>

                <input type="hidden" name="pic_address" value="Alamat PIC default">
                <input type="hidden" name="pic_rt" value="001">
                <input type="hidden" name="pic_rw" value="001">
                <input type="hidden" name="pic_village" value="Kelurahan PIC default">

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number (min 10 digit)" 
                            required pattern="[0-9]{10,20}"> <span class="error-message">Nomor HP wajib diisi (hanya angka, min. 10 digit).</span>
                </div>

                <div class="form-group">
                    <label>Name of PIC</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter PIC name"
                           required> <span class="error-message">Nama PIC wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter login email"
                           required> <span class="error-message">Masukkan format email yang valid.</span>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password"
                           id="password" required minlength="8">
                    <ul id="password-feedback" class="error-message" style="color: red; font-size: 0.9em; margin-top: 5px;">
                        <li id="length" class="invalid">Minimal 8 karakter</li>
                        <li id="uppercase" class="invalid">Mengandung huruf besar</li>
                        <li id="lowercase" class="invalid">Mengandung huruf kecil</li>
                        <li id="number" class="invalid">Mengandung angka</li>
                        <li id="symbol" class="invalid">Mengandung simbol (@$!%*?&)</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password"
                           id="password_confirmation" required>
                    <span class="error-message" id="error-password-confirm">Password tidak cocok.</span>
                </div>

                <div class="form-group">
                    <label>PIC KTP Number</label>
                    <input type="text" name="ktp_number" value="{{ old('ktp_number') }}" placeholder="Enter 16-digit KTP number"
                           required pattern="[0-9]{16}"> <span class="error-message">KTP harus 16 digit angka.</span>
                </div>

                <div class="form-group">
                    <label>Upload photo of PIC</label>
                    <input type="file" name="photo"
                           required> <span class="error-message">Foto PIC wajib di-upload.</span>
                </div>
                
                <div class="form-group">
                    <label>Upload photo of PIC KTP</label>
                    <input type="file" name="ktp_file"
                           required> <span class="error-message">Foto KTP wajib di-upload.</span>
                </div>

                <button type="submit" id="submit-button" disabled style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    Register
                </button>

            </form>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const form = document.querySelector('form');
            const submitButton = document.querySelector('#submit-button');
            const passwordInput = document.querySelector('#password');
            const confirmPasswordInput = document.querySelector('#password_confirmation');
            const confirmPasswordError = document.querySelector('#error-password-confirm');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            // --- Referensi untuk List Password ---
            const passwordFeedback = document.querySelector('#password-feedback');
            const feedbackItems = {
                length: document.querySelector('#length'),
                uppercase: document.querySelector('#uppercase'),
                lowercase: document.querySelector('#lowercase'),
                number: document.querySelector('#number'),
                symbol: document.querySelector('#symbol')
            };
            let isPasswordComplex = false; // Flag untuk melacak kompleksitas password
        
            // --- PERBAIKAN 1: Buat fungsi validasi password terpisah ---
            function validatePassword() {
                const value = passwordInput.value;
                let allValid = true; // Anggap semua valid
            
                // Cek 1: Panjang
                if (value.length >= 8) {
                    feedbackItems.length.className = 'valid';
                } else {
                    feedbackItems.length.className = 'invalid';
                    allValid = false;
                }
                // Cek 2: Huruf Besar
                if (/[A-Z]/.test(value)) {
                    feedbackItems.uppercase.className = 'valid';
                } else {
                    feedbackItems.uppercase.className = 'invalid';
                    allValid = false;
                }
                // Cek 3: Huruf Kecil
                if (/[a-z]/.test(value)) {
                    feedbackItems.lowercase.className = 'valid';
                } else {
                    feedbackItems.lowercase.className = 'invalid';
                    allValid = false;
                }
                // Cek 4: Angka
                if (/\d/.test(value)) {
                    feedbackItems.number.className = 'valid';
                } else {
                    feedbackItems.number.className = 'invalid';
                    allValid = false;
                }
                // Cek 5: Simbol
                if (/[@$!%*?&]/.test(value)) {
                    feedbackItems.symbol.className = 'valid';
                } else {
                    feedbackItems.symbol.className = 'invalid';
                    allValid = false;
                }
                
                isPasswordComplex = allValid; // Update flag
            }
        
            // --- PERBAIKAN 2: Pindahkan Event Listener ke luar fungsi ---
            
            // Tampilkan/sembunyikan list saat fokus
            passwordInput.addEventListener('focus', function() {
                passwordFeedback.style.display = 'block';
            });
            
            // Jalankan validasi password setiap kali mengetik di box password
            passwordInput.addEventListener('input', function() {
                validatePassword();
                checkFormValidity(); // Cek ulang tombol submit
            });
        
            // Cek validasi untuk tombol submit
            function checkFormValidity() {
                let isFormValid = form.checkValidity(); // Cek aturan HTML5
                let isPasswordMatch = true;
            
                // Cek konfirmasi password
                if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value.length > 0) {
                    isPasswordMatch = false;
                    confirmPasswordError.style.display = 'block';
                    confirmPasswordInput.classList.add('interacted');
                } else {
                    confirmPasswordError.style.display = 'none';
                }
                
                // Tombol Aktif JIKA: Form HTML5 valid DAN Password kompleks DAN Password cocok
                if (isFormValid && isPasswordMatch && isPasswordComplex) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }
        
            // Jalankan checkFormValidity setiap kali user mengetik DI MANA SAJA
            inputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    input.classList.add('interacted');
                    checkFormValidity();
                });
            });
        });
    </script>
</body>
</html>