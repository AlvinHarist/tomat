<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk Segera Dipesan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin: 3px 0;
        }
        .category-section {
            margin-bottom: 25px;
        }
        .category-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
            border: 1px solid #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f8f8f8;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .urgent {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DAFTAR PRODUK SEGERA DIPESAN</h2>
        <p>SRS-MartPlace-14</p>
    </div>

    <div class="info-section">
        <p><strong>Nama Seller:</strong> {{ $seller->pic_name }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ $date }}</p>
        <p><strong>Kriteria:</strong> Produk dengan stok kurang dari 10</p>
    </div>

    @if($productsByCategory->count() > 0)
        @foreach($productsByCategory as $categoryName => $products)
        <div class="category-section">
            <div class="category-title">
                Kategori: {{ $categoryName }}
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 35%;">Nama Produk</th>
                        <th style="width: 20%;">Harga</th>
                        <th style="width: 15%;">Stock Tersisa</th>
                        <th style="width: 25%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-center urgent">{{ $product->stock }}</td>
                        <td class="text-center">
                            @if($product->stock == 0)
                                <span class="urgent">HABIS - Segera Restok!</span>
                            @elseif($product->stock < 5)
                                <span class="urgent">Sangat Mendesak</span>
                            @else
                                <span style="color: orange; font-weight: bold;">Perlu Segera Dipesan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach

        <div style="margin-top: 30px; border-top: 2px solid #000; padding-top: 15px;">
            <p><strong>Total Produk yang Perlu Dipesan:</strong> {{ $productsByCategory->flatten()->count() }}</p>
            <p><strong>Total Kategori:</strong> {{ $productsByCategory->count() }}</p>
        </div>
    @else
        <div style="text-align: center; padding: 30px; border: 1px solid #ddd;">
            <p><strong>Semua produk memiliki stok yang cukup.</strong></p>
            <p>Tidak ada produk yang perlu segera dipesan.</p>
        </div>
    @endif
</body>
</html>
