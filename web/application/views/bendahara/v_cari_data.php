<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Cari Data</h1>
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
                            <h3 class="card-title">Cara data berdasarkan Nama atau Alokasi Dana</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="namaCari">Nama</label>
                                        <select class="form-control select2" style="width: 100%;" name="dataNama" id="namaCari">
                                            <?php if (count($result) > 0) : ?>
                                                <option <?= ($nama == '') ? '' : 'selected="selected" '; ?> value="">--Pilih--</option>
                                                <?php foreach ($data_nama as $val) :
                                                    if ($val['nm_lengkap'] != '') : ?>
                                                        <option <?= ($nama == $val['nm_lengkap']) ? ' selected="selected" ' : ''; ?> value="<?= $val['nm_lengkap']; ?>"><?= $val['nm_lengkap']; ?></option>
                                                <?php endif;
                                                endforeach; ?>
                                            <?php else : ?>
                                                <option selected="selected" value="">--Pilih--</option>
                                                <?php foreach ($data_nama as $val) :
                                                    if ($val['nm_lengkap'] != '') : ?>
                                                        <option value="<?= $val['nm_lengkap']; ?>"><?= $val['nm_lengkap']; ?></option>
                                                <?php endif;
                                                endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-1 d-flex align-self-center justify-content-center mt-3">
                                    <h1>/</h1>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alokasiCari">Alokasi Dana</label>
                                        <select class="form-control select2" style="width: 100%;" name="dataAlokasi" id="alokasiCari">
                                            <?php if (count($result) > 0) : ?>
                                                <option <?= ($alokasi == '') ? '' : 'selected="selected" '; ?> value="">--Pilih--</option>
                                                <?php foreach ($data_alokasi as $val) : ?>
                                                    <option <?= ($alokasi == $val['alokasi']) ? ' selected="selected" ' : ''; ?> value="<?= $val['alokasi']; ?>"><?= $val['alokasi']; ?></option>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <option selected="selected" value="">--Pilih--</option>
                                                <?php foreach ($data_alokasi as $val) : ?>
                                                    <option value="<?= $val['alokasi']; ?>"><?= $val['alokasi']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 d-flex align-self-center mt-1">
                                    <button id="btnCariNama" class="btn btn-info mr-2"><i class="fa fa-search"></i> </button>
                                    <?php if (count($result) > 0) :  ?>
                                        <button id="btnCetakAlokasi" class="btn btn-warning"><i class="fa fa-print"></i> </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <?php if (count($result) > 0) :  ?>
                                <div class="row">
                                    <div class="col-3">
                                        <!-- /.info-box -->
                                        <div class="info-box mb-3 bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Kredit</span>
                                                <span class="info-box-number"><?= format_rupiah($kredit); ?></span>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="row mt-2">
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
                                            foreach ($result as $val) : ?>
                                                <tr>
                                                    <td><?= $no; ?>.</td>
                                                    <td><?= konversiTanggal($val['tanggal']); ?></td>
                                                    <td><?= $val['jenis'] . ((empty($val['nm_lengkap'])) ? ' ' : ' ' . $val['nm_lengkap'] . ' ') . $val['alokasi'] ?></td>
                                                    <td><?= format_rupiah($val['total']); ?></td>
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