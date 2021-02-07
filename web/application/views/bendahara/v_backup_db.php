<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Backup Database</h1>
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
                            <h3 class="card-title">Backup database secara berkala</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form id="formimportDB" class="d-flex" action="<?= site_url('admin/getImport'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label for="dbname-import">Import Database</label>
                                            <input id="dbname-import" name="dbname" type="file" accept=".db" class="form-control" placeholder="Masukan Nama lengkap">
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex align-self-center mt-3">
                                        <button type="submit" class="btn btn-info mr-2"><i class="fa fa-download"></i> </button>
                                        <button class="btn btn-danger"><i class="fa fa-ban"></i> </button>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-red">* Hati-hati, ketika database telah diterapkan maka <b>database sebelumnya</b> akan tergantikan dengan database yang baru dimasukan</small> <br />
                                    <small class="text-red">* Ubah terlebih dahulu nama database yang ingin di import menjadi <b>databases.db</b></small>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>Eksport Database</label>
                                </div>
                                <div class="col-12 d-flex align-self-center mt-3">
                                    <button id="eksportDb" class="btn btn-info mr-2"><i class="fa fa-upload"></i> </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-style-italic">* Database yang telah di eksport berada di folder downloads</small> <br />

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <hr>
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