<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - ToMaT</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-600">ToMaT</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('seller.logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Card -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-6">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-600 mt-1">Kelola toko Anda dengan mudah melalui dashboard ini.</p>
                </div>
            </div>
        </div>

        <!-- Info Notice -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800 mb-1">Halaman Dalam Pengembangan</h3>
                    <p class="text-blue-700">
                        Dashboard lengkap untuk mengelola produk, pesanan, dan laporan sedang dikerjakan oleh tim developer. 
                        Fitur-fitur tersebut akan segera tersedia dalam waktu dekat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Features Grid (Coming Soon) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Products Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-2 border-gray-200 opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Segera</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk</h3>
                <p class="text-gray-600 text-sm">Kelola katalog produk toko Anda</p>
            </div>

            <!-- Orders Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-2 border-gray-200 opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Segera</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pesanan</h3>
                <p class="text-gray-600 text-sm">Pantau dan kelola pesanan masuk</p>
            </div>

            <!-- Reports Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border-2 border-gray-200 opacity-60">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Segera</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan</h3>
                <p class="text-gray-600 text-sm">Lihat statistik dan laporan penjualan</p>
            </div>

        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-800">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status Akun</p>
                    <p class="font-medium text-green-600">✓ Terverifikasi</p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>
