<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventaris') — Sistem Inventaris Sekolah</title>
    <meta name="description" content="Sistem Inventaris Sekolah — Proyek Bulan 8 Laravel Dasar">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --sidebar-w: 260px;
            --topbar-h: 64px;
        }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: #f5f3ff;
            color: #1e1b4b;
            transition: background 0.3s, color 0.3s;
        }
        body.dark {
            background: #0f0a1e;
            color: #e0def4;
        }

        /* ── Sidebar ─────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff;
            display: flex; flex-direction: column;
            z-index: 50;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }
        body.dark .sidebar {
            background: linear-gradient(180deg, #312e81 0%, #4c1d95 100%);
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.3rem; font-weight: 800;
            letter-spacing: -0.5px;
            display: flex; align-items: center; gap: 0.6rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .sidebar-brand i { font-size: 1.4rem; }
        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.7rem 1rem; border-radius: 0.75rem;
            color: rgba(255,255,255,0.75); font-weight: 500; font-size: 0.9rem;
            text-decoration: none; transition: all 0.2s;
            margin-bottom: 0.2rem;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .sidebar-nav a.active { font-weight: 600; }
        .sidebar-nav a i { width: 20px; text-align: center; font-size: 1rem; }
        .sidebar-nav .nav-label {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4); padding: 1rem 1rem 0.4rem; font-weight: 600;
        }
        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.12);
            font-size: 0.78rem; color: rgba(255,255,255,0.5);
        }

        /* ── Topbar ──────────────────────────── */
        .topbar {
            position: fixed; top: 0; right: 0; left: var(--sidebar-w);
            height: var(--topbar-h);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 40;
            transition: left 0.3s, background 0.3s;
        }
        body.dark .topbar {
            background: rgba(15,10,30,0.85);
            border-bottom-color: rgba(255,255,255,0.06);
        }
        .topbar-left { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-title { font-weight: 700; font-size: 1.1rem; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .topbar-user {
            display: flex; align-items: center; gap: 0.5rem;
            font-weight: 500; font-size: 0.88rem;
        }
        .topbar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.85rem;
        }
        .hamburger {
            display: none; background: none; border: none; cursor: pointer;
            font-size: 1.3rem; color: inherit; padding: 0.25rem;
        }

        /* ── Theme Toggle ────────────────────── */
        .theme-toggle {
            background: none; border: 1px solid rgba(0,0,0,0.1); border-radius: 0.5rem;
            cursor: pointer; padding: 0.4rem 0.6rem; font-size: 1rem;
            color: inherit; transition: all 0.2s;
        }
        .theme-toggle:hover { background: rgba(0,0,0,0.05); }
        body.dark .theme-toggle { border-color: rgba(255,255,255,0.1); }
        body.dark .theme-toggle:hover { background: rgba(255,255,255,0.08); }

        /* ── Main Content ────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 1.75rem;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left 0.3s;
        }

        /* ── Cards ───────────────────────────── */
        .card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.25s;
        }
        .card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        body.dark .card {
            background: #1a1430;
            border-color: rgba(255,255,255,0.06);
        }
        body.dark .card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.3); }
        .card-body { padding: 1.5rem; }

        /* ── Stat Cards ──────────────────────── */
        .stat-card {
            border-radius: 1rem; padding: 1.5rem;
            position: relative; overflow: hidden;
            color: #fff;
        }
        .stat-card::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 100px; height: 100px; border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 0.75rem;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; margin-bottom: 1rem;
        }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: 0.82rem; opacity: 0.85; margin-top: 0.35rem; }
        .stat-indigo { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .stat-violet { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
        .stat-amber { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-emerald { background: linear-gradient(135deg, #10b981, #34d399); }

        /* ── Tables ──────────────────────────── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        thead th {
            padding: 0.75rem 1rem; text-align: left; font-weight: 600;
            font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;
            color: #6b7280; border-bottom: 2px solid rgba(0,0,0,0.06);
        }
        body.dark thead th { color: #9ca3af; border-bottom-color: rgba(255,255,255,0.08); }
        tbody td {
            padding: 0.75rem 1rem; border-bottom: 1px solid rgba(0,0,0,0.04);
            vertical-align: middle;
        }
        body.dark tbody td { border-bottom-color: rgba(255,255,255,0.04); }
        tbody tr { transition: background 0.15s; }
        tbody tr:hover { background: rgba(99,102,241,0.04); }
        body.dark tbody tr:hover { background: rgba(99,102,241,0.08); }

        /* ── Buttons ─────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.1rem; border-radius: 0.6rem;
            font-weight: 600; font-size: 0.85rem;
            text-decoration: none; border: none; cursor: pointer;
            transition: all 0.2s; line-height: 1.4;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #7c3aed);
            color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,0.25);
        }
        .btn-primary:hover { box-shadow: 0 4px 16px rgba(99,102,241,0.4); transform: translateY(-1px); }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        body.dark .btn-secondary { background: #2a2240; color: #d1d5db; }
        .btn-secondary:hover { background: #e5e7eb; }
        body.dark .btn-secondary:hover { background: #35304a; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 0.35rem 0.7rem; font-size: 0.78rem; }
        .btn-icon { padding: 0.4rem; width: 32px; height: 32px; justify-content: center; border-radius: 0.5rem; }

        /* ── Badge ────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.25rem 0.65rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }

        /* ── Forms ────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block; font-weight: 600; font-size: 0.85rem;
            margin-bottom: 0.4rem; color: #374151;
        }
        body.dark .form-label { color: #d1d5db; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 0.6rem 0.85rem;
            border: 1.5px solid rgba(0,0,0,0.1); border-radius: 0.6rem;
            font-size: 0.9rem; font-family: inherit;
            background: #fff; color: #1e1b4b;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        body.dark .form-input, body.dark .form-select, body.dark .form-textarea {
            background: #1a1430; color: #e0def4; border-color: rgba(255,255,255,0.1);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .form-textarea { resize: vertical; min-height: 80px; }
        .form-error { color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem; }

        /* ── Alert ────────────────────────────── */
        .alert {
            padding: 0.85rem 1.2rem; border-radius: 0.75rem;
            font-size: 0.88rem; font-weight: 500;
            display: flex; align-items: center; gap: 0.6rem;
            margin-bottom: 1.25rem;
            animation: slideDown 0.3s ease-out;
        }
        .alert-success {
            background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
        }
        body.dark .alert-success {
            background: rgba(16,185,129,0.1); color: #6ee7b7; border-color: rgba(16,185,129,0.2);
        }
        .alert-danger {
            background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;
        }
        body.dark .alert-danger {
            background: rgba(239,68,68,0.1); color: #fca5a5; border-color: rgba(239,68,68,0.2);
        }

        /* ── Search & Filters ────────────────── */
        .search-bar {
            display: flex; gap: 0.75rem; flex-wrap: wrap;
            align-items: center; margin-bottom: 1.25rem;
        }
        .search-input-wrapper {
            position: relative; flex: 1; min-width: 200px;
        }
        .search-input-wrapper i {
            position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 0.9rem;
        }
        .search-input-wrapper input {
            padding-left: 2.5rem;
        }

        /* ── Pagination ──────────────────────── */
        .pagination {
            display: flex; gap: 0.3rem; list-style: none; padding: 0; margin: 1.5rem 0 0;
            justify-content: center; flex-wrap: wrap;
        }
        .pagination li a, .pagination li span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 0.5rem;
            border-radius: 0.5rem; font-size: 0.85rem; font-weight: 500;
            text-decoration: none; color: #6b7280; transition: all 0.2s;
            border: 1px solid rgba(0,0,0,0.08);
        }
        body.dark .pagination li a, body.dark .pagination li span {
            color: #9ca3af; border-color: rgba(255,255,255,0.08);
        }
        .pagination li a:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
        .pagination li.active span {
            background: #6366f1; color: #fff; border-color: #6366f1;
        }
        .pagination li.disabled span { opacity: 0.4; cursor: not-allowed; }

        /* ── Empty State ─────────────────────── */
        .empty-state {
            text-align: center; padding: 3rem 1rem; color: #9ca3af;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
        .empty-state p { font-size: 0.95rem; }

        /* ── Grid ────────────────────────────── */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }

        /* ── Animations ──────────────────────── */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out both;
        }
        .fade-in-delay-1 { animation-delay: 0.1s; }
        .fade-in-delay-2 { animation-delay: 0.2s; }
        .fade-in-delay-3 { animation-delay: 0.3s; }
        .fade-in-delay-4 { animation-delay: 0.4s; }

        /* ── Progress Bar ────────────────────── */
        .progress-bar-bg {
            width: 100%; height: 8px; border-radius: 999px;
            background: rgba(0,0,0,0.06); overflow: hidden;
        }
        body.dark .progress-bar-bg { background: rgba(255,255,255,0.08); }
        .progress-bar-fill {
            height: 100%; border-radius: 999px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            transition: width 0.6s ease-out;
        }

        /* ── Overlay (mobile) ────────────────── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 45;
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
            .hamburger { display: block; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }

        /* ── Utility ─────────────────────────── */
        .text-muted { color: #6b7280; }
        body.dark .text-muted { color: #9ca3af; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 0.75rem; }
        .gap-3 { gap: 1rem; }
        .flex-wrap { flex-wrap: wrap; }
        .text-sm { font-size: 0.85rem; }
        .font-bold { font-weight: 700; }
        .w-full { width: 100%; }

        /* ── Detail list ─────────────────────── */
        .detail-list dt { font-weight: 600; font-size: 0.82rem; color: #6b7280; margin-bottom: 0.2rem; text-transform: uppercase; letter-spacing: 0.5px; }
        body.dark .detail-list dt { color: #9ca3af; }
        .detail-list dd { margin: 0 0 1.25rem; font-size: 0.95rem; }
    </style>
    @stack('styles')
    <style>@media print { .sidebar,.topbar,.sidebar-overlay,form,button { display:none!important; } .main-content { margin:0!important;padding:0!important; } body { background:white!important;color:black!important; } .card { box-shadow:none!important; } }</style>
<link rel="stylesheet" href="/shared/projects.css"></head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-school"></i>
            <span>Inventaris</span>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('inventaris.dashboard') }}" class="{{ request()->routeIs('inventaris.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('inventaris.kategori.index') }}" class="{{ request()->routeIs('inventaris.kategori.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Kategori
            </a>
            <a href="{{ route('inventaris.barang.index') }}" class="{{ request()->routeIs('inventaris.barang.*') ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked"></i> Barang
            </a>

            <div class="nav-label">Laporan</div>
            <a href="{{ route('inventaris.laporan') }}" class="{{ request()->routeIs('inventaris.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Laporan
            </a>

            <div class="nav-label">Lainnya</div>
            <a href="/" target="_blank">
                <i class="fas fa-globe"></i> Portfolio
            </a>
        </nav>
        <div class="sidebar-footer">
            &copy; {{ date('Y') }} Bulan 8–9 &mdash; Laravel & Tailwind
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            <div class="topbar-user">
                <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                <span>{{ Auth::user()->name ?? 'User' }}</span>
            </div>
            <form action="{{ route('inventaris.logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="main-content">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const icon = themeToggle.querySelector('i');

        if (localStorage.getItem('inv-theme') === 'dark') {
            body.classList.add('dark');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark');
            const isDark = body.classList.contains('dark');
            icon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
            localStorage.setItem('inv-theme', isDark ? 'dark' : 'light');
        });

        // Mobile sidebar
        const hamburger = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    </script>
    @stack('scripts')
<script src="/shared/projects.js" defer></script></body>
</html>
