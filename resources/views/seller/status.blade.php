<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Verifikasi - ToMaT</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 max-w-md w-full text-center">
        
        @if($status === 'PENDING')
            <!-- Pending Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Toko Belum Terverifikasi</h1>
            <p class="text-gray-600 mb-6">
                Toko Anda sedang dalam proses verifikasi oleh admin.<br>
                <span class="font-semibold text-yellow-600">Silakan cek e-mail secara berkala</span> untuk mendapatkan link verifikasi setelah admin menyetujui pendaftaran Anda.
            </p>

        @elseif($status === 'REJECTED')
            <!-- Rejected Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Toko Tidak Terverifikasi</h1>
            <p class="text-gray-600 mb-6">
                Maaf, pendaftaran toko Anda tidak dapat disetujui.<br>
                <span class="font-semibold text-red-600">Silakan registrasi ulang</span> dengan data yang benar dan lengkap.
            </p>
            
            <a href="{{ route('register') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                Registrasi Ulang
            </a>

        @endif

        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('seller.login') }}" class="text-green-600 hover:underline font-medium">
                ← Kembali ke Login
            </a>
        </div>

        <div class="mt-6">
            <p class="text-xs text-gray-500">
                Butuh bantuan? Hubungi <a href="mailto:support@tomat.com" class="text-green-600 hover:underline">support@tomat.com</a>
            </p>
        </div>

    </div>
</body>
</html>
