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
	<div>
	<a href="<?=base_url("index.php/logoutadmin");?>" class="btn btn-default btn-flat">Sign out</a>
	</div>
	<div>Current Admin:</div>
	<div><b><span id="currentadmin"></span></b></div>
	<div>List Admin:</div>
	<div id="listadmin"></div>	

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


	$.ajax({
		url: "<?php echo base_url() ?>index.php/get_all_admin",
		dataType: 'json',
		success: function (response) {
			for (var i = 0; i < response.length; i++) {
				$("#listadmin").append("<div><b>"+response[i].username+"</b></div>");
			}
		},
		error: function (xhr, status, error) {
			alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
		}
	});


</script>

</html>
