<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Cetak Harian</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cetak data berdasarkan Tanggal</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="tanggalCari">Tanggal</label>
                                        <input type="date" value="<?= $date; ?>" name="tanggalCari" id="tanggalCari" class="form-control" placeholder="Masukan Tanggal">
                                    </div>
                                </div>
                                <div class="col-3 d-flex align-self-center mt-3">
                                    <button type="button" id="cariTgl" class="btn btn-info mr-2"><i class="fa fa-search"></i> </button>
                                    <?php if (count($data_keluar) > 0) : ?>
                                        <button id="cetakTgl" class="btn btn-warning"><i class="fa fa-print"></i> </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start mb-2">
                                <div class="col-3">
                                    <!-- /.info-box -->
                                    <div class="info-box mb-3 bg-success">
                                        <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Debit</span>
                                            <span class="info-box-number">
                                                <?= format_rupiah($total_debit); ?>
                                            </span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>

                                <div class="col-3">
                                    <!-- /.info-box -->
                                    <div class="info-box mb-3 bg-info">
                                        <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Kredit</span>
                                            <span class="info-box-number"><?= format_rupiah($kredit); ?></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                                <div class="col-3">
                                    <!-- /.info-box -->
                                    <div class="info-box mb-3 bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Saldo</span>
                                            <span class="info-box-number"><?= format_rupiah($saldo); ?></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="15%">Tanggal</th>
                                                <th>Keterangan</th>
                                                <th>Kredit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($data_keluar as $d) : ?>
                                                <tr>
                                                    <td><?= $no; ?>.</td>
                                                    <td><?= konversiTanggal($d['tanggal']); ?></td>
                                                    <td><?= $d['jenis'] . ((empty($d['nm_lengkap'])) ? ' ' : ' ' . $d['nm_lengkap'] . ' ') . $d['alokasi']; ?></td>
                                                    <td><?= format_rupiah($d['total']); ?></td>
                                                </tr>
                                            <?php $no++;
                                            endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>