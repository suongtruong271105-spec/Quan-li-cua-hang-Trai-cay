@extends('layouts.admin')

@section('title', 'Thêm Trái cây mới')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 650px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-success">Thêm trái cây mới</h5>
        <a href="/admin/products" class="btn btn-sm btn-outline-secondary">← Quay lại</a>
    </div>

    <div class="card-body">
        <form id="createForm">
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
                <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('createForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const payload = {
            name: document.getElementById('name').value.trim(),
            price_per_kg: document.getElementById('price_per_kg').value,
            origin: document.getElementById('origin').value.trim(),
            image_url: document.getElementById('image_url').value.trim(),
            description: document.getElementById('description').value.trim(),
        };

        const res = await fetch(`${window.API_CONFIG.PRODUCT_URL}/products`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        if (!res.ok) {
            alert('Lỗi: ' + (result.message || 'Vui lòng kiểm tra lại dữ liệu'));
            return;
        }

        // alert('Thêm trái cây thành công!');
        window.location.href = '/admin/products';
    });
</script>
@endsection