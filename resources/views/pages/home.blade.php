@extends('layouts.app')

@section('title', 'Cửa hàng Trái cây')

@section('content')
    <section class="page-card hero-card">
        <div class="hero-content">
            <span class="eyebrow">Tươi ngon mỗi ngày</span>
            <h1>Vị tươi lành từ thiên nhiên</h1>
            <p>Chào mừng bạn đến với cửa hàng trái cây. Khám phá danh mục và đăng nhập để sử dụng các tiện ích phù hợp với tài khoản của bạn.</p>
            <div class="button-row">
                <a class="button" href="/fruits">Khám phá trái cây</a>
                <a class="button button-secondary" href="/login" data-auth-guest>Đăng nhập</a>
            </div>
        </div>
    </section>
    <section class="content-grid" aria-label="Tiện ích cửa hàng">
        <article class="feature-card">
            <span class="feature-icon" aria-hidden="true">01</span>
            <h2>Danh mục trái cây</h2>
            <p>Khám phá các loại trái cây đang có tại cửa hàng.</p>
        </article>
        <article class="feature-card">
            <span class="feature-icon" aria-hidden="true">02</span>
            <h2>Theo dõi đơn hàng</h2>
            <p>Dễ dàng xem thông tin đơn hàng sau khi đăng nhập.</p>
        </article>
        <article class="feature-card">
            <span class="feature-icon" aria-hidden="true">03</span>
            <h2>Quản lý thuận tiện</h2>
            <p>Các công cụ vận hành được sắp xếp theo đúng vai trò.</p>
        </article>
    </section>
@endsection
