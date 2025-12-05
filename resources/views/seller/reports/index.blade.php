<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    @include('seller.partials.sidebar')

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>
                <p class="text-gray-600 mt-2">Kelola dan unduh berbagai laporan penjualan</p>
            </div>

            <!-- Report Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Report Card 1 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Laporan Stock</h3>
                            <p class="text-sm text-gray-500">SRS-MartPlace-12</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Laporan daftar produk berdasarkan jumlah stock yang tersedia</p>
                    <a href="{{ route('seller.reports.stock') }}" 
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition-colors">
                        Unduh PDF
                    </a>
                </div>

                <!-- Report Card 2 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Laporan Rating</h3>
                            <p class="text-sm text-gray-500">SRS-MartPlace-13</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Laporan daftar produk berdasarkan rating dan jumlah review pelanggan</p>
                    <a href="{{ route('seller.reports.rating') }}" 
                       class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 rounded-lg transition-colors">
                        Unduh PDF
                    </a>
                </div>

                <!-- Report Card 3 -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Produk Segera Dipesan</h3>
                            <p class="text-sm text-gray-500">SRS-MartPlace-14</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Laporan daftar produk dengan stock menipis yang perlu segera di-restock</p>
                    <a href="{{ route('seller.reports.restock') }}" 
                       class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded-lg transition-colors">
                        Unduh PDF
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Laporan</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Semua laporan akan diunduh dalam format PDF</li>
                                <li>Laporan berisi data terbaru dari produk Anda</li>
                                <li>Nama file akan menyertakan tanggal pembuatan laporan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
