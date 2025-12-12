<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Seller - ToMaT</title>
  @vite('resources/css/app.css')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
  @include('seller.partials.sidebar')
  
  <!-- Main Content -->
  <div class="ml-64 p-8">
    <div class="max-w-7xl mx-auto">
      <!-- User Info Header -->
      <div class="flex items-center mb-8">
        <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-2xl font-bold text-gray-600">
          {{ substr($seller->pic_name, 0, 1) }}
        </div>
        <div class="ml-4">
          <h1 class="text-2xl font-bold text-gray-800">{{ $seller->pic_name }}</h1>
          <p class="text-gray-600">Seller</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Product Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $productsCount }}</h3>
          <p class="text-sm text-gray-600">Product</p>
        </div>

        <!-- Review Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-center w-12 h-12 bg-cyan-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $totalReviews }}</h3>
          <p class="text-sm text-gray-600">Total review</p>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-800">SITE VISITORS</h3>
          <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option>This Year</option>
            <option>Last Year</option>
          </select>
        </div>
        <div style="height: 300px;">
          <canvas id="visitorsChart"></canvas>
        </div>
      </div>

      <!-- Tables Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Reviewer by Province -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Province Reviewer</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Province Reviewer</th>
                  <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Count</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reviewersByProvince as $reviewer)
                <tr class="border-b border-gray-100">
                  <td class="py-3 px-4 text-sm text-gray-800">{{ is_array($reviewer) ? $reviewer['province'] : $reviewer->province }}</td>
                  <td class="py-3 px-4 text-sm text-gray-600 text-right">{{ is_array($reviewer) ? $reviewer['count'] : $reviewer->count }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mt-4 text-center">
              <a href="{{ route('seller.reviewers.by-province.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">More...</a>
            </div>
          </div>
        </div>

        <!-- Products Table with Sort -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Products</h3>
            <div class="text-right">
              <label class="text-sm font-semibold text-gray-800 block mb-1">Sort:</label>
              <select id="sortProducts" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="stock_desc">Stock descending</option>
                <option value="stock_asc">Stock ascending</option>
                <option value="rating_desc">Rating descending</option>
                <option value="rating_asc">Rating ascending</option>
              </select>
            </div>
          </div>
          <div class="space-y-4" id="productsList">
            @foreach($products->take(3) as $product)
            <div class="flex items-center space-x-4 pb-4 border-b border-gray-100" 
                 data-stock="{{ $product['stock'] }}" 
                 data-rating="{{ $product['rating'] }}">
              <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                @if($product['image'])
                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-800 text-sm truncate">{{ $product['name'] }}</h4>
                <div class="flex items-center space-x-4 mt-1">
                  <span class="text-xs text-gray-600">Stok: {{ $product['stock'] }}</span>
                  <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">{{ $product['category'] }}</span>
                </div>
                <div class="flex items-center space-x-4 mt-1">
                  <span class="text-xs text-gray-600">{{ $product['comments_count'] }} Comments</span>
                  <span class="text-xs text-yellow-600">⭐ {{ $product['rating'] }}</span>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="mt-4 text-center">
            <a href="{{ route('seller.products.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">More...</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Visitors Chart
    const ctx = document.getElementById('visitorsChart').getContext('2d');
    const visitorsData = {!! json_encode($monthlyVisitors) !!};
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: visitorsData.map(item => item.month),
        datasets: [{
          label: 'Visitors',
          data: visitorsData.map(item => item.count),
          backgroundColor: '#10b981',
          borderRadius: 6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 80,
            ticks: {
              stepSize: 20
            },
            grid: {
              display: true,
              color: '#f3f4f6'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
    
    // Product Sort Functionality
    const sortSelect = document.getElementById('sortProducts');
    const productsList = document.getElementById('productsList');
    
    sortSelect.addEventListener('change', function() {
      const sortValue = this.value;
      const products = Array.from(productsList.children);
      
      products.sort((a, b) => {
        const aStock = parseInt(a.dataset.stock);
        const bStock = parseInt(b.dataset.stock);
        const aRating = parseFloat(a.dataset.rating);
        const bRating = parseFloat(b.dataset.rating);
        
        switch(sortValue) {
          case 'stock_desc':
            return bStock - aStock;
          case 'stock_asc':
            return aStock - bStock;
          case 'rating_desc':
            return bRating - aRating;
          case 'rating_asc':
            return aRating - bRating;
          default:
            return 0;
        }
      });
      
      products.forEach(product => productsList.appendChild(product));
    });
  </script>

          <div class="flex items-center justify-center w-12 h-12 bg-cyan-100 rounded-full mb-4">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </div>
          <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ $totalReviews }}</h3>
          <p class="text-sm text-gray-600">Total review</p>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-800">SITE VISITORS</h3>
          <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option>This Year</option>
            <option>Last Year</option>
          </select>
        </div>
        <div style="height: 300px;">
          <canvas id="visitorsChart"></canvas>
        </div>
      </div>

      <!-- Tables Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Reviewer by Province -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Province Reviewer</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Province Reviewer</th>
                  <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Count</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reviewersByProvince as $reviewer)
                <tr class="border-b border-gray-100">
                  <td class="py-3 px-4 text-sm text-gray-800">{{ is_array($reviewer) ? $reviewer['province'] : $reviewer->province }}</td>
                  <td class="py-3 px-4 text-sm text-gray-600 text-right">{{ is_array($reviewer) ? $reviewer['count'] : $reviewer->count }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mt-4 text-center">
                            <!-- Tombol More diarahkan ke halaman penuh -->
              <a href="{{ route('seller.reviewers.by-province.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">More...</a>
            </div>
          </div>
        </div>

        <!-- Products Table with Sort -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Products</h3>
            <div class="text-right">
              <label class="text-sm font-semibold text-gray-800 block mb-1">Sort:</label>
              <select id="sortProducts" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="stock_desc">Stock descending</option>
                <option value="stock_asc">Stock ascending</option>
                <option value="rating_desc">Rating descending</option>
                <option value="rating_asc">Rating ascending</option>
              </select>
            </div>
          </div>
          <div class="space-y-4" id="productsList">
            @foreach($products->take(3) as $product)
            <div class="flex items-center space-x-4 pb-4 border-b border-gray-100" 
               data-stock="{{ $product['stock'] }}" 
               data-rating="{{ $product['rating'] }}">
              <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                @if($product['image'])
                <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover">
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-800 text-sm truncate">{{ $product['name'] }}</h4>
                <div class="flex items-center space-x-4 mt-1">
                  <span class="text-xs text-gray-600">Stok: {{ $product['stock'] }}</span>
                  <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">{{ $product['category'] }}</span>
                </div>
                <div class="flex items-center space-x-4 mt-1">
                  <span class="text-xs text-gray-600">{{ $product['comments_count'] }} Comments</span>
                  <span class="text-xs text-yellow-600">⭐ {{ $product['rating'] }}</span>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="mt-4 text-center">
            <a href="{{ route('seller.products.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">More...</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Visitors Chart
    const ctx = document.getElementById('visitorsChart').getContext('2d');
    const visitorsData = {!! json_encode($monthlyVisitors) !!};
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: visitorsData.map(item => item.month),
        datasets: [{
          label: 'Visitors',
          data: visitorsData.map(item => item.count),
          backgroundColor: '#10b981',
          borderRadius: 6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 80,
            ticks: {
              stepSize: 20
            },
            grid: {
              display: true,
              color: '#f3f4f6'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
    
    // Product Sort Functionality
    const sortSelect = document.getElementById('sortProducts');
    const productsList = document.getElementById('productsList');
    
    sortSelect.addEventListener('change', function() {
      const sortValue = this.value;
      const products = Array.from(productsList.children);
      
      products.sort((a, b) => {
        const aStock = parseInt(a.dataset.stock);
        const bStock = parseInt(b.dataset.stock);
        const aRating = parseFloat(a.dataset.rating);
        const bRating = parseFloat(b.dataset.rating);
        
        switch(sortValue) {
          case 'stock_desc':
            return bStock - aStock;
          case 'stock_asc':
            return aStock - bStock;
          case 'rating_desc':
            return bRating - aRating;
          case 'rating_asc':
            return aRating - bRating;
          default:
            return 0;
        }
      });
      
      products.forEach(product => productsList.appendChild(product));
    });
  </script>
</body>
</html>