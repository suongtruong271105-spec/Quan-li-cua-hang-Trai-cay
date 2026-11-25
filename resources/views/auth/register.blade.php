@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
    <section class="panel">
        <div class="panel-heading">
            <span class="eyebrow">Bắt đầu mua sắm</span>
            <h1>Tạo tài khoản</h1>
            <p class="subtitle">Đăng ký nhanh chóng để khám phá trái cây tươi và theo dõi đơn hàng của bạn.</p>
        </div>
        <form id="register-form" class="form-grid">
            <label>Họ và tên
                <input type="text" name="name" autocomplete="name" maxlength="255" placeholder="Nguyễn Văn An" required>
            </label>
            <label>Email
                <input type="email" name="email" autocomplete="email" placeholder="ban@email.com" required>
            </label>
            <label>Mật khẩu
                <input type="password" name="password" autocomplete="new-password" minlength="8" placeholder="Ít nhất 8 ký tự" required>
            </label>
            <label>Xác nhận mật khẩu
                <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" placeholder="Nhập lại mật khẩu" required>
            </label>
            <button class="primary" type="submit">Đăng ký</button>
            <p class="notice notice-error" id="register-message" role="status" aria-live="polite"></p>
        </form>
        <p class="form-footer">Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('register-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const button = form.querySelector('button[type="submit"]');
            const message = document.getElementById('register-message');
            button.disabled = true;
            message.textContent = '';

            try {
                const payload = await window.Auth.apiFetch('/api/register', {
                    method: 'POST',
                    body: {
                        name: form.elements.name.value,
                        email: form.elements.email.value,
                        password: form.elements.password.value,
                        password_confirmation: form.elements.password_confirmation.value,
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
