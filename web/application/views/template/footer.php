<?php if ($this->uri->segment(2) == 'data_pengeluaran') : ?>
    <div class="modal fade" id="modal-pengeluaran">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_pengeluaran">Tambah Pengeluaran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formSimpanPengeluaran" action="<?= site_url('admin/tambahPengeluaran') ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="nm_lengkap" class="col-sm-3 col-form-label text-right">Nama Lengkap :</label>
                            <div class="col-sm-7">
                                <input type="text" name="nm_lengkap" class="form-control" id="nm_lengkap" placeholder="Nama lengkap">
                            </div>
                        </div>
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="jenis" class="col-sm-3 col-form-label text-right">Jenis Pembayaran :</label>
                            <div class="col-sm-9">
                                <textarea name="jenis" class="form-control" id="jenis" cols="30" rows="2" placeholder="Jenis pembayaran"></textarea>
                            </div>
                        </div>
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="alokasi" class="col-sm-3 col-form-label text-right">Alokasi Dana :</label>
                            <div class="col-sm-6">
                                <input type="text" name="alokasi" class="form-control" id="alokasi" placeholder="Alokasi Dana">
                            </div>
                        </div>
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="total" class="col-sm-3 col-form-label text-right">Total :</label>
                            <div class="col-sm-5">
                                <input type="text" name="total" class="form-control text-right" id="total" placeholder="Total Dana Rp. 500.0000,00">
                            </div>
                        </div>
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="total" class="col-sm-3 col-form-label text-right"></label>
                            <div class="col-sm-9">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="statusPembayaran" name="status" value="1">
                                    <label for="statusPembayaran">
                                        Untuk Pembayaran tanggal sebelumnnya (optional)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-ban"></i> Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php endif; ?>

<?php if ($this->uri->segment(2) == 'data_saldo') : ?>

    <div class="modal fade" id="modal-saldo">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_saldo">Tambah Saldo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formSimpanSaldo" action="<?= site_url('admin/tambahSaldo') ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="alokasi" class="col-sm-4 col-form-label text-right">Periode :</label>
                            <div class="col-sm-8 mt-2">
                                <label for=""><?= konversiHari(date("D")) . ", " . konversiTanggal(date('Y-m-d')); ?></label>
                            </div>
                        </div>
                        <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                            <label for="saldo" id="text_Saldo" class="col-sm-4 col-form-label text-right">Saldo <?= ($status_saldo == 0) ? 'Ac' : 'Tambahan'; ?> :</label>
                            <div class="col-sm-8">
                                <input type="text" name="saldo" class="form-control text-right" id="saldo" placeholder="Misal, Rp. 1000.0000">
                            </div>
                        </div>
                        <?php if ($status_saldo_pindahan == 0) : ?>
                            <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                                <label for="saldo_pindahan" class="col-sm-4 col-form-label text-right">Saldo Pindahan :</label>
                                <div class="col-sm-8">
                                    <input type="text" name="saldo_pindahan" class="form-control text-right" id="saldo_pindahan" placeholder="Misal, Rp. 1000.0000">
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($status_saldo > 0) : ?>
                            <div id="keteranganSaldo"></div>
                            <div class="form-group row border-bottom mb-0 pt-2 pb-2 ml-5">
                                <label for="statusSaldo" class="col-sm-3 col-form-label text-right">Status :</label>
                                <div class="col-sm-9">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="statusSaldo" name="statusSaldo" value="1">
                                        <label for="statusSaldo">
                                            Saldo Lainnya (optional)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-ban"></i> Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

<?php endif; ?>

<div class="modal fade" id="modal-ubah-akun">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah akun</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUbahAkun" action="<?= base_url('admin/ubahAkun') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                        <label for="nm_lengkap" class="col-sm-3 col-form-label text-right">Nama Lengkap :</label>
                        <div class="col-sm-7">
                            <input type="text" value="<?= $this->session->userdata('nama_lengkap'); ?>" name="nm_lengkap" class="form-control" id="nm_lengkap" placeholder="Nama lengkap">
                        </div>
                    </div>
                    <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                        <label for="username" class="col-sm-3 col-form-label text-right">Username :</label>
                        <div class="col-sm-7">
                            <input type="text" value="<?= $this->session->userdata('username'); ?>" name="username" class="form-control" id="username" placeholder="Username" />
                        </div>
                    </div>
                    <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                        <label for="passlama" class="col-sm-3 col-form-label text-right">Password lama :</label>
                        <div class="col-sm-5">
                            <input type="password" name="passlama" class="form-control" id="passlama" placeholder="Password lama">
                        </div>
                    </div>
                    <div class="form-group row border-bottom mb-0 pt-2 pb-2">
                        <label for="passbaru" class="col-sm-3 col-form-label text-right">Password Baru :</label>
                        <div class="col-sm-5">
                            <input type="password" name="passbaru" class="form-control" id="passbaru" placeholder="Password baru">
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-ban"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-confirm">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="title_konfirmasi">Konfirmasi hapus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form id="formHapusData" action="<?= site_url('admin/hapusPengeluaran') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_pengeluaran" class="form-control">
                    Apakah anda yakin ingin menghapus data ini ?
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-ban"></i> Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Ya, Hapus</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2021 SIPDAK APPS & AdminLTE.io.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>

<!-- Control Sidebar -->
<!-- <aside class="control-sidebar control-sidebar-dark"> -->
<!-- Control sidebar content goes here -->
<!-- </aside> -->
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- pace-progress -->
<script src="<?= base_url('assets/plugins/pace-progress/pace.min.js'); ?>"></script>
<!-- Select2 -->
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js'); ?>"></script>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js'); ?>"></script>
<!-- Toastr -->
<script src="<?= base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/dist/js/adminlte.min.js'); ?>"></script>
<script src="<?= base_url('assets/dist/js/axio.min.js'); ?>"></script>
<script src="<?= base_url('assets/custom/script.min.js'); ?>"></script>

</body>

</html>