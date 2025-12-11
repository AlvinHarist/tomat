<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Reviewer Berdasarkan Provinsi - ToMaT Seller</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Style untuk menyesuaikan header/judul halaman */
        .page-header-flex {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }
        
        /* CSS KHUSUS UNTUK TABEL DETAIL PROVINSI */
        .province-table th {
            padding: 15px 20px;
            font-size: 0.85rem;
            color: #888;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            text-align: left; /* Defaultkan ke kiri */
        }
        
        .province-table td {
            padding: 12px 20px;
            font-size: 0.9rem;
            color: #555;
            border-bottom: 1px solid #f9f9f9;
        }
        
        /* Gaya untuk baris terakhir */
        .province-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Menjaga konsistensi lebar kolom */
        .col-no { width: 10%; text-align: center; }
        .col-count { width: 30%; text-align: right; font-weight: bold; color: #333; }
        .col-province { width: 60%; }

    </style>
</head>
<body>
    @include('seller.partials.sidebar')
    
    <main class="main-content">
        <div class="page-container">

            <div class="page-header-flex">
                <a href="{{ route('seller.dashboard') }}" style="color: #999;">
                    <i class="fas fa-arrow-left" style="font-size: 1.5rem;"></i>
                </a>
                <div>
                    <h1 class="page-title" style="margin-bottom: 5px; font-size: 1.6rem; color: #444;">
                        Daftar Lengkap Reviewer Berdasarkan Provinsi
                    </h1>
                    <p class="text-muted" style="margin-top: 0;">Data lokasi reviewer produk Anda.</p>
                </div>
            </div>

            @if ($reviewers->isEmpty())
                <div class="card" style="text-align: center; padding: 40px;">
                    <i class="fas fa-search-location" style="font-size: 2rem; color: #ccc; margin-bottom: 10px;"></i>
                    <p class="text-muted">Belum ada data reviewer berdasarkan provinsi yang tercatat.</p>
                </div>
            @else
                <div class="card" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table class="province-table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th class="col-no">No</th>
                                    <th class="col-province">Provinsi</th>
                                    <th class="col-count">Jumlah Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviewers as $index => $reviewer)
                                <tr>
                                    <td class="col-no">{{ $index + 1 }}</td>
                                    <td class="col-province">{{ is_array($reviewer) ? $reviewer['province'] : $reviewer->province }}</td>
                                    <td class="col-count">{{ is_array($reviewer) ? $reviewer['count'] : $reviewer->count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </main>
</body>
</html>