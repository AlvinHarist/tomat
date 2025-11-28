<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - ToMaT</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/seller/dashboard.css') }}">
</head>
<body>

    <div id="chart-data-source" 
          data-stats="{{ json_encode($chartData) }}" 
          style="display: none;">
    </div>

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
            <a href="{{ route('seller.dashboard') }}" class="active">
                <i class="fas fa-home"></i> Overview
            </a>
            <a href="{{ route('seller.products.create') }}">
                <i class="fas fa-upload"></i> Upload Product
            </a>
            <a href="{{ route('seller.products.index') }}">
                <i class="fas fa-cubes"></i> My Products
            </a>
            <a href="#"><i class="fas fa-chart-line"></i> Report</a>
            
            <form action="{{ route('seller.logout') }}" method="POST" style="margin-top: 20px;">
                @csrf
                <button type="submit" style="background:none; border:none; color:#777; cursor:pointer; font-size:0.9rem; padding:12px 15px; display:flex; align-items:center;">
                    <i class="fas fa-sign-out-alt" style="margin-right:15px;"></i> Logout
                </button>
            </form>
        </nav>

        <div class="logo">ToMaT</div>
    </aside>

    <main class="main-content">
        <h1 class="page-title">Dashboard Penjual</h1>

        <div class="stats-grid">
            <div class="card">
                <div class="icon-box icon-teal"><i class="fas fa-file-alt"></i></div>
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-label">Total Product Anda</div>
            </div>
            
            <div class="card">
                <div class="icon-box icon-pink"><i class="far fa-comment-alt"></i></div>
                <div class="stat-number">{{ $totalReviews }}</div>
                <div class="stat-label">Total Review Produk Anda</div>
            </div>

            <div class="card">
                <div class="icon-box icon-blue"><i class="fas fa-star"></i></div>
                <div class="stat-number">{{ $averageRating }}</div>
                <div class="stat-label">Rata-rata Rating</div>
            </div>

            <div class="card">
                <div class="icon-box icon-purple"><i class="fas fa-store"></i></div>
                <div class="stat-number">{{ Auth::guard('seller')->user()->store_name ?? 'N/A' }}</div>
                <div class="stat-label">Nama Toko Anda</div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">AKTIVITAS REVIEW BULANAN</div>
                <div style="font-size: 0.8rem; color: #999;">Produk Anda Tahun Ini v</div>
            </div>
            <canvas id="visitorsChart" height="80"></canvas>
        </div>

        <div class="bottom-grid" style="grid-template-columns: 1fr;"> 
            <div class="table-card">
                <div class="table-header">
                    <span>Nama Produk</span>
                    <span>Total Review</span>
                </div>
                @forelse($topProducts as $product)
                <div class="list-item">
                    <span>{{ $product->name }}</span>
                    <span>{{ $product->comment_ratings_count }}</span>
                </div>
                @empty
                <div class="list-item">
                    <span>Belum ada produk atau review.</span>
                    <span>0</span>
                </div>
                @endforelse
                <a href="{{ route('seller.products.index') }}" class="more-link">Lihat Semua Produk...</a>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataSource = document.getElementById('chart-data-source');

            if (dataSource) {
                const visitorsData = JSON.parse(dataSource.getAttribute('data-stats'));

                const ctx = document.getElementById('visitorsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Review Produk',
                            data: visitorsData,
                            backgroundColor: '#4CAF50',
                            borderRadius: 5,
                            barThickness: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>