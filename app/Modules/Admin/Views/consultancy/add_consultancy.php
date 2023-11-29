<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/consultancy">View Consultancy</a></li>
					<li class="breadcrumb-item">Add Consultancy</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Add Consultancy</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_consultancy_form">
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
									<input class="form-control" id="var_phone" name="var_phone" required="" maxlength="10" minlength="10" oninput="return isNumberKey(event)" onfocusout="check_unique_phone('var_phone', 'consultancy')" type="text"  pattern="[0-9]*" inputmode="numeric">
									<span id="error_var_phone" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_buiness_name">Business Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_buiness_name" name="var_buiness_name" required="" type="text">
								</div>
							</div>
						</div>
						<hr>
						<div class="row row-sm">
        					<div class="col-12 col-lg-8 col-md-6">
        						<div class="form-group">
        							<label for="var_address">Address <span class="text-danger">*</span></label>
        							<textarea class="form-control" id="var_address" name="var_address" required></textarea>
        						</div>
        					</div>
							<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label for="var_pincode">Pincode <span class="text-danger">*</span></label>
        							<input class="form-control" id="var_pincode" name="var_pincode" required="" type="text" minlength="6" maxlength="6" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
        						</div>
        					</div>
							<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label class="form-label mg-b-0" for="fk_state ">State <span class="text-danger">*</span></label>
        							<select class="form-control" id="fk_state" name="fk_state" onchange="getCityList(this.value,'fk_city')" required>
        							    <option value="">Select State</option>
        								<?php if (!empty($states)) {
        									foreach ($states as $value) { ?>
        										<option value="<?= $value['int_glcode'] ?>"><?= $value['var_title'] ?></option>
        									<?php }
        								} ?>
        							</select>
        						</div>
        					</div>
        					<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label class="form-label mg-b-0" for="fk_city">City <span class="text-danger">*</span></label>
        							<select class="form-control" id="fk_city" name="fk_city" required>
        							    <option value="">Select City</option>
        							</select>
        						</div>
        					</div>
        					<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label for="fk_country">Country <span class="text-danger">*</span></label>
        							<select class="form-control" id="fk_country" name="fk_country" required>
        								<?php if (!empty($country)) {
        									foreach ($country as $value) { ?>
        										<option value="<?= $value['int_glcode'] ?>"><?= $value['var_title'] ?></option>
        									<?php }
        								} ?>
        							</select>
        						</div>
        					</div>
						</div>
						<hr>
						<div class="row row-sm">
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pan">Pan Number</label>
									<input class="form-control" id="var_pancard" name="var_pancard" type="text" minlength="10" maxlength="10" onblur="validPanCardNumber('var_pancard','consultancy','',1)" style="text-transform: uppercase;">
									<span id="error_var_pancard" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_gst">GST Number</label>
									<input class="form-control" id="var_gst" name="var_gst" type="text" minlength="15" maxlength="15" onblur="validGSTNumber('var_gst','consultancy', '', 1)" style="text-transform: uppercase;">
							        <span id="error_var_gst" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pan">Gst Certificate</label>
									<input class="form-control var_image_pdf" id="var_gst_certi" name="var_gst_certi" type="file" accept="image/*,application/pdf,.png,.svg,.jpeg,.png,.HEIF">
								</div>
							</div>
						</div>
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Consultancy</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>


<script>

	$("#fk_state").select2({
		placeholder: 'Select State',
	});
	$("#fk_city").select2({
		placeholder: 'Select City',
	});

	$("#fk_country").select2({
		placeholder: 'Select Country',
	});

	$('#add_consultancy_form').on('submit', function (e) {
		e.preventDefault();
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/consultancy/insertRecord',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#add_consultancy_form').css("opacity",".5");
			},
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/consultancy";
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#add_consultancy_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
	});


</script>