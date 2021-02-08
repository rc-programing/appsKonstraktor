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

})(window, jQuery)
