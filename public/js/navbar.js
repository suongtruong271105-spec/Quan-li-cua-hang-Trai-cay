
// ==========================================
// NAVBAR - PHÂN QUYỀN HIỂN THỊ MENU
// ==========================================

// Biến giả dùng để kiểm tra giao diện trong Tuần 1.
// Các giá trị hợp lệ:
// customer
// inventory_manager
// admin

const DEVELOPMENT_ROLE = 'customer';


// Hàm hiển thị menu theo quyền
function renderNavbar(role) {

    const menuItems = document.querySelectorAll('[data-roles]');

    menuItems.forEach(function (item) {

        const allowedRoles = item.dataset.roles
            .split(',')
            .map(function (roleName) {
                return roleName.trim();
            });

        const hasPermission = allowedRoles.includes(role);

        item.classList.toggle('d-none', !hasPermission);

    });

}


// Thực hiện khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function () {

    renderNavbar(DEVELOPMENT_ROLE);

});
