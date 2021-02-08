<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= $_SERVER["REQUEST_URI"]; ?>" class="nav-link" id="reloadScreen">
                <i class="fas fa-sync"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="javascript:phpdesktop.ToggleFullscreen()" class="nav-link" id="maximizeScreen">
                <i class="fas fa-window-maximize"></i>
            </a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm" style="width: 272px;">
            <input name="dataHanyaNama" class="form-control form-control-navbar" type="search" placeholder="Cari berdasarkan nama" aria-label="Search">
            <div class="input-group-append">
                <button id="cariHanyaNama" class="btn btn-navbar" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="#" role="button">
                Periode: <?= konversiHari(date('D')) . ", " . konversiTanggal(date('Y-m-d')); ?><span id="periode" class="ml-2"> </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-user"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->