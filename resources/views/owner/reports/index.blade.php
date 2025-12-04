<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - ToMaT Owner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        .reports-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .report-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); text-align: center; transition: 0.3s; border: 1px solid #eee; }
        .report-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-color: #4CAF50; }
        .report-icon { font-size: 3rem; color: #4CAF50; margin-bottom: 20px; }
        .report-title { font-weight: bold; font-size: 1.1rem; color: #333; margin-bottom: 10px; }
        .report-desc { color: #888; font-size: 0.9rem; margin-bottom: 25px; }
        .btn-print { background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-print:hover { background-color: #45a049; }
    </style>
</head>
<body>

    @include('owner.sidebar')

    <main class="main-content">
        <h1 class="page-title">Pusat Laporan</h1>

        <div class="reports-grid">
            
            <div class="report-card">
                <div class="report-icon"><i class="fas fa-user-check"></i></div>
                <div class="report-title">Laporan Status Penjual</div>
                <div class="report-desc">Daftar akun penjual diurutkan berdasarkan status aktif dan tidak aktif.</div>
                <a href="{{ route('owner.reports.seller_status') }}" target="_blank" class="btn-print">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div class="report-title">Laporan Lokasi Toko</div>
                <div class="report-desc">Sebaran daftar toko berdasarkan lokasi provinsi.</div>
                <a href="{{ route('owner.reports.seller_province') }}" target="_blank" class="btn-print">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-star"></i></div>
                <div class="report-title">Laporan Rating Produk</div>
                <div class="report-desc">Daftar produk dengan rating tertinggi beserta lokasi pemberi rating.</div>
                <a href="{{ route('owner.reports.product_rating') }}" target="_blank" class="btn-print">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
            </div>

        </div>
    </main>

</body>
</html>