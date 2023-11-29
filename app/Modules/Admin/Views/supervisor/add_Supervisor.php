<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/supervisor">View Supervisor</a></li>
					<li class="breadcrumb-item">Add Supervisor</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Add Supervisor</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0);" enctype="multipart/form-data" method="POST" id="add_supervisor_form">
						<div class="row row-sm">
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_name">Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_name" name="var_name" required="" type="text">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_phone">Phone <span class="text-danger">*</span></label>
									<input class="form-control" id="var_phone" name="var_phone" required="" maxlength="10" minlength="10" oninput="return isNumberKey(event)" type="text" onfocusout="check_unique_phone('var_phone', 'supervisor', '', 0)" pattern="[0-9]*" inputmode="numeric">
									<span id="error_var_phone" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_email">Email</label>
									<input class="form-control" id="var_email" name="var_email" type="email" onfocusout="check_unique_email('var_email', 'supervisor', '', 1)">
									<span id="error_var_email" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_username">User Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_username" name="var_username" required="" type="text" onfocusout="check_unique_UserName('var_username', 'supervisor', '', 0)">
									<span id="error_var_username" class="text-danger"></span>
								</div>
							</div>
        					<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label for="var_password">Password <span class="text-danger">*</span></label>
        							<div class="position-relative">
            							<input class="form-control" id="var_password" name="var_password" required="" type="password" style="padding-right: 43px !important;">
            						    <a href="javascript:void(0)" class="eye-icon" onclick="passwordHide()"><i class="fa fa-eye" aria-hidden="true"></i></a>
        							</div>
        						</div>
        					</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pancard">PAN Number <span class="text-danger">*</span></label>
									<input class="form-control" id="var_pancard" name="var_pancard" required="" type="text" minlength="10" maxlength="10" onblur="validPanCardNumber('var_pancard', 'supervisor', '', 0)" style="text-transform: uppercase;">
							        <span id="error_var_pancard" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_aadhar">Aadhar Card Number <span class="text-danger">*</span></label>
									<input class="form-control" id="var_aadhar" name="var_aadhar" required="" type="text" minlength="12" maxlength="12" onblur="validAAdharCardNumber('var_aadhar', 'supervisor', '', 0)" onkeypress="return isNumberKey(event)">
							        <span id="error_var_aadhar" class="text-danger"></span>
								</div>
							</div>
						</div>
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Supervisor</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	
	function passwordHide(){
		var pass = document.getElementById("var_password");
		if(pass.type == 'password'){
			pass.type = 'text';
		}else{
			pass.type = 'password';
		}
	}

	$('#add_supervisor_form').on('submit', function (e) {
		e.preventDefault();
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/supervisor/insertRecord',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#add_supervisor_form').css("opacity",".5");
			},
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/supervisor";
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#add_supervisor_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
		
	});

</script>