<nav class="navbar" aria-label="Điều hướng chính">
    <div class="navbar-inner">
        <div class="nav-brand">
            <a class="brand nav-links" href="/" data-auth-roles="guest customer inventory_manager admin" hidden>
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12.1 8.3c-3.8-3.4-8.3-.1-7.5 5.1.7 4.5 3.8 7.3 7.4 7.3s6.7-2.8 7.4-7.3c.9-5.2-3.5-8.5-7.3-5.1Z" fill="currentColor" opacity=".22"/>
                        <path d="M12 8.4c-3.8-3.4-8.3-.1-7.4 5.1.7 4.5 3.8 7.2 7.4 7.2s6.7-2.7 7.4-7.2c.9-5.2-3.6-8.5-7.4-5.1Z" stroke="currentColor" stroke-width="1.7"/>
                        <path d="M12 8c-.2-2.4 1.1-4.1 3.4-4.8M12.4 6.1c-1.6-1.2-3.1-1.3-4.7-.5 1 1.7 2.5 2.2 4.7 1.7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                Cửa hàng Trái cây
            </a>
        </div>
        <div class="nav-links" aria-label="Các trang">
            <a href="/login" data-auth-guest @if (request()->routeIs('login')) aria-current="page" @endif>Đăng nhập</a>
            <a href="/register" data-auth-guest @if (request()->routeIs('register')) aria-current="page" @endif>Đăng ký</a>
            <a href="/admin/users" data-auth-roles="admin" @if (request()->routeIs('admin.users')) aria-current="page" @endif hidden>Người dùng</a>
            <a href="/fruits" data-auth-roles="guest customer inventory_manager admin" @if (request()->routeIs('fruits')) aria-current="page" @endif hidden>Trái cây</a>
            <a href="/my-orders" data-auth-roles="customer" @if (request()->routeIs('my-orders')) aria-current="page" @endif hidden>Đơn hàng của tôi</a>
            <a href="/inventory" data-auth-roles="inventory_manager admin" @if (request()->routeIs('inventory')) aria-current="page" @endif hidden>Tồn kho</a>
            <a href="/orders" data-auth-roles="inventory_manager admin" @if (request()->routeIs('orders')) aria-current="page" @endif hidden>Quản lý đơn hàng</a>
        </div>
        <div class="nav-account">
            <span class="nav-identity" data-auth-roles="customer inventory_manager admin" data-auth-identity hidden></span>
            <span class="role-badge role-customer" data-auth-roles="customer" hidden>Khách hàng</span>
            <span class="role-badge role-inventory_manager" data-auth-roles="inventory_manager" hidden>Quản lý kho</span>
            <span class="role-badge role-admin" data-auth-roles="admin" hidden>Quản trị viên</span>
            <button class="logout-button" type="button" data-auth-roles="customer inventory_manager admin" data-auth-logout hidden>Đăng xuất</button>
        </div>
    </div>
</nav>
