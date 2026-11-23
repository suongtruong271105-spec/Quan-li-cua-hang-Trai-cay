let productMap = {};

// Mặc định ngày nhập là hôm nay
document.getElementById('import_date').value = new Date().toISOString().split('T')[0];

// Lấy danh sách tên trái cây từ ProductService (nếu có dữ liệu) để hiển thị kèm Fruit ID
async function loadProductCatalog() {
    try {
        const res = await fetch('/api/products', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const payload = await res.json();
        const products = Array.isArray(payload) ? payload : (payload.data || []);

        const datalist = document.getElementById('fruit-options');
        datalist.innerHTML = '';

        products.forEach(p => {
            productMap[p.id] = p.name;
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.label = `${p.name} (ID: ${p.id})`;
            datalist.appendChild(opt);
        });
    } catch (e) {
        // Bỏ qua nếu ProductService chưa có dữ liệu
    }
}

function showAlert(message, isError = false) {
    const box = document.getElementById('alert-box');
    box.textContent = message;
    box.className = `mb-4 p-3.5 rounded-xl text-xs font-medium ${isError
            ? 'bg-red-50 text-red-700 border border-red-200'
            : 'bg-[#EAF4EE] text-[#247144] border border-[#247144]/20'
        }`;
    box.classList.remove('hidden');
    setTimeout(() => box.classList.add('hidden'), 4000);
}

function renderTable(batches) {
    const tbody = document.getElementById('inventory-table-body');

    if (!batches || batches.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="py-10 text-center text-slate-400 text-xs">Không có lô hàng nào phù hợp.</td></tr>`;
        return;
    }

    tbody.innerHTML = batches.map(batch => {
        const daysLeft = batch.days_remaining;
        const isDanger = daysLeft <= 3;

        // Tô màu đỏ cảnh báo cho các lô sắp hết hạn (<= 3 ngày)
        const rowClass = isDanger
            ? 'bg-red-50/95 hover:bg-red-100/80 text-red-950'
            : 'hover:bg-[#F8FAF8] text-slate-700';

        let statusBadge = `
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-[#EAF4EE] text-[#247144]">
                        Fresh • Còn ${daysLeft} ngày
                    </span>`;

        if (daysLeft < 0) {
            statusBadge = `
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">
                            Quá hạn ${Math.abs(daysLeft)} ngày
                        </span>`;
        } else if (daysLeft <= 3) {
            statusBadge = `
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-300">
                            Sắp hết hạn (${daysLeft} ngày)
                        </span>`;
        }

        const formatDate = (dateStr) => dateStr ? dateStr.split('T')[0] : '';
        const fruitName = productMap[batch.fruit_id]
            ? `<span class="font-bold text-slate-900">${productMap[batch.fruit_id]}</span> <span class="text-xs text-slate-400">(#${batch.fruit_id})</span>`
            : `<span class="font-bold text-slate-900">SP-${batch.fruit_id}</span>`;

        return `
                    <tr class="${rowClass} transition">
                        <td class="py-3.5 px-4 font-semibold text-xs ${isDanger ? 'text-red-700' : 'text-slate-500'}">#${batch.id}</td>
                        <td class="py-3.5 px-4">${fruitName}</td>
                        <td class="py-3.5 px-4 font-bold ${isDanger ? 'text-red-700' : 'text-[#247144]'}">${batch.quantity_kg} kg</td>
                        <td class="py-3.5 px-4 text-xs">${formatDate(batch.import_date)}</td>
                        <td class="py-3.5 px-4 text-xs font-medium">${formatDate(batch.expiry_date)}</td>
                        <td class="py-3.5 px-4">${statusBadge}</td>
                    </tr>
                `;
    }).join('');
}

async function loadAllBatches() {
    const fruitId = document.getElementById('filter-fruit-id').value;
    const url = fruitId ? `/api/inventory?fruit_id=${fruitId}` : '/api/inventory';

    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();

    document.getElementById('stat-total-kg').textContent = `${data.total_stock_kg} kg`;
    const warningCount = (data.batches || []).filter(b => b.days_remaining <= 3).length;
    document.getElementById('stat-warning-count').textContent = `${warningCount} lô`;

    renderTable(data.batches);
}

async function loadExpiringBatches() {
    const res = await fetch('/api/inventory/expiring?days=3', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();

    document.getElementById('stat-warning-count').textContent = `${data.total_warnings} lô`;
    renderTable(data.batches);
}

document.getElementById('import-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const payload = {
        fruit_id: document.getElementById('fruit_id').value,
        quantity_kg: document.getElementById('quantity_kg').value,
        import_date: document.getElementById('import_date').value,
        expiry_date: document.getElementById('expiry_date').value,
    };

    const res = await fetch('/api/inventory', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    const result = await res.json();

    if (res.ok) {
        showAlert(result.message || 'Nhập lô hàng thành công!');
        document.getElementById('quantity_kg').value = '';
        loadAllBatches();
    } else {
        const errorMsg = result.errors
            ? Object.values(result.errors).flat().join(' ')
            : (result.message || 'Có lỗi xảy ra');
        showAlert(errorMsg, true);
    }
});

// Khởi chạy đồng bộ danh mục sản phẩm & dữ liệu tồn kho
(async () => {
    await loadProductCatalog();
    await loadAllBatches();
})();