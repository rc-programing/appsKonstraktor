(function (window, $, undefined) {

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

    // hapus saldo
    const formHapusSaldo = document.querySelector("#formHapusData");
    if (formHapusSaldo != null) {
        formHapusSaldo.addEventListener('submit', event => {
            event.preventDefault()
            let action = formHapusSaldo.getAttribute('action')
            serialized = serialize(formHapusSaldo)

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

})(window, jQuery)
