@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
    <section class="page-card">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Vận hành cửa hàng</span>
                <h1>Quản lý đơn hàng</h1>
                <p>Theo dõi và xử lý các đơn hàng của cửa hàng tại một nơi.</p>
            </div>
        </div>
        <div class="empty-state">
            <span class="feature-icon" aria-hidden="true">↗</span>
            <h2>Chưa có đơn hàng cần xử lý</h2>
            <p>Các đơn hàng mới sẽ được hiển thị tại đây.</p>
        </div>
    </section>
@endsection
