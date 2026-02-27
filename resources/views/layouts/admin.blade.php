<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — SoulMates Inc.</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --cream:    #F5F0E8;
            --black:    #0D0D0D;
            --charcoal: #1A1A1A;
            --sidebar-bg: #111111;
            --warm-gray:#6B6560;
            --accent:   #C8A96E;
            --accent-dark: #A8893E;
            --red:      #C0392B;
            --white:    #FFFFFF;
            --border:   rgba(255,255,255,0.08);
            --font-display: 'Playfair Display', serif;
            --font-body:    'DM Sans', sans-serif;
            --font-mono:    'Space Mono', monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: #F0EBE1;
            color: var(--black);
            display: flex;
            min-height: 100vh;
        }

        /* ============================================
           SIDEBAR
        ============================================ */
        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand .brand-name {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--accent);
            font-style: italic;
        }
        .sidebar-brand .brand-label {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
        }

        .sidebar-section-label {
            font-family: var(--font-mono);
            font-size: 0.58rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 0.75rem 1.5rem 0.4rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 400;
            transition: all 0.18s;
            border-left: 3px solid transparent;
        }

        .sidebar-link i { font-size: 1rem; width: 20px; flex-shrink: 0; }

        .sidebar-link:hover {
            color: var(--white);
            background: rgba(255,255,255,0.05);
            border-left-color: rgba(200,169,110,0.5);
        }

        .sidebar-link.active {
            color: var(--accent);
            background: rgba(200,169,110,0.1);
            border-left-color: var(--accent);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
        }

        .admin-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: var(--black);
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .admin-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .admin-user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-user-role {
            font-family: var(--font-mono);
            font-size: 0.6rem;
            color: var(--accent);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* ============================================
           MAIN CONTENT AREA
        ============================================ */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-topbar {
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .admin-topbar .page-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 900;
        }

        .topbar-actions { display: flex; align-items: center; gap: 1rem; }

        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        /* ============================================
           STAT CARDS
        ============================================ */
        .stat-card {
            background: var(--white);
            border: 1px solid rgba(0,0,0,0.07);
            border-radius: 6px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent);
        }
        .stat-card .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 6px;
            background: rgba(200,169,110,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--accent);
            margin-bottom: 1rem;
        }
        .stat-value {
            font-family: var(--font-mono);
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--warm-gray);
        }

        /* ============================================
           TABLE STYLING
        ============================================ */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .admin-table thead th {
            background: var(--black);
            color: rgba(255,255,255,0.7);
            font-family: var(--font-mono);
            font-size: 0.68rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 0.9rem 1.25rem;
            font-weight: 400;
            border: none;
        }
        .admin-table tbody td {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            font-size: 0.87rem;
            vertical-align: middle;
        }
        .admin-table tbody tr:hover { background: rgba(200,169,110,0.04); }
        .admin-table tbody tr:last-child td { border-bottom: none; }

        /* ============================================
           BADGES
        ============================================ */
        .badge-status {
            font-family: var(--font-mono);
            font-size: 0.62rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-weight: 700;
        }
        .badge-pending  { background: rgba(241,196,15,0.15);  color: #d4a807; }
        .badge-shipped  { background: rgba(41,128,185,0.15);  color: #2471a3; }
        .badge-completed{ background: rgba(39,174,96,0.15);   color: #1e8a49; }
        .badge-admin    { background: rgba(200,169,110,0.2);  color: var(--accent-dark); }
        .badge-user     { background: rgba(107,101,96,0.1);   color: var(--warm-gray); }
        .badge-active   { background: rgba(39,174,96,0.15);   color: #1e8a49; }
        .badge-inactive { background: rgba(192,57,43,0.12);   color: var(--red); }

        /* ============================================
           FORM CARDS (admin forms)
        ============================================ */
        .admin-form-card {
            background: var(--white);
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 6px;
            overflow: hidden;
        }
        .admin-form-card .card-head {
            background: var(--black);
            color: var(--white);
            padding: 1.25rem 1.75rem;
        }
        .admin-form-card .card-head h5 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            margin: 0;
        }
        .admin-form-card .card-body { padding: 1.75rem; }

        /* Form controls inside admin */
        .admin-form-card .form-control,
        .admin-form-card .form-select {
            border: 1.5px solid rgba(0,0,0,0.12);
            border-radius: 3px;
            font-size: 0.88rem;
        }
        .admin-form-card .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #888;
        }

        /* ============================================
           BUTTONS (admin)
        ============================================ */
        .btn-sm-admin {
            font-size: 0.75rem;
            padding: 0.35rem 0.85rem;
            border-radius: 3px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Flash messages */
        .alert { border-radius: 4px; font-size: 0.87rem; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: rgba(200,169,110,0.4); border-radius: 3px; }

        /* Sidebar toggle for mobile */
        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .sidebar-toggle-btn { display: block; }
            .admin-content { padding: 1.25rem; }
        }

        @yield('styles')
    </style>
    @yield('head')
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="brand-name">SoulMates</div>
            <div class="brand-label">Admin Panel</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="sidebar-section-label">Catalog</div>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Products
            </a>

            <div class="sidebar-section-label">Commerce</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Orders
            </a>

            <div class="sidebar-section-label">Users</div>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> All Users
            </a>

            <div class="sidebar-section-label">Site</div>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="bi bi-arrow-up-right-square"></i> View Storefront
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-user-info">
                <div class="admin-avatar">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . auth()->user()->profile_photo) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="admin-user-name">{{ auth()->user()->name }}</div>
                    <div class="admin-user-role">Administrator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-100 text-start" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.4);">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="admin-main">
        {{-- Topbar --}}
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="topbar-actions">
                @yield('topbar-actions')
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success') || session('error'))
            <div class="px-4 pt-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Content --}}
        <div class="admin-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('open');
        });
    </script>
    @yield('scripts')
</body>
</html>
