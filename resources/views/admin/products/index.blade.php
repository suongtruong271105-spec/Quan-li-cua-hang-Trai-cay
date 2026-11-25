@extends('layouts.admin')

@section('title', 'Danh sách Trái cây')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-success">Danh sách Trái cây</h5>
        <a href="/admin/products/create" class="btn btn-success">+ Thêm trái cây</a>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-5 d-flex gap-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Nhập tên trái cây cần tìm...">
                <button class="btn btn-outline-success" onclick="searchProducts()">Tìm</button>
                <button class="btn btn-outline-secondary" onclick="resetSearch()">Tất cả</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th width="60">ID</th>
                        <th width="90">Hình ảnh</th>
                        <th>Tên trái cây</th>
                        <th>Giá / kg (VNĐ)</th>
                        <th>Xuất xứ</th>
                        <th>Mô tả</th>
                        <th width="150" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr><td colspan="7" class="text-center">Đang tải dữ liệu...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const API_URL = '/api/products';

    document.addEventListener('DOMContentLoaded', () => {
        loadProducts();
        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') searchProducts();
        });
    });

    async function loadProducts(keyword = '') {
        const url = keyword ? `${API_URL}?search=${encodeURIComponent(keyword)}` : API_URL;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const result = await res.json();

        const tbody = document.getElementById('productTableBody');
        tbody.innerHTML = '';

        if (!result.data || result.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Không có sản phẩm nào.</td></tr>';
            return;
        }

        result.data.forEach(item => {
            const imgHtml = item.image_url
                ? `<img src="${item.image_url}" alt="${item.name}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">`
                : `<span class="text-muted small">Không có</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${item.id}</td>
                    <td>${imgHtml}</td>
                    <td class="fw-semibold">${item.name}</td>
                    <td>${Number(item.price_per_kg).toLocaleString('vi-VN')} đ</td>
                    <td>${item.origin || ''}</td>
                    <td>${item.description || ''}</td>
                    <td class="text-center">
                        <a href="/admin/products/${item.id}/edit" class="btn btn-sm btn-warning">Sửa</a>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${item.id})">Xóa</button>
                    </td>
                </tr>
            `;
        });
    }

    function searchProducts() {
        loadProducts(document.getElementById('searchInput').value.trim());
    }

    function resetSearch() {
        document.getElementById('searchInput').value = '';
        loadProducts();
    }

    async function deleteProduct(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa trái cây này không?')) return;

        const res = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });

        if (res.ok) {
            loadProducts();
        } else {
            alert('Xóa thất bại!');
        }
    }
</script>
@endsection