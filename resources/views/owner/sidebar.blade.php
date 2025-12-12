<aside class="sidebar">
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name">{{ Auth::guard('owner')->user()->name ?? 'Owner' }}</div>
            <div class="user-role">Owner</div>
        </div>
        <div class="avatar"></div>
    </div>

    <div class="menu-title">MENU</div>
    <nav class="nav-links">
        <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
    
        <a href="{{ route('owner.sellers.index') }}" class="{{ request()->routeIs('owner.sellers.*') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Seller
        </a>

        <a href="{{ route('owner.reports.index') }}" class="{{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Reports</a>

        <a href="{{ route('owner.categories.index') }}" class="{{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Categories</a>

        <form action="{{ route('owner.logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="background:none; border:none; color: #dc2626; cursor:pointer; font-size:0.9rem; padding:12px 15px; display:flex; align-items:center; width: 100%;">
                <i class="fas fa-sign-out-alt" style="margin-right:15px; width: 20px; text-align: center;"></i> Logout
            </button>
        </form>
    </nav>

    <div class="logo">ToMaT</div>
</aside>