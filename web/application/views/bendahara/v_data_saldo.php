<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Saldo</h1>
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
                    <small class="mr-2 d-flex mt-1"> <span>Tambah saldo hari ini | </span></small>
                    <button type="button" id="showModalTambahSaldo" class="btn btn-success btn-sm mr-2"><i class="fas fa-plus"></i> Tambah</button>
                </div>
            </div>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabelSaldo" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Tanggal</th>
                                        <th>Saldo</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($data_saldo as $data) :
                                        if ($data['status_saldo'] != 999) : ?>
                                            <tr>
                                                <td><?= $no++; ?>.</td>
                                                <td><?= konversiTanggal($data['tgl']); ?></td>
                                                <td><?= format_rupiah($data['saldo']); ?></td>
                                                <td><?= $data['ket']; ?></td>
                                                <td class="d-flex justify-content-center align-items-center">
                                                    <?php if ($data['status_saldo'] != 1) : ?>
                                                        <button type="button" data-value="<?= $data['id_saldo']; ?>" class="btn btn-sm btn-info mr-2 showModalUbahSaldo"><i class="fas fa-edit"></i></button>
                                                    <?php endif; ?>
                                                    <?php if ($data['status_saldo'] == 3 || $data['status_saldo'] == 4) : ?>
                                                        <button type="button" data-value="<?= $data['id_saldo']; ?>" class="btn btn-sm btn-danger mr-2 showModalHapusSaldo"><i class="fas fa-edit"></i></button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                    <?php endif;
                                    endforeach; ?>
                                </tbody>
                            </table>
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