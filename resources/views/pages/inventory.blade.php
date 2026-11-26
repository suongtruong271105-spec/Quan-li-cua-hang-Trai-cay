@extends('layouts.app')

@section('title', 'Lô tồn kho')

@section('content')
    <section class="page-card">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Vận hành cửa hàng</span>
                <h1>Lô tồn kho</h1>
                <p>Theo dõi thông tin và tình trạng các lô hàng trong kho.</p>
            </div>
        </div>
        <div class="empty-state">
            <span class="feature-icon" aria-hidden="true">▤</span>
            <h2>Chưa có dữ liệu tồn kho</h2>
            <p>Thông tin các lô hàng sẽ được hiển thị tại đây.</p>
        </div>
    </section>
@endsection
