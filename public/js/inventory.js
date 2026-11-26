document.getElementById('import_date').value = new Date().toISOString().split('T')[0];

let allProducts = [];

// Render danh sách item trong dropdown
function renderProductOptions(keyword = '') {
    const listEl = document.getElementById('fruit-dropdown-list');
    const kw = keyword.toLowerCase().trim();

    const filtered = allProducts.filter(p =>
        p.name.toLowerCase().includes(kw) || String(p.id) === kw
    );

    if (filtered.length === 0) {
        listEl.innerHTML = `<li class="px-3.5 py-3 text-xs text-slate-400 text-center">Không tìm thấy trái cây phù hợp</li>`;
        return;
    }

    listEl.innerHTML = filtered.map(p => `
        <li data-id="${p.id}" data-name="${p.name}"
            class="fruit-option px-3.5 py-2.5 flex items-center justify-between hover:bg-[#EAF4EE] cursor-pointer transition group">
            <span class="font-medium text-slate-700 group-hover:text-[#247144]">${p.name}</span>
            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 group-hover:bg-[#247144] group-hover:text-white transition">
                ID: ${p.id}
            </span>
        </li>
    `).join('');

    // Gắn sự kiện click chọn từng item
    listEl.querySelectorAll('.fruit-option').forEach(item => {
        item.addEventListener('click', () => {
            const id = item.dataset.id;
            const name = item.dataset.name;

            document.getElementById('fruit_id').value = id;
            const labelEl = document.getElementById('fruit-selected-label');
            labelEl.textContent = `${name} (ID: ${id})`;
            labelEl.className = 'font-semibold text-slate-800 truncate';

            toggleFruitDropdown(false);
        });
    });
}

// Đóng / mở menu dropdown
function toggleFruitDropdown(forceState = null) {
    const menu = document.getElementById('fruit-dropdown-menu');
    const arrow = document.getElementById('fruit-dropdown-arrow');
    const isHidden = menu.classList.contains('hidden');
    const shouldOpen = forceState !== null ? forceState : isHidden;

    if (shouldOpen) {
        menu.classList.remove('hidden');
        arrow.classList.add('rotate-180');
        const searchInput = document.getElementById('fruit-search-input');
        searchInput.value = '';
        renderProductOptions();
        setTimeout(() => searchInput.focus(), 50);
    } else {
        menu.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

// Khởi tạo sự kiện Dropdown & tải danh mục từ ProductService
async function loadProductCatalog() {
    const btn = document.getElementById('fruit-dropdown-btn');
    const searchInput = document.getElementById('fruit-search-input');
    const wrapper = document.getElementById('fruit-dropdown-wrapper');

    btn.addEventListener('click', () => toggleFruitDropdown());
    searchInput.addEventListener('input', (e) => renderProductOptions(e.target.value));

    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) toggleFruitDropdown(false);
    });

    try {
        const res = await fetch('/api/products', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('API Error');

        const payload = await res.json();
        allProducts = Array.isArray(payload) ? payload : (payload.data || []);

        renderProductOptions();
    } catch (e) {
        document.getElementById('fruit-dropdown-list').innerHTML =
            `<li class="px-3.5 py-2.5 text-xs text-red-500 text-center">Không tải được danh sách sản phẩm</li>`;
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
            ? 'hover:bg-red-50/40 text-slate-700'
            : 'hover:bg-[#F8FAF8] text-slate-700';

        let statusBadge = `
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap bg-[#EAF4EE] text-[#247144]">
                        Fresh • Còn ${daysLeft} ngày
                    </span>`;

        if (daysLeft < 0) {
            statusBadge = `
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap bg-red-600 text-white">
                            Quá hạn ${Math.abs(daysLeft)} ngày
                        </span>`;
        } else if (daysLeft === 0) {
            statusBadge = `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap bg-red-100 text-red-700 border border-red-300">
                    Hết hạn hôm nay
                </span>`;
        } else if (daysLeft <= 3) {
            statusBadge = `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap bg-red-100 text-red-700 border border-red-300">
                    Hạn còn ${daysLeft} ngày
                </span>`;
        }

        const formatDate = (dateStr) => {
            if (!dateStr) return '';
            const [year, month, day] = dateStr.split('T')[0].split('-');
            return `${day}/${month}/${year}`;
        };

        const product = allProducts.find(p => p.id === batch.fruit_id);
        const fruitName = product
            ? `<span class="font-bold text-slate-900">${product.name}</span> <span class="text-xs text-slate-400">(#${batch.fruit_id})</span>`
            : `<span class="font-bold text-slate-900">SP-${batch.fruit_id}</span>`;

        return `
                    <tr class="${rowClass} transition">
                        <td class="py-3.5 px-4 font-semibold text-xs ${isDanger ? 'text-red-700' : 'text-slate-500'}">#${batch.id}</td>
                        <td class="py-3.5 px-4">${fruitName}</td>
                        <td class="py-3.5 px-4 font-bold ${isDanger ? 'text-red-700' : 'text-[#247144]'}">${batch.quantity_kg} kg</td>
                        <td class="py-3.5 px-4 text-xs">${formatDate(batch.import_date)}</td>
                        <td class="py-3.5 px-4 text-xs">${formatDate(batch.expiry_date)}</td>
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

    const fruitIdVal = document.getElementById('fruit_id').value;
    if (!fruitIdVal) {
        showAlert('Vui lòng chọn một loại trái cây từ danh sách!', true);
        toggleFruitDropdown(true);
        return;
    }

    const payload = {
        fruit_id: fruitIdVal,
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
        document.getElementById('fruit_id').value = '';
        const labelEl = document.getElementById('fruit-selected-label');
        labelEl.textContent = '-- Chọn loại trái cây --';
        labelEl.className = 'text-slate-400 truncate';
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