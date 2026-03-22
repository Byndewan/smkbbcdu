<style>
    .sidebar-group {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        padding: 8px;
        margin-bottom: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }
    .sidebar-link {
        border-radius: 10px;
        color: #4b5563;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .sidebar-link:hover {
        background-color: #f3f4f6;
        color: var(--bbc-primary, #0d6efd);
        transform: translateX(4px);
    }
    .sidebar-link.active {
        background-color: var(--bbc-primary, #0d6efd);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px; height: 100vh; overflow-y: auto;">

    <a href="{{ route('admin.landing') }}" class="d-flex align-items-center mb-4 text-decoration-none px-2 mt-2">
        <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center me-3 shadow" style="width: 42px; height: 42px;">
            <i class="bi bi-wallet2 fs-5"></i>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-5 fw-bold text-dark tracking-tight">BBC Pay</span>
            <span class="text-primary fw-bold" style="font-size: 9px; letter-spacing: 1.5px;">ADMIN PANEL</span>
        </div>
    </a>

    <ul class="nav nav-pills flex-column mb-auto flex-grow-1">

        {{-- MASTER DATA --}}
        <div class="sidebar-group">
            <li class="nav-header mb-2 text-uppercase fw-bold px-3 pt-1" style="font-size: 10px; color: #9ca3af; letter-spacing: 1px;">Master Data</li>
            <li>
                <a href="{{ route('admin.core.students.index') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.core.students*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Data Siswa
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.classes.index') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.core.classes*') ? 'active' : '' }}">
                    <i class="bi bi-door-open me-2"></i> Data Kelas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.majors.index') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.core.majors*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard me-2"></i> Jurusan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.school-years.index') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.core.school-years*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-range me-2"></i> Tahun Ajaran
                </a>
            </li>
        </div>

        {{-- DAFTAR ULANG --}}
        <div class="sidebar-group">
            <li class="nav-header mb-2 text-uppercase fw-bold px-3 pt-1" style="font-size: 10px; color: #9ca3af; letter-spacing: 1px;">Daftar Ulang</li>
            <li>
                <a href="{{ route('admin.du.dashboard') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.du.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow me-2"></i> Dashboard DU
                </a>
            </li>
            <li>
                <a href="{{ route('admin.du.transactions.index') }}" class="nav-link sidebar-link mb-1 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.du.transactions*') ? 'active' : '' }}">
                    <span><i class="bi bi-cash-stack me-2"></i> Transaksi Siswa</span>
                    <span id="sidebar-badge-du" class="badge bg-danger rounded-pill d-none">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.du.bills.index') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.du.bills*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> Setting Tagihan
                </a>
            </li>
        </div>

        {{-- FINANCE --}}
        <div class="sidebar-group">
            <li class="nav-header mb-2 text-uppercase fw-bold px-3 pt-1" style="font-size: 10px; color: #9ca3af; letter-spacing: 1px;">Finance & Laporan</li>
            <li>
                <a href="{{ route('admin.finance.dashboard') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.finance.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-pie-chart me-2"></i> Analytics
                </a>
            </li>
            <li>
                <a href="{{ route('admin.finance.index') }}" class="nav-link sidebar-link mb-1 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                    <span><i class="bi bi-check-circle me-2"></i> Verifikasi Manual</span>
                    <span id="sidebar-badge-finance" class="badge bg-danger rounded-pill d-none">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.finance.report') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.finance.report') ? 'active' : '' }}">
                    <i class="bi bi-printer me-2"></i> Cetak Laporan
                </a>
            </li>
        </div>

        {{-- PENGATURAN --}}
        <div class="sidebar-group">
            <li class="nav-header mb-2 text-uppercase fw-bold px-3 pt-1" style="font-size: 10px; color: #9ca3af; letter-spacing: 1px;">Sistem & Akses</li>
            <li>
                <a href="{{ route('admin.core.users.index') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.core.users*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge me-2"></i> Pengguna
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.roles.index') }}" class="nav-link sidebar-link mb-1 {{ request()->routeIs('admin.core.roles*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock me-2"></i> Peran & Hak Akses
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.front.index') }}" class="nav-link sidebar-link {{ request()->routeIs('admin.settings.front*') ? 'active' : '' }}">
                    <i class="bi bi-layout-text-window-reverse me-2"></i> Landing Page
                </a>
            </li>
        </div>

        {{-- ARCHIVIST --}}
        <div class="sidebar-group border-danger border-opacity-25 bg-danger bg-opacity-10">
            <li class="nav-header mb-2 text-uppercase fw-bold px-3 pt-1 text-danger" style="font-size: 10px; letter-spacing: 1px;">Archivist</li>
            <li>
                <a href="{{ route('admin.trash.dashboard') }}" class="nav-link sidebar-link text-danger mb-1 {{ request()->routeIs('admin.trash.*') ? 'active bg-danger text-white' : '' }}">
                    <i class="bi bi-trash3 me-2"></i> Kelola Sampah
                </a>
            </li>
            <li>
                <a href="/archivist/filemanager?type=files" target="_blank" class="nav-link sidebar-link text-danger">
                    <i class="bi bi-folder-symlink me-2"></i> File Manager
                </a>
            </li>
        </div>

    </ul>

    {{-- LOGOUT (Pakai mt-auto biar selalu di bawah) --}}
    <div class="mt-auto pt-4 mb-2">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger d-flex align-items-center bg-danger bg-opacity-10 p-3 rounded-4 sidebar-link">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="bi bi-box-arrow-left text-danger"></i>
            </div>
            <span class="fw-bold">Keluar Aplikasi</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</div>
