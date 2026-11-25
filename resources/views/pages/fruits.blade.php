@extends('layouts.app')

@section('title', 'Trái cây')

@section('content')
    <section class="page-card">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Tươi ngon mỗi ngày</span>
                <h1>Danh mục trái cây</h1>
                <p>Khám phá những lựa chọn tươi ngon trong cửa hàng.</p>
            </div>
        </div>
        <div class="empty-state">
            <span class="feature-icon" aria-hidden="true">✦</span>
            <h2>Danh mục đang được cập nhật</h2>
            <p>Các sản phẩm trái cây sẽ sớm xuất hiện tại đây.</p>
        </div>
    </section>
@endsection
