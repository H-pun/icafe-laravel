<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
	<title>Tambah Tugas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli'>
	<link rel="stylesheet" href="{{ asset('src/css/style.css') }}">
</head>

<body>
	<div class="pt-5">
		<div class="logo">
			<img src="{{ asset('src/img/iclass.png') }}">
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-5 mx-auto">
					<div class="card card-body">

						<form id="submitForm" action="/login" method="post" data-parsley-validate=""
							data-parsley-errors-messages-disabled="true" novalidate="" _lpchecked="1"><input
								type="hidden" name="_csrf" value="7635eb83-1f95-4b32-8788-abec2724a9a4">
							<div class="form-group required">
								<label for="username">Tugas</label>
								<input type="text" class="form-control text-lowercase" id="username" required=""
									name="username" value="">
							</div>
							<div class="form-group">
								<label>Detail Tugas</label>
								<textarea class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group pt-1">
								<button class="btn btn-primary btn-block" type="submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="particles"></div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<script src="{{ asset('src/js/particle.js') }}"></script>
<script>
	$(document).ready(function () {
		// const liffId = "1655333907-dxZ76e05";
		// liff.init({ liffId: liffId }).then(() => {
		// 	$.getJSON("../json/tugas.json").then((data) => {
				// liff.sendMessages([{
				// 	'type': 'flex',
				// 	'altText': 'Tambah tugas',
				// 	'contents': JSON.parse(data)
				// }]).then(function () {
				// 	window.alert('Message sent');
				// 	liff.closeWindow();
				// }).catch(function (error) {
				// 	window.alert('Error sending message: ' + error);
				// });
		// 	});
		// });
	});
</script>

</html>