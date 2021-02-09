(function (window, $, undefined) {

    let baseUrl = window.location.origin;

    function allData(url) {
        const promise = axios.get(url)
        const dataPromise = promise.then((response) => response.data)
        return dataPromise
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

    // View cari data
    let btnSearch = document.querySelector("#cariTgl");
    if (btnSearch != null) {
        btnSearch.onclick = function () {
            let tgl = document.querySelector("input[name='tanggalCari']").value;
            window.location.href = baseUrl + "/page/cetak_harian/" + tgl;
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
})(window, jQuery)
