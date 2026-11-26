<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị Cửa hàng Trái cây')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/admin/products">Fruit Store Admin</a>
            <div class="navbar-nav">
                <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="/admin/products">Sản phẩm</a>

                @if(!auth()->check() || in_array(auth()->user()->role, ['admin', 'inventory_manager']))
                    <a class="nav-link {{ request()->routeIs('manager.inventory') ? 'active' : '' }}" href="{{ route('manager.inventory') }}">Kho hàng</a>
                @endif
                
                <a class="nav-link" href="#">Đơn hàng</a>
                <a class="nav-link" href="#">Người dùng</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>