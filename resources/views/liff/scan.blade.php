<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('src/css/style.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>iCafe | Scan</title>
</head>

<body>
    <div class="preloader">
        <div class="loading">
            <!-- <img src="https://i.pinimg.com/originals/8f/c3/21/8fc32146cbf72ae17430e05ecc8b61be.gif" width="150"> -->
            <img src="https://c.tenor.com/pTP-f4a0rhIAAAAi/bunny-drink.gif" width="150">
            <p>Please Wait...</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <h1>Scan QR</h1>
                    <a href="#" class="btn" id="add"><i class="uil uil-qrcode-scan"></i></a>
                    <p>Click icon above to scan your QR Code on the PC screen.</p>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".preloader").fadeOut();
        }, 2500);
        liff
            .init({
                liffId: "1655333907-N22P0pXe"
            })
            .then(() => {
                if (!liff.isInClient()) {
                    // window.location.href = 'notline';
                } else {
                    liff.getProfile().then(function(profile) {
                        $("#add").click(function(e) {
                            e.preventDefault();
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
                                        error: ({
                                            responseJSON
                                        }) => {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: responseJSON.data,
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
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                });
            });
    });
</script>

</html>