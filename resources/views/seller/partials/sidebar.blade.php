<aside class="sidebar">
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name">{{ Auth::user()->name ?? 'Seller' }}</div>
            <div class="user-role">Seller</div>
        </div>
        <div class="avatar">
            <span style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: #f0f0f0; color: #4CAF50; font-weight: bold;">
                {{ substr(Auth::user()->name ?? 'S', 0, 1) }}
            </span>
        </div>
    </div>

    <div class="menu-title">MENU</div>
    <nav class="nav-links">
        <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        
        <a href="{{ route('seller.products.index') }}" class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <i class="fas fa-cubes"></i> Produk
        </a>
        
        <a href="{{ route('seller.reports.index') }}" class="{{ request()->routeIs('seller.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
        
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="background:none; border:none; color:#777; cursor:pointer; font-size:0.9rem; padding:12px 15px; display:flex; align-items:center;">
                <i class="fas fa-sign-out-alt" style="margin-right:15px;"></i> Logout
            </button>
        </form>
    </nav>

    <div class="logo">ToMaT</div>
</aside>