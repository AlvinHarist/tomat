<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller/dashboard.css') }}">
    <style>
        .table-container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px; color: #888; font-size: 0.85rem; border-bottom: 1px solid #eee; }
        td { padding: 15px; color: #555; font-size: 0.9rem; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
        .product-name { font-weight: bold; }
        .btn-edit, .btn-delete { padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: 0.3s; margin-right: 5px; }
        .btn-edit { background-color: #2196f3; color: white; }
        .btn-edit:hover { background-color: #0b7dda; }
        .btn-delete { background-color: #f44336; color: white; border: none; cursor: pointer; }
        .btn-delete:hover { background-color: #d32f2f; }
        .stock-low { color: #f44336; font-weight: bold; }
        .stock-ok { color: #4CAF50; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="user-profile">
            <div class="user-info">
                <div class="user-name">{{ Auth::guard('seller')->user()->pic_name ?? 'Seller' }}</div>
                <div class="user-role">Seller</div>
            </div>
            <div class="avatar"></div>
        </div>

        <div class="menu-title">MENU</div>
        <nav class="nav-links">
            <a href="{{ route('seller.dashboard') }}"><i class="fas fa-home"></i> Overview</a>
            <a href="{{ route('seller.products.create') }}"><i class="fas fa-upload"></i> Upload Product</a>
            <a href="{{ route('seller.products.index') }}" class="active"><i class="fas fa-cubes"></i> My Products</a>
            <a href="#"><i class="fas fa-chart-line"></i> Report</a>
        </nav>
        <div class="logo">ToMaT</div>
    </aside>

    <main class="main-content">
        <h1 class="page-title">Produk Saya</h1>
        
        @if (session('success'))
            <div style="background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Diunggah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="product-name">{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="{{ $product->stock < 5 ? 'stock-low' : 'stock-ok' }}">
                                {{ $product->stock }}
                            </span>
                            @if($product->stock < 5) (Low Stock!) @endif
                        </td>
                        <td>{{ $product->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="#" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus produk ini?')"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Anda belum mengunggah produk apapun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>