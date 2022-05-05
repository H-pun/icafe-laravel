<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Scan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli'>
    <link rel="stylesheet" href="{{ asset('src/css/style.css') }}">
</head>

<body>
    <div class="pt-5">
        <div class="logo">
            <img src="{{ asset('src/img/sgdesktoplogo.png') }}" style="width:200px">
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 id="username" class="card-title text-center">Hi,</h5>
                            <h6 class="card-subtitle text-center">Saldo Rp30000,-</h6>
                        </div>
                        <div class="card card-body">
                            <p class="card-text text-center">Silakan scan QR Code yang tampil di layar. Jangan lupa pastikan saldo kamu cukup sebelum scan QR Code.</p>
                            <div class="form-group pt-1">
                                <button class="btn btn-primary btn-block btn-primary" id="add">SCAN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="particles"></div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{ asset('src/js/particle.js') }}"></script>
<script>
    $(document).ready(function() {
        liff
            .init({
                liffId: "1655333907-N22P0pXe"
            })
            .then(() => {
                if (!liff.isInClient()) {
                    // window.location.href = '403';
                } else {
                    liff.getProfile().then(function(profile) {
                        $("#username").text("Hi, " + profile.displayName);
                        $("#add").click(function(e) {
                            e.preventDefault(); // avoid to execute the actual submit of the form.
                            liff.scanCodeV2()
                                .then(({
                                    value
                                }) => {
                                    var formData = new FormData();
                                    value = JSON.parse(value)
                                    formData.append('userId', profile.userId)
                                    formData.append('token', value.token)
                                    formData.append('type', value.billing_type)

                                    Swal.fire({
                                        title: 'Please Wait !',
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        onBeforeOpen: () => {
                                            Swal.showLoading()
                                        },
                                    });
                                    $.ajax({
                                        url: "api/billing/start",
                                        data: formData,
                                        method: 'POST',
                                        processData: false,
                                        contentType: false,
                                        enctype: 'multipart/form-data',
                                        success: (data) => {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Pembelian Berhasil',
                                                allowOutsideClick: false,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                liff.closeWindow();
                                            });
                                        },
                                        error: (data) => {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: 'Something went wrong!',
                                            });
                                        }
                                    });
                                })
                                .catch((err) => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: err,
                                    });
                                });
                        });
                    }).catch((error) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error getting profile: ' + error,
                        });
                        liff.closeWindow()
                    });
                }
            }).catch((err) => {
                alert(err.code + " " + err.message);
            });

    });
</script>

</html>