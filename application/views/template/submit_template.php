<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="<?=base_url("dist/css/bootstrap.min.css");?>">
	<link rel="stylesheet" href="<?=base_url("dist/css/style.css");?>">

	<title>BEKKO</title>
	<style>
		.overlay-loader {
			position: fixed; /* Sit on top of the page content */
			width: 100%; /* Full width (cover the whole page) */
			height: 100%; /* Full height (cover the whole page) */
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(255,255,255,1); /* Black background with opacity */
			z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
			cursor: pointer; /* Add a pointer on hover */
		}

		.loadercontent{
			position: absolute; 
			left: 0; 
			right: 0;
			margin-left: auto; 
			margin-right: auto; 
			top: 35%;
			width: 300px; /* Need a specific value to work */
		}

		.loader {
			border: 8px solid #f3f3f3; /* Light grey */
			border-top: 8px solid #3498db; /* Blue */
			border-radius: 50%;
			width: 50px;
			height: 50px;
			animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
</head>

<body>
	<div class="overlay-loader" style="display: none">
		<div class = "loadercontent" id="loadingloader">
			<div class="loader center-item"></div>
			<h5 id="loadingtext" class="loader-text text-center">Mohon tunggu beberapa menit</h5>
		</div>
	</div>


	<div class="container">

		<div class="row">
			<div class="col-sm-4">
				<h2 class="text-center"><small>Submit with loading</small></h2>
				<form id="formloading" onsubmit="formloadingstatus(event)">
					<div class="form-group">
						<label for="usr">Username</label>
						<input type="text" class="form-control" name="username">
					</div>
					<div class="form-group">
						<label for="usr">Password</label>
						<input type="password" class="form-control" name="password">
					</div>
					<button id="submitloading" type="submit" class="btn btn-primary center-item">Submit</button>
				</form>
			</div>





			<div class="col-sm-4">
				<h2 class="text-center"><small>Submit with status progress</small></h2>
				<form id="formstatus" onsubmit="formsubmitstatus(event)">
					<div class="form-group">
						<label for="usr">Username</label>
						<input type="text" class="form-control" name="username">
					</div>
					<div class="form-group">
						<label for="usr">Password</label>
						<input type="password" class="form-control" name="password">
					</div>
					<button id="submitstatus" type="submit" class="btn btn-primary center-item">Submit</button>
				</form>
			</div> 




			

			<div class="col-sm-4">
				<h2 class="text-center"><small>Submit with upload progress</small></h2>
				<form id="formupload" enctype="multipart/form-data" onsubmit="formsubmitupload(event)">
					<div class="form-group">
						<label for="usr">Username</label>
						<input type="text" class="form-control" name="username">
					</div>
					<div class="form-group">
						<label for="usr">File</label>
						<input type="file" class="form-control" name="userfile" required>
					</div>
					<button id="submitupload" type="submit" class="btn btn-primary center-item">Submit</button>
				</form>
				<div id="progress" style="margin-top: 50px; display: none">
					<p></p>
					<div class="progress" style="height: 15px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>
</body>
<script src="<?=base_url("dist/js/jquery.min.js");?>"></script>
<script src="<?=base_url("dist/js/popper.min.js");?>"></script>
<script src="<?=base_url("dist/js/bootstrap.min.js");?>"></script>



<script>
	function formloadingstatus(event) {
		event.preventDefault();
		var dataString = $("#formloading").serialize();
		$("#submitloading").prop("disabled", true);
		$(".overlay-loader").show();
		$.ajax({
			url: "<?php echo base_url() ?>template/submit_loading",
			type: 'POST',
			data: dataString,
			success: function (response) {
				alert(response);
				$("#submitloading").prop("disabled", false);
				$(".overlay-loader").hide();
			},
			error: function (xhr, status, error) {
				alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
				$("#submitloading").prop("disabled", false);
				$(".overlay-loader").hide();
			}
		});
	}
</script>



<script>
	function formsubmitstatus(event) {
		event.preventDefault();
		var dataString = $("#formstatus").serialize();
		$("#submitstatus").prop("disabled", true);
		$(".overlay-loader").show();
		$.ajax({
			url: "<?php echo base_url() ?>template/submit_status",
			type: 'POST',
			data: dataString,
			success: function (response) {
				clearInterval(progresstimer);
				alert(response);
				$("#submitstatus").prop("disabled", false);
				$(".overlay-loader").hide();
				$("#loadingtext").html("Mohon Tunggu");	
			},
			error: function (xhr, status, error) {
				alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
				$("#submitstatus").prop("disabled", false);
				$(".overlay-loader").hide();
				$("#loadingtext").html("Mohon Tunggu");	
			}
		});



		progresstimer = setInterval(function(){
			$.ajax({
				url: "<?php echo base_url() ?>template/check_progress",
				success: function (response) {
					$("#loadingtext").html(response);	
				},
				error: function (xhr, status, error) {
					alert(status + '- ' + xhr.status + ': ' + xhr.statusText);
				}
			});
		}, 1000);
	}






	function formsubmitupload(event){
		event.preventDefault();
		formdata = new FormData($('#formupload')[0]);
		$("#submitupload").prop("disabled", true);
		$.ajax({
			xhr: function() {
				xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(e) {
					if(e.lengthComputable) {
						percent = Math.round((e.loaded / e.total) * 100);
						$('.progress-bar').attr('aria-valuenow', percent).css('width', percent + '%');
						$('.progress-bar').html(percent + '%');
						if(percent == 100) {
							$('#progress p').text('Processing file...');
						}
					}
				});
				return xhr;
			},
			type: 'post',
			url: '<?php echo base_url() ?>template/submit_upload',
			dataType: 'json',
			data: formdata,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$('#progress').show();
				$('#progress p').text('Uploading files...');
				$('.modal-footer button').prop('disabled', true);
			},
			success: function(data) {
				showAlert(data);
			},
			error: function(e) { 
				console.log(e.responseText);
			},
			complete: function() {
				$('#formupload').trigger('reset');
				$('#progress').hide();
				$('#progress p').text('');
				$('.progress-bar').attr('aria-valuenow', 0).css('width', '0%');
				$('#submitupload').prop('disabled', false);
			}
		});
	}

</script>


</html>
