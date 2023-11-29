<style>
	.imagecheck-figure {
    background-color: #fdfdff;
    border-color: #e4e6fc;
    border-width: 1px;
    border-style: solid;
    border-radius: 3px;
    margin: 0;
    position: relative;
    vertical-align: top;
    white-space: nowrap;
    padding-left: 30px;
    display: flex;
    align-items: center;
}

.imagecheck{
	width: 100%;
}
</style>
<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/invoice">View Invoice</a></li>
					<li class="breadcrumb-item">Add Invoice</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Add Invoice</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_invoice_form">
						<div class="form-group row">
							<label for="fk_customer" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Customer <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_customer" id="fk_customer" onchange="getGSTDropDown(); getCustomerAddress(); getUpaidinvoicelist();" required>
									<option value=""></option>
									<?php if(!empty($customerData)){ 
										foreach($customerData as $value){ ?>
											<option value="<?= $value['int_glcode'] ?>"><?= $value['var_displayname'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div id="customer-address"></div>
						<div class="form-group row">
							<label for="fk_project" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Project <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_project" id="fk_project" required onchange="getUpaidinvoicelist()"></select>
									<option value=""></option>
									<?php if(!empty($projectData)){ 
										foreach($projectData as $value){ ?>
											<option value="<?= $value['int_glcode'] ?>"><?= $value['var_project'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="fk_profile" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Company Profile <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_profile" id="fk_profile"  onchange="getGSTDropDown()" required>
									<option value=""></option>
									<?php if(!empty($companyProfileData)){ 
										foreach($companyProfileData as $value){ ?>
											<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="var_Invoice_id" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Invoice Id <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<input type="text" readonly name="var_Invoice_id" id="var_Invoice_id" class="form-control" value="<?= $autoInvoiceID ?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="var_invoice_date" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Invoice Date <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<input type="date" name="var_invoice_date" id="var_invoice_date" class="form-control" max="<?= date('Y-m-d'); ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="var_subject" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Subject</label>
							<div class="col-sm-9 col-lg-4">
								<textarea class="form-control" id="var_subject" name="var_subject"></textarea>
							</div>
						</div>
						<hr/>
						<br/>
						<div class="row row-sm">
							<div class="col-12">
								<!-- <h6>Item Details</h6> -->
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped invoice-table">
										<thead>
											<tr>
												<th>Item</th>
												<th>Quantity</th>
												<th>Rate (<?= CURRENCY_ICON ?>)</th>
												<th>Tax</th>
												<th>Amount (<?= CURRENCY_ICON ?>)</th>
												<th>Option</th>
											</tr>
										</thead>
										<tbody class="addInvoiceItems">
											<tr>
												<td>
													<input class="form-control" id="var_item1" name="var_item[]" required="" type="text" placeholder="Item Name" minlength="3" maxlength="500">
													<input class="form-control" type="text" required id="var_hsn1" name="var_hsn[]" class="hsn-small" placeholder="Search HSN Here" oninput="searchhsn('var_hsn1')" value="">
													<input type="hidden" id="hide_var_hsn1" name="hide_var_hsn[]" value="">
													<div id="hsn_suggestion_var_hsn1"></div>
												</td>
												<td>
													<input class="form-control" id="var_quantity1" name="var_quantity[]" oninput="return isNumberKey(event);" required="" type="text" value="0" maxlength="5" onfocusOut ="itemTotalAmount(1)">
												</td>
												<td>
													<input class="form-control" id="var_rate1" name="var_rate[]" oninput="return isNumberKeyWithDot(event);" required="" type="text" value="0" maxlength="15" onfocusOut ="itemTotalAmount(1)">
													
												</td>
												<td>
													<select class="form-control fk_tax" name="fk_tax[]" id="fk_tax1" onchange ="itemTotalAmount(1)">
														<option value="" disabled>Tax</option>
														<option value="0">No GST</option>
														<?php if(!empty($gstData)){
															foreach($gstData as $value){ 
																if($value['var_percent']==0){
																	continue;
																}
																?>
																<option value="<?= $value['int_glcode'] ?>" data-val="<?= $value['var_percent'] ?>"><?= $value['var_percent'] ?>% GST</option>
															<?php }
														} ?>
														<input type="hidden" value="0" id="var_tax1" name="var_tax[]">
													</select>
												</td>
												<td>
													<input type="hidden" name="var_total[]" value="0" id="var_row_total1">
													<span class="total-amount" id="var_row_total_txt1">0.00</span>
												</td>
												<td>
													<input type="hidden" name="no_row" value="1" id="no_row">
													<a href="javascript:void(0)" class="add-invoice" title="add item" id="btnAddInvoice"><i class="fas fa-plus m-0"></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<hr/>
								<br/>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="var_customer_note">Customer Notes <span class="text-danger">*</span></label>
									<textarea class="form-control" id="var_customer_note" name="var_customer_note" required></textarea>
								</div>
								<label class="" for="var_payment_status">
									<input type="checkbox" name="var_payment_status" id="var_payment_status" class="custom-switch-input">
									<!-- onchange="getUpaidinvoicelist()" -->
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description"><b>Paid</b></span>
								</label>
							</div>
							<div class="col-lg-5">
								<div class="invoice-total-sec">
									<div class="d-flex justify-content-between align-items-center mb-3" id="sub_total_div">
										<h6 class="m-0">Sub Total</h6>
										<span class="sub-total" id="var_subtotal_txt">0.00</span>
									</div>
									<div id="sub_total_gst_div"></div>
									
									<div class="d-flex justify-content-between align-items-center mb-3">
										<div style="display: flex;align-items: center;gap: 15px;">
											<label for="var_adjustment">Adjustment</label>
											<input class="form-control" id="var_adjustment" name="var_adjustment" type="text" oninput="adjustmentTotal(), isNumberKeyWithDot(event);">
										</div>
										<span class="sub-total" id="var_adjustment_txt">0.00</span>
									</div>
									<div class="d-flex justify-content-between align-items-center">
										<h5 class="m-0">Total</h5>
										<span class="sub-total grand-total" id="var_final_amount_txt">0.00</span>
										<input type="hidden" name = "var_subtotal" id="var_subtotal" value="0" >
										<input type="hidden" name = "var_gst" id="var_gst" value="0">
										<input type="hidden" name = "var_final_amount" id="var_final_amount" value="0">
										<input type="hidden" name = "fk_receivedId" id="fk_receivedId" value="0">
										<input type="hidden" name = "gst_type" id="gst_type" value="GST">
									</div>
								</div>
							</div>
						</div>
						<hr />
						<div class="col-12 d-none" id="unpaidInvoiceSection">
							<h6>Unpaid Invoices</h6>
							<div class="table-responsive">
								<table id="mainTable" class="table table-striped received-table">
								<thead>
									<tr>
									<th>Invoice Id</th>
									<th>Invoice Date</th>
									<th>Invoice Amount (<?= CURRENCY_ICON ?>)</th>
									<th>Paid Amount (<?= CURRENCY_ICON ?>)</th>
									<th>Due Amount (<?= CURRENCY_ICON ?>)</th>
									<th>Payment (<?= CURRENCY_ICON ?>)</th>
									</tr>
								</thead>
								<tbody class="addUnpaidInvoicelist"><input type="hidden" id="total_due_amount" name="total_due_amount" value="0"></tbody>
								</table>
							</div>
						</div>
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Invoice</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="<?= base_url() ?>/public/assets/dist/js/addInvoice.js"></script>
<script>
	$('#add_invoice_form').on('submit', function (e) {
		e.preventDefault();
		var var_final_amount = $("#var_final_amount").val();
		if(parseFloat(var_final_amount) > 0){
			$.ajax({
				type: 'post',
				url: sitepath+'/admin/invoice/insertRecord',
				data: new FormData(this),
				dataType: 'json',
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function(){
					$('.submit_save').attr("disabled","disabled");
					$('#add_invoice_form').css("opacity",".5");
				},
				success: function(response){
					if(response.status > 0){
						window.location.href = sitepath+"/admin/invoice";
					}else{
						$('#add_invoice_form').css("opacity","");
						$(".submit_save").removeAttr("disabled");
					}
				}
			});
		}else{
			iziToast.error({
                title: '',
                message: 'Total of invoice must be more then 0',
                position: 'topRight'
            });
		}
	});
</script>