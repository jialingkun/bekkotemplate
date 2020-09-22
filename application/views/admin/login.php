<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="<?=base_url("dist/css/bootstrap.min.css");?>">
	<link rel="stylesheet" href="<?=base_url("dist/css/style.css");?>">

	<title>BEKKO</title>
</head>

<body>
	<div class="container">
		<h2 class="text-center"><small>Login</small></h2>
		<p class="text-center grey-text"><small>As Admin</small></p>
		<form id="form" method="POST" onsubmit="submitform(event)">
			<div class="form-group">
				<label for="usr">Username:</label>
				<input type="text" class="form-control" name="username" required>
			</div>
			<div class="form-group">
				<label for="usr">Password:</label>
				<input type="password" class="form-control" name="password" required>
			</div>
			<button id="submit" type="submit" class="btn btn-primary center-item">Sign In</button>
		</form>
	</div>
</body>
<script src="<?=base_url("dist/js/jquery.min.js");?>"></script>
<script src="<?=base_url("dist/js/popper.min.js");?>"></script>
<script src="<?=base_url("dist/js/bootstrap.min.js");?>"></script>

<script>
	function submitform(event) {
		event.preventDefault();
		var dataString = $("#form").serialize();
		$("#submit").prop("disabled", true);
		$.ajax({
			url: "<?php echo base_url() ?>index.php/cekloginadmin",
			type: 'POST',
			data: dataString,
			success: function (response) {
				if (response == "berhasil login") {
					window.location.replace("<?php echo base_url() ?>index.php/dashboardadmin");
				} else {
					alert(response);
					$("#submit").prop("disabled", false);
				}
			},
			error: function (xhr, status, error) {
				alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
				$("#submit").prop("disabled", false);
			}
		});
	}

</script>


</html>
