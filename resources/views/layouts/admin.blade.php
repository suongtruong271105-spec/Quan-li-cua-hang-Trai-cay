<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị Cửa hàng Trái cây')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
  @yield('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container">
            
                  <!-- LOGO -->
                <a class="navbar-brand fruit-brand" href="{{ route('customer.store') }}">
                    <span class="brand-logo">
                        <i class="bi bi-basket2-fill"></i>
                        <span class="brand-leaf">
                            <i class="bi bi-leaf-fill"></i>
                        </span>
                    </span>

                    <span class="brand-text">
                        <span class="brand-name">FruitStore<span>Admin</span></span>
                        <small>FRESH FROM NATURE</small>
                    </span>
                </a>
            <div class="navbar-nav">

                <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="/admin/products">Sản phẩm</a>

                @if(!auth()->check() || in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                    <a class="nav-link {{ request()->routeIs('manager.inventory') ? 'active' : '' }}" href="{{ route('manager.inventory') }}">Kho hàng</a>
                @endif
                
            <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="/admin/orders">Đơn hàng</a>
    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">Người dùng</a>

            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
   <!-- FOOTER -->
    <footer class="fruit-footer">
        <div class="container">

            <div class="footer-main">
                <div class="footer-brand">
                    <i class="bi bi-basket2-fill"></i>
                    FruitStore
                </div>

                <p>
                    Trái cây tươi ngon – Lựa chọn xanh cho sức khỏe mỗi ngày.
                </p>
            </div>

            <div class="footer-bottom">
                <span>© 2026 FruitStore. All rights reserved.</span>

                <span>
                    Made with <i class="bi bi-heart-fill"></i> for healthy living
                </span>
            </div>

        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
     <script src="{{ asset('js/config.js') }}"></script>
    @yield('scripts')
</body>
</html>