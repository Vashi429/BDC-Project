
<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/expense">View Expense</a></li>
					<li class="breadcrumb-item">Add Expense</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Add Expense</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_expense_form">
						<div class="row">
							<div class="col-12 col-lg-12 col-md-12">
								<div class="form-group">
									<label for="fk_expense_type">Type <span class="text-danger">*</span></label>
									<div class="selectgroup w-100">
										<label class="selectgroup-item">
										<input type="radio" name="radio1" class="selectgroup-input-radio" checked="" value="Advance" onclick="check_option();">
										<span class="selectgroup-button">Advance</span>
										</label>
										<label class="selectgroup-item">
										<input type="radio" name="radio1" class="selectgroup-input-radio" value="Against to bill" onclick="check_option();">
										<span class="selectgroup-button">Against To Bill</span>
										</label>
										<label class="selectgroup-item">
										<input type="radio" name="radio1" class="selectgroup-input-radio" value="Labour" onclick="check_option();">
										<span class="selectgroup-button">Labour</span>
										</label>
									</div>
								</div>
							</div>	
							
							<div class="col-12 col-lg-4 col-md-6" id="advance_exp_type">
								<div class="form-group">
									<label for="var_expense_type">Expense Type <span class="text-danger">*</span></label>
									<select class="form-control" name="var_expense_type" id="var_expense_type" required onchange="check_expense_type(this.value)">
										<option value="Vendor" selected>Vendor</option>
										<option value="Labour">Labour</option>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6" id="against_exp_type" style="display: none;">
								<div class="form-group">
									<label for="var_expense_type_against">Expense Type <span class="text-danger">*</span></label>
									<select class="form-control" name="var_expense_type_against" id="var_expense_type_against" required onchange="check_expense_type(this.value)">
										<option value="Vendor" selected>Vendor</option>
									</select>
								</div>
							</div>
							
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="fk_profile">Company Profile <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_profile" id="fk_profile" required>
										<option value=""></option>
										<?php if(!empty($companyProfileData)){ 
											foreach($companyProfileData as $value){ ?>
												<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6" id="project">
								<div class="form-group">
								<label for="fk_project">Project <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_project" id="fk_project" required onchange="getLabors(this.value)">
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
									<label for="var_expense_date">Expense Date <span class="text-danger">*</span></label>
									<input type="text" name="var_expense_date" id="var_expense_date" class="form-control datepicker" required>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6" id="vendor">
								<div class="form-group">
									<label for="fk_vendor">Vendor <span class="text-danger">*</span></label>
									<select class="form-control" name="fk_vendor" id="fk_vendor" onchange="getBillList()">
										<option value=""></option>
										<?php if(!empty($vendorData)){ 
											foreach($vendorData as $value){ ?>
												<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>	

							<div class="col-12 col-lg-4 col-md-6" id="labor" style="display: none;">
								<label for="fk_labor">Labor<span class="text-danger">*</span></label>
								<select class="form-control" name="fk_labor" id="fk_labor">
									<option value=""></option>
								</select>
							</div>	

							<div class="col-12 col-lg-4 col-md-6">
								<label for="var_payment_mode">Payment Mode <span class="text-danger">*</span></label>
								<select class="form-control" name="var_payment_mode" id="var_payment_mode" required="" onchange="displayChequeNo(this.value)">
									<option value="">Select Payment Mode</option>
									<option value="Cash">Petty Cash</option>
									<option value="Online">Online</option>
									<option value="RTGS">RTGS</option>
									<option value="Cheque">Cheque</option>
								</select>
							</div>

							<div class="col-12 col-lg-4 col-md-6" id="cheque_style" style="display: none;">
								<label for="var_cheque_number">Cheque Number <span class="text-danger">*</span></label>
								<input type="text" name="var_cheque_number" id="var_cheque_number" class="form-control">
							</div>

							<div class="col-12 col-lg-4 col-md-6" id="labor_assignment" style="display: none;">
								<div class="form-group">
									<label for="var_no_assign_labor">Number of assign of labor<span class="text-danger">*</span></label>
									<input type="text" name="var_no_assign_labor" id="var_no_assign_labor" class="form-control" onkeyup="check_expense(this.value)">
									<input type="hidden" name="var_no_assign_labor_hidden" id="var_no_assign_labor_hidden">
								</div>
							</div>	
							<div class="col-12 col-lg-4 col-md-6" id="advance_exp">
								<div class="form-group">
									<label for="fk_expense">Expense <span class="text-danger">*</span></label>
									<input type="text" name="fk_expense" id="fk_expense" class="form-control" required onkeypress="return isNumber(event)">
									<input type="hidden" name="fk_expense_hidden" id="fk_expense_hidden">
								</div>
							</div>
							<div class="col-12 col-lg-4 col-md-6">
								<div class="form-group">
									<label for="var_expense_desription">Description <span class="text-danger"></span></label>
									<textarea  class="form-control" name="var_expense_desription" id="var_expense_desription"></textarea>
								</div>
							</div>	
						</div>

						<div class="row" id="labour_div" style="display:none;">
							<div class="col-12">
								<h6>Labour Expense</h6> 
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped received-table">
										<thead>
											<tr>
												<th>Id</th>
												<th>Date</th>
												<th>Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Paid Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Due Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Amount</th>
												<th class="text-center" style="display:none;">
													<div class="custom-checkbox custom-checkbox-table custom-control">
														<input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
														<label for="checkbox-all" class="custom-control-label">&nbsp;</label>
													</div>
												</th>
											</tr>
										</thead>
										<tbody class="addLabourlist">
											<input type ="hidden" id="totalChallanAmount" name="totalChallanAmount" value="0">
											<input type ="hidden" id="id" name="id[]" value="0">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row" id="bill_div" style="display:none;">
							<div class="col-12">
								<h6>Bill</h6> 
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped received-table">
										<thead>
											<tr>
												<th>Bill Id</th>
												<th>Bill Date</th>
												<th>Bill Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Bill Paid Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Bill Due Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Amount</th>
												<th class="text-center" style="display:none;">
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
						<div class="col-12 col-lg-4 col-md-6"  id="against_exp" style="display:none;">
							<div class="form-group">
								<label for="fk_expense_adv">Expense <span class="text-danger">*</span></label>
								<input type="text" name="fk_expense_adv" id="fk_expense_adv" class="form-control" style="pointer-events: none;" onkeypress="return isNumber(event)">
							</div>
						</div>
						<hr/>					
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Expense</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- <script src="<?= base_url() ?>/public/assets/dist/js/addExpense.js"></script> -->
<script>
	$("#fk_project").select2({
		placeholder:'Select Project',
	});

	$("#fk_profile").select2({
		placeholder:'Select Company Profile',
	});

	$("#var_expense_type").select2({
		placeholder:'Select Expense Type',
	});

	$("#fk_vendor").select2({
		placeholder:'Select Vendor',
	});

	$("#var_payment_mode").select2({
		placeholder:'Select Payment Mode',
	});

	function getAmount(id){
		var check_cheked_or_not = $('#checkbox-'+id).is(':checked');
		var amount = $('#amount-'+id).val();
		var total_amount = $('#fk_expense_adv').val();
		if(check_cheked_or_not == true){
			total_amount = Number(total_amount);
			amount = Number(amount);
			var final_amount = total_amount + amount;
			$('#fk_expense_adv').val(final_amount);
		}else{
			total_amount = Number(total_amount);
			amount = Number(amount);
			var final_amount = total_amount - amount;
			$('#fk_expense_adv').val(final_amount);
		}
	}
	function getBillAmount(amount){
		var array = []
		var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

		for (var i = 0; i < checkboxes.length; i++) {
			array.push(checkboxes[i].value)
		}
		var chklength = array.length;
		var final_amount = 0;
		for(k=0;k< chklength;k++)
		{
			var current_amount = $('#amount-'+array[k]).val();
			var var_due_amount = $('#var_due_amount_'+array[k]).html();
			current_amount = Number(current_amount);
			var_due_amount = Number(var_due_amount);
			if(current_amount > var_due_amount){
				var final_amount = final_amount + var_due_amount;
				alert('Amount should not be greater than the Bill Due Amount!')
				$('#amount-'+array[k]).val(var_due_amount);
			}else if(current_amount <= 0){
				var final_amount = final_amount + var_due_amount;
				alert('Amount should be greater than 0!')
				$('#amount-'+array[k]).val(var_due_amount);
			}else{
				var final_amount = final_amount + current_amount;
			}
		}		
		$('#fk_expense_adv').val(final_amount);
	}


	function getLaborAmount(amount){
		var array = []
		var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
		for (var i = 0; i < checkboxes.length; i++) {
			array.push(checkboxes[i].value)
		}
		var chklength = array.length;
		var final_amount = 0;
		for(k=0;k< chklength;k++)
		{
			var current_amount = $('#amount_labour-'+array[k]).val();
			var var_due_amount = $('#var_due_amount_labour'+array[k]).html();
			current_amount = Number(current_amount);
			var_due_amount = Number(var_due_amount);
			if(current_amount > var_due_amount){
				var final_amount = final_amount + var_due_amount;
				alert('Amount should not be greater than the Labor Due Amount!')
				$('#amount_labour-'+array[k]).val(var_due_amount);
			}else if(current_amount <= 0){
				var final_amount = final_amount + var_due_amount;
				alert('Amount should be greater than 0!')
				$('#amount_labour-'+array[k]).val(var_due_amount);
			}else{
				var final_amount = final_amount + current_amount;
			}
		}		
		$('#fk_expense_adv').val(final_amount);
	}

	function check_option(){
		var check = $('input[name="radio1"]:checked').val();
		if(check == 'Against to bill'){
			$("#bill_div").show();
			$("#labour_div").hide();
			$("#against_exp").show();
			$("#advance_exp").hide();
			$("#fk_expense").prop('required',false);
			$("#fk_expense_adv").prop('required',true);
			$("#var_expense_type_against").prop('required',true);
			$("#var_expense_type").prop('required',false);
			$("#advance_exp_type").hide();
			$("#against_exp_type").show();
			$("#vendor").css('display','flex');
			$("#fk_vendor").prop('required',true);
			$("#labor").hide();
			$("#fk_labor").prop('required',false);
			$("#labor_assignment").hide();
			$("#var_no_assign_labor").prop('required',false);
			$("#fk_expense").attr('readonly', false);
		}else if(check == 'Advance'){
			$("#bill_div").hide();
			$("#labour_div").hide();
			$("#advance_exp").show();
			$("#against_exp").hide();
			$("#fk_expense").prop('required',true);
			$("#fk_expense_adv").prop('required',false);
			$("#var_expense_type_against").prop('required',false);
			$("#var_expense_type").prop('required',true);
			$("#advance_exp_type").show();
			$("#against_exp_type").hide();
			$("#vendor").show();
			$("#fk_vendor").prop('required',true);
		}else if(check == 'Labour'){
			$("#bill_div").hide();
			$("#labour_div").show();
			$("#advance_exp").hide();
			$("#against_exp").show();
			$("#fk_expense").prop('required',false);
			$("#fk_expense_adv").prop('required',true);
			$("#var_expense_type_against").prop('required',false);
			$("#var_expense_type").prop('required',false);
			$("#advance_exp_type").hide();
			$("#against_exp_type").hide();
			$("#vendor").hide();
			$("#fk_vendor").prop('required',false);
		}
	}

	function check_expense_type(type){
		if(type == 'vendor'){
			$("#vendor").css('display','flex');
			$("#fk_vendor").prop('required',true);
			$("#labor").hide();
			$("#fk_labor").prop('required',false);
			$("#labor_assignment").hide();
			$("#var_no_assign_labor").prop('required',false);
			$("#fk_expense").attr('readonly', false);
		}else{
			$("#labor").show();
			$("#fk_labor").prop('required',true);
			$("#vendor").css('display','none');
			$("#fk_vendor").prop('required',false);
			$("#labor_assignment").show();
			$("#var_no_assign_labor").prop('required',true);
			$("#fk_expense").attr('readonly', true);
		}
	}
	function getProjects(){
		var fk_profile = $("#fk_profile").val();
		$.ajax({
			url: sitepath+'/admin/expense/getProjects',
			method: 'POST',
			data: {fk_profile:fk_profile},
			beforeSend:function(){
				// $('.submit_save').attr("disabled","disabled");
				// $('#add_invoice_form').css("opacity",".5");
			},
			success: function(response){
				$("#project").html(response);
				$("#fk_project").select2({
					placeholder:'Select Project',
				});
				$(".submit_save").removeAttr("disabled");
			}
		});
	}
	function getLabors(project_id){
		$.ajax({
			url: sitepath+'/admin/expense/getLabor',
			method: 'POST',
			data: {project_id:project_id},
			beforeSend:function(){
				// $('.submit_save').attr("disabled","disabled");
				// $('#add_invoice_form').css("opacity",".5");
			},
			success: function(response){
				$('.addLabourlist').html(response);
				var amount = $("#totalLaborAmount").val();
				$('#fk_expense_adv').val(amount);
			}
		});
	}
	function getassignmentprice(id){
		var var_expense_type = $("#var_expense_type").val();
		if(var_expense_type == 'labour'){
			$.ajax({
				url: sitepath+'/admin/expense/getassignmentprice',
				method: 'POST',
				data: {id:id},
				dataType: 'json',
				beforeSend:function(){
					// $('.submit_save').attr("disabled","disabled");
					// $('#add_invoice_form').css("opacity",".5");
				},
				success: function(response){
					$("#var_no_assign_labor").val(response.final_total_number_assign);
					$("#fk_expense").val(response.final_labor_charge);
					$("#var_no_assign_labor_hidden").val(response.final_total_number_assign);
					$("#fk_expense_hidden").val(response.final_labor_charge);
					$("#fk_expense").attr('readonly', true);
				}
			});
		}
	}

	

	function check_expense(current_no_of_assign){
		var main_expense = $("#fk_expense_hidden").val();
		var main_no_assign_labor_hidden = $("#var_no_assign_labor_hidden").val();

		if(Number(current_no_of_assign) > Number(main_no_assign_labor_hidden)){
			alert('Number of assign of labor should not be greater than '+ main_no_assign_labor_hidden);
			$("#var_no_assign_labor").val(main_no_assign_labor_hidden);
			$("#fk_expense").val(main_expense);
		}else{
			var final_amount_of_1_assign = Number(main_expense) / Number(main_no_assign_labor_hidden);
			var final_amount = final_amount_of_1_assign * current_no_of_assign;
			$("#fk_expense").val(final_amount);
		}
	}

	function displayChequeNo(type){
		if(type == 'Cheque'){
			$("#cheque_style").show();
			$("#var_cheque_number").prop('required',true);
		}else{
			$("#cheque_style").hide();
			$("#var_cheque_number").prop('required',false);
		}
	}
</script>

<script>
	$('#add_expense_form').on('submit', function (e) {
		e.preventDefault();
		// var var_final_amount = $("#var_final_amount").val();
		// if(parseFloat(var_final_amount) > 0){
			$.ajax({
				type: 'post',
				url: sitepath+'/admin/expense/insertRecord',
				data: new FormData(this),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function(){
					$('.submit_save').attr("disabled","disabled");
					$('#add_expense_form').css("opacity",".5");
				},
				success: function(response){
					if(response.status > 0){
						window.location.href = sitepath+"/admin/expense";
					}else{
						$('#add_expense_form').css("opacity","");
						$(".submit_save").removeAttr("disabled");
					}
				}
			});
		// }else{
		// 	iziToast.error({
        //         title: '',
        //         message: 'Total of invoice must be more then 0',
        //         position: 'topRight'
        //     });
		// }
	});


	

	function getBillList(){
		var fk_project = $("#fk_project").val();
		var fk_vendor = $("#fk_vendor").val();
		$.ajax({
			url: sitepath + "/admin/bill/getBillList",
			method: 'POST',
			data: {fk_project:fk_project, fk_vendor:fk_vendor, fk_bill:''},
			success:function(response){
				$('.addChallanList').html(response);
				var amount = $("#totalBillAmount").val();
				$('#fk_expense_adv').val(amount);
			}
		});
	}


	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
</script>