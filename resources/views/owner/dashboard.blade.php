<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - ToMaT</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
</head>
<body>

    <div id="chart-data-source"
        data-visitors='@json($chartData)'
        data-products-by-category='@json($productByCategory->map(fn($c)=>["name"=>$c->name,"count"=>$c->products_count]))'
        data-stores-by-province='@json($sellerByProvince)'
        data-seller-status='@json(["active"=>$activeSellers,"inactive"=>$nonActiveSellers])'
        data-engagement='@json(["commenters"=>$commentersCount,"raters"=>$ratersCount])'
        style="display:none;">
    </div>


    @include('owner.sidebar')

    <main class="main-content">
        <h1 class="page-title">Dashboard Pemilik</h1>

        <div class="stats-grid">
            <div class="card">
                <div class="icon-box icon-pink"><i class="fas fa-users"></i></div>
                <div class="stat-number">{{ $activeSellers }}/{{ $nonActiveSellers }}</div>
                <div class="stat-label">Seller (active/non)</div>
            </div>
            
            <div class="card">
                <div class="icon-box icon-teal"><i class="fas fa-file-alt"></i></div>
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-label">Product</div>
            </div>

            <div class="card">
                <div class="icon-box icon-blue"><i class="far fa-heart"></i></div>
                <div class="stat-number">{{ $totalCategories }}</div>
                <div class="stat-label">Category</div>
            </div>

            <div class="card">
                <div class="icon-box icon-blue"><i class="far fa-comment-alt"></i></div>
                <div class="stat-number">{{ $totalReviews }}</div>
                <div class="stat-label">Total review</div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">SITE VISITORS</div>
                <div style="font-size: 0.8rem; color: #999;">This Year v</div>
            </div>
            <canvas id="visitorsChart" height="80"></canvas>
        </div>

       <div class="chart-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">SEBARAN PRODUK PER KATEGORI</div>
                </div>
                <canvas id="productsByCategoryChart" height="110"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">SEBARAN TOKO PER PROVINSI</div>
                </div>
                <canvas id="storesByProvinceChart" height="110"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">SELLER AKTIF vs TIDAK AKTIF</div>
                </div>
                <canvas id="sellerStatusChart" height="110"></canvas>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">PENGUNJUNG (KOMENTAR & RATING)</div>
                </div>
                <canvas id="engagementChart" height="110"></canvas>
            </div>
        </div>

    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataSource = document.getElementById('chart-data-source');
            if (!dataSource) return;

            const visitors = JSON.parse(dataSource.getAttribute('data-visitors') || '[]');
            const products = JSON.parse(dataSource.getAttribute('data-products-by-category') || '[]');
            const stores   = JSON.parse(dataSource.getAttribute('data-stores-by-province') || '[]');
            const status   = JSON.parse(dataSource.getAttribute('data-seller-status') || '{}');
            const engage   = JSON.parse(dataSource.getAttribute('data-engagement') || '{}');

            // 1) Visitors
            const vctx = document.getElementById('visitorsChart');
            if (vctx) {
                new Chart(vctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                        datasets: [{
                            label: 'Visitors',
                            data: visitors,
                            backgroundColor: '#21BD38',
                            borderRadius: 5,
                            barThickness: 15
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            }

            // 2) Sebaran produk per kategori
            const pc = document.getElementById('productsByCategoryChart');
            if (pc) {
                new Chart(pc.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: products.map(x => x.name),
                        datasets: [{ data: products.map(x => x.count), borderRadius: 5, backgroundColor: '#21BD38'}]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            }

            // 3) Sebaran toko per provinsi
            const sp = document.getElementById('storesByProvinceChart');
            if (sp) {
                new Chart(sp.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: stores.map(x => x.name),
                        datasets: [{
                            data: stores.map(x => x.count),
                            borderRadius: 5,
                            backgroundColor: '#21BD38'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 60,
                                    minRotation: 60
                                }
                            }
                        }
                    }
                });
            }


            // 4) Seller aktif vs tidak aktif
            const ss = document.getElementById('sellerStatusChart');
            if (ss) {
                new Chart(ss.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Tidak Aktif'],
                        datasets: [{ data: [status.active || 0, status.inactive || 0], backgroundColor: ['#21BD38', '#bd2121ff'] }]
                    },
                    options: { responsive: true }
                });
            }

            // 5) Pengunjung komentar & rating
            const ec = document.getElementById('engagementChart');
            if (ec) {
                new Chart(ec.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Komentar', 'Rating'],
                        datasets: [{ data: [engage.commenters || 0, engage.raters || 0], borderRadius: 5, backgroundColor: '#21BD38'}]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            }
        });
    </script>
</body>
</html>