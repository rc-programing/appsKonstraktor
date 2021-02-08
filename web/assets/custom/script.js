(function (window, $, undefined) {

    // ## function
    var baseUrl = window.location.origin;

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

    // view Akun
    let modalAkun = document.querySelector("#showModalUbahakun");
    if (modalAkun != null) {
        modalAkun.onclick = function () {
            $('#modal-ubah-akun').modal('show');
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

    //## Deklarasi
    $('.select2').select2()
    $("#example1, #tabelSaldo").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "desc"]]
    })

})(window, jQuery)
