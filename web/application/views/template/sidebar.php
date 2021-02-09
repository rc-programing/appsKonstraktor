<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?= base_url('assets/dist/img/SipdakLogo.png'); ?>" alt="SIPDAK Logo" width="128" height="128" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIPDAK APPS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/dist/img/SipdakLogo1.png'); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $this->session->userdata('nama_lengkap'); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= site_url('page'); ?>" class="nav-link <?= ($this->uri->segment(1) == "page" && empty($this->uri->segment(2))) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == "data_pengeluaran" || $this->uri->segment(2) == "data_saldo") ? "menu-open" : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == "data_pengeluaran" || $this->uri->segment(2) == "data_saldo") ? "active" : ''; ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" <?= ($this->uri->segment(2) == "data_pengeluaran" || $this->uri->segment(2) == "data_saldo") ? 'style="display: block;"' : ''; ?>>
                        <li class="nav-item">
                            <a href="<?= site_url('page/data_saldo'); ?>" class="nav-link <?= ($this->uri->segment(2) == "data_saldo") ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Saldo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('page/data_pengeluaran'); ?>" class="nav-link <?= ($this->uri->segment(2) == "data_pengeluaran") ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pengeluaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == "searching" || $this->uri->segment(2) == "backup" || $this->uri->segment(2) == "cetak_harian") ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == "searching" || $this->uri->segment(2) == "backup" || $this->uri->segment(2) == "cetak_harian") ? "active" : ''; ?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Utilitis
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" <?= ($this->uri->segment(2) == "searching" || $this->uri->segment(2) == "backup" || $this->uri->segment(2) == "cetak_harian") ? 'style="display: block;"' : ''; ?>>
                        <li class="nav-item">
                            <a href="<?= site_url('page/cetak_harian'); ?>" class="nav-link <?= ($this->uri->segment(2) == "cetak_harian") ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cetak Harian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('page/searching'); ?>" class="nav-link <?= ($this->uri->segment(2) == "searching") ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cari Data</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('page/backup'); ?>" class="nav-link <?= ($this->uri->segment(2) == "backup") ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Backup Database</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link" id="showModalUbahakun">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Ubah akun</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('page/logout'); ?>" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>