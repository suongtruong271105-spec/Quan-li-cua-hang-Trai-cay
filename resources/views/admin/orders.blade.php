<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Đơn hàng - OrderService | Fruit Store SOA</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .badge-pending_payment { background-color: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
        .badge-processing { background-color: #DBEAFE; color: #1E40AF; border: 1px solid #93C5FD; }
        .badge-completed { background-color: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
        .badge-cancelled { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-apple-whole text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight">Fruit Store SOA</h1>
                    <p class="text-xs text-emerald-600 font-semibold tracking-wide flex items-center gap-1.5">
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="openCreateOrderModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none transition">
                    <i class="fa-solid fa-cart-plus mr-2"></i> Tạo đơn hàng mới
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Banner Thông tin Phân hệ -->
        <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Phân hệ Xử lý Đơn hàng</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="fetchStatistics(); fetchOrders();" class="text-xs font-medium text-slate-600 hover:text-emerald-700 bg-white border border-slate-300 hover:border-emerald-500 px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrows-rotate"></i> Làm mới dữ liệu
                </button>
            </div>
        </div>

        <!-- 3-4 Thẻ thống kê nhanh tình hình kinh doanh -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Thẻ 1: Tổng đơn hàng -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tổng số đơn hàng</p>
                    <h3 id="statTotalOrders" class="text-2xl font-black text-slate-800 mt-1">--</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>

            <!-- Thẻ 2: Tổng doanh thu -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tổng doanh thu</p>
                    <h3 id="statTotalRevenue" class="text-2xl font-black text-emerald-600 mt-1">-- đ</h3>
                    <p class="text-xs text-emerald-500 mt-1"><i class="fa-solid fa-arrow-trend-up"></i> Các đơn không bị hủy</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>

            <!-- Thẻ 3: Đơn chờ xử lý / Chờ thanh toán -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Đơn chờ xử lý</p>
                    <h3 id="statPendingOrders" class="text-2xl font-black text-amber-600 mt-1">--</h3>
                    <p class="text-xs text-amber-500 mt-1">Cần thanh toán / giao hàng</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- Thẻ 4: Đơn đã hoàn tất -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Đơn hoàn thành</p>
                    <h3 id="statCompletedOrders" class="text-2xl font-black text-indigo-600 mt-1">--</h3>
                    <p class="text-xs text-indigo-500 mt-1">Giao dịch thành công</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Bảng Quản lý đơn hàng -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <h2 class="text-base font-bold text-slate-800 whitespace-nowrap">Danh sách Đơn hàng</h2>
                    <span id="orderCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-200 text-slate-700">0 đơn</span>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-end">
                    <!-- Status Filter -->
                    <div class="flex items-center gap-2">
                        <label for="statusFilter" class="text-xs font-semibold text-slate-500">Trạng thái:</label>
                        <select id="statusFilter" onchange="fetchOrders()" class="text-xs font-medium bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending_payment">Chờ thanh toán (Pending)</option>
                            <option value="processing">Đang xử lý (Processing)</option>
                            <option value="completed">Đã hoàn thành (Completed)</option>
                            <option value="cancelled">Đã hủy (Cancelled)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Mã đơn</th>
                            <th scope="col" class="px-6 py-3.5">Khách hàng</th>
                            <th scope="col" class="px-6 py-3.5">Mặt hàng</th>
                            <th scope="col" class="px-6 py-3.5">Tổng tiền</th>
                            <th scope="col" class="px-6 py-3.5">Trạng thái</th>
                            <th scope="col" class="px-6 py-3.5">Ngày tạo</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody" class="divide-y divide-slate-200 bg-white">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-spinner fa-spin text-2xl text-emerald-500 mb-2"></i>
                                <p>Đang tải dữ liệu đơn hàng...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal Xem Chi Tiết Đơn Hàng -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 transform transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modalOrderId">Chi tiết Đơn hàng #</h3>
                        <p class="text-xs text-slate-500" id="modalOrderDate"></p>
                    </div>
                </div>
                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Order info cards -->
            <div class="grid grid-cols-3 gap-3 my-4 p-3 bg-slate-50 rounded-xl text-xs">
                <div>
                    <span class="text-slate-400 block">Khách hàng ID:</span>
                    <strong class="text-slate-800 text-sm" id="modalUserId">User #</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Trạng thái đơn:</span>
                    <span id="modalOrderStatus" class="inline-block mt-0.5 px-2 py-0.5 rounded-full font-semibold text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 block">Tổng thanh toán:</span>
                    <strong class="text-emerald-600 text-sm" id="modalTotalAmount">0 đ</strong>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mt-4">
                <h4 class="text-xs uppercase tracking-wider font-bold text-slate-500 mb-2">Danh sách mặt hàng</h4>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold">
                            <tr>
                                <th class="px-4 py-2.5 text-left">Trái cây (ID)</th>
                                <th class="px-4 py-2.5 text-right">Khối lượng (Kg)</th>
                                <th class="px-4 py-2.5 text-right">Đơn giá / Kg</th>
                                <th class="px-4 py-2.5 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsTableBody" class="divide-y divide-slate-200 bg-white">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tạo Đơn Hàng Mới (Test & Demo Flow) -->
    <div id="createOrderModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                        <i class="fa-solid fa-cart-plus"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Tạo đơn hàng mới</h3>
                </div>
                <button onclick="closeCreateOrderModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="createOrderForm" onsubmit="handleCreateOrder(event)" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mã khách hàng (User ID)</label>
                    <input type="number" id="createUserId" min="1" value="1" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Chọn Trái cây (ProductService)</label>
                    <select id="createFruitId" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="1">Trái cây ID #1 (Táo Envy Mỹ)</option>
                        <option value="2">Trái cây ID #2 (Cam Sành Sài Gòn)</option>
                        <option value="3">Trái cây ID #3 (Nho Mẫu Đơn Nhật)</option>
                        <option value="4">Trái cây ID #4 (Xoài Cát Hòa Lộc)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Khối lượng đặt mua (Kg)</label>
                    <input type="number" step="0.1" min="0.1" id="createQuantityKg" value="2.5" required class="w-full text-xs px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600">
                    <p class="font-semibold text-emerald-800"><i class="fa-solid fa-bolt mr-1"></i> Quy trình tự động:</p>
                    <ul class="list-disc ml-5 mt-1 space-y-0.5">
                        <li>OrderService gọi HTTP ProductService lấy giá theo kg.</li>
                        <li>OrderService gọi HTTP InventoryService trừ kho theo FIFO.</li>
                        <li>Ghi nhận đơn vào <code class="bg-slate-200 px-1 py-0.5 rounded">order_db.sqlite</code>.</li>
                    </ul>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeCreateOrderModal()" class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">Hủy</button>
                    <button type="submit" id="btnSubmitOrder" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i> Xác nhận tạo đơn
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden transition transform duration-300">
        <div id="toastContent" class="flex items-center space-x-3 px-4 py-3 rounded-xl shadow-lg text-xs font-medium text-white">
            <span id="toastIcon"></span>
            <span id="toastMessage"></span>
        </div>
    </div>

    <!-- JavaScript Logic -->
    <script>
        const statusMap = {
            'pending_payment': { label: 'Chờ thanh toán', class: 'badge-pending_payment' },
            'processing': { label: 'Đang xử lý', class: 'badge-processing' },
            'completed': { label: 'Đã hoàn thành', class: 'badge-completed' },
            'cancelled': { label: 'Đã hủy', class: 'badge-cancelled' }
        };

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount || 0);
        }

        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toastContent');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.innerText = message;
            toastContent.className = isSuccess 
                ? 'flex items-center space-x-3 px-4 py-3 rounded-xl shadow-lg text-xs font-medium text-white bg-emerald-600'
                : 'flex items-center space-x-3 px-4 py-3 rounded-xl shadow-lg text-xs font-medium text-white bg-rose-600';
            toastIcon.innerHTML = isSuccess ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>';

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3500);
        }

        // 1. Fetch Statistics (Tổng số đơn, tổng doanh thu, tình hình kinh doanh)
        async function fetchStatistics() {
            try {
                const res = await fetch('/api/orders/statistics');
                const result = await res.json();
                if (res.ok && result.data) {
                    const data = result.data;
                    document.getElementById('statTotalOrders').innerText = data.total_orders;
                    document.getElementById('statTotalRevenue').innerText = data.formatted_total_revenue;
                    document.getElementById('statPendingOrders').innerText = (data.status_counts.pending_payment || 0) + (data.status_counts.processing || 0);
                    document.getElementById('statCompletedOrders').innerText = data.status_counts.completed || 0;
                }
            } catch (err) {
                console.error('Lỗi lấy thống kê:', err);
            }
        }

        // 2. Fetch Orders List
        async function fetchOrders() {
            const status = document.getElementById('statusFilter').value;
            let url = '/api/orders';
            if (status) {
                url += `?status=${encodeURIComponent(status)}`;
            }

            try {
                const res = await fetch(url);
                const result = await res.json();
                const tbody = document.getElementById('ordersTableBody');
                const badge = document.getElementById('orderCountBadge');

                if (res.ok && result.data) {
                    const orders = result.data;
                    badge.innerText = `${orders.length} đơn`;

                    if (orders.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300"></i>
                                    <p>Không có đơn hàng nào phù hợp.</p>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    tbody.innerHTML = orders.map(order => {
                        const statusInfo = statusMap[order.status] || { label: order.status, class: 'bg-slate-100 text-slate-700' };
                        const itemCount = order.items ? order.items.length : 0;
                        const totalKg = order.items ? order.items.reduce((sum, it) => sum + parseFloat(it.quantity_kg), 0).toFixed(1) : 0;
                        const createdAt = new Date(order.created_at).toLocaleString('vi-VN');

                        return `
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900">
                                    #ORD-${String(order.id).padStart(4, '0')}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <span class="inline-flex items-center gap-1.5"><i class="fa-regular fa-user text-slate-400"></i> Khách #${order.user_id}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <span class="font-medium">${itemCount} loại</span> 
                                    <span class="text-xs text-slate-400">(${totalKg} kg)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-emerald-700">
                                    ${formatCurrency(order.total_amount)}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${statusInfo.class}">
                                        ${statusInfo.label}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    ${createdAt}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <button onclick="viewOrderDetail(${order.id})" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-md transition">
                                        <i class="fa-regular fa-eye mr-1"></i> Chi tiết
                                    </button>
                                    <select onchange="updateOrderStatus(${order.id}, this.value)" class="text-xs border border-slate-300 rounded-md px-2 py-1 bg-white text-slate-700 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                        <option value="" disabled selected>Đổi trạng thái...</option>
                                        <option value="pending_payment" ${order.status === 'pending_payment' ? 'disabled' : ''}>Chờ thanh toán</option>
                                        <option value="processing" ${order.status === 'processing' ? 'disabled' : ''}>Đang xử lý</option>
                                        <option value="completed" ${order.status === 'completed' ? 'disabled' : ''}>Đã hoàn thành</option>
                                        <option value="cancelled" ${order.status === 'cancelled' ? 'disabled' : ''}>Đã hủy</option>
                                    </select>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (err) {
                console.error('Lỗi lấy danh sách đơn:', err);
            }
        }

        // 3. Update Order Status
        async function updateOrderStatus(orderId, newStatus) {
            if (!newStatus) return;

            try {
                const res = await fetch(`/api/orders/${orderId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const result = await res.json();
                if (res.ok) {
                    showToast(`Cập nhật trạng thái đơn #${orderId} thành công!`, true);
                    fetchOrders();
                    fetchStatistics();
                } else {
                    showToast(result.message || 'Lỗi khi cập nhật trạng thái', false);
                }
            } catch (err) {
                showToast('Không thể kết nối đến máy chủ', false);
            }
        }

        // 4. View Order Detail Modal
        async function viewOrderDetail(orderId) {
            try {
                const res = await fetch(`/api/orders/${orderId}`);
                const result = await res.json();

                if (res.ok && result.data) {
                    const order = result.data;
                    document.getElementById('modalOrderId').innerText = `Chi tiết Đơn hàng #ORD-${String(order.id).padStart(4, '0')}`;
                    document.getElementById('modalOrderDate').innerText = `Ngày tạo: ${new Date(order.created_at).toLocaleString('vi-VN')}`;
                    document.getElementById('modalUserId').innerText = `User #${order.user_id}`;
                    document.getElementById('modalTotalAmount').innerText = formatCurrency(order.total_amount);

                    const statusInfo = statusMap[order.status] || { label: order.status, class: 'bg-slate-100 text-slate-700' };
                    const statusBadge = document.getElementById('modalOrderStatus');
                    statusBadge.innerText = statusInfo.label;
                    statusBadge.className = `inline-block mt-0.5 px-2.5 py-0.5 rounded-full font-semibold text-xs ${statusInfo.class}`;

                    const itemsTbody = document.getElementById('modalItemsTableBody');
                    if (order.items && order.items.length > 0) {
                        itemsTbody.innerHTML = order.items.map(it => {
                            const lineTotal = it.quantity_kg * it.unit_price;
                            return `
                                <tr>
                                    <td class="px-4 py-2 font-medium text-slate-800">Trái cây #${it.fruit_id}</td>
                                    <td class="px-4 py-2 text-right text-slate-600">${parseFloat(it.quantity_kg)} kg</td>
                                    <td class="px-4 py-2 text-right text-slate-600">${formatCurrency(it.unit_price)}</td>
                                    <td class="px-4 py-2 text-right font-bold text-emerald-700">${formatCurrency(lineTotal)}</td>
                                </tr>
                            `;
                        }).join('');
                    } else {
                        itemsTbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-slate-400">Không có mặt hàng nào.</td></tr>`;
                    }

                    document.getElementById('detailModal').classList.remove('hidden');
                }
            } catch (err) {
                showToast('Lỗi tải chi tiết đơn hàng', false);
            }
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function openCreateOrderModal() {
            document.getElementById('createOrderModal').classList.remove('hidden');
        }

        function closeCreateOrderModal() {
            document.getElementById('createOrderModal').classList.add('hidden');
        }

        // 5. Handle Create Order
        async function handleCreateOrder(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitOrder');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Đang xử lý...';

            const userId = parseInt(document.getElementById('createUserId').value);
            const fruitId = parseInt(document.getElementById('createFruitId').value);
            const quantityKg = parseFloat(document.getElementById('createQuantityKg').value);

            try {
                const res = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        items: [
                            { fruit_id: fruitId, quantity_kg: quantityKg }
                        ]
                    })
                });

                const result = await res.json();
                if (res.ok) {
                    showToast('Tạo đơn hàng thành công! Đã trừ kho FIFO.', true);
                    closeCreateOrderModal();
                    fetchOrders();
                    fetchStatistics();
                } else {
                    showToast(result.message || 'Lỗi khi tạo đơn hàng', false);
                }
            } catch (err) {
                showToast('Không thể kết nối máy chủ', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Xác nhận tạo đơn';
            }
        }

        // Initial Load
        document.addEventListener('DOMContentLoaded', () => {
            fetchStatistics();
            fetchOrders();
        });
    </script>
</body>
</html>
