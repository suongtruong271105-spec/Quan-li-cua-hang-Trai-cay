<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cửa hàng Trái cây')</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #173b2a;
            background: #f6f9f4;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            --green-900: #14532d;
            --green-800: #166534;
            --green-700: #15803d;
            --green-100: #dcfce7;
            --ink: #173b2a;
            --muted: #587064;
            --line: #dce8dd;
            --surface: #fff;
        }
        * { box-sizing: border-box; }
        body {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            margin: 0;
            background: #f6f9f4;
        }
        body::before, body::after {
            position: fixed;
            z-index: -1;
            width: 22rem;
            height: 22rem;
            border-radius: 50%;
            background: #e7f4e5;
            content: "";
            filter: blur(1px);
            pointer-events: none;
        }
        body::before { top: 8rem; left: -16rem; }
        body::after { right: -17rem; bottom: -7rem; width: 30rem; height: 30rem; background: #edf5e9; }
        a { color: var(--green-800); }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible {
            outline: 3px solid rgb(21 128 61 / 28%);
            outline-offset: 2px;
        }
        .navbar {
            position: sticky;
            z-index: 5;
            top: 0;
            border-bottom: 1px solid rgb(220 232 221 / 90%);
            background: rgb(255 255 255 / 94%);
            box-shadow: 0 5px 22px rgb(23 59 42 / 5%);
            backdrop-filter: blur(14px);
        }
        .navbar-inner {
            display: flex;
            max-width: 1200px;
            min-height: 76px;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin: auto;
            padding: 12px 28px;
        }
        .nav-brand, .nav-links, .nav-account { display: flex; align-items: center; }
        .nav-brand { flex: 0 0 auto; }
        .nav-links { gap: 5px; flex-wrap: wrap; }
        .nav-account { justify-content: flex-end; gap: 10px; }
        .nav-links a, .nav-links button {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            padding: 9px 12px;
            background: transparent;
            color: #365847;
            font: inherit;
            font-size: .93rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color .18s ease, color .18s ease, transform .18s ease;
            cursor: pointer;
        }
        .nav-links a:hover, .nav-links button:hover { transform: translateY(-1px); background: #edf7ed; color: var(--green-800); }
        .nav-links a[aria-current="page"] { background: #eaf5e9; color: var(--green-900); }
        .brand {
            gap: 10px;
            color: var(--green-900) !important;
            font-size: 1rem !important;
            font-weight: 800 !important;
            letter-spacing: -.025em;
            white-space: nowrap;
        }
        .brand-mark {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            border-radius: 13px;
            background: #e8f6e8;
            color: var(--green-800);
        }
        .brand-mark svg { width: 22px; height: 22px; }
        .nav-identity { color: #365847; font-size: .88rem; font-weight: 650; }
        .role-badge, .eyebrow {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: .01em;
        }
        .role-badge { padding: 6px 10px; }
        .role-customer { background: #dcfce7; color: #166534; }
        .role-inventory_manager { background: #dbeafe; color: #1d4ed8; }
        .role-admin { background: #ede9fe; color: #6d28d9; }
        .logout-button {
            border: 1px solid #f1d5d2 !important;
            background: #fff8f7 !important;
            color: #a3312b !important;
        }
        .logout-button:hover { border-color: #e8b8b2 !important; background: #fff0ee !important; color: #8e211d !important; }
        main { width: min(100% - 48px, 1120px); margin: 42px auto 0; padding-bottom: 64px; }
        .panel, .page-card {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--surface);
            box-shadow: 0 18px 48px rgb(23 59 42 / 7%);
        }
        .panel { width: min(100%, 520px); margin: 12px auto; padding: 38px; }
        .page-card { padding: clamp(26px, 5vw, 42px); }
        .panel-heading { margin-bottom: 26px; }
        .eyebrow {
            margin-bottom: 12px;
            padding: 7px 11px;
            background: #eaf6e9;
            color: var(--green-800);
        }
        h1, h2, h3 { color: var(--ink); letter-spacing: -.035em; }
        h1 { margin: 0 0 10px; font-size: clamp(1.8rem, 4vw, 2.5rem); line-height: 1.14; }
        h2 { margin: 0 0 10px; font-size: clamp(1.35rem, 3vw, 1.8rem); }
        p { margin: 0 0 16px; color: var(--muted); line-height: 1.7; }
        .subtitle { max-width: 56ch; margin-bottom: 0; }
        .form-grid { display: grid; gap: 18px; margin-top: 26px; }
        .field-label, label {
            display: grid;
            gap: 8px;
            color: #2e5140;
            font-size: .93rem;
            font-weight: 700;
        }
        input, select {
            width: 100%;
            min-height: 46px;
            border: 1px solid #c9d9cc;
            border-radius: 11px;
            padding: 11px 13px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            transition: border-color .18s ease, box-shadow .18s ease;
        }
        input::placeholder { color: #718779; }
        input:focus, select:focus { border-color: #45a35f; outline: 0; box-shadow: 0 0 0 4px rgb(21 128 61 / 12%); }
        .button, button.primary {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 11px;
            padding: 11px 17px;
            background: var(--green-700);
            color: #fff;
            font: inherit;
            font-weight: 750;
            text-align: center;
            text-decoration: none;
            transition: background-color .18s ease, box-shadow .18s ease, transform .18s ease;
            cursor: pointer;
        }
        .button:hover, button.primary:hover { transform: translateY(-1px); background: var(--green-800); box-shadow: 0 8px 18px rgb(21 128 61 / 18%); }
        .button-secondary { border: 1px solid #cfe0d0; background: #fff; color: var(--green-800); }
        .button-secondary:hover { background: #f1f8f0; box-shadow: none; }
        button:disabled { opacity: .65; cursor: wait; }
        .form-footer { margin: 20px 0 0; color: var(--muted); font-size: .92rem; text-align: center; }
        .form-footer a { font-weight: 750; text-decoration-thickness: 1px; text-underline-offset: 3px; }
        .notice {
            margin: 16px 0 0;
            border: 1px solid #c8e8d0;
            border-radius: 12px;
            padding: 12px 14px;
            background: #effaf1;
            color: #17613a;
            font-size: .92rem;
            line-height: 1.55;
        }
        .notice-error { border-color: #f1c7c1; background: #fff3f1; color: #a3312b; }
        .notice-info { border-color: #c9def3; background: #f0f7ff; color: #245b8f; }
        .notice:empty { display: none; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
        .page-heading p { max-width: 64ch; margin: 0; }
        .hero-card {
            position: relative;
            overflow: hidden;
            min-height: 310px;
            padding: clamp(32px, 7vw, 68px);
            background: linear-gradient(120deg, #fff 0%, #f5fbf1 62%, #e8f5e5 100%);
        }
        .hero-card::after {
            position: absolute;
            right: -55px;
            bottom: -135px;
            width: 360px;
            height: 360px;
            border: 1px solid rgb(21 128 61 / 12%);
            border-radius: 50%;
            box-shadow: 0 0 0 28px rgb(21 128 61 / 4%), 0 0 0 58px rgb(21 128 61 / 3%);
            content: "";
            pointer-events: none;
        }
        .hero-content { position: relative; z-index: 1; max-width: 620px; }
        .hero-card h1 { max-width: 14ch; font-size: clamp(2.4rem, 6vw, 4rem); }
        .hero-card p { max-width: 58ch; margin-bottom: 24px; font-size: 1.05rem; }
        .button-row { display: flex; flex-wrap: wrap; gap: 12px; }
        .content-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 230px), 1fr)); gap: 18px; margin-top: 24px; }
        .feature-card, .empty-state {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 22px;
            background: #fbfdfb;
        }
        .feature-card h2, .feature-card h3 { margin: 0 0 8px; font-size: 1.08rem; letter-spacing: -.015em; }
        .feature-card p, .empty-state p { margin: 0; font-size: .94rem; }
        .feature-icon {
            display: grid;
            width: 42px;
            height: 42px;
            margin-bottom: 16px;
            place-items: center;
            border-radius: 13px;
            background: #e9f6e9;
            color: var(--green-800);
            font-size: 1.15rem;
            font-weight: 800;
        }
        .empty-state { margin-top: 22px; border-style: dashed; background: #f9fcf8; text-align: center; }
        .empty-state .feature-icon { margin: 0 auto 14px; }
        .empty-state h2 { margin-bottom: 7px; font-size: 1.12rem; }
        .table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 14px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { min-width: 110px; padding: 14px 16px; border-bottom: 1px solid #e8efe8; }
        th { background: #f3f8f2; color: #365847; font-size: .82rem; font-weight: 800; letter-spacing: .035em; text-transform: uppercase; }
        td { color: #294a38; font-size: .94rem; }
        tbody tr:nth-child(even) { background: #fbfdfb; }
        tbody tr { transition: background-color .15s ease; }
        tbody tr:hover { background: #f0f8ef; }
        tbody tr:last-child td { border-bottom: 0; }
        .table-role-select { min-width: 150px; min-height: 38px; padding: 7px 10px; font-size: .88rem; }
        .table-actions { text-align: right; }
        td button.delete-button {
            min-height: 36px;
            border: 1px solid #f0cfcb;
            border-radius: 9px;
            padding: 7px 11px;
            background: #fff8f7;
            color: #a3312b;
            font: inherit;
            font-size: .86rem;
            font-weight: 700;
            cursor: pointer;
        }
        td button.delete-button:hover { border-color: #e8b8b2; background: #fff0ee; }
        [hidden] { display: none !important; }
        @media (max-width: 900px) {
            .navbar-inner { flex-wrap: wrap; padding: 12px 20px; }
            .nav-links { order: 3; width: 100%; overflow-x: auto; flex-wrap: nowrap; padding-bottom: 2px; }
            .nav-account { margin-left: auto; }
            .page-heading { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 600px) {
            .navbar-inner { gap: 8px; padding: 10px 16px; }
            .brand { font-size: .93rem !important; }
            .brand-mark { width: 34px; height: 34px; }
            .nav-account { gap: 6px; }
            .nav-identity { display: none; }
            .role-badge { padding: 6px 8px; font-size: .72rem; }
            .nav-links a, .nav-links button { min-height: 38px; padding: 8px 10px; font-size: .85rem; white-space: nowrap; }
            main { width: min(100% - 30px, 1120px); margin-top: 24px; padding-bottom: 40px; }
            .panel { padding: 27px 22px; }
            .page-card { padding: 25px 20px; }
            .hero-card { min-height: 0; padding: 32px 24px; }
            .hero-card::after { right: -200px; bottom: -210px; }
            .button-row .button { flex: 1 1 100%; }
            th, td { padding: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

   <script src="{{ asset('js/config.js') }}"></script>
<script src="{{ asset('js/auth.js') }}"></script>
@stack('scripts')
</body>
</html>
