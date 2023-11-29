<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/bill">View Bill</a></li>
					<li class="breadcrumb-item">Edit Bill</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Bill</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="edit_bill_form">
						<div class="row">
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_vendor">Vendor <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_vendor" id="fk_vendor" required onchange="getChalanList(<?= $data['int_glcode'] ?>)">
										<option value=""></option>
										<?php if(!empty($vendorData)){ 
											foreach($vendorData as $value){ 
												$selected = '';
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
									<label for="fk_profile">Company Profile <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_profile" id="fk_profile" required onchange="getProjects()">
										<option value=""></option>
										<?php if(!empty($profileData)){ 
											foreach($profileData as $value){ 
												$selected = '';
												if($value['int_glcode']==$data['fk_profile']){
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
									<select class="form-control" name="fk_project" id="fk_project" onchange="getChalanList(<?= $data['int_glcode'] ?>)" required>
										<option value=""></option>
										<?php if(!empty($projectData)){ 
											foreach($projectData as $value){ 
												$selected = '';
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
									<label for="var_bill_no">Bill Number <span class="text-danger">*</span></label>
									<input type="text" name="var_bill_no" id="var_bill_no" class="form-control" required value="<?= $data['var_bill_no'] ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_bill_date">Bill Date <span class="text-danger">*</span></label>
									<input type="date" readonly name="var_bill_date" id="var_bill_date" class="form-control datepicker" max="<?= date('Y-m-d') ?>" <?= $data['var_bill_date'] ?>>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_bill_amount">Amount (<?= CURRENCY_ICON ?>) <span class="text-danger">*</span></label>
									<input type="text" name="var_bill_amount" id="var_bill_amount" maxlength="10" class="form-control" required oninput="isNumberKeyWithDot(event)" value="<?= $data['var_bill_amount'] ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_image">Image <span class="text-danger">*</span></label>
									<input class="form-control" id="var_image" name="var_image" type="file" <?= ($data['var_bill_image']==''?'required':'') ?>>
									<input class="form-control" id="hvar_image" name="hvar_image" value="<?= $data['var_bill_image'] ?>" type="hidden">
									<?php if($data['var_bill_image']!=""){ ?>
										<a href= "<?= base_url() ?>/uploads/bill/<?= $data['var_bill_image'] ?>" target="_blank">Open Bill Image</a>
									<?php } ?>
								</div>
							</div>
							<hr/>
						</div>
						<div class="row d-none" id="unbillChallanList">
							<div class="col-12">
								<h6>Unbill Challan</h6> 
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped received-table">
										<thead>
											<tr>
												<th>Challan Id</th>
												<th>Challan Date</th>
												<th>Challan Item</th>
												<th>Challan Amount (<?= CURRENCY_ICON ?>)</th>
												<th class="text-center">
													<div class="custom-checkbox custom-checkbox-table custom-control">
														<input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
														<label for="checkbox-all" class="custom-control-label">&nbsp;</label>
													</div>
												</th>
											</tr>
										</thead>
										<tbody class="addChallanList">
											<input type ="hidden" id="totalChallanAmount" name="totalChallanAmount" value="0">
											<input type ="hidden" id="id" name="id[]" value="0">
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="row d-none" id="AdvanceExpeseList">
							<div class="col-12">
								<h6>Advance Paid Expesnes</h6> 
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped received-table">
										<thead>
											<tr>
												
												<th>Expesne Id</th>
												<th>Expesne Date</th>
												<th>Expesne Amount (<?= CURRENCY_ICON ?>)</th>
												<th class="text-center">
													<div class="custom-checkbox custom-checkbox-table custom-control">
														<input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all-expense">
														<label for="checkbox-all-expense" class="custom-control-label">&nbsp;</label>
													</div>
												</th>
											</tr>
										</thead>
										<tbody class="addExpesneList">
											<input type ="hidden" id="totalExpesneAmount" name="totalExpesneAmount" value="0">
											<input type ="hidden" id="expesnse_ids" name="expesnse_ids[]" value="0">
										</tbody>
									</table>
								</div>
							</div>
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
	$("#fk_profile").select2({
		placeholder: 'Select Profile',
	});
	$(document).ready(function(){
		var fk_bill = '<?= $data['int_glcode'] ?>';
		getChalanList(fk_bill);
	});
	function getChalanList(fk_bill=''){
		var fk_project = $("#fk_project").val();
		var fk_vendor = $("#fk_vendor").val();
		$.ajax({
			url: sitepath + "/admin/bill/getChallanList",
			method: 'POST',
			data: {fk_project:fk_project, fk_vendor:fk_vendor, fk_bill:fk_bill},
			success:function(response){
				var data = $.parseJSON(response);
				if(data.status==1){
					$("#unbillChallanList").removeClass('d-none');
					$('.addChallanList').html(data.html);
				}else{
					$("#unbillChallanList").addClass('d-none');
				}
			}
		});

		$.ajax({
			url: sitepath + "/admin/bill/getAdvanceExpenseList",
			method: 'POST',
			data: {fk_project:fk_project, fk_vendor:fk_vendor, fk_bill:fk_bill},
			success:function(response){
				var data = $.parseJSON(response);
				if(data.status==1){
					$("#AdvanceExpeseList").removeClass('d-none');
					$('.addExpesneList').html(data.html);
				}else{
					$("#AdvanceExpeseList").addClass('d-none');
				}
			}
		});
	}

	function getProjects(){
		var fk_profile = $("#fk_profile").val();
		$.ajax({
			url: sitepath + "/admin/bill/getProjects",
			method: 'POST',
			data: {fk_project:fk_project},
			success:function(response){
				$('#fk_project').html(response);
			}
		});
	}


	$('#edit_bill_form').on('submit', function (e) {
		e.preventDefault();
		var sum = 0;
		var ExSum = 0;
		var id = "<?= $data['int_glcode'] ?>";
		var var_bill_amount = $("#var_bill_amount").val();
		$("input[name='ids[]']:checked").each(function (){
			sum += parseFloat($(this).data('val'));
		});

		$("input[name='expesnse_ids[]']:checked").each(function (){
			ExSum += parseFloat($(this).data('val'));
		});
		$("#totalChallanAmount").val(sum);
		$("#totalExpesneAmount").val(ExSum);
		// if(sum==0){
		// 	iziToast.error({
		// 		title: '',
		// 		message: 'Please select at least one challan againt the bill.',
		// 		position: 'topRight'
		// 	});
		// }else 
		if(parseFloat(sum) > parseFloat(var_bill_amount)){
			iziToast.error({
				title: '',
				message: 'Bill total amount is less than the total of checked challan amount.',
				position: 'topRight'
			});
		}else if(parseFloat(ExSum) > parseFloat(var_bill_amount)){
			iziToast.error({
				title: '',
				message: 'Bill total amount is less than the total of checked Advance Expense amount.',
				position: 'topRight'
			});
		}else{
			$.ajax({
				type: 'post',
				url: sitepath+'/admin/bill/updateRecord/'+id,
				data: new FormData($('#edit_bill_form')[0]),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function(){
					$('.submit_save').attr("disabled","disabled");
					$('#edit_bill_form').css("opacity",".5");
				},
				success: function(response){
					if(response.status > 0){
						window.location.href = sitepath+"/admin/bill";
					}else{
						$('#edit_bill_form').css("opacity","");
						$(".submit_save").removeAttr("disabled");
					}
				}
			});
		}
	});
</script>