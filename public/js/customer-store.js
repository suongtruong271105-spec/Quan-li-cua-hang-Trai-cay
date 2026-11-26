
document.addEventListener('DOMContentLoaded', function () {

    const featuredProductList =
        document.getElementById('featuredProductList');

    const filteredSection =
        document.getElementById('filteredProductsSection');

    const filteredProductList =
        document.getElementById('filteredProductList');

    const filteredHeading =
        document.getElementById('filteredHeading');

    const filteredSubheading =
        document.getElementById('filteredSubheading');

    const filteredCount =
        document.getElementById('filteredCount');

    const categoryButtons =
        document.querySelectorAll('[data-category]');

    if (!featuredProductList || !filteredProductList) {
        return;
    }


    // =====================================
    // API CONFIGURATION
    // =====================================

    const API_URL = (
        window.APP_CONFIG?.API_URL ||
        `${window.location.origin}/api`
    ).replace(/\/+$/, '');

    const PRODUCTS_URL = API_URL + '/products';

    const DEFAULT_IMAGE =
        'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=500&q=85';


    let allProducts = [];

    let currentCategory = null;

    let isLoaded = false;


    // =====================================
    // CATEGORY CONFIGURATION
    // =====================================

    const categoryNames = {
        all: 'Tất cả trái cây',
        tao: 'Táo',
        cam: 'Cam',
        nho: 'Nho',
        dau: 'Dâu tây',
        xoai: 'Xoài'
    };


    const categoryKeywords = {
        tao: ['tao', 'apple'],
        cam: ['cam', 'orange'],
        nho: ['nho', 'grape'],
        dau: ['dau', 'strawberry'],
        xoai: ['xoai', 'mango']
    };


    // 4 nhóm dùng để chọn sản phẩm nổi bật
    const featuredConfig = [
        {
            category: 'tao',
            preferredNames: ['tao fuji']
        },
        {
            category: 'cam',
            preferredNames: ['cam tuoi']
        },
        {
            category: 'nho',
            preferredNames: ['nho tuoi']
        },
        {
            category: 'dau',
            preferredNames: ['dau tay']
        }
    ];


    // =====================================
    // HELPER FUNCTIONS
    // =====================================

    function normalize(value) {

        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();

    }


    function matchesCategory(product, category) {

        if (category === 'all') {
            return true;
        }

        const name = normalize(product.name);

        const productCategory =
            normalize(product.category || '');

        const keywords =
            categoryKeywords[category] || [];

        return keywords.some(function (keyword) {

            return name.includes(keyword) ||
                productCategory.includes(keyword);

        });

    }


    // =====================================
    // GET 4 FEATURED PRODUCTS
    // =====================================

    function getFeaturedProducts() {

        const featuredProducts = [];

        const usedIds = new Set();

        featuredConfig.forEach(function (config) {

            const candidates = allProducts.filter(function (product) {

                return matchesCategory(
                    product,
                    config.category
                );

            });


            // Ưu tiên sản phẩm có tên đã chọn
            let selected = candidates.find(function (product) {

                return config.preferredNames.includes(
                    normalize(product.name)
                );

            });


            // Không có tên ưu tiên thì lấy sản phẩm đầu tiên
            if (!selected) {
                selected = candidates[0];
            }

            if (!selected) return;

            const id = String(
                selected.id ?? selected.name
            );

            // Không hiển thị trùng sản phẩm
            if (usedIds.has(id)) return;

            usedIds.add(id);

            featuredProducts.push(selected);

        });

        return featuredProducts;

    }


    // =====================================
    // PRICE & IMAGE
    // =====================================

    function formatPrice(price) {

        if (
            price === null ||
            price === undefined ||
            price === ''
        ) {
            return 'Chưa cập nhật giá';
        }

        const amount = Number(price);

        if (!Number.isFinite(amount)) {
            return 'Chưa cập nhật giá';
        }

        return new Intl.NumberFormat('vi-VN', {
            maximumFractionDigits: 0
        }).format(amount) + ' đ/kg';

    }


    function getImageUrl(url) {

        if (!url) return DEFAULT_IMAGE;

        try {

            const imageUrl = new URL(
                String(url).trim(),
                window.location.origin
            );

            if (
                imageUrl.protocol === 'https:' ||
                imageUrl.protocol === 'http:'
            ) {
                return imageUrl.href;
            }

        } catch (error) {

            console.warn('Ảnh không hợp lệ:', url);

        }

        return DEFAULT_IMAGE;

    }


    // =====================================
    // STATUS MESSAGE
    // =====================================

    function showMessage(
        container,
        message,
        type = 'empty',
        retry = false
    ) {

        container.replaceChildren();

        const box = document.createElement('div');

        box.className =
            `product-state product-state--${type}`;

        if (type === 'loading') {

            const spinner = document.createElement('span');

            spinner.className = 'product-state__spinner';

            box.appendChild(spinner);

        }

        const text = document.createElement('p');

        text.className = 'product-state__text';

        text.textContent = message;

        box.appendChild(text);


        if (retry) {

            const button = document.createElement('button');

            button.type = 'button';

            button.className = 'product-state__retry';

            button.textContent = 'Thử tải lại';

            button.addEventListener('click', loadProducts);

            box.appendChild(button);

        }

        container.appendChild(box);

    }


    // =====================================
    // CREATE PRODUCT CARD
    // =====================================

    function createProductCard(product) {

        const card = document.createElement('article');

        card.className = 'product-card';

        card.dataset.productCard = '';

        card.dataset.name = product.name || '';

        card.dataset.id = product.id || '';


        // Hình ảnh
        const imageWrapper = document.createElement('div');

        imageWrapper.className = 'product-image';

        const name = String(product.name || 'Sản phẩm');

        const normalizedName = normalize(name);


        if (normalizedName.includes('tao')) {

            imageWrapper.classList.add('apple-bg');

        } else if (normalizedName.includes('cam')) {

            imageWrapper.classList.add('orange-bg');

        } else if (normalizedName.includes('nho')) {

            imageWrapper.classList.add('grape-bg');

        } else if (normalizedName.includes('dau')) {

            imageWrapper.classList.add('strawberry-bg');

        }


        const image = document.createElement('img');

        image.src = getImageUrl(product.image_url);

        image.alt = name;

        image.loading = 'lazy';

        image.addEventListener('error', function () {

            this.src = DEFAULT_IMAGE;

        }, { once: true });


        const badge = document.createElement('span');

        badge.className = 'product-badge';

        badge.textContent = 'Fresh';

        imageWrapper.append(image, badge);


        // Nội dung sản phẩm
        const info = document.createElement('div');

        info.className = 'product-info';


        const origin = document.createElement('span');

        origin.className = 'product-category';

        origin.textContent = product.origin
            ? 'XUẤT XỨ: ' + product.origin
            : 'TRÁI CÂY TƯƠI';


        const title = document.createElement('h3');

        title.textContent = name;

        info.append(origin, title);


        if (product.description) {

            const description = document.createElement('p');

            description.className = 'product-description';

            description.textContent = product.description;

            info.appendChild(description);

        }


        // Giá
        const bottom = document.createElement('div');

        bottom.className = 'product-bottom';

        const price = document.createElement('strong');

        price.textContent = formatPrice(
            product.price_per_kg
        );

        bottom.appendChild(price);

        info.appendChild(bottom);


        card.append(imageWrapper, info);

        return card;

    }


    // =====================================
    // RENDER FEATURED - RIGHT SIDE
    // =====================================

    function renderFeatured() {

        const featuredProducts = getFeaturedProducts();

        featuredProductList.replaceChildren();


        if (featuredProducts.length === 0) {

            showMessage(
                featuredProductList,
                'Chưa có đủ sản phẩm nổi bật trong cửa hàng.'
            );

            return;

        }


        // Chỉ hiển thị tối đa 4 sản phẩm
        featuredProducts.slice(0, 4).forEach(function (product) {

            featuredProductList.appendChild(
                createProductCard(product)
            );

        });

    }


    // =====================================
    // RENDER FILTERED PRODUCTS - BELOW CATEGORY
    // =====================================

    function renderFilteredProducts(products) {

        filteredProductList.replaceChildren();


        filteredCount.textContent =
            `${products.length} sản phẩm`;


        if (products.length === 0) {

            showMessage(
                filteredProductList,
                currentCategory === 'all'
                    ? 'Hiện chưa có sản phẩm nào.'
                    : 'Chưa có sản phẩm thuộc danh mục này.'
            );

            return;

        }


        products.forEach(function (product) {

            filteredProductList.appendChild(
                createProductCard(product)
            );

        });

    }


    // =====================================
    // FILTER CATEGORY
    // =====================================

    function filterProducts(category) {

        currentCategory = category;


        // Hiện khu vực sản phẩm phía dưới danh mục
        filteredSection.hidden = false;


        // Cập nhật tiêu đề
        filteredHeading.textContent =
            categoryNames[category] || 'Tất cả trái cây';

        filteredSubheading.textContent =
            category === 'all'
                ? 'Tất cả sản phẩm hiện có trong cửa hàng'
                : `Các sản phẩm thuộc danh mục ${categoryNames[category]}`;


        // Đánh dấu danh mục đang chọn
        categoryButtons.forEach(function (button) {

            const active =
                button.dataset.category === category;

            button.classList.toggle('is-active', active);

            button.setAttribute(
                'aria-pressed',
                String(active)
            );

        });


        // Chưa tải xong dữ liệu
        if (!isLoaded) {

            showMessage(
                filteredProductList,
                'Đang tải sản phẩm...',
                'loading'
            );

            return;

        }


        // Lọc sản phẩm
        const filteredProducts = allProducts.filter(
            function (product) {

                return matchesCategory(product, category);

            }
        );


        renderFilteredProducts(filteredProducts);


        // Cuộn đến danh sách trên điện thoại
        if (window.innerWidth <= 767) {

            filteredSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }

    }


    // =====================================
    // LOAD API
    // =====================================

    async function loadProducts() {

        isLoaded = false;

        showMessage(
            featuredProductList,
            'Đang tải sản phẩm nổi bật...',
            'loading'
        );


        try {

            const response = await fetch(PRODUCTS_URL, {

                method: 'GET',

                headers: {
                    'Accept': 'application/json'
                }

            });


            const result = await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    `Lỗi HTTP ${response.status}`
                );

            }


            if (
                result.status &&
                result.status !== 'success'
            ) {

                throw new Error(
                    result.message ||
                    'Không thể tải sản phẩm.'
                );

            }


            if (!Array.isArray(result.data)) {

                throw new Error(
                    'Dữ liệu API không đúng định dạng.'
                );

            }


            allProducts = result.data;

            isLoaded = true;


            // Render riêng khu vực bên phải
            renderFeatured();


            // Nếu người dùng đã nhấn danh mục trong lúc tải API
            if (currentCategory !== null) {

                filterProducts(currentCategory);

            }


            console.log(
                'Tổng sản phẩm:',
                allProducts.length
            );

            console.log(
                'Sản phẩm nổi bật:',
                getFeaturedProducts()
            );


        } catch (error) {

            console.error('Lỗi tải sản phẩm:', error);

            showMessage(
                featuredProductList,
                'Không thể tải sản phẩm. Kiểm tra kết nối API.',
                'error',
                true
            );

            if (currentCategory !== null) {

                showMessage(
                    filteredProductList,
                    'Không thể tải danh sách sản phẩm.',
                    'error',
                    true
                );

            }

        }

    }


    // =====================================
    // CATEGORY CLICK EVENTS
    // =====================================

    categoryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const category = this.dataset.category;

            if (!category) return;

            filterProducts(category);

        });

    });


    // =====================================
    // INITIALIZE
    // =====================================

    // Mở trang chỉ tải 4 sản phẩm nổi bật.
    // Chưa hiển thị toàn bộ danh sách phía dưới.
    loadProducts();

});
