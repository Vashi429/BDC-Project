<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/project/estimationList/<?= base64_encode($data['int_glcode']) ?>">View Estimation</a></li>
					<li class="breadcrumb-item"><?= (isset($fk_estimation)?'Edit':'Add') ?> Estimation</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4><?= (isset($fk_estimation)?'Edit':'Add') ?> Estimation of <?= $data['var_project'] ?></h4>
				</div>
				<div class="card-body">
					<form action="" id="projectEstimate" enctype="multipart/form-data" method="POST">
						<input type="hidden" value="<?= $data['int_glcode'] ?>" name="fk_project" id="fk_project">
						<input type="hidden" value="<?= $data['fk_customer'] ?>" name="fk_customer" id="fk_customer">
						<input type="hidden" value="<?= $data['fk_profile'] ?>" name="fk_profile" id="fk_profile">
						<input type="hidden" value="Y" name="is_existing">
						<input type="hidden" value="<?= ($fk_estimation) ?? '0'; ?>" name="fk_estimation" id="fk_estimation">
						<div class="col-12 col-lg-8 col-md-6">
							<div class="form-group">
								<label for="var_estimate_title">Estimate Title <span class="text-danger">*</span></label>
								<input class="form-control" value="<?= ($estimationData['var_estimate_title']) ?? ""; ?>" id="var_estimate_title" name="var_estimate_title" required="" type="text">
							</div>
						</div>
						<div class="col-12 col-lg-8 col-md-6">
							<div class="form-group">
								<label for="var_date">Estimate Date <span class="text-danger">*</span></label>
								<input class="form-control datepicker" value="<?= ($estimationData['var_date']) ?? ""; ?>" id="var_date" name="var_date" required="" type="text">
							</div>
						</div>
						<div class="row row-sm">
							<div class="col-12">
								<div class="table-responsive">
									<table id="mainTable" class="table table-striped estimate-table">
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
										<tbody class="addEstimateItems">
											<?php if (!empty($estimationData['estimationItems'])) {
												$total = 0;
												foreach ($estimationData['estimationItems'] as $key => $val) {
													if ($key == 0) { 
														$btn = '<a href="javascript:void(0)" class="add-invoice"
														id="btnAddEstimate"><i class="fas fa-plus m-0"></i></a>';
													}else{
														$btn = '<a href="javascript:void(0);" class="remove-invoice"
														id="btnlaborremove"><i class="fas fa-minus m-0"></i></a>';
													}
													$rowNo = $key+1; 
													$total ++;?>
													<tr class = "addEstimates">
														<td>
															<input type="hidden" name="var_item_id[]" id="var_item_id<?= $rowNo ?>" value="<?= $val['int_glcode'] ?>">
															<input class="form-control" value="<?= $val['var_item'] ?>" id="var_item<?= $rowNo ?>" name="var_item[]" required="" type="text" placeholder="Item Name" minlength="3" maxlength="500">
														</td>
														<td>
															<input class="form-control" id="var_quantity<?= $rowNo ?>" name="var_quantity[]" onkeypress="return isNumberKey(event);" value="<?= $val['var_quantity'] ?>" required="" type="text" maxlength="5" onfocusout="itemTotalAmount(1)">
														</td>
														<td>
															<input class="form-control" id="var_rate<?= $rowNo ?>" name="var_rate[]" onkeypress="return isNumberKeyWithDot(event);" value="<?= $val['var_rate'] ?>" required="" type="text" maxlength="15" onfocusout="itemTotalAmount(1)">

														</td>
														<td>
															<select class="form-control fk_tax" name="fk_tax[]" id="fk_tax<?= $rowNo ?>"
																onchange="itemTotalAmount(1)">
																<option value="0">No <?= $gstType ?> </option>
																<?php if (!empty($gst)) {
																	foreach ($gst as $value) {
																		if ($value['var_percent'] == 0) {
																			continue;
																		} ?>
																		<option value="<?= $value['int_glcode'] ?>" data-val="<?= $value['var_percent'] ?>" <?= ($value['var_percent'] == $val['var_percent']) ? "selected" : ""; ?>> <?= $value['var_percent'] ?>% <?= $gstType ?></option>
																	<?php }
																} ?>
																<input type="hidden" value="<?= (number_format($val['var_tax'], '2', '.', '')) ?? "0.00"; ?>" id="var_tax<?= $rowNo ?>" name="var_tax[]">
															</select>
														</td>
														<td>
															<input type="hidden" name="var_total[]" id="var_row_total<?= $rowNo ?>" value="<?= (number_format($val['var_total'], '2', '.', '')) ?? "0.00"; ?>">
															<span class="total-amount" id="var_row_total_txt<?= $rowNo ?>">
																<?= (CURRENCY_ICON.number_format($val['var_total'], '2', '.', '')) ?? CURRENCY_ICON."0.00"; ?>
															</span>
														</td>
														<td><?= $btn; ?></td>
													</tr>
												<?php } ?>
												<input type="hidden" name="no_row" value="<?= $total ?>" id="no_row">
											<?php } else { ?>
												<tr class="addEstimates">
													<td>
														<input class="form-control" id="var_item1" name="var_item[]"
															required="" type="text" placeholder="Item Name" minlength="3"
															maxlength="500">
													</td>
													<td>
														<input class="form-control" id="var_quantity1" name="var_quantity[]"
															onkeypress="return isNumberKey(event);" required="" type="text"
															value="0" maxlength="5" onfocusout="itemTotalAmount(1)">
													</td>
													<td>
														<input class="form-control" id="var_rate1" name="var_rate[]"
															onkeypress="return isNumberKeyWithDot(event);" required=""
															type="text" value="0" maxlength="15"
															onfocusout="itemTotalAmount(1)">

													</td>
													<td>
														<select class="form-control fk_tax" name="fk_tax[]" id="fk_tax1"
															onchange="itemTotalAmount(1)">
															<option value="0">No<?= $gstType ?></option>
															<?php if (!empty($gst)) {
																foreach ($gst as $value) {
																	if ($value['var_percent'] == 0) {
																		continue;
																	}
																	?>
																	<option value="<?= $value['int_glcode'] ?>" data-val="<?= $value['var_percent'] ?>"> <?= $value['var_percent'] ?>% <?= $gstType ?>
																	</option>
																<?php }
															} ?>
															<input type="hidden" value="0" id="var_tax1" name="var_tax[]">
														</select>
													</td>
													<td>
														<input type="hidden" name="var_total[]" value="0" id="var_row_total1">
														<span class="total-amount" id="var_row_total_txt1"><?= CURRENCY_ICON ?>0.00</span>
													</td>
													<td>
														<input type="hidden" name="no_row" value="1" id="no_row">
														<a href="javascript:void(0)" class="add-invoice"
															id="btnAddEstimate"><i class="fas fa-plus m-0"></i></a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-lg-6 mt-5">
								<div class="form-group">
									<label for="var_customer_note">Notes </label>
									<textarea class="form-control"
										value="<?= ($estimationData['var_note']) ?? ""; ?>" id="var_customer_note"
										name="var_customer_note"><?= ($estimationData['var_note']) ?? ""; ?></textarea>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="invoice-total-sec">
									<div class="d-flex justify-content-between align-items-center mb-3">
										<span>Sub Total</span>
										<span id="var_subtotal_txt">
											<?= (isset($estimationData['var_sub_total'])?CURRENCY_ICON.number_format($estimationData['var_sub_total'], '2', '.', ''): CURRENCY_ICON."0.00"); ?>
										</span>
									</div>
									<div id="sub_total_gst_div">
										<?php if(isset($estimationData['tax'])){
											if($gstType=='GST' || $gstType == 'IGST'){ 
												$tax = number_format($estimationData['tax'], '2', '.', '');
												?>
												<div class="d-flex justify-content-between align-items-center mb-3"><h6 class="m-0"><?= $gstType ?> Total</h6><span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$tax ?></span></div>
											<?php }else{ 
												$tax = number_format($estimationData['tax']/2, '2', '.', '');?>
												<div class="d-flex justify-content-between align-items-center mb-3"><h6 class="m-0">CGST Total</h6><span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$tax ?></span></div><div class="d-flex justify-content-between align-items-center mb-3"><h6 class="m-0">SGST Total</h6><span class="sub-total" id="var_gst_txt"><?= CURRENCY_ICON.$tax ?></span></div>
											<?php } 
										} ?>
									</div>
									<div class="d-flex justify-content-between align-items-center mb-3">
										<div style="display: flex;align-items: center;gap: 15px;">
											<span>Service Tax</span>
											<input class="form-control" id="var_service_tax"
												value="<?= (isset($estimationData['var_servicetax'])?$estimationData['var_servicetax']:"0.00") ?>"
												onkeypress="return isNumberKeyWithDot(event);" name="var_service_tax"
												type="text" style="width: 100px; padding: 4px;"
												oninput="adjustmentTotal()">
										</div>
										<span id="var_service_tax_txt">
											<?= (isset($estimationData['var_servicetax'])) ? CURRENCY_ICON.number_format($estimationData['var_servicetax'], '2', '.', '') : CURRENCY_ICON."0.00"; ?>
										</span>
									</div>
									<div class="d-flex justify-content-between align-items-center mb-3">
										<div style="display: flex;align-items: center;gap: 15px;">
											<span>Adjustment</span>
											<input class="form-control" id="var_adjustment"
												value="<?= (isset($estimationData['var_adjustment'])?$estimationData['var_adjustment'] : "0.00"); ?>"
												name="var_adjustment" type="text" oninput="adjustmentTotal()"
												style="width: 100px; padding: 4px;">
										</div>

										<span id="var_adjustment_txt">
											<?= (isset($estimationData['var_adjustment'])) ? CURRENCY_ICON.number_format($estimationData['var_adjustment'], '2', '.', '') : CURRENCY_ICON."0.00"; ?>
										</span>

									</div>
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="m-0">Total</h6>
										<span class="sub-total" id="var_final_amount_txt">
											<?= (isset($estimationData['var_total_amount'])) ? CURRENCY_ICON.number_format($estimationData['var_total_amount'], '2', '.', '') : CURRENCY_ICON."0.00"; ?>
										</span>
										<input type="hidden" name="var_subtotal" id="var_subtotal" value="<?= (isset($estimationData['var_sub_total'])?$estimationData['var_sub_total']: "0.00"); ?>">
										<input type="hidden" name="var_gst" id="var_gst" value="<?= (isset($estimationData['tax'])?$estimationData['tax']: "0.00"); ?>">
										<input type="hidden" name="var_final_amount" id="var_final_amount" value="<?= (isset($estimationData['var_total_amount'])?$estimationData['var_total_amount']: "0.00"); ?>">
										<input type="hidden" name="gst_type" id="gst_type" value="<?= $gstType ?>">
									</div>
								</div>
							</div>
						</div>.
						<button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit"><?= (isset($fk_estimation)?'Update':'Add Estimation') ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="<?= base_url() ?>/public/assets/dist/js/addproject.js"></script>
<script>
	$('#fk_customer').select2('destroy');
	$(".fk_tax").select2();
	getGSTDropDown();


</script>