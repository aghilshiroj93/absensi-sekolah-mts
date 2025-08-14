<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/img/logo-sekolah.png" class="img-circle elevation-2" alt="Logo Sekolah">
        </div>
        <div class="info">
            <a href="#" class="d-block">MTs Nurul islam</a>
        </div>
    </div>


    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="/beranda" class="nav-link {{ $title === 'Beranda' ? 'active' : '' }}">
                    <i class="bi bi-house-fill"></i>
                    <p class="pl-1">
                        Beranda
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/scan-qr" class="nav-link {{ $title === 'Scan QR' ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    <p class="pl-1">
                        Scan QR
                    </p>
                </a>
            </li>
            @can('guru')
                <li class="nav-item">
                    <a href="/kelas-saya" class="nav-link {{ $title === 'Kelas Saya' ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <p class="pl-1">
                            Kelas Saya
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('jadwalsaya') }}" class="nav-link {{ $title === 'Jadwal Saya' ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <p class="pl-1">
                            Jadwal Saya
                        </p>
                    </a>
                </li>
            @endcan

            @can('admin')
                <li
                    class="nav-item {{ in_array($title, ['Daftar Jadwal Guru', 'Input Jadwal Guru', 'Detail Jadwal Guru', 'Edit Jadwal Guru', 'Daftar Guru', 'Detail Guru', 'Input Guru', 'Daftar Murid', 'Daftar Mata Pelajaran', 'Input Mata Pelajaran', 'Detail Mata Pelajaran', 'Daftar Kelas', 'Daftar Tahun', 'Detail Murid', 'Detail Kelas', 'Detail Tahun', 'Daftar Tahun Akademik']) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ in_array($title, ['Daftar Jadwal Guru', 'Input Jadwal Guru', 'Detail Jadwal Guru', 'Edit Jadwal Guru', 'Edit Guru', 'Daftar Guru', 'Detail Guru', 'Input Guru', 'Daftar Murid', 'Daftar Mata Pelajaran', 'Input Mata Pelajaran', 'Detail Mata Pelajaran', 'Daftar Kelas', 'Daftar Tahun', 'Detail Murid', 'Detail Kelas', 'Detail Tahun', 'Daftar Tahun Akademik']) ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <p class="pl-1">
                            Master Data
                            <i class="bi bi-caret-down-fill right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/daftar-murid"
                                class="nav-link {{ in_array($title, ['Daftar Murid', 'Detail Murid']) ? 'active' : '' }}">
                                <p>Data Murid</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/kelas/daftar"
                                class="nav-link {{ in_array($title, ['Daftar Kelas', 'Detail Kelas']) ? 'active' : '' }}">
                                <p>Data Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/guru"
                                class="nav-link {{ in_array($title, ['Daftar Guru', 'Input Guru', 'Detail Guru', 'Edit Guru']) ? 'active' : '' }}">
                                <p>Data Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jadwalguru.index') }}"
                                class="nav-link {{ in_array($title, ['Daftar Jadwal Guru', 'Input Jadwal Guru', 'Detail Jadwal Guru', 'Edit Jadwal Guru']) ? 'active' : '' }}">
                                <p>Data Jadwal Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/mapel"
                                class="nav-link {{ in_array($title, ['Daftar Mata Pelajaran', 'Input Mata Pelajaran', 'Detail Mata Pelajaran', 'Edit Mata Pelajaran']) ? 'active' : '' }}">
                                <p>Data Mata Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tahun"
                                class="nav-link {{ in_array($title, ['Daftar Tahun', 'Detail Tahun', 'Daftar Tahun Akademik']) ? 'active' : '' }}">
                                <p>Data Tahun Akademik</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <hr>
            <div class="mt-4 ml-4">
                <form action="/keluar" method="post">
                    @csrf
                    <p>
                        <button class="btn btn-outline-danger" type="submit">Keluar</button>
                    </p>
                </form>
            </div>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
