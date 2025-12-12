<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Reviewer Berdasarkan Provinsi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Memuat sidebar partial -->
    @include('seller.partials.sidebar')
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Lengkap Reviewer Berdasarkan Provinsi</h1>

            <a href="{{ route('seller.dashboard') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mb-4 inline-block">&larr; Kembali ke Dashboard</a>

            @if ($reviewers->isEmpty())
                <div class="text-center py-10 border border-gray-200 rounded-lg mt-4">
                    <p class="text-gray-500">Belum ada data reviewer berdasarkan provinsi.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">No</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Provinsi</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Jumlah Review</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($reviewers as $index => $reviewer)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-800">{{ $index + 1 }}</td>
                                <!-- Menggunakan is_array untuk kompatibilitas data dari DB atau array yang di-mock -->
                                <td class="py-3 px-4 text-sm text-gray-800">{{ is_array($reviewer) ? $reviewer['province'] : $reviewer->province }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 text-right">{{ is_array($reviewer) ? $reviewer['count'] : $reviewer->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>