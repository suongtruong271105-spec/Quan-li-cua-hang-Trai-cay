
@extends('layouts.customer')

@section('title', 'FruitStore - Fresh & Natural')

@section('content')

<div class="store-page">

    {{-- KHU VỰC PHÍA TRÊN: BANNER, DANH MỤC VÀ NỔI BẬT --}}
    <div class="store-layout">

        {{-- CỘT BÊN TRÁI --}}
        <div class="store-main">

            {{-- BANNER --}}
            <section class="hero-banner">

                <div class="hero-content">

                    <span class="hero-label">
                        <i class="bi bi-stars"></i>
                        FRESH &amp; NATURAL
                    </span>

                    <h1>
                        Explore Delicious<br>
                        <span>&amp; Fresh Fruits</span>
                    </h1>

                    <p>
                        Khám phá những loại trái cây tươi ngon,
                        tuyển chọn mỗi ngày để chăm sóc sức khỏe
                        cho bạn và gia đình.
                    </p>

                    <a href="#categorySection" class="btn-hero">
                        Khám phá ngay
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <div class="hero-features">
                        <span>
                            <i class="bi bi-check-circle-fill"></i>
                            Tươi mỗi ngày
                        </span>

                        <span>
                            <i class="bi bi-check-circle-fill"></i>
                            Chọn lọc chất lượng
                        </span>
                    </div>

                </div>

                <div class="hero-art">

                    <div class="hero-image-wrap">
                        <img
                            src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=900&q=85"
                            alt="Trái cây tươi">
                    </div>

                    <span class="hero-sticker">
                        <i class="bi bi-heart-fill"></i>
                        Fresh every day
                    </span>

                    <span class="hero-leaf leaf-one">✦</span>
                    <span class="hero-leaf leaf-two">✿</span>

                </div>

            </section>


            {{-- DANH MỤC --}}
            <section class="category-section" id="categorySection">

                <div class="section-heading">
                    <div>
                        <span class="section-label">SHOP BY CATEGORY</span>
                        <h2>Danh mục trái cây</h2>

                        <p class="category-subtitle">
                            Chọn loại trái cây bạn yêu thích
                        </p>
                    </div>
                </div>

                <div class="category-list">

                    <button
                        type="button"
                        class="category-item"
                        data-category="all"
                        aria-pressed="false">

                        <span class="category-image category-all-image">
                            <i class="bi bi-grid-fill"></i>
                        </span>

                        <strong>Tất cả</strong>
                    </button>

                    <button
                        type="button"
                        class="category-item"
                        data-category="tao"
                        aria-pressed="false">

                        <span class="category-image">
                            <img src="https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=240&q=80"
                                 alt="Táo">
                        </span>

                        <strong>Táo</strong>
                    </button>

                    <button
                        type="button"
                        class="category-item"
                        data-category="cam"
                        aria-pressed="false">

                        <span class="category-image">
                            <img src="https://images.unsplash.com/photo-1547514701-42782101795e?auto=format&fit=crop&w=240&q=80"
                                 alt="Cam">
                        </span>

                        <strong>Cam</strong>
                    </button>

                    <button
                        type="button"
                        class="category-item"
                        data-category="nho"
                        aria-pressed="false">

                        <span class="category-image">
                            <img src="https://images.unsplash.com/photo-1537640538966-79f369143f8f?auto=format&fit=crop&w=240&q=80"
                                 alt="Nho">
                        </span>

                        <strong>Nho</strong>
                    </button>

                    <button
                        type="button"
                        class="category-item"
                        data-category="dau"
                        aria-pressed="false">

                        <span class="category-image">
                            <img src="https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=240&q=80"
                                 alt="Dâu tây">
                        </span>

                        <strong>Dâu tây</strong>
                    </button>

                    <button
                        type="button"
                        class="category-item"
                        data-category="xoai"
                        aria-pressed="false">

                        <span class="category-image">
                            <img src="https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=240&q=80"
                                 alt="Xoài">
                        </span>

                        <strong>Xoài</strong>
                    </button>

                </div>

            </section>

        </div>


        {{-- CỘT BÊN PHẢI: 4 SẢN PHẨM NỔI BẬT --}}
        <aside class="store-sidebar">

            <section class="sidebar-products">

                <div class="sidebar-heading">

                    <div>
                        <span class="section-label">
                            OUR BEST PICKS
                        </span>

                        <h2>Trái cây nổi bật</h2>

                        <p class="featured-subtitle">
                            4 loại trái cây được tuyển chọn
                        </p>
                    </div>

                    <span class="sidebar-heading-icon">
                        <i class="bi bi-basket2"></i>
                    </span>

                </div>

                <div
                    class="featured-product-list"
                    id="featuredProductList"
                    aria-live="polite"
                    aria-busy="true">

                    <div class="product-state product-state--loading">
                        <span class="product-state__spinner"></span>
                        <p>Đang tải sản phẩm...</p>
                    </div>

                </div>

            </section>

        </aside>

    </div>


    {{-- KHU VỰC SẢN PHẨM: NẰM DƯỚI TOÀN BỘ HAI CỘT --}}
    <section
        class="filtered-products-section"
        id="filteredProductsSection"
        hidden>

        <div class="filtered-heading">

            <div>
                <span class="section-label">FRESH FRUITS</span>

                <h2 id="filteredHeading">
                    Tất cả trái cây
                </h2>

                <p id="filteredSubheading">
                    Các sản phẩm hiện có trong cửa hàng
                </p>
            </div>

            <span
                class="filtered-count"
                id="filteredCount">
                0 sản phẩm
            </span>

        </div>

        <div
            class="filtered-product-list"
            id="filteredProductList"
            aria-live="polite">

        </div>

    </section>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/customer-store.js') }}"></script>
@endpush
