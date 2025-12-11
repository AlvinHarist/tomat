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
    </style>
</head>
<body>

    <div id="chart-data-source" 
        data-stats="{{ json_encode(array_column($monthlyVisitors, 'count')) }}" 
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

        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">SITE VISITORS</div>
                <div style="font-size: 0.8rem; color: #999;">This Year v</div>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="visitorsChart"></canvas>
            </div>
        </div>

        <div class="bottom-grid">
            
            <div class="table-card">
                <div class="table-header">
                    <span>Province Reviewer</span>
                    <span>Count</span>
                </div>
                @foreach($reviewersByProvince as $reviewer)
                <div class="list-item">
                    <span>{{ is_array($reviewer) ? $reviewer['province'] : $reviewer->province }}</span>
                    <span>{{ is_array($reviewer) ? $reviewer['count'] : $reviewer->count }}</span>
                </div>
                @endforeach
                <a href="{{ route('seller.reviewers.by-province.index') }}" class="more-link">More...</a>
            </div>

            <div class="table-card">
                <div class="chart-header">
                    <div class="chart-title" style="margin: 0; text-transform: none; font-weight: 600;">Products</div>
                    <select id="sortProducts" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 8px; font-size: 0.85rem; color: #777;">
                        <option value="stock_desc">Stock descending</option>
                        <option value="stock_asc">Stock ascending</option>
                        <option value="rating_desc">Rating descending</option>
                        <option value="rating_asc">Rating ascending</option>
                    </select>
                </div>
                <div id="productsList">
                    @foreach($products->take(3) as $product)
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
                <a href="{{ route('seller.products.index') }}" class="more-link">More...</a>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Visitors Chart (Meniru implementasi di Owner)
            const dataSource = document.getElementById('chart-data-source');

            if (dataSource) {
                const visitorsData = JSON.parse(dataSource.getAttribute('data-stats'));
                const ctx = document.getElementById('visitorsChart').getContext('2d');
                
                const chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartLabels, 
                        datasets: [{
                            label: 'Visitors',
                            data: visitorsData,
                            backgroundColor: '#4CAF50', 
                            borderRadius: 5,
                            barThickness: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, 
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: '#f0f0f0' } 
                            },
                            x: { 
                                grid: { display: false } 
                            }
                        }
                    }
                });
            }

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