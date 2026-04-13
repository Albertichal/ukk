<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – SMA Al-Azhar Jakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --primary: #022448;
            --gold:    #FFB21D;
            --bg:      #F7F9FC;
            --surface: #FFFFFF;
            --text:    #191C1E;
            --muted:   #74777F;
            --border:  #E0E3E6;
            --sidebar: 260px;
            --topbar:  64px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
        }
        .msym {
            font-family: 'Material Symbols Outlined';
            font-weight: normal; font-style: normal;
            font-size: 20px; line-height: 1;
            letter-spacing: normal; text-transform: none;
            display: inline-block; white-space: nowrap;
            font-variation-settings: 'FILL' 0,'wght' 300,'GRAD' 0,'opsz' 24;
            vertical-align: middle; user-select: none;
        }
        .msym.filled { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
        .msym.sz18 { font-size: 18px; }
        .msym.sz22 { font-size: 22px; }
        .msym.sz28 { font-size: 28px; }
        a { text-decoration: none; color: inherit; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; left: 0; top: 0;
            width: var(--sidebar); height: 100vh;
            background: var(--primary);
            display: flex; flex-direction: column;
            z-index: 300; transition: transform .28s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        .sidebar-hd {
            padding: 20px 16px 18px;
            display: flex; align-items: center; gap: 11px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            flex-shrink: 0;
        }
        .sidebar-logo {
            width: 40px; height: 40px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .logo-fallback {
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-fallback .msym { color: rgba(255,255,255,.8); font-size: 22px; }
        .school-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 13px; color: #fff; line-height: 1.2;
        }
        .school-sub { font-size: 11px; color: rgba(255,255,255,.5); }
        .role-badge {
            display: inline-block;
            background: var(--gold); color: var(--primary);
            font-size: 9px; font-weight: 700;
            padding: 2px 7px; border-radius: 9999px;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .sidebar-nav {
            flex: 1; overflow-y: auto; padding: 10px 10px;
            scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.1) transparent;
        }
        .nav-sect {
            color: rgba(255,255,255,.3);
            font-size: 10px; text-transform: uppercase;
            letter-spacing: .1em; font-weight: 700;
            padding: 10px 10px 4px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: rgba(255,255,255,.62);
            font-size: 13.5px; font-weight: 500;
            transition: background .15s, color .15s;
            margin-bottom: 2px; cursor: pointer;
        }
        .nav-link:hover { background: rgba(255,255,255,.1); color: rgba(255,255,255,.92); }
        .nav-link.active { background: rgba(255,255,255,.14); color: #fff; }
        .nav-link.active .msym { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
        .nav-link .msym { font-size: 20px; }
        .sidebar-ft {
            padding: 14px 10px;
            border-top: 1px solid rgba(255,255,255,.1);
            flex-shrink: 0;
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 8px; border-radius: 8px; margin-bottom: 8px;
        }
        .usr-init {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,.16);
            color: #fff; font-weight: 700; font-size: 14px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .usr-name {
            color: rgba(255,255,255,.88); font-size: 12.5px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .logout-btn {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 9px 12px; border-radius: 8px;
            background: rgba(239,68,68,.12); border: none;
            color: #FCA5A5; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: background .15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .logout-btn:hover { background: rgba(239,68,68,.22); }
        .logout-btn .msym { font-size: 18px; }

        /* ── OVERLAY ── */
        .overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 250;
        }
        .overlay.show { display: block; }

        /* ── MAIN ── */
        .main-wrap { margin-left: var(--sidebar); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar); background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 24px; gap: 12px;
            position: sticky; top: 0; z-index: 100;
        }
        .hamburger {
            display: none; align-items: center; justify-content: center;
            width: 36px; height: 36px; border: none;
            background: none; border-radius: 8px;
            cursor: pointer; color: var(--text); flex-shrink: 0;
        }
        .hamburger:hover { background: var(--bg); }
        .hamburger .msym { font-size: 22px; }
        .topbar-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 16px; color: var(--text); flex: 1;
        }
        .topbar-right { display: flex; align-items: center; gap: 8px; }
        .topbar-uname { font-size: 13px; font-weight: 600; color: var(--text); }
        .tb-badge {
            font-size: 10px; font-weight: 700;
            padding: 3px 9px; border-radius: 9999px; text-transform: uppercase;
        }
        .tb-admin { background: #DBEAFE; color: #1D4ED8; }
        .tb-siswa { background: #DCFCE7; color: #15803D; }
        .tb-pj    { background: #FEF9C3; color: #854D0E; }

        /* ── CONTENT ── */
        .content-area { flex: 1; padding: 24px; }

        /* ── FLASH ── */
        .flash {
            display: flex; align-items: center; gap: 10px;
            padding: 13px 16px; border-radius: 10px;
            margin-bottom: 20px; font-size: 13.5px; font-weight: 500;
            animation: fadeDown .3s ease;
        }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash-ok  { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }
        .flash-err { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }
        .flash .msym { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
        .flash-x {
            margin-left: auto; background: none; border: none;
            cursor: pointer; color: inherit; opacity: .6; padding: 2px; line-height: 1;
        }

        /* ── BOTTOM NAV ── */
        .bottom-nav {
            display: none; position: fixed; bottom: 0; left: 0; right: 0;
            height: 62px; background: var(--surface);
            border-top: 1px solid var(--border); z-index: 99;
        }
        .bn-inner {
            display: flex; align-items: center; justify-content: space-around;
            height: 100%; padding: 0 8px;
        }
        .bn-item {
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            padding: 6px 14px; border-radius: 8px;
            color: var(--muted); font-size: 10px; font-weight: 600;
            transition: color .15s; min-width: 56px;
        }
        .bn-item .msym { font-size: 22px; }
        .bn-item.active { color: var(--primary); }
        .bn-item.active .msym { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }

        /* ── SHARED CARD / TABLE ── */
        .card {
            background: var(--surface); border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .card-hd {
            padding: 16px 20px; border-bottom: 1px solid var(--border);
            font-weight: 700; font-size: 14.5px; color: var(--text);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-bd { padding: 20px; }
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th {
            padding: 10px 14px; background: var(--bg);
            font-size: 11px; text-transform: uppercase;
            letter-spacing: .06em; color: var(--muted); font-weight: 700;
            border-bottom: 1px solid var(--border); white-space: nowrap;
        }
        .tbl td {
            padding: 12px 14px; border-bottom: 1px solid var(--border);
            font-size: 13.5px; color: var(--text); vertical-align: middle;
        }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr { transition: background .12s; }
        .tbl tbody tr:hover { background: #F7F9FC; }
        .tbl-wrap { overflow-x: auto; border-radius: 0 0 12px 12px; }

        /* ── STATUS BADGES ── */
        .sbadge {
            display: inline-block; padding: 3px 10px;
            border-radius: 9999px; font-size: 11.5px; font-weight: 700;
            white-space: nowrap;
        }
        .s-menunggu  { background: #FFF3CD; color: #856404; }
        .s-diassign  { background: #CFE2FF; color: #084298; }
        .s-proses    { background: #CFF4FC; color: #055160; }
        .s-selesai   { background: #D1E7DD; color: #0A3622; }
        .s-ditolakpj { background: #F8D7DA; color: #842029; }
        .s-ditolak   { background: #F8D7DA; color: #842029; }
        .s-diproses  { background: #CFF4FC; color: #055160; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            border: none; transition: filter .15s, transform .1s; line-height: 1;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary { background: var(--primary); color: var(--gold); }
        .btn-primary:hover { filter: brightness(1.15); }
        .btn-secondary { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }
        .btn-danger  { background: #FEE2E2; color: #DC2626; }
        .btn-danger:hover { background: #FECACA; }
        .btn-success { background: #DCFCE7; color: #15803D; }
        .btn-success:hover { background: #BBF7D0; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            border: none; cursor: pointer; transition: background .15s;
            font-size: 0; background: transparent;
        }
        .btn-icon .msym { font-size: 18px; }
        .btn-icon-blue   { color: #084298; } .btn-icon-blue:hover   { background: #CFE2FF; }
        .btn-icon-green  { color: #15803D; } .btn-icon-green:hover  { background: #DCFCE7; }
        .btn-icon-red    { color: #DC2626; } .btn-icon-red:hover    { background: #FEE2E2; }
        .btn-icon-orange { color: #854D0E; } .btn-icon-orange:hover { background: #FEF9C3; }

        /* ── FORM FIELDS ── */
        .field { margin-bottom: 18px; }
        .field label {
            display: block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .07em;
            color: var(--muted); margin-bottom: 6px;
        }
        .field .req { color: #DC2626; }
        .inp, .sel, .ta {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px; color: var(--text); outline: none;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 9px 12px;
            transition: border-color .2s;
        }
        .inp:focus, .sel:focus, .ta:focus { border-color: var(--primary); background: var(--surface); }
        .ta { resize: vertical; min-height: 80px; }
        .field .err-msg { color: #DC2626; font-size: 12px; margin-top: 4px; }
        .char-counter { font-size: 11px; color: var(--muted); text-align: right; margin-top: 3px; }

        /* ── MODAL ── */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.4); z-index: 500;
            align-items: center; justify-content: center; padding: 16px;
            backdrop-filter: blur(2px);
        }
        .modal-backdrop.show { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: 16px;
            width: 100%; max-width: 460px; max-height: 90vh;
            display: flex; flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            animation: modalIn .2s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(.95) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-hd {
            padding: 18px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px;
        }
        .modal-close {
            width: 30px; height: 30px; border-radius: 8px;
            background: none; border: none; cursor: pointer;
            color: var(--muted); display: flex; align-items: center; justify-content: center;
        }
        .modal-close:hover { background: var(--bg); }
        .modal-close .msym { font-size: 20px; }
        .modal-bd { padding: 20px; overflow-y: auto; flex: 1; }
        .modal-ft {
            padding: 14px 20px; border-top: 1px solid var(--border);
            display: flex; gap: 10px; justify-content: flex-end;
        }

        /* ── STATS CARD ── */
        .stat-card {
            background: var(--surface); border-radius: 12px;
            border: 1px solid var(--border);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
        }
        .stat-icon {
            width: 46px; height: 46px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon .msym { font-size: 24px; font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
        .stat-num {
            font-family: 'Poppins', sans-serif;
            font-weight: 800; font-size: 26px; line-height: 1;
        }
        .stat-lbl { font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 2px; }

        /* ── AVATAR INITIAL ── */
        .av {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%; font-weight: 700; flex-shrink: 0;
        }
        .av-sm  { width: 32px; height: 32px; font-size: 13px; }
        .av-md  { width: 40px; height: 40px; font-size: 15px; }
        .av-admin { background: #DBEAFE; color: #1D4ED8; }
        .av-siswa { background: #DCFCE7; color: #15803D; }
        .av-pj    { background: #FEF9C3; color: #854D0E; }

        /* ── ALERT BAR ── */
        .alert-bar {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px;
            margin-bottom: 16px; font-size: 13.5px; font-weight: 500;
        }
        .alert-warn { background: #FFF3CD; border: 1px solid #FFC107; color: #856404; }
        .alert-bar .msym { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .hamburger { display: flex; }
            .bottom-nav { display: block; }
            .content-area { padding: 16px; padding-bottom: 78px; }
            .topbar-uname { display: none; }
            .topbar { padding: 0 16px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr !important; }
            .stat-num { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="overlay" id="sidebarOverlay"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-hd">
            <img src="{{ asset('images/logo.png') }}"
                 class="sidebar-logo"
                 id="sidebarLogoImg"
                 onerror="this.style.display='none';document.getElementById('logoFb').style.display='flex';"
                 alt="Logo">
            <div class="logo-fallback" id="logoFb" style="display:none;">
                <span class="msym sz22">school</span>
            </div>
            <div style="min-width:0;">
                <div class="school-name">SMA Al-Azhar</div>
                <div class="school-sub">Jakarta</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @php $role = auth()->user()->role; @endphp
            @if(auth()->user()->isAdmin())
                <div class="nav-sect">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <span class="msym">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.histori') }}" class="nav-link {{ Route::is('admin.histori') ? 'active' : '' }}">
                    <span class="msym">history</span> Histori
                </a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ Route::is('admin.users') ? 'active' : '' }}">
                    <span class="msym">group</span> Data User
                </a>
                <a href="{{ route('admin.kategori') }}" class="nav-link {{ Route::is('admin.kategori') ? 'active' : '' }}">
                    <span class="msym">category</span> Kategori
                </a>
            @elseif(auth()->user()->isSiswa())
                <div class="nav-sect">Siswa</div>
                <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ Route::is('siswa.dashboard') ? 'active' : '' }}">
                    <span class="msym">add_circle</span> Form Pengaduan
                </a>
                <a href="{{ route('siswa.riwayat') }}" class="nav-link {{ Route::is('siswa.riwayat') ? 'active' : '' }}">
                    <span class="msym">receipt_long</span> Riwayat
                </a>
            @elseif(auth()->user()->isPJ())
                <div class="nav-sect">PJ</div>
                <a href="{{ route('pj.dashboard') }}" class="nav-link {{ Route::is('pj.dashboard') ? 'active' : '' }}">
                    <span class="msym">assignment</span> Tugas Saya
                </a>
            @endif
        </nav>

        <div class="sidebar-ft">
            <div class="sidebar-user">
                <div class="usr-init">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div style="min-width:0;flex:1;">
                    <div class="usr-name">{{ auth()->user()->name }}</div>
                    <span class="role-badge">
                        {{ $role === 'penanggung_jawab' ? 'PJ' : ucfirst($role) }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="msym sz18">logout</span> Keluar dari Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="main-wrap">
        <header class="topbar">
            <button class="hamburger" id="hamburgerBtn">
                <span class="msym">menu</span>
            </button>
            <div class="topbar-title">@yield('title','Dashboard')</div>
            <div class="topbar-right">
                <span class="topbar-uname">{{ auth()->user()->name }}</span>
                @if($role === 'admin')
                    <span class="tb-badge tb-admin">Admin</span>
                @elseif($role === 'siswa')
                    <span class="tb-badge tb-siswa">Siswa</span>
                @else
                    <span class="tb-badge tb-pj">PJ</span>
                @endif
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="flash flash-ok" id="flashOk">
                    <span class="msym filled">check_circle</span>
                    <span>{{ session('success') }}</span>
                    <button class="flash-x" onclick="this.closest('.flash').remove()">
                        <span class="msym sz18">close</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-err" id="flashErr">
                    <span class="msym filled">error</span>
                    <span>{{ session('error') }}</span>
                    <button class="flash-x" onclick="this.closest('.flash').remove()">
                        <span class="msym sz18">close</span>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- BOTTOM NAV --}}
    <nav class="bottom-nav">
        <div class="bn-inner">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="bn-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <span class="msym">dashboard</span><span>Home</span>
                </a>
                <a href="{{ route('admin.histori') }}" class="bn-item {{ Route::is('admin.histori') ? 'active' : '' }}">
                    <span class="msym">history</span><span>Histori</span>
                </a>
                <a href="{{ route('admin.users') }}" class="bn-item {{ Route::is('admin.users') ? 'active' : '' }}">
                    <span class="msym">group</span><span>User</span>
                </a>
                <a href="{{ route('admin.kategori') }}" class="bn-item {{ Route::is('admin.kategori') ? 'active' : '' }}">
                    <span class="msym">category</span><span>Kategori</span>
                </a>
            @elseif(auth()->user()->isSiswa())
                <a href="{{ route('siswa.dashboard') }}" class="bn-item {{ Route::is('siswa.dashboard') ? 'active' : '' }}">
                    <span class="msym">add_circle</span><span>Pengaduan</span>
                </a>
                <a href="{{ route('siswa.riwayat') }}" class="bn-item {{ Route::is('siswa.riwayat') ? 'active' : '' }}">
                    <span class="msym">receipt_long</span><span>Riwayat</span>
                </a>
            @elseif(auth()->user()->isPJ())
                <a href="{{ route('pj.dashboard') }}" class="bn-item {{ Route::is('pj.dashboard') ? 'active' : '' }}">
                    <span class="msym">assignment</span><span>Tugas</span>
                </a>
            @endif
        </div>
    </nav>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const burger  = document.getElementById('hamburgerBtn');
        const openSB  = () => { sidebar.classList.add('open'); overlay.classList.add('show'); document.body.style.overflow='hidden'; };
        const closeSB = () => { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow=''; };
        burger?.addEventListener('click', openSB);
        overlay.addEventListener('click', closeSB);

        // Auto dismiss flash
        const fok = document.getElementById('flashOk');
        if (fok) setTimeout(() => fok.style.display='none', 4000);

        // Modal helpers
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }
        // Close on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(m => {
            m.addEventListener('click', e => { if(e.target === m) m.classList.remove('show'); });
        });
    </script>
    @stack('scripts')
</body>
</html>
