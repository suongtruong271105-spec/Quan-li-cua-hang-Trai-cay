@extends('layouts.admin')

@section('title', 'Quản Lý Kho & Lô Hàng - FruitStore')

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #F8FAF8;
        }
        .navbar a {
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    <div class="pb-8 text-slate-800">

        <!-- Hero / Header Banner -->
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
                        Vận hành kho thông minh theo chuẩn <strong>FIFO</strong> — chủ động cảnh báo cận hạn để mọi lô trái cây luôn đạt độ tươi chuẩn vị.
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
                    <div class="relative" id="fruit-dropdown-wrapper">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1.5">
                            Loại trái cây
                        </label>
                        <input type="hidden" id="fruit_id" required>

                        <button type="button" id="fruit-dropdown-btn"
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50/70 border border-slate-200 rounded-xl flex items-center justify-between text-left hover:bg-white focus:bg-white focus:outline-none focus:border-[#247144] focus:ring-2 focus:ring-[#247144]/15 transition cursor-pointer">
                            <span id="fruit-selected-label" class="text-slate-400 truncate">-- Chọn loại trái cây --</span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" id="fruit-dropdown-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="fruit-dropdown-menu"
                             class="hidden absolute z-30 mt-1.5 w-full bg-white border border-slate-200/90 rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-2 border-b border-slate-100 bg-slate-50/60">
                                <input type="text" id="fruit-search-input" placeholder="Tìm tên hoặc mã trái cây..."
                                       class="w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-[#247144]">
                            </div>
                            <ul id="fruit-dropdown-list" class="max-h-52 overflow-y-auto divide-y divide-slate-50 py-1 text-sm">
                                <li class="px-3.5 py-2.5 text-xs text-slate-400 text-center">Đang tải danh sách...</li>
                            </ul>
                        </div>
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
                            <span>Sắp hết hạn</span>
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
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/inventory.js') }}"></script>
@endsection