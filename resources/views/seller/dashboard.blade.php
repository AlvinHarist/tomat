<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Seller - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}"> 
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Style untuk menyesuaikan tinggi chart di bottom-grid */
        .chart-box { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.02); 
            height: 400px; /* Tinggi Konsisten untuk Chart Bawah */
        }
    </style>
</head>
<body>

    <div id="chart-data-source" 
        data-stock-labels="{{ json_encode($productStockData->pluck('name')->toArray()) }}" 
        data-stock-values="{{ json_encode($productStockData->pluck('stock')->toArray()) }}" 
        data-rating-labels="{{ json_encode(array_keys($ratingDistribution)) }}"
        data-rating-values="{{ json_encode(array_values($ratingDistribution)) }}"
        data-province-labels="{{ json_encode($reviewersByProvince->pluck('province')->toArray()) }}"
        data-province-values="{{ json_encode($reviewersByProvince->pluck('count')->toArray()) }}"
        style="display: none;">
    </div>

    @include('seller.partials.sidebar')
    
    <main class="main-content">
        <h1 class="page-title">Dashboard Seller</h1>

        <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);"> 
            <div class="card">
                <div class="icon-box icon-teal"><i class="fas fa-file-alt"></i></div>
                <div class="stat-number">{{ $productsCount }}</div>
                <div class="stat-label">Product</div>
            </div>

            <div class="card">
                <div class="icon-box icon-blue"><i class="far fa-comment-alt"></i></div>
                <div class="stat-number">{{ $totalReviews }}</div>
                <div class="stat-label">Total review</div>
            </div>
        </div>

        <div class="chart-container" style="margin-bottom: 20px;">
            <div class="chart-header">
                <div class="chart-title">SEBARAN STOK PRODUK</div>
                <div style="font-size: 0.8rem; color: #999;">Top 10 Produk</div>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="stockChart"></canvas>
            </div>
        </div>

        <div class="bottom-grid" style="gap: 20px;">
            
            <div class="chart-box" style="padding: 25px;">
                <div class="chart-header" style="margin-bottom: 15px; justify-content: center;">
                    <div class="chart-title" style="text-align: center;">SEBARAN NILAI RATING PRODUK</div>
                </div>
                <div style="position: relative; height: calc(100% - 40px); display: flex; justify-content: center; align-items: center;">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>

            <div class="chart-box" style="padding: 25px;">
                <div class="chart-header" style="margin-bottom: 15px;">
                    <div class="chart-title">PEMBERI RATING BERDASARKAN PROVINSI</div>
                    <a href="{{ route('seller.reviewers.by-province.index') }}" class="more-link" style="margin-top: 0;">More...</a>
                </div>
                <div style="position: relative; height: calc(100% - 40px);">
                    <canvas id="provinceChart"></canvas>
                </div>
            </div>
            
        </div>
        
        <div class="chart-container" style="margin-top: 20px;">
            <div class="table-card" style="padding: 0; box-shadow: none;">
                <div class="chart-header" style="padding: 20px 25px; border-bottom: 1px solid #eee;">
                    <div class="chart-title" style="margin: 0; text-transform: none; font-weight: 600;">Top Produk (Berdasarkan Stok & Rating)</div>
                    <select id="sortProducts" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 8px; font-size: 0.85rem; color: #777;">
                        <option value="stock_desc">Stock descending</option>
                        <option value="stock_asc">Stock ascending</option>
                        <option value="rating_desc">Rating descending</option>
                        <option value="rating_asc">Rating ascending</option>
                    </select>
                </div>
                <div id="productsList" style="padding: 0 25px 25px 25px;">
                    @foreach($products->take(5) as $product)
                    <div class="list-item" 
                        data-stock="{{ $product['stock'] }}" 
                        data-rating="{{ $product['rating'] }}"
                        style="padding: 10px 0; border-bottom: 1px solid #eee; align-items: flex-start; gap: 10px;">
                        
                        <div style="width: 50px; height: 50px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background: #eee;">
                             @if($product['image'])
                             <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="file-preview" style="height: 50px; width: 100%; object-fit: cover;">
                             @endif
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <span style="font-weight: bold; color: #444; font-size: 0.95rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product['name'] }}</span>
                            <div style="font-size: 0.8rem; color: #888; margin-top: 3px;">
                                Stok: {{ $product['stock'] }} | 
                                <span style="color: #2196f3; font-weight: bold;">{{ $product['category'] }}</span>
                                <br>
                                {{ $product['comments_count'] }} Comments | 
                                <span style="color: #ffc107; font-weight: bold;">⭐ {{ $product['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('seller.products.index') }}" class="more-link" style="margin-top: 0; padding-bottom: 20px;">More...</a>
            </div>
        </div>
        
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataSource = document.getElementById('chart-data-source');
            if (!dataSource) return;

            const stockLabels = JSON.parse(dataSource.getAttribute('data-stock-labels'));
            const stockValues = JSON.parse(dataSource.getAttribute('data-stock-values'));
            const ratingLabels = JSON.parse(dataSource.getAttribute('data-rating-labels'));
            const ratingValues = JSON.parse(dataSource.getAttribute('data-rating-values'));
            const provinceLabels = JSON.parse(dataSource.getAttribute('data-province-labels'));
            const provinceValues = JSON.parse(dataSource.getAttribute('data-province-values'));
            
            const primaryColor = '#4CAF50';
            const primaryHoverColor = '#45a049';
            const colors = [primaryColor, '#2196f3', '#ffc107', '#e91e63', '#00bcd4', '#9c27b0']; // Colors for charts

            // --- 1. CHART SEBARAN STOK PRODUK (Bar Chart) ---
            const stockCtx = document.getElementById('stockChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'bar',
                data: {
                    labels: stockLabels,
                    datasets: [{
                        label: 'Stock',
                        data: stockValues,
                        backgroundColor: primaryColor,
                        borderRadius: 5,
                        barThickness: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        x: { grid: { display: false } }
                    }
                }
            });
            
            // 2. CHART SEBARAN NILAI RATING (Doughnut Chart)
            const ratingCtx = document.getElementById('ratingChart').getContext('2d');
            new Chart(ratingCtx, {
                type: 'doughnut',
                data: {
                    labels: ratingLabels.map(l => `${l} Bintang`),
                    datasets: [{
                        data: ratingValues,
                        backgroundColor: colors,
                        hoverBackgroundColor: colors.map(c => c + 'AA'),
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true, // Biarkan ini true agar chart tetap berbentuk lingkaran di tengah box
                    plugins: {
                        legend: { position: 'right' },
                        title: { display: false },
                    }
                }
            });
            
            // 3. CHART SEBARAN PEMBERI RATING BERDASARKAN PROVINSI (Horizontal Bar Chart)
            const provinceCtx = document.getElementById('provinceChart').getContext('2d');
            new Chart(provinceCtx, {
                type: 'bar',
                data: {
                    labels: provinceLabels,
                    datasets: [{
                        label: 'Jumlah Reviewer',
                        data: provinceValues,
                        backgroundColor: primaryColor,
                        hoverBackgroundColor: primaryHoverColor,
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat Horizontal Bar Chart
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        y: { grid: { display: false } }
                    }
                }
            });

            // Product Sort Functionality
            const sortSelect = document.getElementById('sortProducts');
            const productsList = document.getElementById('productsList');
            
            if (sortSelect && productsList) {
                sortSelect.addEventListener('change', function() {
                    const sortValue = this.value;
                    const products = Array.from(productsList.children);
                    
                    products.sort((a, b) => {
                        const aStock = parseInt(a.dataset.stock);
                        const bStock = parseInt(b.dataset.stock);
                        const aRating = parseFloat(a.dataset.rating);
                        const bRating = parseFloat(b.dataset.rating);
                        
                        switch(sortValue) {
                            case 'stock_desc': return bStock - aStock;
                            case 'stock_asc': return aStock - bStock;
                            case 'rating_desc': return bRating - aRating;
                            case 'rating_asc': return aRating - bRating;
                            default: return 0;
                        }
                    });
                    
                    products.forEach(product => productsList.appendChild(product));
                });
            }
        });
    </script>
</body>
</html>