@extends('layouts.customer')

@section('title', 'FruitStore - Lịch sử đơn hàng')

@section('content')

<div class="orders-page">

    <!-- TIÊU ĐỀ -->
    <div class="orders-header">

        <div>
            <span class="section-label">MY ACCOUNT</span>
            <h1>Lịch sử đơn hàng</h1>
            <p>Theo dõi và quản lý những đơn hàng của bạn.</p>
        </div>

        <a href="{{ route('customer.store') }}" class="orders-back">
            <i class="bi bi-arrow-left"></i>
            Tiếp tục mua sắm
        </a>

    </div>


    <!-- THỐNG KÊ -->
    <div class="orders-stats">

        <div class="orders-stat-card">
            <span class="orders-stat-icon">
                <i class="bi bi-receipt"></i>
            </span>

            <div>
                <strong>0</strong>
                <small>Tổng đơn hàng</small>
            </div>
        </div>

        <div class="orders-stat-card">
            <span class="orders-stat-icon pending">
                <i class="bi bi-clock-history"></i>
            </span>

            <div>
                <strong>0</strong>
                <small>Đang xử lý</small>
            </div>
        </div>

        <div class="orders-stat-card">
            <span class="orders-stat-icon completed">
                <i class="bi bi-check-circle"></i>
            </span>

            <div>
                <strong>0</strong>
                <small>Đã hoàn thành</small>
            </div>
        </div>

    </div>


    <!-- DANH SÁCH ĐƠN HÀNG -->
    <section class="orders-panel">

        <div class="orders-panel-heading">

            <div>
                <h2>Đơn hàng của tôi</h2>
                <p>Thông tin các đơn hàng bạn đã đặt.</p>
            </div>

            <span class="orders-total">
                0 đơn hàng
            </span>

        </div>


        <!-- BẢNG ĐƠN HÀNG -->
        <div class="table-responsive">

            <table class="table orders-table">

                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td colspan="5" class="orders-empty">

                            <div class="orders-empty-content">

                                <div class="orders-empty-icon">
                                    <i class="bi bi-bag-x"></i>
                                </div>

                                <h3>Bạn chưa có đơn hàng nào</h3>

                                <p>
                                    Những đơn hàng bạn đặt sẽ được hiển thị tại đây.
                                    Hãy khám phá cửa hàng và chọn những loại trái cây yêu thích nhé!
                                </p>

                                <a href="{{ route('customer.store') }}">
                                    <i class="bi bi-basket2"></i>
                                    Khám phá cửa hàng
                                </a>

                            </div>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection