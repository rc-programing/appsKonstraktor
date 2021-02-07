<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Pengeluaran</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 d-flex mb-4">
                    <small class="mr-2 d-flex mt-1"> <span>Tambah data pengeluaran | </span></small>
                    <button type="button" id="showModalTambahPengeluaran" class="btn btn-success btn-sm mr-2"><i class="fas fa-plus"></i> Tambah</button>
                    <a href="<?= site_url('admin/do_cetak_harian/' . TODAY); ?>" class="btn btn-warning btn-sm"><i class="fas fa-print"></i> Cetak</a>
                </div>
            </div>

            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row d-flex justify-content-start ">

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
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th>Nama Lengkap</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Alokasi Dana</th>
                                                <th>Total Pembayaran</th>
                                                <th>Jam</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($data_keluar as $data) : ?>
                                                <tr>
                                                    <td><?= $no++; ?>.</td>
                                                    <td><?= (empty($data['nm_lengkap'])) ? '-' : $data['nm_lengkap']; ?></td>
                                                    <td><?= $data['jenis']; ?></td>
                                                    <td><?= $data['alokasi']; ?></td>
                                                    <td><?= format_rupiah($data['total']); ?></td>
                                                    <td><?= $data['time_created']; ?></td>
                                                    <td class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-sm btn-info mr-2 showModalUbahPengeluaran" data-value="<?= $data['id_pengeluaran']; ?>"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-sm btn-danger showModalHapuspengeluaran" data-value="<?= $data['id_pengeluaran']; ?>"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>

                                            <?php endforeach; ?>

                                        </tbody>
                                        <!-- <tfoot>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Alokasi Dana</th>
                                        <th>Total Pembayaran</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot> -->
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>