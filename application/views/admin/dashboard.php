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
		<div>
			<a href="<?=base_url("admin/logoutadmin");?>" class="btn btn-default btn-flat">Sign out</a>
		</div>
		<div>Current Admin:</div>
		<div><b><span id="currentadmin"></span></b></div>
		<div>List Admin:</div>
		<div id="listadmin"></div>
		<div>Add Admin:</div>
		<form id="form" onsubmit="submitform(event)">
			<div class="form-group">
				<label for="usr">Username</label>
				<input type="text" class="form-control" name="username">
			</div>
			<div class="form-group">
				<label for="usr">Password</label>
				<input type="password" class="form-control" name="password">
			</div>
			<button id="submit" type="submit" class="btn btn-primary center-item">Add</button>
		</form>
	</div>
</body>
<script src="<?=base_url("dist/js/jquery.min.js");?>"></script>
<script src="<?=base_url("dist/js/popper.min.js");?>"></script>
<script src="<?=base_url("dist/js/bootstrap.min.js");?>"></script>

<?php $this->load->view("function_cookie");?>

<script>

	// setCookie('testcookie','testvalue',function(){
	// 	alert('cookie created');
	// });

	getLoginCookie('adminCookie', function(response){
		$("#currentadmin").html(response);
	});


	function refreshlist(){
		$.ajax({
			url: "<?php echo base_url() ?>admin/get_all_admin",
			dataType: 'json',
			success: function (response) {
				$("#listadmin").empty();
				for (var i = 0; i < response.length; i++) {
					$("#listadmin").append("<div><b>"+response[i].username+"</b></div>");
				}
			},
			error: function (xhr, status, error) {
				alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
			}
		});
	}

	refreshlist();



	function submitform(event) {
		event.preventDefault();
		var dataString = $("#form").serialize();
		$("#submit").prop("disabled", true);
		$.ajax({
			url: "<?php echo base_url() ?>admin/insert_admin",
			type: 'POST',
			data: dataString,
			success: function (response) {
				if (response == "success") {
					refreshlist();
				} else {
					alert(response);
				}
				$("#submit").prop("disabled", false);
			},
			error: function (xhr, status, error) {
				alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
				$("#submit").prop("disabled", false);
			}
		});
	}


</script>

</html>
