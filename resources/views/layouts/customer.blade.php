<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'FruitStore - Trái cây tươi')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg fruit-navbar">
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
                        <span class="brand-name">Fruit<span>Store</span></span>
                        <small>FRESH FROM NATURE</small>
                    </span>
                </a>

                <!-- MOBILE BUTTON -->
                <button class="navbar-toggler fruit-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Mở menu">
                    <i class="bi bi-list"></i>
                </button>

                <!-- MENU -->
                <div class="collapse navbar-collapse" id="mainNavbar">

                    <ul class="navbar-nav fruit-nav ms-lg-auto">

                        <li class="nav-item" data-roles="customer,admin">
                            <a class="nav-link {{ request()->routeIs('customer.store') ? 'active' : '' }}"
                                href="{{ route('customer.store') }}">
                                <i class="bi bi-house-door"></i>
                                <span>Cửa hàng</span>
                            </a>
                        </li>

                        <li class="nav-item" data-roles="customer">
                            <a class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}"
                                href="{{ route('customer.orders') }}">
                                <i class="bi bi-receipt"></i>
                                <span>Lịch sử đơn hàng</span>
                            </a>
                        </li>

                        <li class="nav-item" data-roles="inventory_manager,admin">
                            <a class="nav-link" href="#">
                                <i class="bi bi-box-seam"></i>
                                <span>Quản lý kho</span>
                            </a>
                        </li>

                        <li class="nav-item" data-roles="admin">
                            <a class="nav-link" href="#">
                                <i class="bi bi-tags"></i>
                                <span>Sản phẩm</span>
                            </a>
                        </li>

                        <li class="nav-item" data-roles="admin">
                            <a class="nav-link" href="#">
                                <i class="bi bi-clipboard2-check"></i>
                                <span>Đơn hàng</span>
                            </a>
                        </li>

                        <li class="nav-item" data-roles="admin">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people"></i>
                                <span>Tài khoản</span>
                            </a>
                        </li>

                    </ul>

                    <div class="navbar-note d-none d-lg-flex">
                        <i class="bi bi-leaf-fill"></i>
                        <span>Fresh &amp; Natural</span>
                    </div>

                </div>
            </div>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="container page-container">
        @yield('content')
    </main>

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

    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>

    @stack('scripts')

</body>
</html>