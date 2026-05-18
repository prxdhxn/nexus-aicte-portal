<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nexus - Edu Hub')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        /* ═══ LIGHT THEME VARIABLES ═══ */
        :root {
            --bg-color: #f4f7f6;
            --surface-color: #ffffff;
            --border-color: #e2e8f0;
            --accent-color: #0ea5e9;
            --accent-hover: #0284c7;
            --accent-glow: rgba(14,165,233,0.2);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --nav-bg: #0ea5e9;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 15px rgba(0,0,0,0.06);
            --radius: 14px;
            --transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }

        /* ═══ DARK THEME VARIABLES ═══ */
        [data-theme="dark"] {
            --bg-color: #0f172a;
            --surface-color: #1e293b;
            --border-color: #334155;
            --accent-color: #38bdf8;
            --accent-hover: #0ea5e9;
            --accent-glow: rgba(56,189,248,0.15);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --nav-bg: #0f172a;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 15px rgba(0,0,0,0.3);
        }

        /* ═══ BASE ═══ */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        .text-muted { color: var(--text-muted) !important; }
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }

        /* ═══ PAGE LOADER ═══ */
        #page-loader {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--surface-color);
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.4s ease;
        }
        #page-loader.hidden { opacity: 0; pointer-events: none; }
        .loader-spinner {
            width: 44px; height: 44px;
            border: 3px solid var(--border-color);
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ═══ NAVBAR ═══ */
        .navbar {
            background-color: var(--nav-bg) !important;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 10px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky; top: 0; z-index: 1000;
            transition: var(--transition);
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff !important;
            display: flex; align-items: center; gap: 12px;
            font-size: 1.5rem;
            font-family: 'Outfit', sans-serif;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            border-radius: 8px;
            padding: 6px 12px !important;
        }
        .nav-link:hover { color: #ffffff !important; background: rgba(255,255,255,0.1); }
        .nav-link i { margin-right: 6px; font-size: 0.85rem; }

        /* ═══ DARK MODE TOGGLE ═══ */
        .dark-toggle {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.85rem;
        }
        .dark-toggle:hover { background: rgba(255,255,255,0.22); transform: scale(1.1); }

        /* ═══ NOTIFICATION BELL ═══ */
        .notif-wrapper { position: relative; }
        .notif-bell {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.85rem;
            position: relative;
        }
        .notif-bell:hover { background: rgba(255,255,255,0.22); transform: scale(1.1); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.6rem; font-weight: 700;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--nav-bg);
            animation: pulse-badge 2s infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .notif-dropdown {
            position: absolute; top: calc(100% + 14px); right: -10px;
            width: 340px;
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            z-index: 1100;
            overflow: hidden;
            animation: slideDown 0.2s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .notif-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 700; font-size: 0.9rem;
            color: var(--text-main);
        }
        .notif-header button {
            background: none; border: none;
            color: var(--accent-color); font-size: 0.75rem; font-weight: 600;
            cursor: pointer; padding: 0;
        }
        .notif-item {
            display: flex; gap: 12px; padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
            text-decoration: none;
            cursor: pointer;
        }
        .notif-item:hover { background: var(--bg-color); }
        .notif-item.unread { background: color-mix(in srgb, var(--accent-color) 6%, var(--surface-color)); }
        .notif-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--accent-glow);
            color: var(--accent-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; flex-shrink: 0;
        }
        .notif-title { font-weight: 600; font-size: 0.83rem; color: var(--text-main); line-height: 1.3; }
        .notif-msg { font-size: 0.76rem; color: var(--text-muted); margin-top: 2px; line-height: 1.4; }
        .notif-time { font-size: 0.7rem; color: var(--text-muted); margin-top: 3px; }
        .notif-empty { padding: 30px 16px; text-align: center; color: var(--text-muted); font-size: 0.85rem; }
        .notif-footer { padding: 10px 16px; text-align: center; border-top: 1px solid var(--border-color); }

        /* ═══ USER BADGE ═══ */
        .user-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12);
            padding: 6px 14px; border-radius: 30px;
            font-weight: 600; color: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 0.88rem;
        }

        /* ═══ CARDS ═══ */
        .card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .card-header {
            font-weight: 700;
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 18px 22px;
            font-size: 1rem;
        }
        .card-body { padding: 22px; }

        /* ═══ BUTTONS ═══ */
        .btn { border-radius: 10px; font-weight: 600; padding: 9px 18px; transition: var(--transition); }
        .btn-primary { background-color: var(--accent-color); border-color: var(--accent-color); color: #fff; }
        .btn-primary:hover { background-color: var(--accent-hover); border-color: var(--accent-hover); box-shadow: 0 4px 14px var(--accent-glow); transform: translateY(-1px); }
        .btn-outline-danger { border-radius: 10px; color: var(--danger-color); border-color: var(--danger-color); }
        .btn-outline-danger:hover { background-color: var(--danger-color); color: white; }
        .btn-outline-secondary { color: var(--text-muted); border-color: var(--border-color); }
        .btn-outline-secondary:hover { background: var(--bg-color); color: var(--text-main); border-color: var(--border-color); }

        /* ═══ FORMS ═══ */
        .form-control, .form-select {
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 10px;
            padding: 10px 14px;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--surface-color);
            color: var(--text-main);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .form-control::placeholder { color: var(--text-muted); opacity: 0.7; }
        .form-label { color: var(--text-main); font-weight: 600; margin-bottom: 6px; }

        /* ═══ TABLES ═══ */
        .table { color: var(--text-main); --bs-table-bg: transparent; }
        .table th { color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.6px; border-color: var(--border-color); padding: 14px 10px; }
        .table td { border-color: var(--border-color); padding: 14px 10px; vertical-align: middle; color: var(--text-main); }
        .table-light { background-color: var(--bg-color) !important; --bs-table-bg: var(--bg-color); }
        .table-hover tbody tr:hover { background-color: color-mix(in srgb, var(--accent-color) 4%, var(--surface-color)); }

        /* ═══ ALERTS ═══ */
        .alert { border-radius: 12px; font-weight: 500; border: none; }
        .alert-success { background: rgba(16,185,129,0.1); color: #065f46; }
        .alert-danger { background: rgba(239,68,68,0.1); color: #7f1d1d; }
        [data-theme="dark"] .alert-success { background: rgba(16,185,129,0.15); color: #6ee7b7; }
        [data-theme="dark"] .alert-danger { background: rgba(239,68,68,0.15); color: #fca5a5; }

        /* ═══ BADGES ═══ */
        .badge { padding: 5px 10px; border-radius: 8px; font-weight: 600; }

        /* ═══ ANIMATIONS ═══ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .fade-up-delay-1 { animation-delay: 0.05s; opacity: 0; }
        .fade-up-delay-2 { animation-delay: 0.1s; opacity: 0; }
        .fade-up-delay-3 { animation-delay: 0.15s; opacity: 0; }
        .fade-up-delay-4 { animation-delay: 0.2s; opacity: 0; }

        /* ═══ LOGOUT BTN ═══ */
        .logout-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
        }
        .logout-btn:hover { background: rgba(239,68,68,0.3); border-color: rgba(239,68,68,0.5); }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-spinner"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                <i class="fa-solid fa-graduation-cap" style="font-size:1.4rem;"></i>
                Nexus
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @auth
                        @php
                            $dashRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : (auth()->user()->role === 'sme' ? 'sme.dashboard' : 'institute.dashboard');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route($dashRoute) }}"><i class="fa-solid fa-layer-group"></i>{{ __('messages.nav_dashboard') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('curricula.index') }}"><i class="fa-solid fa-book-open-reader"></i>{{ __('messages.nav_curricula') }}</a>
                        </li>
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.analytics') }}"><i class="fa-solid fa-chart-line"></i>{{ __('messages.nav_analytics') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users"></i>{{ __('messages.nav_users') }}</a>
                            </li>
                        @endif

                        <!-- Notification Bell -->
                        <li class="nav-item notif-wrapper" id="notifWrapper">
                            <button class="notif-bell" id="notifBell" onclick="toggleNotifDropdown(event)">
                                <i class="fa-solid fa-bell"></i>
                                <span class="notif-badge" id="notifBadge" style="{{ (isset($unreadGlobalCount) && $unreadGlobalCount > 0) ? '' : 'display:none' }}">{{ $unreadGlobalCount ?? 0 }}</span>
                            </button>
                            <div class="notif-dropdown" id="notifDropdown" style="display:none">
                                <div class="notif-header">
                                    <span><i class="fa-solid fa-bell me-2" style="color:var(--accent-color)"></i>Notifications</span>
                                    <button onclick="markAllRead()">Mark all read</button>
                                </div>
                                <div id="notifList">
                                    <div class="notif-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</div>
                                </div>
                                <div class="notif-footer">
                                    <small class="text-muted">Showing latest 5</small>
                                </div>
                            </div>
                        </li>

                        <!-- Dark Mode Toggle -->
                        <li class="nav-item">
                            <button class="dark-toggle" id="darkToggle" onclick="toggleDarkMode()" title="Toggle dark mode">
                                <i class="fa-solid fa-moon" id="darkIcon"></i>
                            </button>
                        </li>

                        <!-- Language Toggle Removed per user request -->

                        <!-- Google Translator Dropdown -->
                        <li class="nav-item ms-2">
                            @include('partials.translator')
                        </li>

                        <li class="nav-item ms-2">
                            <div class="user-badge">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <span class="badge bg-secondary" style="font-size:0.68rem;font-weight:500;">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="fa-solid fa-right-from-bracket text-danger me-1"></i>{{ __('messages.nav_logout') }}
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <x-flash />

        @yield('content')
    </div>

    {{-- Unit III: URL Generation Helpers --}}
    {{-- Demonstrates: route() for named routes (navbar above), asset() for static files, url() for absolute paths --}}
    <footer style="background:var(--surface-color);border-top:1px solid var(--border-color);padding:18px 0;margin-top:20px;">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small style="color:var(--text-muted);">
                &copy; {{ date('Y') }} <strong style="color:var(--accent-color);">Nexus</strong> &mdash; AICTE Curriculum Portal
            </small>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/help') }}" style="color:var(--text-muted);font-size:0.82rem;text-decoration:none;" class="footer-link">
                    <i class="fa-solid fa-circle-question me-1"></i>{{ __('messages.nav_help') }}
                </a>
                <a href="{{ route('login') }}" style="color:var(--text-muted);font-size:0.82rem;text-decoration:none;" class="footer-link">
                    <i class="fa-solid fa-right-to-bracket me-1"></i>{{ __('messages.nav_login') }}
                </a>
                <span style="color:var(--border-color);">|</span>
                <small style="color:var(--text-muted);font-size:0.75rem;">v1.0.0</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
    <script>
        // ═══ PAGE LOADER ═══
        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');
            loader.classList.add('hidden');
            setTimeout(() => loader.style.display = 'none', 400);
        });
        document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript"]):not([target="_blank"])').forEach(link => {
            link.addEventListener('click', e => {
                const loader = document.getElementById('page-loader');
                if (loader) { loader.style.display = 'flex'; loader.classList.remove('hidden'); }
            });
        });

        // ═══ DARK MODE ═══
        const html = document.documentElement;
        const darkIcon = document.getElementById('darkIcon');
        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            if (darkIcon) {
                darkIcon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        }
        applyTheme(localStorage.getItem('nexus-theme') || 'light');
        function toggleDarkMode() {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            localStorage.setItem('nexus-theme', next);
            applyTheme(next);
        }

        // ═══ NOTIFICATIONS ═══
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        let notifOpen = false;

        function fetchNotifications() {
            fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const list  = document.getElementById('notifList');
                    if (!badge || !list) return;

                    if (data.unread_count > 0) {
                        badge.style.display = 'flex';
                        badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    } else {
                        badge.style.display = 'none';
                    }

                    if (data.notifications.length === 0) {
                        list.innerHTML = '<div class="notif-empty"><i class="fa-regular fa-bell-slash mb-2" style="font-size:1.5rem;opacity:0.4"></i><br>No notifications yet</div>';
                        return;
                    }

                    const icons = { curriculum: 'fa-book-open', adoption: 'fa-file-arrow-up', grade: 'fa-star', default: 'fa-bell' };
                    list.innerHTML = data.notifications.map(n => `
                        <div class="notif-item ${!n.is_read ? 'unread' : ''}" onclick="readNotif('${n._id}', '${n.link || ''}')">
                            <div class="notif-icon"><i class="fa-solid ${icons[n.type] || icons.default}"></i></div>
                            <div style="flex:1;min-width:0">
                                <div class="notif-title">${n.title}</div>
                                <div class="notif-msg">${n.message}</div>
                                <div class="notif-time">${timeAgo(n.created_at)}</div>
                            </div>
                            ${!n.is_read ? '<div style="width:7px;height:7px;background:var(--accent-color);border-radius:50%;flex-shrink:0;margin-top:6px"></div>' : ''}
                        </div>`).join('');
                }).catch(() => {});
        }

        function readNotif(id, link) {
            fetch(`/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(() => { fetchNotifications(); if (link) window.location = link; });
        }

        function markAllRead() {
            fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(() => fetchNotifications());
        }

        function toggleNotifDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('notifDropdown');
            notifOpen = !notifOpen;
            dd.style.display = notifOpen ? 'block' : 'none';
            if (notifOpen) fetchNotifications();
        }

        document.addEventListener('click', e => {
            if (notifOpen && !document.getElementById('notifWrapper')?.contains(e.target)) {
                document.getElementById('notifDropdown').style.display = 'none';
                notifOpen = false;
            }
        });

        function timeAgo(dateStr) {
            const diff = Date.now() - new Date(dateStr).getTime();
            const m = Math.floor(diff/60000), h = Math.floor(m/60), d = Math.floor(h/24);
            if (d > 0) return `${d}d ago`;
            if (h > 0) return `${h}h ago`;
            if (m > 0) return `${m}m ago`;
            return 'Just now';
        }

        // Poll notifications every 60 seconds
        @auth fetchNotifications(); setInterval(fetchNotifications, 60000); @endauth
    </script>
</body>
</html>
