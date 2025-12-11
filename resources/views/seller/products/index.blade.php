<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Style untuk penomoran halaman Laravel agar terlihat rapi dengan CSS Owner */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 8px;
            color: #777;
            border: 1px solid #ddd;
            transition: 0.3s;
        }
        .pagination li a:hover:not(.active) {
            background-color: #f0f0f0;
        }
        .pagination li.active span {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        .pagination li.disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    @include('seller.partials.sidebar')
    
    <main class="main-content"> 
        <div class="page-container" style="max-width: 100%;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h1 class="page-title" style="margin-bottom: 5px;">Produk Saya</h1>
                    <p class="text-muted">Kelola produk toko Anda</p>
                </div>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus" style="margin-right: 5px;"></i> Tambah Produk
                </a>
            </div>
            
            <div>
                
                @if(session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error') || $errors->any())
                <div class="alert alert-danger">
                    <p>{{ session('error') ?? 'Terjadi kesalahan' }}</p>
                </div>
                @endif
                
                <div class="card" style="padding: 0; text-align: left;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <th style="padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #888; font-weight: 600;">Produk</th>
                                    <th style="padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #888; font-weight: 600;">Kategori</th>
                                    <th style="padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #888; font-weight: 600;">Harga</th>
                                    <th style="padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #888; font-weight: 600;">Stok</th>
                                    <th style="padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #888; font-weight: 600;">Tanggal</th>
                                    <th style="padding: 15px 20px; text-align: right; font-size: 0.85rem; color: #888; font-weight: 600;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr style="border-bottom: 1px solid #f9f9f9; background: white;">
                                    <td style="padding: 15px 20px;">
                                        <div style="display: flex; align-items: center;">
                                            @php
                                                // Logika untuk mengambil gambar pertama
                                                $imageArray = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                                $firstImage = ($imageArray && is_array($imageArray) && count($imageArray) > 0) ? $imageArray[0] : null;
                                            @endphp
                                            
                                            @if($firstImage)
                                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" class="file-preview" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; margin-right: 15px;">
                                            @else
                                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #eee; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fas fa-image" style="color: #ccc; font-size: 1.2rem;"></i>
                                            </div>
                                            @endif
                                            <div style="max-width: 250px;">
                                                <p style="font-size: 0.95rem; font-weight: bold; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $product->name }}</p>
                                                <p style="font-size: 0.8rem; color: #888; margin-top: 2px; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($product->description, 50) }}</p>
                                                @if($imageArray && count($imageArray) > 1)
                                                <p style="font-size: 0.75rem; color: #999; margin-top: 2px;">+{{ count($imageArray) - 1 }} foto lainnya</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px; font-size: 0.9rem; color: #555;">
                                        <span style="display: inline-block; padding: 4px 10px; background: #e3f2fd; color: #2196f3; border-radius: 5px; font-size: 0.75rem; font-weight: 600;">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; font-size: 0.9rem; font-weight: bold; color: #333;">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <span style="display: inline-block; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 5px; {{ $product->stock > 10 ? 'background: #edfdf6; color: #065f46;' : ($product->stock > 0 ? 'background: #fffbe5; color: #a16207;' : 'background: #fff5f5; color: #e53e3e;') }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px; font-size: 0.85rem; color: #888;">
                                        {{ $product->created_at->format('d M Y') }}
                                    </td>
                                    <td style="padding: 15px 20px; text-align: right;">
                                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                            <a href="{{ route('seller.products.edit', $product->id) }}" style="color: #2196f3; font-size: 1rem; padding: 5px; border-radius: 5px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#e3f2fd'" onmouseout="this.style.backgroundColor='transparent'">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: #e53e3e; background: none; border: none; cursor: pointer; font-size: 1rem; padding: 5px; border-radius: 5px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#ffebee'" onmouseout="this.style.backgroundColor='transparent'">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="padding: 50px; text-align: center;">
                                        <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc; margin-bottom: 10px;"></i>
                                        <p style="color: #888; margin-bottom: 15px;">Belum ada produk</p>
                                        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus" style="margin-right: 5px;"></i> Tambah Produk Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($products->hasPages())
                    <div style="padding: 15px 20px; border-top: 1px solid #eee; text-align: center;">
                        {{ $products->links() }} 
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>