<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk Berdasarkan Stock</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DAFTAR PRODUK BERDASARKAN STOCK</h2>
    </div>

    <div class="info-section">
        <p>
            <strong>Nama Seller:</strong> 
            {{ $seller->pic_name ?? (Auth::check() ? Auth::user()->name : 'Nama Seller Tidak Diketahui') }}
        </p>
        <p><strong>Tanggal Cetak:</strong> {{ $date }}</p>
        <p><strong>Rentang Produk Dibuat:</strong> {{ $filterDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Produk</th> 
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Harga</th>
                <th style="width: 15%;">Rating</th>
                <th style="width: 10%;">Stock</th>
                </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                
                <td class="text-center">
                    {{ number_format($product->reviews_avg_rating ?? 0, 1) }} 
                </td>
                
                <td class="text-center">{{ $product->stock }}</td>
                
                </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data produk</td> </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 40px;">
        <p><strong>Total Produk:</strong> {{ $products->count() }}</p>
    </div>
</body>
</html>