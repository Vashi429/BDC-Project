<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/invoice">View Invoice</a></li>
					<li class="breadcrumb-item">Edit Invoice</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Invoice</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
					<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="edit_invoice_form">
						<div class="form-group row">
							<label for="fk_customer" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Customer <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_customer" id="fk_customer" onchange="getGSTDropDown(); getCustomerAddress();" required>
									<option value=""></option>
									<?php if(!empty($customerData)){ 
										foreach($customerData as $value){ 
											$selected = "";
											if($value['int_glcode']==$data['fk_customer']){
												$selected = "selected";
											}
											?>
											<option value="<?= $value['int_glcode'] ?>" <?= $selected; ?>><?= $value['var_displayname'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="fk_project" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Project <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_project" id="fk_project" required>
									<option value=""></option>
									<?php if(!empty($projectData)){ 
										foreach($projectData as $value){
											$selected = "";
											if($value['int_glcode']==$data['fk_project']){
												$selected = "selected";
											}
											?>
											<option value="<?= $value['int_glcode'] ?>" <?= $selected ?>><?= $value['var_project'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="fk_profile" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Company Profile <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<select class="form-control" name="fk_profile" id="fk_profile" onchange="getGSTDropDown()" required>
									<option value=""></option>
									<?php if(!empty($companyProfileData)){ 
										foreach($companyProfileData as $value){
											$selected = "";
											if($value['int_glcode']==$data['fk_profile']){
												$selected = "selected";
											}
											?>
											<option value="<?= $value['int_glcode'] ?>" <?= $selected; ?>><?= $value['var_name'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="var_Invoice_id" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Invoice Id <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<input type="text" readonly name="var_Invoice_id" id="var_Invoice_id" class="form-control" value="<?= $data['var_Invoice_id'] ?>" >
							</div>
						</div>
						<div class="form-group row">
							<label for="var_invoice_date" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Invoice Date <span class="text-danger">*</span></label>
							<div class="col-sm-9 col-lg-4">
								<input type="text" name="var_invoice_date" id="var_invoice_date" class="form-control datepicker" value="<?= $data['var_invoice_date'] ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="var_subject" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Subject</label>
							<div class="col-sm-9 col-lg-4">
								<textarea class="form-control" id="var_subject" name="var_subject"><?= $data['var_subject'] ?></textarea>
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
												<th>Rate</th>
												<th>Tax</th>
												<th>Amount</th>
												<th>Option</th>
											</tr>
										</thead>
										<tbody class="addInvoiceItems">
											<?php if(!empty($data['invoice_item'])){
												foreach($data['invoice_item'] as  $ii_key => $ii_value){ 
													$ii_key = $ii_key + 1;
													?>
													<tr class="addmoreInvoice" id="InvoiceItem<?= $ii_value['int_glcode']?>">
														<td>
															<input class="form-control" id="var_item<?= $ii_key ?>" name="var_item[]" required="" type="text" placeholder="Item Name" minlength="3" maxlength="500" value="<?= $ii_value['var_item_name'] ?>">
															<input class="form-control" type="text" id="var_hsn<?= $ii_key ?>" name="var_hsn[]" class="hsn-small" placeholder="Search HSN Here" onkeypress="searchhsn('var_hsn<?= $ii_key ?>')" value="<?= $ii_value['var_hsn'] ?>">
															<input type="hidden" id="hide_var_hsn<?= $ii_key ?>" name="hide_var_hsn[]" value="<?= $ii_value['var_hsn'] ?>">
															<div id="hsn_suggestion_var_hsn<?= $ii_key ?>"></div>
														</td>
														<td>
															<input class="form-control" id="var_quantity<?= $ii_key ?>" name="var_quantity[]" oninput="return isNumberKey(event);" required="" type="text" value="<?= $ii_value['var_qty'] ?>" maxlength="5" onfocusOut ="itemTotalAmount(<?= $ii_key ?>)">
														</td>
														<td>
															<input class="form-control" id="var_rate<?= $ii_key ?>" name="var_rate[]" oninput="return isNumberKeyWithDot(event);" required="" type="text" value="<?= $ii_value['var_rate'] ?>" maxlength="15" onfocusOut ="itemTotalAmount(<?= $ii_key ?>)">
														</td>
														<td>
															<select class="form-control fk_tax" name="fk_tax[]" id="fk_tax<?= $ii_key ?>" onchange ="itemTotalAmount(<?= $ii_key ?>)">
																<option value="" disabled>Tax</option>
																<option value="0" <?php if($ii_value['fk_gst']==0){echo 'selected';} ?>>No <?= $gstType ?></option>
																<?php if(!empty($gstData)){
																	foreach($gstData as $value){ 
																		if($value['var_percent']==0){
																			continue;
																		}
																		$selected ="";
																		if($ii_value['fk_gst']==$value['int_glcode']){
																			$selected = 'selected';
																		}
																		?>
																		<option value="<?= $value['int_glcode'] ?>" data-val="<?= $value['var_percent'] ?>" <?= $selected ?>><?= $value['var_percent'] ?>% <?= $gstType ?></option>
																	<?php }
																} ?>
																<input type="hidden" value="<?= $ii_value['var_tax'] ?>" id="var_tax<?= $ii_key ?>" name="var_tax[]">
															</select>
														</td>
														<td>
															<input type="hidden" name="var_total[]" value="<?= $ii_value['var_amount']-$ii_value['var_tax'] ?>" id="var_row_total<?= $ii_key ?>">
															<span class="total-amount" id="var_row_total_txt<?= $ii_key ?>"><?= CURRENCY_ICON.$ii_value['var_amount'] ?></span>
														</td>
														<td>
															<?php if($ii_key==1){ ?>
																<a href="javascript:void(0)" class="add-invoice" title="add item" id="btnAddInvoice"><i class="fas fa-plus m-0"></i></a>
															<?php }else{ ?>
																<a href="javascript:void(0);" class="remove-invoice" title="remove item" onclick="removeInvoiceItem(<?= $ii_value['int_glcode'] ?>, <?= $ii_value['var_amount']; ?>)"><i class="fas fa-minus m-0"></i></a>
															<?php } ?>
														</td>
														<input type="hidden" name="invoice_item[]" value="<?= $ii_value['int_glcode'] ?>" id="invoice_item<?= $ii_key ?>">
													</tr>
												<?php } ?>
												<input type="hidden" name="no_row" value="<?= count($data['invoice_item']) ?>" id="no_row">
											<?php }else{ ?>
												<tr>
													<td>
														<input class="form-control" id="var_item1" name="var_item[]" required="" type="text" placeholder="Item Name" minlength="3" maxlength="500">
														<input class="form-control" type="text" id="var_hsn1" name="var_hsn[]" class="hsn-small" placeholder="Search HSN Here" onkeyup="searchhsn('var_hsn1')" value="">
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
															<option value="0">No <?= $gstType ?></option>
															<?php if(!empty($gstData)){
																foreach($gstData as $value){ 
																	if($value['var_percent']==0){
																		continue;
																	}
																	?>
																	<option value="<?= $value['int_glcode'] ?>" data-val="<?= $value['var_percent'] ?>"><?= $value['var_percent'] ?>% <?= $gstType ?></option>
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
														<a href="javascript:void(0)" class="add-invoice" id="btnAddInvoice"><i class="fas fa-plus m-0"></i></a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<hr/>
								<br/>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="var_customer_note">Customer Notes <span class="text-danger">*</span></label>
									<textarea class="form-control" id="var_customer_note" name="var_customer_note" required><?= $data['var_customer_note'] ?></textarea>
								</div>
								<label class="" for="var_payment_status">
									<?php  $checked ="";
									if($data['var_payment_status']=='Paid'){
										$checked = 'checked';
									} ?>
									<input type="checkbox" name="var_payment_status" id="var_payment_status" class="custom-switch-input" <?= $checked; ?>>
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description"><b>Paid</b></span>
								</label>
							</div>
							<div class="col-lg-5">
								<div class="invoice-total-sec">
									<div class="d-flex justify-content-between align-items-center mb-3" id="sub_total_div">
										<h6 class="m-0">Sub Total</h6>
										<span class="sub-total" id="var_subtotal_txt"><?= CURRENCY_ICON.$data['var_subtotal'] ?></span>
									</div>
									<div id="sub_total_gst_div">
										<?php if($gstType=='IGST'){ ?>
											<div class="d-flex justify-content-between align-items-center mb-3">
												<h6 class="m-0"><?= $gstType ?> Total</h6>
												<span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$data['var_gst'] ?></span>
											</div>
										<?php }else{ ?>
											<div class="d-flex justify-content-between align-items-center mb-3">
												<h6 class="m-0">CGST Total</h6>
												<span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$data['var_gst']/2 ?></span>
											</div>
											<div class="d-flex justify-content-between align-items-center mb-3">
												<h6 class="m-0">SGST Total</h6>
												<span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$data['var_gst']/2 ?></span>
											</div>
										<?php } ?>
									</div>
									
									<div class="d-flex justify-content-between align-items-center mb-3">
										<div style="display: flex;align-items: center;gap: 15px;">
											<label for="var_adjustment">Adjustment</label>
											<input class="form-control" id="var_adjustment" name="var_adjustment" type="text" oninput="adjustmentTotal(), isNumberKeyWithDot(event);" value="<?= $data['var_adjustment'] ?>" >
										</div>
										<span class="sub-total" id="var_adjustment_txt"><?= CURRENCY_ICON.$data['var_adjustment'] ?></span>
									</div>
									<div class="d-flex justify-content-between align-items-center">
										<h5 class="m-0">Total</h5>
										<span class="sub-total grand-total" id="var_final_amount_txt"><?= CURRENCY_ICON.$data['var_final_amount'] ?></span>
										<input type="hidden" name = "var_subtotal" id="var_subtotal" value="<?= $data['var_subtotal'] ?>" >
										<input type="hidden" name = "var_gst" id="var_gst" value="<?= $data['var_gst'] ?>">
										<input type="hidden" name = "var_final_amount" id="var_final_amount" value="<?= $data['var_final_amount'] ?>">
										<input type="hidden" name = "var_due_amount" id="var_due_amount" value="<?= $data['var_due_amount'] ?>">
										<input type="hidden" name = "var_paid_amount" id="var_paid_amount" value="<?= $data['var_paid_amount'] ?>">
										<input type="hidden" name="fk_receivedId" id="fk_receivedId" value="<?= $data['fk_receivedId'] ?>">
										<input type="hidden" name = "gst_type" id="gst_type" value="<?= $gstType ?>">
									</div>
								</div>
							</div>
						</div>
						<hr />
						<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Update Invoice</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="<?= base_url() ?>/public/assets/dist/js/addInvoice.js"></script>
<script>
	
		$('#edit_invoice_form').on('submit', function (e) {
			e.preventDefault();
			id = '<?= $data['int_glcode'] ?>';
			var var_paid_amount = parseFloat($("#var_paid_amount").val()).toFixed(2);
			var var_final_amount = parseFloat($("#var_final_amount").val()).toFixed(2);
			if(var_final_amount > var_paid_amount){
				iziToast.error({
					title: 'Error',
					message: 'Invoice due  amount is less then the total amount.',
					position: 'topRight'
				});
			}else{
				$.ajax({
					type: 'post',
					url: sitepath+'/admin/invoice/updateRecord/'+id,
					data: new FormData(this),
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function(){
						$('.submit_save').attr("disabled","disabled");
						$('#edit_invoice_form').css("opacity",".5");
					},	
					success: function(response){
						if(response.status > 0){
							window.location.href = sitepath+"/admin/invoice";
						}else{
							$('#edit_invoice_form').css("opacity","");
							$(".submit_save").removeAttr("disabled");
						}
					}
				});
			}
		});
</script>