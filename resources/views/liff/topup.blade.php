<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('src/css/style.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>iCafe | Top Up</title>
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
                    <h1>Top Up</h1>
                    <a a href="#" class="btn" id="add"><i class="uil uil-qrcode-scan"></i></a>
                    <div class="input_div">
                        <input class="input" type="text" id="code" placeholder="Redeem Code...">
                        <button class="addButton" id="redeem">Redeem</i></button>
                    </div>
                    <p>Click icon to scan QR Code or insert the redeem code to claim.</p>
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

        function verify(formData) {
            Swal.fire({
                title: 'Please Wait !',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                url: "api/redeem/verify",
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success: (data) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Top Up Berhasil',
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
        }

        liff
            .init({
                liffId: "1655333907-98EgXyMR"
            })
            .then(() => {
                if (!liff.isInClient()) {
                    window.location.href = '403';
                } else {
                    liff.getProfile().then(function(profile) {
                        var formData = new FormData();
                        formData.append('userId', profile.userId);
                        formData.append('displayName', profile.displayName);
                        formData.append('pictureUrl', profile.pictureUrl);
                        $("#redeem").click(function(e) {
                            e.preventDefault(); // avoid to execute the actual submit of the form.
                            // formData.append('code', value.code)
                            formData.append('code', $("#code").val())
                            verify(formData);
                        });
                        $("#add").click(function(e) {
                            e.preventDefault(); // avoid to execute the actual submit of the form.
                            liff.scanCodeV2()
                                .then(({
                                    value
                                }) => {
                                    value = JSON.parse(value)
                                    formData.append('code', value.code)
                                    verify(formData);
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