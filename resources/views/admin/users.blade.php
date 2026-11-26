@extends('layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
    <section class="page-card">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Khu vực quản trị</span>
                <h1>Quản lý người dùng</h1>
                <p>Quản lý tài khoản và phân quyền truy cập trong cửa hàng.</p>
            </div>
        </div>
        <p id="users-message" class="notice notice-info" role="status" aria-live="polite">Đang kiểm tra quyền truy cập…</p>
        <div id="users-content" hidden>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th class="table-actions">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body"></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const message = document.getElementById('users-message');
            const content = document.getElementById('users-content');
            const tableBody = document.getElementById('users-table-body');
            const roles = ['customer', 'inventory_manager', 'admin'];
            const roleLabels = {
                customer: 'Khách hàng',
                inventory_manager: 'Quản lý kho',
                admin: 'Quản trị viên',
            };

            function renderUsers(users) {
                tableBody.replaceChildren();

                users.forEach((user) => {
                    const row = document.createElement('tr');
                    const name = document.createElement('td');
                    const email = document.createElement('td');
                    const roleCell = document.createElement('td');
                    const actions = document.createElement('td');
                    actions.className = 'table-actions';
                    const roleBadge = document.createElement('span');
                    roleBadge.className = `role-badge role-${user.role}`;
                    roleBadge.textContent = roleLabels[user.role] || user.role;
                    const select = document.createElement('select');
                    select.className = 'table-role-select';
                    select.dataset.userRole = '';
                    select.dataset.userId = user.id;

                    name.textContent = user.name;
                    email.textContent = user.email;
                    roles.forEach((role) => {
                        const option = document.createElement('option');
                        option.value = role;
                        option.textContent = roleLabels[role];
                        option.selected = role === user.role;
                        select.append(option);
                    });

                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'delete-button';
                    remove.dataset.deleteUser = user.id;
                    remove.textContent = 'Xóa';

                    roleCell.append(roleBadge, select);
                    actions.append(remove);
                    row.append(name, email, roleCell, actions);
                    tableBody.append(row);
                });
            }

            async function loadUsers() {
               const payload = await window.Auth.apiFetch(`${window.API_CONFIG.USER_URL}/admin/users`);
                if (!Array.isArray(payload.data)) {
                    throw new Error('Phản hồi danh sách người dùng không hợp lệ.');
                }

                renderUsers(payload.data);
                content.hidden = false;
                message.textContent = '';
                message.classList.remove('notice-info', 'notice-error');
                message.classList.add('notice-success');
            }

            window.Auth.syncUser()
                .then((user) => {
                    if (!user || user.role !== 'admin') {
                        message.textContent = 'Bạn cần đăng nhập bằng tài khoản quản trị viên để xem trang này.';
                        message.classList.remove('notice-info');
                        message.classList.add('notice-error');
                        return;
                    }

                    return loadUsers();
                })
                .catch((error) => {
                    message.textContent = error.message || 'Không thể tải danh sách người dùng.';
                    message.classList.remove('notice-info');
                    message.classList.add('notice-error');
                });

            tableBody.addEventListener('change', async (event) => {
                const select = event.target.closest('[data-user-role]');
                if (!select) return;

                select.disabled = true;
                try {
                    const payload = await window.Auth.apiFetch(`${window.API_CONFIG.USER_URL}/admin/users/${select.dataset.userId}/role`, {
                        method: 'PATCH',
                        body: { role: select.value },
                    });
                    if (Number(select.dataset.userId) === Number(window.Auth.getUser()?.id)) {
                        window.Auth.saveAuth({
                            token: window.Auth.getToken(),
                            role: payload.user.role,
                            user: payload.user,
                        });
                        if (payload.user.role !== 'admin') {
                            window.location.assign('/fruits');
                        }
                    }
                    const row = select.closest('tr');
                    const badge = row.querySelector('.role-badge');
                    badge.className = `role-badge role-${payload.user.role}`;
                    badge.textContent = roleLabels[payload.user.role] || payload.user.role;
                    message.textContent = payload.message;
                    message.classList.remove('notice-info', 'notice-error');
                    message.classList.add('notice-success');
                } catch (error) {
                    message.textContent = error.message;
                    message.classList.remove('notice-info', 'notice-success');
                    message.classList.add('notice-error');
                    await loadUsers().catch((loadError) => {
                        message.textContent = loadError.message;
                    });
                } finally {
                    select.disabled = false;
                }
            });

            tableBody.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-delete-user]');
                if (!button || !window.confirm('Bạn có chắc muốn xóa người dùng này?')) return;

                button.disabled = true;
                try {
                    const payload = await window.Auth.apiFetch(`${window.API_CONFIG.USER_URL}/admin/users/${button.dataset.deleteUser}`, {
                        method: 'DELETE',
                    });
                    message.textContent = payload.message;
                    message.classList.remove('notice-info', 'notice-error');
                    message.classList.add('notice-success');
                    await loadUsers();
                } catch (error) {
                    message.textContent = error.message;
                    message.classList.remove('notice-info', 'notice-success');
                    message.classList.add('notice-error');
                } finally {
                    button.disabled = false;
                }
            });
        })();
    </script>
@endpush
