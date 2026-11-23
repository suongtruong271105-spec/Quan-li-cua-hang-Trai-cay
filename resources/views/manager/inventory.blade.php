<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Kho & Lô Hàng - FruitStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #F8FAF8;
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col">

    <!-- Top Navbar đồng bộ với FruitStore -->
    <header class="bg-[#247144] text-white shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-[#FACC15]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-extrabold tracking-tight leading-none">
                        <span class="text-white">Fruit</span><span class="text-[#FACC15]">Store</span>
                    </div>
                    <span class="text-[9px] font-semibold tracking-[0.2em] text-emerald-100/90 uppercase block mt-0.5">
                        FRESH FROM NATURE
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-2 sm:gap-3 text-sm">
                <a href="/" class="px-3.5 py-2 rounded-xl text-emerald-50 hover:bg-white/10 transition flex items-center gap-2">
                    <svg class="w-4 h-4 opacity-85" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="hidden sm:inline">Cửa hàng</span>
                </a>

                <a href="{{ route('manager.inventory') }}" class="px-4 py-2 rounded-xl bg-white/20 border border-white/25 text-white font-semibold shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FACC15]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Quản lý kho</span>
                </a>

                <div class="hidden md:flex items-center pl-4 ml-2 border-l border-white/20 text-xs text-emerald-100 font-medium">
                    Warehouse &amp; FIFO
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">

        <!-- Hero / Header Banner theo phong cách card bo góc của Thảo -->
        <div class="bg-gradient-to-r from-[#EFF6F1] via-[#F4F9F5] to-[#FDF8EC] border border-[#247144]/10 rounded-3xl p-6 sm:p-8 mb-8 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#247144] mb-2">
                        <span>✦</span>
                        <span>WAREHOUSE &amp; QUALITY CONTROL</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                        <span class="text-[#247144]">Quản Lý Kho &amp;</span>
                        <span class="text-[#D97757]">Lô Hàng Trái Cây</span>
                    </h1>
                    <p class="text-sm text-slate-600 mt-1.5 max-w-xl">
                        Theo dõi tồn kho theo nguyên tắc xuất trước <strong>FIFO</strong> và tự động cảnh báo các lô hàng sắp hết hạn để đảm bảo độ tươi ngon mỗi ngày.
                    </p>
                </div>

                <!-- 2 Thẻ thống kê nhanh -->
                <div class="grid grid-cols-2 gap-3 sm:gap-4 shrink-0">
                    <div class="bg-white/95 backdrop-blur px-5 py-4 rounded-2xl border border-emerald-900/10 shadow-sm flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-[#EAF4EE] text-[#247144] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Tổng tồn kho</span>
                            <span id="stat-total-kg" class="text-xl font-extrabold text-[#247144]">0 kg</span>
                        </div>
                    </div>

                    <div class="bg-white/95 backdrop-blur px-5 py-4 rounded-2xl border border-red-200/80 shadow-sm flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-red-500 block">Sắp hết hạn (≤ 3d)</span>
                            <span id="stat-warning-count" class="text-xl font-extrabold text-red-600">0 lô</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bố cục 2 cột chính -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Cột trái (4 cột): Form nhập lô hàng mới -->
            <section class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#247144] block">STOCK INTAKE</span>
                    <h2 class="text-lg font-bold text-slate-900 mt-0.5">Nhập lô hàng mới</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bổ sung số lượng trái cây nhập kho trong ngày</p>
                </div>

                <div id="alert-box" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium"></div>

                <form id="import-form" class="space-y-4">
                    <div>
                        <label for="fruit_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1.5">
                            Mã trái cây (Fruit ID)
                        </label>
                        <input type="number" id="fruit_id" list="fruit-options" min="1" required placeholder="Nhập mã VD: 1"
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15 transition">
                        <datalist id="fruit-options"></datalist>
                    </div>

                    <div>
                        <label for="quantity_kg" class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1.5">
                            Khối lượng nhập (kg)
                        </label>
                        <input type="number" id="quantity_kg" step="0.1" min="0.1" required placeholder="VD: 25.5"
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                        <div>
                            <label for="import_date" class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1.5">
                                Ngày nhập kho
                            </label>
                            <input type="date" id="import_date" required
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15 transition">
                        </div>

                        <div>
                            <label for="expiry_date" class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1.5">
                                Hạn sử dụng
                            </label>
                            <input type="date" id="expiry_date" required
                                   class="w-full px-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15 transition">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-2 bg-[#247144] hover:bg-[#1B5734] text-white font-semibold text-sm py-3 px-5 rounded-xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>Nhập lô vào kho</span>
                        <span>→</span>
                    </button>
                </form>
            </section>

            <!-- Cột phải (8 cột): Bảng theo dõi tồn kho & Cảnh báo -->
            <section class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#247144] block">FIFO INVENTORY LIST</span>
                        <h2 class="text-lg font-bold text-slate-900 mt-0.5">Danh sách lô hàng tồn kho</h2>
                    </div>

                    <!-- Thanh lọc theo phong cách nút pill -->
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="filter-fruit-id" min="1" placeholder="Lọc Fruit ID..."
                               class="w-32 px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-full focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15">

                        <button id="btn-all" onclick="loadAllBatches()"
                                class="px-4 py-2 text-xs font-semibold bg-[#247144] hover:bg-[#1B5734] text-white rounded-full shadow-sm transition cursor-pointer">
                            Tất cả lô
                        </button>

                        <button id="btn-expiring" onclick="loadExpiringBatches()"
                                class="px-4 py-2 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-full transition cursor-pointer flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <span>Sắp hết hạn (≤ 3 ngày)</span>
                        </button>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F3F8F5] border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-[#247144]">
                                <th class="py-3.5 px-4">Mã lô</th>
                                <th class="py-3.5 px-4">Trái cây</th>
                                <th class="py-3.5 px-4">Tồn kho</th>
                                <th class="py-3.5 px-4">Ngày nhập</th>
                                <th class="py-3.5 px-4">Hạn sử dụng</th>
                                <th class="py-3.5 px-4">Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table-body" class="divide-y divide-slate-100 text-sm">
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">Đang tải dữ liệu kho...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Chú thích màu sắc dưới bảng -->
                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-red-100 border border-red-300 inline-block"></span>
                            Lô còn ≤ 3 ngày sử dụng (Cảnh báo ưu tiên xuất)
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-[#EAF4EE] border border-[#247144]/30 inline-block"></span>
                            Lô đạt chuẩn tươi mới
                        </span>
                    </div>
                    <span>Thứ tự hiển thị: Nhập trước – Xuất trước (FIFO)</span>
                </div>
            </section>
        </div>
    </main>

    <script src="{{ asset('js/inventory.js') }}"></script>
</body>
</html>