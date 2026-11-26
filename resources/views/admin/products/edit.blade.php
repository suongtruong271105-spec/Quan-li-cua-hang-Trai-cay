@extends('layouts.admin')

@section('title', 'Cập nhật Trái cây')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 650px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-warning">Cập nhật trái cây #{{ $id }}</h5>
        <a href="/admin/products" class="btn btn-sm btn-outline-secondary">← Quay lại</a>
    </div>

    <div class="card-body">
        <form id="editForm">
            <div class="mb-3">
                <label class="form-label">Tên trái cây *</label>
                <input type="text" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá mỗi kg (VNĐ) *</label>
                <input type="number" id="price_per_kg" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Xuất xứ</label>
                <input type="text" id="origin" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Link hình ảnh (URL)</label>
                <input type="text" id="image_url" class="form-control" placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea id="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/admin/products" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const PRODUCT_ID = {{$id}};
    const API_URL = `/api/products/${PRODUCT_ID}`;

    // Tự động tải dữ liệu cũ điền vào form
    document.addEventListener('DOMContentLoaded', async () => {
        const res = await fetch(API_URL, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
            alert('Không tìm thấy sản phẩm!');
            window.location.href = '/admin/products';
            return;
        }

        const result = await res.json();
        const item = result.data;

        document.getElementById('name').value = item.name;
        document.getElementById('price_per_kg').value = item.price_per_kg;
        document.getElementById('origin').value = item.origin || '';
        document.getElementById('image_url').value = item.image_url || '';
        document.getElementById('description').value = item.description || '';
    });

    // Gửi request PUT khi bấm Cập nhật
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const payload = {
            name: document.getElementById('name').value.trim(),
            price_per_kg: document.getElementById('price_per_kg').value,
            origin: document.getElementById('origin').value.trim(),
            image_url: document.getElementById('image_url').value.trim(),
            description: document.getElementById('description').value.trim(),
        };

        const res = await fetch(API_URL, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        if (!res.ok) {
            alert('Lỗi: ' + (result.message || 'Cập nhật thất bại'));
            return;
        }

        // alert('Cập nhật thành công!');
        window.location.href = '/admin/products';
    });
</script>
@endsection