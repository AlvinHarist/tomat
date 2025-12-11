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
        .report-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); text-align: center; border: 1px solid #eee; }
        .report-icon { font-size: 2.5rem; color: #21BD38; margin-bottom: 15px; }
        .report-title { font-weight: bold; font-size: 1.1rem; color: #333; margin-bottom: 10px; }
        .report-desc { color: #888; font-size: 0.85rem; margin-bottom: 20px; min-height: 40px; }
        
        /* Style Input Tanggal */
        .date-filter { display: flex; gap: 10px; justify-content: center; margin-bottom: 15px; }
        .date-group { text-align: left; }
        .date-group label { display: block; font-size: 0.7rem; color: #666; margin-bottom: 2px; }
        .date-group input { padding: 6px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.8rem; width: 110px; }

        .btn-print { background-color: #21BD38; color: white; padding: 10px 20px; border-radius: 25px; border: none; cursor: pointer; font-size: 0.9rem; transition: 0.3s; }
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
                <div class="report-title">Status Penjual</div>
                <div class="report-desc">Daftar akun penjual berdasarkan status aktif/tidak.</div>
                
                <form action="{{ route('owner.reports.seller_status') }}" method="GET" target="_blank">
                    <div class="date-filter">
                        <div class="date-group">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" required>
                        </div>
                        <div class="date-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-print"><i class="fas fa-print"></i> Cetak PDF</button>
                </form>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div class="report-title">Lokasi Toko</div>
                <div class="report-desc">Daftar toko berdasarkan provinsi.</div>
                
                <form action="{{ route('owner.reports.seller_province') }}" method="GET" target="_blank">
                    <div class="date-filter">
                        <div class="date-group">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" required>
                        </div>
                        <div class="date-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-print"><i class="fas fa-print"></i> Cetak PDF</button>
                </form>
            </div>

            <div class="report-card">
                <div class="report-icon"><i class="fas fa-star"></i></div>
                <div class="report-title">Rating Produk</div>
                <div class="report-desc">Produk top rated & lokasi reviewer.</div>
                
                <form action="{{ route('owner.reports.product_rating') }}" method="GET" target="_blank">
                    <div class="date-filter">
                        <div class="date-group">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" required>
                        </div>
                        <div class="date-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-print"><i class="fas fa-print"></i> Cetak PDF</button>
                </form>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            const lastMonthStr = lastMonth.toISOString().split('T')[0];

            document.querySelectorAll('input[name="end_date"]').forEach(el => el.value = today);
            document.querySelectorAll('input[name="start_date"]').forEach(el => el.value = lastMonthStr);
        });
    </script>

</body>
</html>