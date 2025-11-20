<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi ToMaT</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

    </head>
<body>
    <div class="container">
        
        <div class="left-panel">
            <img src="{{ asset('images/store.png') }}" alt="Shop Illustration" style="max-width: 100%;">
        </div>

        <div class="right-panel">
            <div class="logo">ToMaT</div>
            <h2>Registrasi</h2>
            <p>Sudah punya akun? <br> <a href="#">Masuk</a></p>
            
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
                    <label>Nama Toko</label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Masukkan nama toko"
                           required> <span class="error-message">Nama toko wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" placeholder="Masukkan deskripsi toko">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Nama PIC</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama PIC"
                    required> <span class="error-message">Nama PIC wajib diisi.</span>
                </div>
                
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon" 
                            required pattern="[0-9]{10,20}"> <span class="error-message">Nomor telepon wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email PIC"
                           required> <span class="error-message">Masukkan format email yang valid.</span>
                </div>

                <div class="form-group">
                    <label>Alamat Jalan</label>
                    <input type="text" name="jalan" value="{{ old('jalan') }}" placeholder="Masukkan nama jalan"
                    required> <span class="error-message">Nama Jalan wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Alamat RT</label>
                    <input type="text" name="rt" value="{{ old('rt') }}" placeholder="Masukkan RT"
                    required> <span class="error-message">RT wajib diisi.</span>
                </div>
                
                <div class="form-group">
                    <label>Alamat RW</label>
                    <input type="text" name="rw" value="{{ old('rw') }}" placeholder="Masukkan RW"
                    required> <span class="error-message">RW wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Alamat Kelurahan</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" placeholder="Masukkan nama kelurahan"
                    required> <span class="error-message">Nama kelurahan wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Alamat Kabupaten/Kota</label>
                    <input type="text" name="kabupatenkota" value="{{ old('kabupatenkota') }}" placeholder="Masukkan nama kabupaten/kota"
                    required> <span class="error-message">Nama kabupaten/kota wajib diisi.</span>
                </div>

                <div class="form-group">
                    <label>Alamat Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi') }}" placeholder="Masukkan nama provinsi"
                    required> <span class="error-message">Nama provinsi wajib diisi.</span>
                </div>

                
                <input type="hidden" name="pic_address" value="Alamat PIC default">
                <input type="hidden" name="pic_rt" value="001">
                <input type="hidden" name="pic_rw" value="001">
                <input type="hidden" name="pic_village" value="Kelurahan PIC default">




                <div class="form-group">
                    <label>Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" placeholder="Masukkan password"
                               id="password" required minlength="8" style="padding-right: 40px;">
                        <button type="button" id="toggle-password" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 18px;">
                            👁️
                        </button>
                    </div>
                    <ul id="password-feedback" class="error-message" style="color: red; font-size: 0.9em; margin-top: 5px;">
                        <li id="length" class="invalid">Minimal 8 karakter</li>
                        <li id="uppercase" class="invalid">Mengandung huruf besar</li>
                        <li id="lowercase" class="invalid">Mengandung huruf kecil</li>
                        <li id="number" class="invalid">Mengandung angka</li>
                        <li id="symbol" class="invalid">Mengandung simbol (@$!%*?&)</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi password"
                               id="password_confirmation" required style="padding-right: 40px;">
                        <button type="button" id="toggle-password-confirm" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 18px;">
                            👁️
                        </button>
                    </div>
                    <span class="error-message" id="error-password-confirm">Password tidak cocok.</span>
                </div>

                <div class="form-group">
                    <label>Nomor PIC KTP</label>
                    <input type="text" name="ktp_number" value="{{ old('ktp_number') }}" placeholder="Masukkan 16 digit nomor KTP"
                           required pattern="[0-9]{16}"> <span class="error-message">KTP harus 16 digit angka.</span>
                </div>

                <div class="form-group">
                    <label>Unggah foto PIC</label>
                    <input type="file" name="photo"
                           required> <span class="error-message">Foto PIC wajib di-upload.</span>
                </div>
                
                <div class="form-group">
                    <label>Unggah Foto KTP</label>
                    <input type="file" name="ktp_file"
                           required> <span class="error-message">Foto KTP wajib di-upload.</span>
                </div>

                <button type="submit" id="submit-button" disabled style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    Daftar
                </button>

            </form>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. AMBIL ELEMEN (Gunakan try-catch atau check null agar aman)
            const form = document.querySelector('form');
            const submitButton = document.querySelector('#submit-button'); // Pastikan ID ini ada di tombol submit
            const passwordInput = document.querySelector('#password');
            
            // Elemen opsional (jika belum dibuat di HTML, biar script tidak error)
            const confirmPasswordInput = document.querySelector('#password_confirmation');
            const confirmPasswordError = document.querySelector('#error-password-confirm');
            
            // Elemen List Validasi
            const passwordFeedback = document.querySelector('#password-feedback');
            const feedbackItems = {
                length: document.querySelector('#length'),
                uppercase: document.querySelector('#uppercase'),
                lowercase: document.querySelector('#lowercase'),
                number: document.querySelector('#number'),
                symbol: document.querySelector('#symbol')
            };

            let isPasswordComplex = false;

            // --- FUNGSI 1: Validasi Password ---
            function validatePassword() {
                // Jika input password tidak ditemukan, stop
                if (!passwordInput) return;

                const value = passwordInput.value;
                let allValid = true;

                // Helper: Sembunyikan jika valid, Tampilkan jika invalid
                const toggleDisplay = (element, isValid) => {
                    if (!element) return; // Safety check jika ID salah ketik

                    if (isValid) {
                        element.style.display = 'none'; // MENGHILANG
                        element.classList.remove('invalid');
                        element.classList.add('valid');
                    } else {
                        element.style.display = 'list-item'; // MUNCUL KEMBALI (Penting: pakai list-item untuk LI)
                        element.style.color = 'red'; 
                        element.classList.remove('valid');
                        element.classList.add('invalid');
                        allValid = false;
                    }
                };

                // Cek Logika
                toggleDisplay(feedbackItems.length, value.length >= 8);
                toggleDisplay(feedbackItems.uppercase, /[A-Z]/.test(value));
                toggleDisplay(feedbackItems.lowercase, /[a-z]/.test(value));
                toggleDisplay(feedbackItems.number, /\d/.test(value));
                toggleDisplay(feedbackItems.symbol, /[@$!%*?&#+_-]/.test(value));

                isPasswordComplex = allValid;

                // Atur visibilitas container UL (Feedback list)
                if (passwordFeedback) {
                    if (allValid) {
                        passwordFeedback.style.display = 'none'; // Sembunyikan kotak jika semua benar
                    } else {
                        passwordFeedback.style.display = 'block'; // Tampilkan kotak jika masih ada salah
                    }
                }
            }

            // --- FUNGSI 2: Cek Seluruh Form (termasuk Confirm Password) ---
            function checkFormValidity() {
                // Jika elemen pendukung tidak lengkap, bypass saja biar tidak error
                if (!submitButton) return;

                let isFormValid = form ? form.checkValidity() : false;
                let isPasswordMatch = true;

                // Cek Konfirmasi Password (hanya jika fieldnya ada)
                if (confirmPasswordInput && confirmPasswordError) {
                    if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value.length > 0) {
                        isPasswordMatch = false;
                        confirmPasswordError.style.display = 'block';
                    } else {
                        confirmPasswordError.style.display = 'none';
                    }
                }

                // Aktifkan/Matikan Tombol
                if (isFormValid && isPasswordMatch && isPasswordComplex) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

            // --- EVENT LISTENER ---

            if (passwordInput) {
                // 1. Saat user klik kolom password (Focus)
                passwordInput.addEventListener('focus', function() {
                    validatePassword(); // Jalankan validasi langsung agar status terkini muncul
                });

                // 2. Saat user mengetik (Input)
                passwordInput.addEventListener('input', function() {
                    validatePassword();
                    checkFormValidity();
                });
            }

            // Listener untuk input lain di form
            const inputs = form ? form.querySelectorAll('input, textarea, select') : [];
            inputs.forEach(function(input) {
                input.addEventListener('input', checkFormValidity);
            });

            // Listener Toggle Mata (Password)
            const togglePassword = document.querySelector('#toggle-password');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '🙈';
                });
            }

            // Listener Toggle Mata (Confirm Password)
            const togglePasswordConfirm = document.querySelector('#toggle-password-confirm');
            if (togglePasswordConfirm && confirmPasswordInput) {
                togglePasswordConfirm.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.textContent = type === 'password' ? '👁️' : '🙈';
                });
            }

            // Jalankan sekali di awal untuk reset tombol
            checkFormValidity();
        });
        </script>
</body>
</html>