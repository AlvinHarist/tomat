<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - ToMaT Seller</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Mengadopsi style grid dan card dari Owner Reports CSS */
        .reports-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .report-card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); 
            text-align: center; 
            border: 1px solid #eee;
            transition: all 0.3s;
        }
        .report-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        .report-icon { 
            font-size: 2.5rem; 
            color: #4CAF50; 
            margin-bottom: 15px; 
        }
        .report-title { 
            font-weight: bold; 
            font-size: 1.1rem; 
            color: #333; 
            margin-bottom: 5px;
        }
        .report-desc { 
            color: #888; 
            font-size: 0.85rem; 
            margin-bottom: 20px; 
            min-height: 40px; 
        }
        .btn-filter { 
            background-color: #4CAF50;
            color: white; 
            padding: 10px 20px; 
            border-radius: 25px; 
            border: none; 
            cursor: pointer; 
            font-size: 0.9rem; 
            transition: 0.3s; 
            text-decoration: none;
            display: inline-block;
        }
        .btn-filter:hover { 
            background-color: #45a049; 
        }
        
        /* --- PERBAIKAN: Style untuk Info Section agar Hijau/Konsisten --- */
        .info-card-container {
            background: #edfdf6; /* HIJAU MUDA */
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #10b981; /* BORDER HIJAU */
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .info-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .info-icon-large {
            color: #10b981; /* ICON HIJAU */
            font-size: 1.2rem;
            margin-right: 15px;
        }
        .info-title-small {
            font-size: 1rem;
            font-weight: bold;
            color: #065f46; /* TEKS HIJAU GELAP */
        }
        .info-list {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #065f46; /* TEKS LIST HIJAU GELAP */
            list-style: disc;
            margin-left: 20px;
        }
    </style>
</head>
<body>
@include('seller.partials.sidebar')

<main class="main-content">
    <div class="page-container" style="max-width: 1000px;">

        <h1 class="page-title">Pusat Laporan Penjualan</h1>
        <p class="text-muted" style="margin-bottom: 30px;">Kelola dan unduh berbagai laporan penjualan</p>

        <div class="reports-grid">

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-boxes"></i></div>
                <div class="report-title">Laporan Stock</div>
                <div class="report-desc">Laporan daftar produk berdasarkan jumlah stock yang tersedia.</div>
                
                <a href="{{ route('seller.reports.products-by-stock.filter') }}"
                   class="btn-filter">
                    <i class="fas fa-search"></i> Buka Halaman Filter
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-star"></i></div>
                <div class="report-title">Laporan Rating</div>
                <div class="report-desc">Laporan daftar produk berdasarkan rating dan jumlah review pelanggan.</div>
                
                <a href="{{ route('seller.reports.products-by-rating.filter') }}"
                   class="btn-filter">
                    <i class="fas fa-search"></i> Buka Halaman Filter
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="report-title">Produk Segera Dipesan</div>
                <div class="report-desc">Laporan produk dengan stock menipis yang perlu segera di-restock.</div>
                
                <a href="{{ route('seller.reports.products-need-restock.filter') }}"
                   class="btn-filter">
                    <i class="fas fa-search"></i> Buka Halaman Filter
                </a>
            </div>

        </div>

        <div class="info-card-container">
            <div class="info-header">
                <i class="fas fa-info-circle info-icon-large"></i>
                <h3 class="info-title-small">Informasi Laporan</h3>
            </div>
            
            <ul class="info-list">
                <li>Semua laporan diunduh dalam format PDF</li>
                <li>Data laporan selalu menggunakan data terbaru</li>
                <li>Nama file mencakup tanggal pembuatan laporan</li>
                <li>Anda dapat memfilter laporan berdasarkan rentang tanggal produk di-upload di halaman filter.</li>
            </ul>
        </div>

    </div>
</main>
</body>
</html>