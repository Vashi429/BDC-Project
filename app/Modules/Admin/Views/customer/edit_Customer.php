<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row mb-4">
				<div class="col-9">
					<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url() . '/admin/dashboard' ?>">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo base_url() . '/admin/customer'; ?>">View Customer</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4>Edit Customer</h4>
						<p class="mb-0 text-muted tx-13"></p>
					</div>
					<div class="card-body">
						<form action="javascript:void(0);" enctype="multipart/form-data" method="POST" id="edit_customer_form">
							<div class="row row-sm">
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
									<div class="control-label">Customer Type <span class="text-danger">*</span></div>
									<div class="custom-switches-stacked mt-2 d-block">
										<label class="custom-switch pl-0">
										<input type="radio" name="var_customer_type" value="Business" class="custom-switch-input" <?php if($data['var_customer_type'] == 'Business'){ echo "checked";}?>>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">Business</span>
										</label>
										<label class="custom-switch">
										<input type="radio" name="var_customer_type" value="Individual" class="custom-switch-input" <?php if($data['var_customer_type'] == 'Individual'){ echo "checked";}?>>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">Individual</span>
										</label>
									</div>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_name">Name <span class="text-danger">*</span></label>
										<input class="form-control" id="var_name" name="var_name" required="" type="text" value="<?php echo isset($data['var_name']) ? $data['var_name'] : ""; ?>">
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_phone">Phone <span class="text-danger">*</span></label>
										<input class="form-control" id="var_phone" name="var_phone" required="" maxlength="10" minlength="10" oninput="return isNumberKey(event)" onfocusout="check_unique_phone('var_phone', 'customer', <?= $data['int_glcode'] ?>)" type="text" value="<?php echo isset($data['var_phone']) ? $data['var_phone'] : ""; ?>" pattern="[0-9]*" inputmode="numeric">
										<span id="error_var_phone" class="text-danger"></span>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_alt_phone">Alt. Phone </label>
										<input class="form-control" id="var_alt_phone" name="var_alt_phone" maxlength="10" minlength="10" oninput="return isNumberKey(event)" type="text" value="<?php echo isset($data['var_alt_phone']) ? $data['var_alt_phone'] : ""; ?>" pattern="[0-9]*" inputmode="numeric">
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_email">Email <span class="text-danger">*</span></label>
										<input class="form-control" id="var_email" name="var_email" required="" type="email" onfocusout="check_unique_email('var_email', 'customer', <?= $data['int_glcode'] ?>)" value="<?php echo isset($data['var_email']) ? $data['var_email'] : ""; ?>">
										<span id="error_var_email" class="text-danger"></span>
									</div>
								</div>
								<?php if($data['var_customer_type'] == 'Business'){
									$displayClass = 'd-block';
									$attribute = 'required';
								}else{
									$displayClass = 'd-none';
									$attribute = '';
								}?>
								<div class="col-12 col-lg-4 col-md-6 <?= $displayClass; ?>" id="businessNameDiv">
									<div class="form-group">
										<label for="var_business_name">Business Name</label>
										<input class="form-control" id="var_business_name" name="var_business_name" type="text" value="<?php echo isset($data['var_business_name']) ? $data['var_business_name'] : ""; ?>" <?= $attribute ?>>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_displayname">Display Name <span class="text-danger">*</span></label>
										<input class="form-control" id="var_displayname" name="var_displayname" required="" type="text" onclick="createdisplayname()" value="<?php echo isset($data['var_displayname']) ? $data['var_displayname'] : ""; ?>">
										<div id="suggetion_display_name"></div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row row-sm">
								<div class="col-12 col-lg-8 col-md-6">
									<div class="form-group">
										<label for="var_office_address">Address <span class="text-danger">*</span></label>
										<textarea class="form-control" id="var_office_address" name="var_office_address" required><?= $data['var_office_address'] ?></textarea>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_pincode">Pincode <span class="text-danger">*</span></label>
										<input class="form-control" id="var_pincode" name="var_pincode" required="" type="text" minlength="6" maxlength="6" oninput="return isNumberKey(event)" value="<?php echo isset($data['var_pincode']) ? $data['var_pincode'] : ""; ?>" pattern="[0-9]*" inputmode="numeric">
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="fk_state">State <span class="text-danger">*</span></label>
										<select class="form-control" id="fk_state" name="fk_state" onchange="getCityList(this.value,'fk_city')">
											<option value="">Select State</option>
											<?php if (!empty($states)) {
												foreach ($states as $value) { ?>
													<option value="<?= $value['int_glcode'] ?>" <?php if($value['int_glcode'] == $data['fk_state']){ echo "selected"; } ?>><?= $value['var_title'] ?></option>
												<?php }
											} ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="fk_city">City <span class="text-danger">*</span></label>
										<select class="form-control" id="fk_city" name="fk_city">
											<option value="0" <?php if($data['fk_city']==0){ echo "selected"; } ?>>Other</option>
											<?php if (!empty($city)) {
												foreach ($city as $value) { ?>
													<option value="<?= $value['int_glcode'] ?>" <?php if($value['int_glcode'] == $data['fk_city']){ echo "selected"; } ?>><?= $value['var_title'] ?></option>
												<?php }
											} ?>
										</select>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="fk_country">Country <span class="text-danger">*</span></label>
										<select class="form-control" id="fk_country" name="fk_country">
											<?php if (!empty($country)) {
												foreach ($country as $value) { ?>
													<option value="<?= $value['int_glcode'] ?>" <?php if($value['int_glcode'] == $data['fk_country']){ echo "selected"; } ?>><?= $value['var_title'] ?></option>
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
										<label for="var_pancard">PAN Number</label>
										<input class="form-control" id="var_pancard" name="var_pancard" type="text" minlength="10" maxlength="10" onblur="validPanCardNumber('var_pancard', 'customer', <?= $data['int_glcode'] ?>, 1)" style="text-transform: uppercase;" value="<?php echo isset($data['var_pan']) ? $data['var_pan'] : ""; ?>" >
										<span id="error_var_pancard" class="text-danger"></span>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_gst">GST Number</label>
										<input class="form-control" id="var_gst" name="var_gst" type="text" minlength="15" maxlength="15" onblur="validGSTNumber('var_gst', 'customer', <?= $data['int_glcode'] ?>, 1)" style="text-transform: uppercase;" value="<?php echo isset($data['var_gst']) ? $data['var_gst'] : ""; ?>">
										<span id="error_var_gst" class="text-danger"></span>
									</div>
								</div>
								<div class="col-12 col-lg-4 col-md-6">
									<div class="form-group">
										<label for="var_gst_certi">Gst Certificate</label>
										<input class="form-control var_image_pdf" id="var_gst_certi" name="var_gst_certi" type="file" accept="image/*,application/pdf,.png,.svg,.jpeg,.png,.HEIF">
										<?php if($data['var_gst_certi']!=""){ ?>
											<a href="<?= base_url().'/uploads/customer/'.$data['var_gst_certi'] ?>" target="_blank">Open Gst Cerificate</a>
										<?php } ?>
										<input class="form-control" id="var_hgst_certi" name="var_hgst_certi" value="<?php echo $data['var_gst_certi']; ?>" type="hidden">
									</div>
								</div>
								<div class="col-12 col-lg-12 col-md-6">
									<div class="form-group">
										<label for="var_details">Other Details</label>
										<textarea class="form-control summernote" id="var_details" name="var_details"><?php echo isset($data['var_details']) ? $data['var_details'] : ""; ?></textarea>
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
	$("#fk_state").select2({
		placeholder: 'Select State',
	});
	$("#fk_city").select2({
		placeholder: 'Select City',
	});
	$("#fk_country").select2({
		placeholder: 'Select Country',
	});

    function createdisplayname(){
        var var_name = $('#var_name').val();
        var var_email = $('#var_email').val();
        var var_business_name = $('#var_business_name').val();
		$.ajax({
			url: sitepath + "/admin/customer/createdisplayname",
			type: 'POST',
			data: {var_name: var_name,var_email:var_email, var_business_name:var_business_name},
			success: function(responce) {
				$('#suggetion_display_name').html(responce);
			}
		});
    }

	$('input[name=var_customer_type]').change(function() {
		var id = $(this).val(); 
		if (id == 'Business') {
			$("#businessNameDiv").removeClass('d-none').addClass('d-block');
			$("#var_business_name").attr('required', 'required');
		}else{
			$("#businessNameDiv").addClass('d-none').removeClass('d-block');
			$("#var_business_name").removeAttr('required');

		}
	});

	function selectedDisplayname(clickedElement) {
		$("#var_displayname").val($(clickedElement).text());
		$('#suggetion_display_name').html('');
	}

	$('#edit_customer_form').on('submit', function (e) {
		e.preventDefault();
		id = '<?= $data['int_glcode'] ?>';
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/customer/updateCustomer/'+id,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#edit_customer_form').css("opacity",".5");
			},	
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/customer";
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#edit_customer_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
		
	});


</script>