<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/challan">View Challan</a></li>
					<li class="breadcrumb-item">Edit Challan</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Challan</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="edit_challan_form">
						<div class="row">
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_vendor">Vendor <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_vendor" id="fk_vendor" required>
										<option value=""></option>
										<?php if(!empty($vendorData)){ 
											foreach($vendorData as $value){ 
												$selected = "";
												if($value['int_glcode']==$data['fk_vendor']){
													$selected = 'selected';
												}
												?>
												<option value="<?= $value['int_glcode'] ?>" <?= $selected ?>><?= $value['var_name'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
						
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_project">Project <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_project" id="fk_project" required onchange="getItemData()">
										<option value=""></option>
										<?php if(!empty($projectData)){ 
											foreach($projectData as $value){ 
												$selected = "";
												if($value['int_glcode']==$data['fk_project']){
													$selected = 'selected';
												}
												?>
												<option value="<?= $value['int_glcode'] ?>" <?= $selected ?>><?= $value['var_project'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_challan_id">Challan Id <span class="text-danger">*</span></label>
									<input type="text" readonly name="var_challan_id" id="var_challan_id" class="form-control" value="<?= $data['var_challan_id'] ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_item">Item <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_item" id="fk_item" required>
										<option value=""></option>
										<?php if(!empty($itemData)){ 
											foreach($itemData as $value){ 
												$selected = "";
												if($value['int_glcode']==$data['fk_item']){
													$selected = 'selected';
												}
												?>
												<option value="<?= $value['int_glcode'] ?>" <?= $selected ?> data-due-stock = "<?= $value['var_due_stock'] ?>"><?= $value['var_item']. ' ('.$value['var_unit'].')' ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_quantity">Quantity <span class="text-danger">*</span></label>
									<input type="text" name="var_quantity" id="var_quantity" maxlength="10" class="form-control" required oninput="isNumberKey(event)" value="<?= $data['var_quantity'] ?>">
									<span class="text-danger" id="error_var_quantity"></span>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_amount">Amount (<?= CURRENCY_ICON ?>)<span class="text-danger">*</span></label>
									<input type="text" name="var_amount" id="var_amount" maxlength="10" class="form-control" required oninput="isNumberKeyWithDot(event)" value="<?= $data['var_amount'] ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_image">Image <span class="text-danger">*</span></label>
									<input class="form-control" id="var_image" name="var_image" type="file">
									<?php if($data['var_image']!=""){ ?>
										<a href="<?= base_url().'/uploads/challan/'.$data['var_image'] ?>" target="_blank">Open challan</a>
									<?php } ?>
									<input class="form-control" id="hvar_image" name="hvar_image" value="<?php echo $data['var_image']; ?>" type="hidden">
								</div>
							</div>
							<input type="hidden" name="fk_expense" id="fk_expense" value="<?= $data['fk_expense'] ?>">

							<hr/>
						</div>
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	$("#fk_vendor").select2({
		placeholder: 'Select Vendor',
	});
	$("#fk_project").select2({
		placeholder: 'Select Project',
	});
	$("#fk_superviser").select2({
		placeholder: 'Select Supervisor',
	});

	$("#fk_item").select2({
		placeholder: 'Select Item',
	});

	$(document).on('change','#fk_item', function(){
		var due_stock = $(this).find(':selected').attr('data-due-stock')
		console.log(due_stock);
		$("#var_quantity").attr('max', due_stock);
	});
	
	$(document).ready(function(){
		var due_stock = $("#fk_item").find(':selected').attr('data-due-stock')
		console.log(due_stock);
		$("#var_quantity").attr('max', due_stock);
	});

	var old_qty = $("#var_quantity").val();
	$(document).on('input', '#var_quantity', function(){
		var var_quantity = $(this).val();
		var maxVal = $(this).attr('max');
		var totalStock = parseFloat(maxVal) + parseFloat(old_qty);
		console.log(totalStock);
		if(parseFloat(var_quantity) > parseFloat(totalStock)){
			$("#error_var_quantity").text('Maximum quantity is available for this project item is '+totalStock+'.');
			$('.submit_save').attr("disabled","disabled");
		}else{
			$("#error_var_quantity").text('');
			$(".submit_save").removeAttr("disabled");
		}
	});


	function getItemData(){
		var fk_project = $("#fk_project").val();
		$.ajax({
			url: sitepath + "/admin/challan/getProjectItem",
			method: 'POST',
			data: {fk_project:fk_project},
			success:function(response){
				$('#fk_item').html(response);
			}
		});
	}


	$('#edit_challan_form').on('submit', function (e) {
		var id = '<?= $data['int_glcode'] ?>';
		e.preventDefault();
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/challan/updateRecord/'+id,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#edit_challan_form').css("opacity",".5");
			},
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/challan";
				}else{
					$('#edit_challan_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
	});
</script>