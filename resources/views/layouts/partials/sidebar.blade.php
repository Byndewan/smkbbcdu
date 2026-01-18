<div class="sidebar d-flex flex-column flex-shrink-0 p-3">
    <a href="{{ route('admin.landing') }}"
        class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-decoration-none px-2 mt-2">
        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center me-3 shadow-sm"
            style="width: 36px; height: 36px;">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-6 fw-bold text-dark-emphasis">BBC Pay</span>
            <span class="text-muted" style="font-size: 10px; letter-spacing: 1px;">ADMIN PANEL</span>
        </div>
    </a>

    <hr class="opacity-10 my-4">

    <ul class="nav nav-pills flex-column mb-auto gap-1">

        <li class="nav-item">
            <a href="{{ route('admin.landing') }}" class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">
                <i class="bi bi-grid-fill me-2"></i>
                Menu Utama
            </a>
        </li>

        @if (request()->is('admin/core*'))
            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Master Data</li>

            <li>
                <a href="{{ route('admin.core.students.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.students*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Data Siswa
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.classes.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.classes*') ? 'active' : '' }}">
                    <i class="bi bi-door-open me-2"></i> Data Kelas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.majors.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.majors*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard me-2"></i> Jurusan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.school-years.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.school-years*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-range me-2"></i> Tahun Ajaran
                </a>
            </li>

            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">Akses
                & User</li>
            <li>
                <a href="{{ route('admin.core.users.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.users*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge me-2"></i> Pengguna
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.roles.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.roles*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock me-2"></i> Peran & Hak
                </a>
            </li>
            <li>
                <a href="{{ route('admin.core.permissions.index') }}"
                    class="nav-link {{ request()->routeIs('admin.core.permissions*') ? 'active' : '' }}">
                    <i class="bi bi-lock me-2"></i> Hak Akses
                </a>
            </li>
        @endif

        @if (request()->is('admin/daftar-ulang*'))
            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Transaksi</li>

            <li>
                <a href="{{ route('admin.du.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.du.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.du.transactions.index') }}"
                    class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.du.transactions*') ? 'active' : '' }}">
                    <span><i class="bi bi-cash-stack me-2"></i> Pembayaran</span>
                    <span id="sidebar-badge-du" class="badge bg-danger rounded-pill d-none">0</span>
                </a>
            </li>

            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Konfigurasi</li>
            <li>
                <a href="{{ route('admin.du.bills.index') }}"
                    class="nav-link {{ request()->routeIs('admin.du.bills*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> Tagihan (Bills)
                </a>
            </li>
        @endif

        @if (request()->is('admin/keuangan*'))
            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Finance</li>

            <li>
                <a href="{{ route('admin.finance.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.finance.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.finance.index') }}"
                    class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                    <span><i class="bi bi-check-circle me-2"></i> Verifikasi</span>
                    <span id="sidebar-badge-finance" class="badge bg-danger rounded-pill d-none">0</span>
                </a>
            </li>

            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Output</li>
            <li>
                <a href="{{ route('admin.finance.report') }}"
                    class="nav-link {{ request()->routeIs('admin.finance.report') ? 'active' : '' }}">
                    <i class="bi bi-printer me-2"></i> Laporan
                </a>
            </li>
        @endif

        @if (request()->is('admin/settings*'))
            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">
                Website</li>
            <li>
                <a href="{{ route('admin.settings.front.index') }}"
                    class="nav-link {{ request()->routeIs('admin.settings.front*') ? 'active' : '' }}">
                    <i class="bi bi-layout-text-window-reverse me-2"></i> Landing Page
                </a>
            </li>

            <li class="nav-header mt-3 mb-1 text-uppercase small fw-bold text-muted ps-3" style="font-size: 11px;">Akun
            </li>
            <li>
                <a href="{{ route('admin.settings.profile.index') }}"
                    class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear me-2"></i> Profil Saya
                </a>
            </li>
        @endif

    </ul>

    <div class="mt-auto pt-4 border-top border-secondary border-opacity-10">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="nav-link text-danger d-flex align-items-center">
            <i class="bi bi-box-arrow-left me-2"></i>
            <span>Keluar Aplikasi</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</div>
