(function () {
    const storageKeys = {
        token: 'auth_token',
        role: 'auth_role',
        user: 'auth_user',
    };
    const roles = ['customer', 'inventory_manager', 'admin'];
    const roleLabels = {
        customer: 'Khách hàng',
        inventory_manager: 'Quản lý kho',
        admin: 'Quản trị viên',
    };

    function getToken() {
        return localStorage.getItem(storageKeys.token);
    }

    function getUser() {
        try {
            return JSON.parse(localStorage.getItem(storageKeys.user) || 'null');
        } catch (error) {
            console.error('Không thể đọc thông tin người dùng đã lưu.', error);
            return null;
        }
    }

    function getRole() {
        if (!getToken()) {
            return 'guest';
        }

        const role = localStorage.getItem(storageKeys.role);

        return roles.includes(role) ? role : 'guest';
    }

    function saveAuth(payload) {
        if (!payload || !payload.token || !payload.user || !roles.includes(payload.role)) {
            throw new Error('Phản hồi xác thực không hợp lệ.');
        }

        localStorage.setItem(storageKeys.token, payload.token);
        localStorage.setItem(storageKeys.role, payload.role);
        localStorage.setItem(storageKeys.user, JSON.stringify(payload.user));
        updateNavbar();
    }

    function clearAuth() {
        Object.values(storageKeys).forEach((key) => localStorage.removeItem(key));
        updateNavbar();
    }

    async function apiFetch(url, options) {
        const requestOptions = options || {};
        const headers = new Headers(requestOptions.headers || {});
        headers.set('Accept', 'application/json');

        const token = getToken();
        if (token) {
            headers.set('Authorization', `Bearer ${token}`);
        }

        let body = requestOptions.body;
        if (body && typeof body === 'object' && !(body instanceof FormData)) {
            headers.set('Content-Type', 'application/json');
            body = JSON.stringify(body);
        }

        const response = await fetch(url, { ...requestOptions, headers, body });
        const payload = await response.json();

        if (!response.ok) {
            const error = new Error(payload.message || 'Yêu cầu không thành công.');
            error.status = response.status;
            error.payload = payload;
            throw error;
        }

        return payload;
    }

    async function syncUser() {
        if (!getToken()) {
            updateNavbar();
            return null;
        }

        try {
            const payload = await apiFetch('/api/me');
            const user = payload.user;

            if (!user || !roles.includes(user.role)) {
                throw new Error('Thông tin người dùng từ máy chủ không hợp lệ.');
            }

            localStorage.setItem(storageKeys.role, user.role);
            localStorage.setItem(storageKeys.user, JSON.stringify(user));
            updateNavbar();

            return user;
        } catch (error) {
            if (error.status === 401) {
                clearAuth();
                return null;
            }

            console.error('Không thể đồng bộ thông tin người dùng.', error);
            throw error;
        }
    }

    function updateNavbar() {
        const role = getRole();
        const user = getUser();

        document.querySelectorAll('[data-auth-roles]').forEach((element) => {
            const allowedRoles = element.dataset.authRoles.split(/\s+/);
            element.hidden = !allowedRoles.includes(role);
        });
        document.querySelectorAll('[data-auth-guest]').forEach((element) => {
            element.hidden = role !== 'guest';
        });

        const identity = document.querySelector('[data-auth-identity]');
        if (identity) {
            const name = user && user.name ? user.name : '';
            identity.textContent = role === 'guest'
                ? ''
                : `${name} · ${roleLabels[role] || role}`;
        }
    }

    function logout() {
        clearAuth();
        window.location.assign('/');
    }

    window.Auth = {
        apiFetch,
        clearAuth,
        getRole,
        getToken,
        getUser,
        logout,
        saveAuth,
        syncUser,
        updateNavbar,
    };

    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-auth-logout]')) {
            logout();
        }
    });

    updateNavbar();
    syncUser().catch(() => {});
})();
