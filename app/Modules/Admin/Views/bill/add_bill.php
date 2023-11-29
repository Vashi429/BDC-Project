<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/bill">View Bill</a></li>
					<li class="breadcrumb-item">Add Bill</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Add Bill</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_bill_form">
						<div class="row">
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_vendor">Vendor <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_vendor" id="fk_vendor" required onchange="getChalanList()">
										<option value=""></option>
										<?php if(!empty($vendorData)){ 
											foreach($vendorData as $value){ ?>
												<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_profile">Company Profile <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_profile" id="fk_profile" required>
										<option value=""></option>
										<?php if(!empty($profileData)){ 
											foreach($profileData as $value){ ?>
												<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_project">Project <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_project" id="fk_project" onchange="getChalanList()" required>
										<option value=""></option>
										<?php if(!empty($projectData)){ 
											foreach($projectData as $value){ ?>
												<option value="<?= $value['int_glcode'] ?>"><?= $value['var_project'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_bill_no">Bill Number <span class="text-danger">*</span></label>
									<input type="text" name="var_bill_no" id="var_bill_no" class="form-control" required>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_bill_date">Bill Date <span class="text-danger">*</span></label>
									<input type="date" readonly name="var_bill_date" id="var_bill_date" class="form-control datepicker" max="<?= date('Y-m-d') ?>">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_bill_amount">Amount (<?= CURRENCY_ICON ?>) <span class="text-danger">*</span></label>
									<input type="text" name="var_bill_amount" id="var_bill_amount" maxlength="10" class="form-control" required oninput="isNumberKeyWithDot(event)">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_image">Image <span class="text-danger">*</span></label>
									<input class="form-control" id="var_image" name="var_image" type="file" required>
									<input class="form-control" id="hvar_image" name="hvar_image" value="" type="hidden">
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
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Bill</button>
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
	
	// $('#fk_profile').on('change', function (e) {
	// 	var fk_profile = $("#fk_profile").val();
	// 	$.ajax({
	// 		url: sitepath + "/admin/bill/getProjects",
	// 		method: 'POST',
	// 		data: {fk_profile:fk_profile},
	// 		success:function(response){
	// 			$('#fk_project').html(response);
	// 		}
	// 	});
	// });


	function getChalanList(){
		var fk_project = $("#fk_project").val();
		var fk_vendor = $("#fk_vendor").val();
		$.ajax({
			url: sitepath + "/admin/bill/getChallanList",
			method: 'POST',
			data: {fk_project:fk_project, fk_vendor:fk_vendor, fk_bill:''},
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
			data: {fk_project:fk_project, fk_vendor:fk_vendor, fk_bill:''},
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

	$('#add_bill_form').on('submit', function (e) {
		e.preventDefault();
		var sum = 0;
		var ExSum = 0;
		var var_bill_amount = $("#var_bill_amount").val();
		var bill_no = $("#var_bill_no").val();
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
			if($.trim(bill_no) != ''){
				$.ajax({
					type: 'POST',
					url: sitepath+'/admin/bill/checkBillNumber',
					data: {bill_no:bill_no},
					beforeSend: function(){
						// $('.submit_save').attr("disabled","disabled");
						// $('#add_bill_form').css("opacity",".5");
					},
					success: function(response){
						if(response == '0'){
							iziToast.error({
								title: '',
								message: 'Bill number should be always unique.',
								position: 'topRight'
							});
						}else{
							$.ajax({
								type: 'post',
								url: sitepath+'/admin/bill/insertRecord',
								data: new FormData($('#add_bill_form')[0]),
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								beforeSend: function(){
									// $('.submit_save').attr("disabled","disabled");
									// $('#add_bill_form').css("opacity",".5");
								},
								success: function(response){
									if(response.status > 0){
										window.location.href = sitepath+"/admin/bill";
									}else{
										$('#add_bill_form').css("opacity","");
										$(".submit_save").removeAttr("disabled");
									}
								}
							});
						}
					}
				});
			}
		}
	});
</script>