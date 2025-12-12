<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter {{ $reportTitle }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
@include('seller.partials.sidebar')

<div class="ml-64 p-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Filter {{ $reportTitle }}</h1>
            <p class="text-gray-600 mt-2">Pilih rentang tanggal untuk mencetak laporan.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Perhatian!</strong>
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
        @endif


        <div class="bg-white rounded-lg shadow-xl p-8">
            <form action="{{ $reportRoute }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="start_date" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai:</label>
                        <input type="date" name="end_date" id="end_date" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cetak Laporan (Filter Tanggal)
                    </button>
                    <a href="{{ $reportRoute }}"
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cetak Laporan (Keseluruhan)
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 text-sm text-blue-700 rounded">
            <p class="font-bold">Catatan:</p>
            <ul class="list-disc list-inside ml-4 mt-1">
                <li>Untuk mencetak laporan keseluruhan tanpa filter tanggal, klik tombol "Cetak Laporan (Keseluruhan)".</li>
                <li>Peringatan akan muncul jika pada rentang tanggal yang dipilih tidak ada produk yang di-upload.</li>
            </ul>
        </div>

    </div>
</div>
</body>
</html>