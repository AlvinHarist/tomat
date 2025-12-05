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
         data-stats="{{ json_encode($chartData) }}" 
         style="display: none;">
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

<div class="bottom-grid">
            
            <div class="table-card">
                <div class="table-header">
                    <span>Category</span>
                    <span>Product Count</span>
                </div>
                
                <div id="category-list">
                    @foreach($productByCategory as $index => $cat)
                    <div class="list-item {{ $index >= 5 ? 'hidden-row' : '' }}">
                        <span>{{ $cat->name }}</span>
                        <span>{{ $cat->products_count }}</span>
                    </div>
                    @endforeach
                </div>

                @if($productByCategory->count() > 5)
                    <a href="javascript:void(0)" class="more-link" onclick="showMore('category-list', this)">More...</a>
                @endif
            </div>

            <div class="table-card">
                <div class="table-header">
                    <span>Location</span>
                    <span>Seller Count</span>
                </div>

                <div id="location-list">
                    @foreach($sellerByLocation as $index => $loc)
                    <div class="list-item {{ $index >= 5 ? 'hidden-row' : '' }}">
                        <span>{{ $loc->pic_province }}</span>
                        <span>{{ $loc->total }}</span>
                    </div>
                    @endforeach
                </div>

                @if($sellerByLocation->count() > 5)
                    <a href="javascript:void(0)" class="more-link" onclick="showMore('location-list', this)">More...</a>
                @endif
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil elemen tersembunyi tadi
            const dataSource = document.getElementById('chart-data-source');

            // 2. Ambil isinya (data-stats) dan ubah dari teks JSON menjadi Objek/Array JS
            if (dataSource) {
                const visitorsData = JSON.parse(dataSource.getAttribute('data-stats'));

                // 3. Buat Chart seperti biasa
                const ctx = document.getElementById('visitorsChart').getContext('2d');
                const visitorsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
        function showMore(containerId, btnElement) {
            // 1. Cari container listnya
            const container = document.getElementById(containerId);
            
            // 2. Cari semua baris yang tersembunyi
            const hiddenRows = container.querySelectorAll('.hidden-row');
            
            // 3. Tampilkan semua baris tersebut
            hiddenRows.forEach(row => {
                row.style.display = 'flex'; // Kembalikan ke display flex agar rapi
            });

            // 4. Sembunyikan tombol "More" karena semua data sudah tampil
            btnElement.style.display = 'none';
        }
    </script>

</body>
</html>