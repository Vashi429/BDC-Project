<div class="content-wrapper">
	<div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row mb-4">
				<div class="col-9">
					<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url() . '/admin/dashboard' ?>">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo base_url() . '/admin/companyProfile'; ?>">View Company Profile</a></li>
							<li class="breadcrumb-item active" aria-current="page">Edit Company Profile</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Company Profile</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0);" enctype="multipart/form-data" method="POST" id="edit_company_profile_form">
						<div class="row row-sm">
							<div class="col-12 col-lg-6 col-md-6">
								<div class="form-group">
									<label for="var_name">Name <span class="text-danger">*</span></label>
									<input class="form-control" id="var_name" name="var_name" required="" type="text" value="<?php echo isset($data['var_name']) ? $data['var_name'] : ""; ?>">
								</div>
							</div>
							<div class="col-12 col-lg-6 col-md-6">
								<div class="form-group">
									<label for="var_phone">Phone <span class="text-danger">*</span></label>
									<input class="form-control" id="var_phone" name="var_phone" required="" maxlength="10" minlength="10" oninput="return isNumberKey(event)" onfocusout="check_unique_phone('var_phone', 'companyProfile', <?= $data['int_glcode'] ?>)" type="text" value="<?php echo isset($data['var_phone']) ? $data['var_phone'] : ""; ?>"  pattern="[0-9]*" inputmode="numeric">
									<span id="error_var_phone" class="text-danger"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row row-sm">
        					<div class="col-12 col-lg-8 col-md-6">
        						<div class="form-group">
        							<label for="var_address">Address <span class="text-danger">*</span></label>
        							<textarea class="form-control" id="var_address" name="var_address" required><?php echo isset($data['var_address']) ? $data['var_address'] : ""; ?></textarea>
        						</div>
        					</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pincode">Pincode <span class="text-danger">*</span></label>
									<input class="form-control" id="var_pincode" name="var_pincode" required="" type="text" minlength="6" maxlength="6" oninput="return isNumberKey(event)" value="<?php echo isset($data['var_pincode']) ? $data['var_pincode'] : ""; ?>"  pattern="[0-9]*" inputmode="numeric">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
        						<div class="form-group">
        							<label class="form-label mg-b-0" for="fk_state ">State <span class="text-danger">*</span></label>
        							<select class="form-control" id="fk_state" name="fk_state" onchange="getCityList(this.value,'fk_city')" required>
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
        							<label class="form-label mg-b-0" for="fk_city">City <span class="text-danger">*</span></label>
        							<select class="form-control" id="fk_city" name="fk_city" required>
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
        							<select class="form-control" id="fk_country" name="fk_country" required>
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
									<label for="var_pan">Pan Number</label>
									<input class="form-control" id="var_pancard" name="var_pancard" type="text" minlength="10" maxlength="10" onblur="validPanCardNumber('var_pancard', 'companyProfile', <?= $data['int_glcode'] ?>, 1)" style="text-transform: uppercase;" value="<?php echo isset($data['var_pan']) ? $data['var_pan'] : ""; ?>">
									<span id="error_var_pancard" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_gst">GST Number</label>
									<input class="form-control" id="var_gst" name="var_gst" type="text" minlength="15" maxlength="15" onblur="validGSTNumber('var_gst', 'companyProfile', <?= $data['int_glcode'] ?>, 1)" style="text-transform: uppercase;" value="<?php echo isset($data['var_gst']) ? $data['var_gst'] : ""; ?>">
							        <span id="error_var_gst" class="text-danger"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_pan">Gst Certificate</label>
									<input class="form-control var_image_pdf" id="var_gst_certi" name="var_gst_certi" type="file" accept="image/*,application/pdf,.png,.svg,.jpeg,.png,.HEIF">
									<?php if($data['var_gst_certi']!=""){ ?>
										<a href="<?= base_url().'/uploads/company_profile/'.$data['var_gst_certi'] ?>" target="_blank">Open Gst Cerificate</a>
									<?php } ?>
									<input class="form-control" id="var_hgst_certi" name="var_hgst_certi" value="<?php echo $data['var_gst_certi']; ?>" type="hidden">
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

	$('#edit_company_profile_form').on('submit', function (e) {
		e.preventDefault();
		id = '<?= $data['int_glcode'] ?>';
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/companyProfile/updateCompanyProfile/'+id,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#edit_company_profile_form').css("opacity",".5");
			},	
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/companyProfile";
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#edit_company_profile_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
		
	});


</script>