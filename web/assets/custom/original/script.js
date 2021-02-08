(function (window, $, undefined) {

    // ## function
    let baseUrl = window.location.origin;

    function allData(url) {
        const promise = axios.get(url)
        const dataPromise = promise.then((response) => response.data)
        return dataPromise
    }

    function serialize(form) {
        let requestArray = [];
        form.querySelectorAll('[name]').forEach((elem) => {
            requestArray.push(elem.name + '=' + elem.value);
        });
        if (requestArray.length > 0)
            return requestArray.join('&');
        else
            return false;
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function isNumberKey(evt) {
        key = evt.which || evt.keyCode;
        var charStr = String.fromCharCode(key);
        if (key != 188 // Comma
            && key != 8 // Backspace
            && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
            && (key < 48 || key > 57) // Non digit
        ) {
            evt.preventDefault();
            return;
        }
    }

    //## implementasi function
    // view Saldo Fix
    let modalSaldo = document.querySelector("#showModalTambahSaldo");
    if (modalSaldo != null) {
        modalSaldo.onclick = function () {
            allData(baseUrl + '/admin/getStatuSaldo')
                .then(data => {
                    let dataModal = data;
                    let text_Saldo = document.querySelector("#text_Saldo")
                    let htmlKeterangan = document.querySelector("#keteranganSaldo")
                    let statusSaldo = document.querySelector("#statusSaldo")
                    text_Saldo.innerHTML = 'Saldo Ac :';
                    if (dataModal > 0) {
                        text_Saldo.innerHTML = 'Saldo Tambahan :';
                    }

                    document.querySelector("input[name='saldo']").value = "";
                    document.querySelector("#title_saldo").innerHTML = "Tambah Saldo";
                    document.querySelector('form#formSimpanSaldo').setAttribute("action", baseUrl + "/admin/tambahSaldo");
                    if (htmlKeterangan != null) {
                        htmlKeterangan.innerHTML = '';
                    }

                    if (statusSaldo != null) {
                        statusSaldo.checked = false;
                        statusSaldo.disabled = false;
                    }

                    console.log(data);
                    $('#modal-saldo').modal('show');
                })
                .catch(err => console.log(err))

        }
    }

    Array.from(document.querySelectorAll('.showModalUbahSaldo')).forEach(e => {
        e.dataset.onclickvalue = e.getAttribute('data-value');
        e.onclick = "";

        e.addEventListener('click', function () {
            allData(baseUrl + '/admin/getSaldo/' + this.dataset.onclickvalue)
                .then(data => {
                    let dataModal = data[0];
                    let html = '<div class="form-group row border-bottom mb-0 pt-2 pb-2">';
                    html += '<label for="keterangan" class="col-sm-4 col-form-label text-right">Keterangan :</label>';
                    html += '<div class="col-sm-8">';
                    html += '<textarea rows="2" name="keterangan" class="form-control" id="keterangan" placeholder="Masukan keterangan saldo"></textarea>';
                    html += '</div></div> ';

                    let text_Saldo = document.querySelector("#text_Saldo")
                    let htmlKeterangan = document.querySelector("#keteranganSaldo")
                    let statusSaldo = document.querySelector("#statusSaldo")

                    document.querySelector("#title_saldo").innerHTML = "Ubah Saldo";
                    document.querySelector('input[name="saldo"]').value = formatRupiah(dataModal.saldo, 'Rp. ');

                    if (dataModal.status_saldo == 4) {
                        statusSaldo.disabled = false;
                        text_Saldo.innerHTML = 'Saldo Tambahan';
                        htmlKeterangan.innerHTML = html;
                        statusSaldo.value = 4;
                        statusSaldo.checked = true;
                        let ket = document.querySelector('textarea[name="keterangan"]')
                        ket.value = dataModal.ket;
                    }

                    if (dataModal.status_saldo == 2) {
                        text_Saldo.innerHTML = 'Saldo Ac';
                        htmlKeterangan.innerHTML = '';
                        statusSaldo.value = 3;
                        statusSaldo.checked = false;
                        statusSaldo.disabled = true;
                    }

                    if (dataModal.status_saldo == 3) {
                        statusSaldo.disabled = false;
                        text_Saldo.innerHTML = 'Saldo Tambahan';
                        htmlKeterangan.innerHTML = '';
                        statusSaldo.value = 3;
                        statusSaldo.checked = false;
                    }

                    document.querySelector('form#formSimpanSaldo').setAttribute("action", baseUrl + "/admin/ubahSaldo/" + this.dataset.onclickvalue);
                    $('#modal-saldo').modal('show')
                })
                .catch(err => console.log(err))
        })
    })

    // delete saldo
    Array.from(document.querySelectorAll('.showModalHapusSaldo')).forEach(e => {
        e.dataset.onclickvalue = e.getAttribute('data-value');
        e.onclick = "";

        e.addEventListener('click', function () {
            document.querySelector('input[name="id_pengeluaran"]').value = this.dataset.onclickvalue;
            document.querySelector('form#formHapusData').setAttribute("action", baseUrl + "/admin/hapusSaldo");
            $('#modal-confirm').modal('show')
        })
    })

    let keyupSaldo = document.querySelector("input[name='saldo']");
    if (keyupSaldo != null) {
        keyupSaldo.onkeyup = function (e) {
            this.value = formatRupiah(this.value, 'Rp. ')
        }
    }

    // statusSaldo
    let statusSaldo = document.querySelector("#statusSaldo");
    if (statusSaldo != null) {
        let htmlKeterangan = document.querySelector("#keteranganSaldo")
        let html = '<div class="form-group row border-bottom mb-0 pt-2 pb-2">';
        html += '<label for="keterangan" class="col-sm-4 col-form-label text-right">Keterangan :</label>';
        html += '<div class="col-sm-8">';
        html += '<textarea rows="2" name="keterangan" class="form-control" id="keterangan" placeholder="Masukan keterangan saldo"></textarea>';
        html += '</div></div> ';

        statusSaldo.onclick = function () {
            if (this.checked) {
                this.value = 4
                htmlKeterangan.innerHTML = html;
            } else {
                this.value = 3
                htmlKeterangan.innerHTML = '';
            }
        }
    }

    // view Pengeluaran
    let modalPengeluaran = document.querySelector("#showModalTambahPengeluaran");
    if (modalPengeluaran != null) {
        modalPengeluaran.onclick = function () {
            document.querySelector("#title_pengeluaran").innerHTML = "Tambah Pengeluaran";
            $('#modal-pengeluaran').modal('show');
        }
    }

    let statusPembayaran = document.querySelector("#statusPembayaran");
    if (statusPembayaran != null) {
        statusPembayaran.onclick = function () {
            if (this.checked) {
                this.value = 0;
            } else {
                this.value = 1;
            }

            console.log(this.value);
        }
    }

    let keyupTotal = document.querySelector("input[name='total']");
    if (keyupTotal != null) {
        keyupTotal.onkeyup = function (e) {
            this.value = formatRupiah(this.value, 'Rp. ');
        }

        keyupTotal.onkeydown = function (event) {
            return isNumberKey(event);
        }
    }

    Array.from(document.querySelectorAll('.showModalUbahPengeluaran')).forEach(e => {
        e.dataset.onclickvalue = e.getAttribute('data-value');
        e.onclick = "";

        e.addEventListener('click', function () {
            allData(baseUrl + '/admin/getPengeluaran/' + this.dataset.onclickvalue)
                .then(data => {
                    let dataModal = data[0];
                    document.querySelector("#title_pengeluaran").innerHTML = "Ubah Pengeluaran";
                    document.querySelector('input[name="nm_lengkap"]').value = dataModal.nm_lengkap;
                    document.querySelector('textarea[name="jenis"]').value = dataModal.jenis;
                    document.querySelector('input[name="alokasi"]').value = dataModal.alokasi;
                    document.querySelector('input[name="total"]').value = formatRupiah(dataModal.total, 'Rp. ');
                    document.querySelector('form#formSimpanPengeluaran').setAttribute("action", baseUrl + "/admin/ubahPengeluaran/" + this.dataset.onclickvalue);
                    $('#modal-pengeluaran').modal('show')
                })
                .catch(err => console.log(err))
        })
    });

    Array.from(document.querySelectorAll('.showModalHapuspengeluaran')).forEach(e => {
        e.dataset.onclickvalue = e.getAttribute('data-value');
        e.onclick = "";

        e.addEventListener('click', function () {
            document.querySelector('input[name="id_pengeluaran"]').value = this.dataset.onclickvalue;
            document.querySelector('form#formHapusData').setAttribute("action", baseUrl + "/admin/hapusPengeluaran");
            $('#modal-confirm').modal('show')
        })
    })

    // view Akun

    let modalAkun = document.querySelector("#showModalUbahakun");
    if (modalAkun != null) {
        modalAkun.onclick = function () {
            $('#modal-ubah-akun').modal('show');
        }
    }

    //  View DB
    let btneXport = document.querySelector("#eksportDb");
    if (btneXport != null) {
        btneXport.onclick = function () {
            allData(baseUrl + '/admin/getEksport')
                .then(data => {
                    download(data.url, data.filename);
                    toastr.success('Data berhasil didownload')

                    axios.get(baseUrl + '/admin/getDeleteZipDB/' + data.filename)
                        .then(function (response) {
                            console.log(response)
                        });
                })
                .catch(err => console.log(err))
        }
    }

    function download(url, filename) {
        fetch(url, {
            mode: 'no-cors' /*{mode:'cors'}*/
        }).then((transfer) => {
            return transfer.blob();
        }).then((bytes) => {
            let elm = document.createElement('a');
            elm.href = URL.createObjectURL(bytes);
            elm.setAttribute('download', filename);
            elm.click()
        }).catch((error) => {
            console.log(error);
        })
    }

    // View cari data
    let btnSearch = document.querySelector("#cariTgl");
    if (btnSearch != null) {
        btnSearch.onclick = function () {
            let tgl = document.querySelector("input[name='tanggalCari']").value;
            window.location.href = baseUrl + "/page/cetak_harian/" + tgl;
        }
    }

    function cariHanyaNama() {
        let nama = document.querySelector("input[name='dataHanyaNama']").value;
        let alokasi = '';
        if (nama != '')
            window.location.href = baseUrl + "/page/searching/" + nama.replace(' ', '-') + "/" + alokasi;
        else
            toastr.error('Masukan nama terlebih dahulu')
    }

    let inputHanyaNama = document.querySelector("input[name='dataHanyaNama']");
    if (inputHanyaNama != null) {
        inputHanyaNama.onkeyup = function (event) {
            if (event.keyCode === 13) {
                cariHanyaNama();
            }
        }
    }

    let btnHanyaNama = document.querySelector("#cariHanyaNama");
    if (btnHanyaNama != null) {
        btnHanyaNama.onclick = function (event) {
            cariHanyaNama();
        }
    }

    let btnCariNama = document.querySelector("#btnCariNama");
    if (btnCariNama != null) {
        btnCariNama.onclick = function () {
            let nama = document.querySelector("select[name='dataNama']").value;
            let alokasi = document.querySelector("select[name='dataAlokasi']").value;
            if (nama != '' || alokasi != '')
                window.location.href = baseUrl + "/page/searching/" + ((nama == '') ? 'null' : nama.replace(' ', '-')) + "/" + alokasi.replace(' ', '-');
            else
                toastr.error('Pilih nama atau alokasi terlebih dahulu')
        }
    }

    // cetak dataHanyaNama
    let btnCetakHarian = document.querySelector("#cetakTgl");
    if (btnCetakHarian != null) {
        btnCetakHarian.onclick = function () {
            let tgl = document.querySelector("input[name='tanggalCari']").value;
            window.location.href = baseUrl + "/admin/do_cetak_harian/" + tgl;
        }
    }

    // cetak dataHanyaNama
    let btnCetakCariData = document.querySelector("#btnCetakAlokasi");
    if (btnCetakCariData != null) {
        btnCetakCariData.onclick = function () {
            let nama = document.querySelector("select[name='dataNama']").value;
            let alokasi = document.querySelector("select[name='dataAlokasi']").value;
            window.location.href = baseUrl + "/admin/do_cetak_cariData/" + ((nama == "") ? 'null' : nama) + "/" + alokasi;
        }
    }

    //## crud
    // crud saldo
    const formSimpanSaldo = document.querySelector("#formSimpanSaldo");
    if (formSimpanSaldo != null) {
        formSimpanSaldo.addEventListener('submit', event => {
            event.preventDefault()
            let action = formSimpanSaldo.getAttribute('action')

            let saldoAc = document.querySelector("input[name='saldo']");
            let saldoPindahan = document.querySelector("input[name='saldo_pindahan']");
            let ketSaldo = document.querySelector("textarea[name='keterangan']");
            let check = true;

            if (saldoPindahan != null) {
                if (saldoAc.value == '' || saldoPindahan.value == '') check = false;
            } else if (ketSaldo != null) {
                if (saldoAc.value == '' || ketSaldo.value == '') check = false;
            }

            if (check) {
                axios.post(action, serialize(formSimpanSaldo))
                    .then(function (response) {
                        console.log(response);
                        if (response.data.status) {
                            window.location.reload();
                            toastr.success(response.data.msg)
                        } else {
                            toastr.error(response.data.msg)
                        }
                    });
            } else {
                toastr.error('Gagal simpan data,  masih ada data yang kosong')
            }

        })
    }

    // crud pengeluaran add and delete
    const formSimpanPengeluaran = document.querySelector("#formSimpanPengeluaran");
    if (formSimpanPengeluaran != null) {
        formSimpanPengeluaran.addEventListener('submit', event => {
            event.preventDefault()
            let action = formSimpanPengeluaran.getAttribute('action')
            serialized = serialize(formSimpanPengeluaran)

            axios.post(action, serialized)
                .then(function (response) {
                    if (response.data.status) {
                        toastr.success(response.data.msg)
                        window.location.reload();
                    } else {
                        toastr.error(response.data.msg)
                    }
                });
        })
    }

    // hapus pengeluaran
    const formHapusPengeluaran = document.querySelector("#formHapusData");
    if (formHapusPengeluaran != null) {
        formHapusPengeluaran.addEventListener('submit', event => {
            event.preventDefault()
            let action = formHapusPengeluaran.getAttribute('action')
            serialized = serialize(formHapusPengeluaran)

            axios.post(action, serialized)
                .then(function (response) {
                    if (response.data.status) {
                        window.location.reload();
                        toastr.success(response.data.msg)
                    } else {
                        toastr.error(response.data.msg)
                    }
                });
        })
    }

    // crud akun
    const formUbahakun = document.querySelector("#formUbahAkun");
    if (formUbahakun != null) {
        formUbahakun.addEventListener('submit', event => {
            event.preventDefault()
            let action = formUbahakun.getAttribute('action')
            serialized = serialize(formUbahakun)

            let nm_lengkap = document.querySelector("input[name='nm_lengkap']");
            let username = document.querySelector("input[name='username']");
            let passlama = document.querySelector("input[name='passlama']");
            let passbaru = document.querySelector("input[name='passbaru']");
            let check = true;

            if (nm_lengkap.value == '' || username.value == '' || passlama.value == '' || passbaru.value == '') check = false;

            if (check) {
                axios.post(action, serialized)
                    .then(function (response) {
                        if (response.data.status) {
                            window.location.reload();
                            toastr.success(response.data.msg)
                        } else {
                            toastr.error(response.data.msg)
                        }
                    });
            } else {
                toastr.error('Gagal simpan data,  masih ada data yang kosong')
            }
        })
    }

    // crud import db
    // importDB
    const formimportDB = document.querySelector("#formimportDB");
    if (formimportDB != null) {
        formimportDB.addEventListener('submit', event => {
            event.preventDefault()
            let items = document.querySelector("#dbname-import");
            let valueFile = items.value;
            let action = formimportDB.getAttribute('action')

            if (valueFile == "") {
                toastr.error('Data file masih kosong')
            } else {
                let formData = new FormData();
                formData.append("file", items.files[0]);

                axios.post(action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(function (response) {
                        console.log(response);
                        if (response.status) {
                            toastr.success(response.data.msg)
                            window.location.href = baseUrl + '/auth/logout';
                        } else
                            toastr.error(response.data.msg)

                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        })
    }

    //## Deklarasi

    $('.select2').select2()
    $("#example1, #tabelSaldo").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "desc"]]
    })

})(window, jQuery)
