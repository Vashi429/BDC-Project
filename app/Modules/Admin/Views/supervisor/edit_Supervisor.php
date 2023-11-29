<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row mb-4">
				<div class="col-9">
					<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url() . '/admin/dashboard' ?>">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo base_url() . '/admin/supervisor'; ?>">View Supervisor</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit Supervisor</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Supervisor</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="<?php echo base_url() . '/admin/supervisor/updateSupervisor/' . $data['int_glcode']; ?>" enctype="multipart/form-data" method="POST" id="edit_supervisor_form">
						<div class="row row-sm">
						    <div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_name">Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_name" name="var_name" required="" type="text" value="<?php echo isset($data['var_name']) ? $data['var_name'] : ""; ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_phone">Phone <span class="text-danger">*</span></label>
									<input class="form-control" id="var_phone" name="var_phone" required="" maxlength="10" minlength="10" oninput="return isNumberKey(event)" onfocusout="check_unique_phone('var_phone', 'supervisor', <?= $data['int_glcode'] ?>, 0)" type="text" value="<?php echo isset($data['var_phone']) ? $data['var_phone'] : ""; ?>"  pattern="[0-9]*" inputmode="numeric">
									<span id="error_var_phone" class="text-danger"></span>
								</div>
							</div><div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_email">Email </label>
									<input class="form-control" id="var_email" name="var_email" type="email" onfocusout="check_unique_email('var_email', 'supervisor', <?= $data['int_glcode'] ?>, 1)" value="<?php echo isset($data['var_email']) ? $data['var_email'] : ""; ?>">
									<span id="error_var_email" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_username">User Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_username" name="var_username" required="" type="text" onfocusout="check_unique_UserName('var_username', 'supervisor', <?= $data['int_glcode'] ?>, 0)" value="<?php echo isset($data['var_username']) ? $data['var_username'] : ""; ?>">
									<span id="error_var_username" class="text-danger"></span>
								</div>
							</div>
        					<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label for="var_password">Password <span class="text-danger">*</span></label>
        							<div class="position-relative">
            							<input class="form-control" id="var_password" name="var_password" required="" type="password" value="<?php echo isset($data['var_password']) ? $mylibrary->decryptPass($data['var_password']) : ""; ?>" style="padding-right: 43px !important;">
            						    <a href="javascript:void(0)" class="eye-icon" onclick="passwordHide()"><i class="fa fa-eye" aria-hidden="true"></i></a>
        							</div>
        						</div>
        					</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pancard">PAN Number <span class="text-danger">*</span></label>
									<input class="form-control" id="var_pancard" name="var_pancard" required="" type="text" minlength="10" maxlength="10" onblur="validPanCardNumber('var_pancard','supervisor',<?=$data['int_glcode']?>,0)" style="text-transform: uppercase;" value="<?php echo isset($data['var_pancard']) ? $data['var_pancard'] : ""; ?>">
							        <span id="error_var_pancard" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_aadhar">Aadhar Card Number <span class="text-danger">*</span></label>
									<input class="form-control" id="var_aadhar" name="var_aadhar" required="" type="text" minlength="12" maxlength="12" onblur="validAAdharCardNumber('var_aadhar','supervisor',<?=$data['int_glcode']?>,0)" onkeypress="return isNumberKey(event)" value="<?php echo isset($data['var_aadhar']) ? $data['var_aadhar'] : ""; ?>">
							        <span id="error_var_aadhar" class="text-danger"></span>
								</div>
							</div>
						</div>
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" name="submit" value="submit" type="submit">Save</button>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
</div>
<script type="text/javascript">
	
	function passwordHide(){
		var pass = document.getElementById("var_password");
		if(pass.type == 'password'){
			pass.type = 'text';
		}else{
			pass.type = 'password';
		}
	}
    
	$('#edit_supervisor_form').on('submit', function (e) {
		e.preventDefault();
		id = '<?= $data['int_glcode'] ?>';
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/supervisor/updateSupervisor/'+id,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#edit_supervisor_form').css("opacity",".5");
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
					$('#edit_supervisor_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
		
	});

</script>