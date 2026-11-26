@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
    <section class="panel">
        <div class="panel-heading">
            <span class="eyebrow">Chào mừng trở lại</span>
            <h1>Đăng nhập</h1>
            <p class="subtitle">Truy cập cửa hàng và các tính năng dành riêng cho tài khoản của bạn.</p>
        </div>
        <form id="login-form" class="form-grid">
            <label>Email
                <input type="email" name="email" autocomplete="email" placeholder="ban@email.com" required>
            </label>
            <label>Mật khẩu
                <input type="password" name="password" autocomplete="current-password" placeholder="Nhập mật khẩu" required>
            </label>
            <button class="primary" type="submit">Đăng nhập</button>
            <p class="notice notice-error" id="login-message" role="status" aria-live="polite"></p>
        </form>
        <p class="form-footer">Chưa có tài khoản? <a href="/register">Tạo tài khoản mới</a></p>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('login-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const button = form.querySelector('button[type="submit"]');
            const message = document.getElementById('login-message');
            button.disabled = true;
            message.textContent = '';

            try {
                const payload = await window.Auth.apiFetch('/api/login', {
                    method: 'POST',
                    body: {
                        email: form.elements.email.value,
                        password: form.elements.password.value,
                    },
                });
                window.Auth.saveAuth(payload);
                window.location.assign('/fruits');
            } catch (error) {
                message.textContent = error.message;
            } finally {
                button.disabled = false;
            }
        });
    </script>
@endpush
