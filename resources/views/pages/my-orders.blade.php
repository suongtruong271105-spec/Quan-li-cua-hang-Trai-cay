@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <section class="page-card">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Tài khoản của bạn</span>
                <h1>Đơn hàng của tôi</h1>
                <p>Xem lại tình trạng các đơn hàng của bạn tại đây.</p>
            </div>
        </div>
        <div class="empty-state">
            <span class="feature-icon" aria-hidden="true">♡</span>
            <h2>Bạn chưa có đơn hàng</h2>
            <p>Các đơn hàng của bạn sẽ xuất hiện tại đây.</p>
        </div>
    </section>
@endsection
