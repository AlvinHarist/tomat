<aside class="w-64 bg-white shadow-lg flex flex-col fixed h-screen">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-green-600">ToMaT</h1>
        <p class="text-sm text-gray-600 mt-1">Seller Dashboard</p>
    </div>
    
    <!-- User Info -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 font-semibold text-lg">
                    {{ substr(Auth::user()->name ?? 'S', 0, 1) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ Auth::user()->name ?? 'Seller' }}
                </p>
                <p class="text-xs text-gray-500">Seller</p>
            </div>
        </div>
    </div>
    
    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Menu</p>
        
        <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('seller.dashboard') ? 'text-white bg-green-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        
        <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('seller.products.*') ? 'text-white bg-green-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Produk
        </a>
        
        <a href="{{ route('seller.reports.index') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('seller.reports.*') ? 'text-white bg-green-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Laporan
        </a>
    </nav>
    
    <!-- Logout -->
    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
