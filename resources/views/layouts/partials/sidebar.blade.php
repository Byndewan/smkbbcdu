<div class="sidebar bg-white p-3 d-flex flex-column" style="width: 260px;">
    <div class="mb-4 text-center">
        <h5 class="fw-bold text-primary">
            @if (request()->is('admin/core*'))
                MASTER DATA
            @elseif(request()->is('admin/daftar-ulang*'))
                DAFTAR ULANG
            @elseif(request()->is('admin/keuangan*'))
                KEUANGAN
            @elseif(request()->is('admin/settings*'))
                SETTINGS
            @else
                MODUL
            @endif
        </h5>
        <small class="text-muted">Panel Admin</small>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ route('landing') }}" class="nav-link link-dark">
                <i class="bi bi-grid me-2"></i> Menu Utama
            </a>
        </li>

        @if (request()->is('admin/core*'))
            <li class="nav-header text-muted small fw-bold mt-2">MASTER DATA</li>

            <li>
                <a href="{{ route('core.students.index') }}"
                    class="nav-link {{ request()->routeIs('core.students*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-people-fill me-2"></i> Siswa
                </a>
            </li>
            <li>
                <a href="{{ route('core.school-years.index') }}"
                    class="nav-link {{ request()->routeIs('core.school-years*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-calendar-event me-2"></i> Tahun Ajaran
                </a>
            </li>
            <li>
                <a href="{{ route('core.majors.index') }}"
                    class="nav-link {{ request()->routeIs('core.majors*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-mortarboard me-2"></i> Jurusan
                </a>
            </li>
            <li>
                <a href="{{ route('core.classes.index') }}"
                    class="nav-link {{ request()->routeIs('core.classes*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-door-open me-2"></i> Kelas
                </a>
            </li>

            <li class="nav-header text-muted small fw-bold mt-3">USER MANAGEMENT</li>
            <li><a href="#" class="nav-link link-dark"><i class="bi bi-people me-2"></i> Users</a></li>
        @endif

        @if (request()->is('admin/daftar-ulang*'))
            <li class="nav-header text-muted small fw-bold mt-2">TRANSAKSI</li>
            <li><a href="#" class="nav-link link-dark"><i class="bi bi-cash-stack me-2"></i> Data Transaksi</a>
            </li>
            <li>
                <a href="{{ route('du.transactions.index') }}"
                    class="nav-link {{ request()->routeIs('du.transactions.*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-cash-stack me-2"></i> Transaksi

                    <span id="badge-pending-transactions" class="badge bg-danger rounded-pill ms-auto d-none">0</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('du.transactions.index') }}"
                    class="nav-link {{ request()->routeIs('du.transactions.*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-cash-stack me-2"></i> Transaksi Lunas

                    <span id="badge-pending-transactions" class="badge bg-danger rounded-pill ms-auto d-none">0</span>
                </a>
            </li> --}}

            <li class="nav-header text-muted small fw-bold mt-2">PENGATURAN</li>
            <li>
                <a href="{{ route('du.bills.index') }}"
                    class="nav-link {{ request()->routeIs('du.bills*') ? 'active' : 'link-dark' }}">
                    <i class="bi bi-gear me-2"></i> Pengaturan Tagihan
                </a>
            </li>
        @endif

        @if (request()->is('admin/keuangan*'))
            <li class="nav-header text-muted small fw-bold mt-2">FINANCE</li>
            <li><a href="{{ route('finance.index') }}"
                    class="nav-link {{ request()->routeIs('finance.index') ? 'active' : 'link-dark' }}"><i
                        class="bi bi-check-circle me-2"></i> Verifikasi Bayar</a></li>

            <li class="nav-header text-muted small fw-bold mt-2">MASTER DATA</li>
            <li><a href="#" class="nav-link link-dark"><i class="bi bi-bank me-2"></i> Rekening Sekolah</a></li>
            <li class="nav-header text-muted small fw-bold mt-2">LAPORAN</li>
            <li><a href="{{ route('finance.report') }}"
                    class="nav-link {{ request()->routeIs('finance.report') ? 'active' : 'link-dark' }}"><i
                        class="bi bi-graph-up me-2"></i> Laporan Keuangan</a>
            </li>
        @endif

        @if (request()->is('admin/settings*'))
            <li class="nav-header text-muted small fw-bold mt-2">FINANCE</li>
            <li><a href="{{ route('settings.front.index') }}"
                    class="nav-link {{ request()->routeIs('settings*') ? 'active' : 'link-dark' }}"><i
                        class="bi bi-check-circle me-2"></i> Settings</a></li>
        @endif

    </ul>
</div>
