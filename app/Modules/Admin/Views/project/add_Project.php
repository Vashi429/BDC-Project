<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/select2/dist/css/select2.min.css">
<script src="<?= base_url() ?>/public/assets/bundles/select2/dist/js/select2.full.min.js"></script>
<style>
    .select2-container {
        width: 100% !important;
    }
	.side_menu_deactive{
		pointer-events: none;
	}
	.task_save{
    	display: block;
    	margin: 0px 0px 7px auto;
	}
</style>
<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/project">View Project</a></li>
					<li class="breadcrumb-item">Add Project</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Create New Project</h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
				<div class="card-body">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-3" style="border-right: 1px solid #f15a2433;">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active side_menu_deactive" id="home-tab" data-toggle="tab" href="#home" role="tab"
                              aria-controls="home" aria-selected="true">Project Information</a>
                          </li>
                          <li class="nav-item">
                            <!-- <a class="nav-link side_menu_deactive" id="Purchase-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="Purchase" aria-selected="false">PO and Other Documets</a> -->
                            <a class="nav-link side_menu_deactive" id="Purchase-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="Purchase" aria-selected="false">PO and Other Documets</a>
                          </li>
                          <li class="nav-item" onclick="getGSTDropDown()">
                            <a class="nav-link side_menu_deactive" id="estimate-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="estimate" aria-selected="false">Estimate Management</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link side_menu_deactive" id="milestone-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="milestone" aria-selected="false">Milestone Payments</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link side_menu_deactive" id="task-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="task" aria-selected="false">Create Task</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link side_menu_deactive" id="documents-tab" data-toggle="tab" href="javascript:void(0)" role="tab"
                              aria-controls="documents" aria-selected="false">All Documets</a>
                          </li>
                        </ul>						
                      </div>
                      <div class="col-12 col-sm-12 col-md-9">
                        <input type="hidden" name="fk_project" id="fk_project" value="2">
                        <div class="tab-content no-padding" id="myTab2Content">
                          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="" id="projectinfo" enctype="multipart/form-data" method="POST">
                                <div class="row">
                                    <div class="col-12 col-lg-6 col-md-6">
                						<div class="form-group">
                							<label class="form-label mg-b-0" for="fk_customer ">Customer Name <span class="text-danger">*</span></label>
                							<select class="form-control" id="fk_customer" name="fk_customer" required>
                							    <option value=""></option>
                								<?php if (!empty($customer)) {
                									foreach ($customer as $value) { ?>
                										<option value="<?= $value['int_glcode'] ?>"><?= $value['var_displayname'] ?></option>
                									<?php }
                								} ?>
                							</select>
                						</div>
                					</div>
									<div class="col-12 col-lg-6 col-md-6">
                						<div class="form-group">
                							<label class="form-label mg-b-0" for="fk_profile ">Company Profile<span class="text-danger">*</span></label>
                							<select class="form-control" id="fk_profile" name="fk_profile" required>
                							    <option value=""></option>
                								<?php if (!empty($companyProfile)) {
                									foreach ($companyProfile as $value) { ?>
                										<option value="<?= $value['int_glcode'] ?>"><?= $value['var_name'] ?></option>
                									<?php }
                								} ?>
                							</select>
                						</div>
                					</div>
									
                					<div class="col-12 col-lg-6 col-md-6">
        								<div class="form-group">
        									<label for="var_project_type">Project Type <span class="text-danger">*</span></label>
        									<input class="form-control" id="var_project_type" name="var_project_type" required="" type="text">
        									<div id="suggetion_display_name"></div>
        								</div>
        							</div>
                                    <div class="col-12 col-lg-8 col-md-6">
        								<div class="form-group">
        									<label for="var_project">Project Name <span class="text-danger">*</span></label>
        									<input class="form-control" id="var_project" name="var_project" required="" type="text">
        									<div id="suggetion_display_name"></div>
        								</div>
        							</div>
									<div class="col-12 col-lg-4 col-md-6">
        								<div class="form-group">
        									<label for="var_Project_id">Project Id <span class="text-danger">*</span></label>
        									<input class="form-control" id="var_Project_id" name="var_Project_id" required="" value='<?= $autoProjectID ?>' type="text" readonly>
        									<div id="suggetion_display_name"></div>
        								</div>
        							</div>
                					<div class="col-12 col-lg-12 col-md-6">
                						<div class="form-group">
                							<label for="var_address">Address <span class="text-danger">*</span></label>
                							<textarea class="form-control" id="var_address" name="var_address" required></textarea>
                						</div>
                					</div>
        							<div class="col-12 col-lg-4 col-md-4">
                						<div class="form-group">
                							<label class="form-label mg-b-0" for="fk_state ">State <span class="text-danger">*</span></label>
                							<select class="form-control" id="fk_state" name="fk_state" onchange="getCityList(this.value,'fk_city')" required>
                							    <option value=""></option>
                								<?php if (!empty($states)) {
                									foreach ($states as $value) { ?>
                										<option value="<?= $value['int_glcode'] ?>"><?= $value['var_title'] ?></option>
                									<?php }
                								} ?>
                							</select>
                						</div>
                					</div>
                					<div class="col-12 col-lg-4 col-md-4">
                						<div class="form-group">
                							<label class="form-label mg-b-0" for="fk_city">City <span class="text-danger">*</span></label>
                							<select class="form-control" id="fk_city" name="fk_city" required>
                							    <option value=""></option>
                							</select>
                						</div>
                					</div>
                					<div class="col-12 col-lg-4 col-md-4">
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
                					<div class="col-12 col-lg-4 col-md-4">
                						<div class="form-group">
                							<label for="var_pincode">Pincode <span class="text-danger">*</span></label>
                							<input class="form-control" id="var_pincode" name="var_pincode" required="" type="text" minlength="6" maxlength="6" onkeyup="return isNumberKey(event)">
                						</div>
                					</div>
                					<div class="col-12 col-lg-4 col-md-4">
        								<div class="form-group">
        									<label>Start Date <span class="text-danger">*</span></label>
        									<input type="text" class="form-control datepicker" id="start_date" name="start_date" required>
        								</div>
        							</div>
        							<div class="col-12 col-lg-4 col-md-4">
        								<div class="form-group">
        									<label>End Date <span class="text-danger">*</span></label>
        									<input type="text" class="form-control datepicker" id="end_date" name="end_date" required>
        								</div>
        							</div>
            					</div>
            					<button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save & Continue</button>
            				</form>
                          </div>
                          
                          <div class="tab-pane fade" id="Purchase" role="tabpanel" aria-labelledby="Purchase-tab">
                            <form action="" id="projectPOdocs" enctype="multipart/form-data" method="POST">
                                <div class="col-12">
                                    <div class="form-group">
            							<label>Purchase Order and Other Documets</label>
            						</div>
            						<div id="ddAreaPlan" class="ddArea">
            							<span>Drag & Drop or Click here to browse</span>
            						</div>
            						<input type="hidden" value="" name="uploaded_plans" id="uploaded_plans">
            						<span id="chk-plan-error" class="text-danger"></span>
            						<input type="file" class="d-none" id="selectfile" multiple />
            					</div>
                    			<div class="col-12">
            						<div class="form-group row" id="showThumbPlan"></div>
            					</div>
								<div class="col-12">
									<div class="form-group" id="showCurrentPlan"></div>
            					</div>
            					<button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save & Continue</button>
                            </form>
                          </div>
                          
                          <div class="tab-pane fade" id="estimate" role="tabpanel" aria-labelledby="estimate-tab">
                            <form action="" id="projectEstimate" enctype="multipart/form-data" method="POST">
                                <div class="col-12 col-lg-8 col-md-6">
    								<div class="form-group">
    									<label for="var_estimate_title">Estimate Title <span class="text-danger">*</span></label>
    									<input class="form-control" id="var_estimate_title" name="var_estimate_title" required="" type="text">
    								</div>
    							</div>
    							<div class="col-12 col-lg-8 col-md-6">
    								<div class="form-group">
    									<label for="var_date">Estimate Date <span class="text-danger">*</span></label>
    									<input class="form-control datepicker" id="var_date" name="var_date" required="" type="text">
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
        												<th>Rate</th>
        												<th>Tax</th>
        												<th>Amount</th>
        												<th>Option</th>
        											</tr>
        										</thead>
        										<tbody class="addEstimateItems">
													<tr class="addEstimates">
														<td>
															<input class="form-control" id="var_item1" name="var_item[]" required="" type="text" placeholder="Item Name" minlength="3" maxlength="500">
														</td>
														<td>
															<input class="form-control" id="var_quantity1" name="var_quantity[]" onkeyup="return isNumberKey(event);" required="" type="text" value="0" maxlength="5" onfocusOut ="itemTotalAmount(1)">
														</td>
														<td>
															<input class="form-control" id="var_rate1" name="var_rate[]" onkeyup="return isNumberKeyWithDot(event);" required="" type="text" value="0" maxlength="15" onfocusOut ="itemTotalAmount(1)">
															
														</td>
														<td>
															<select class="form-control fk_tax" name="fk_tax[]" id="fk_tax1" onchange ="itemTotalAmount(1)">
																<option value="" disabled>Tax</option>
																<option value="0">No GST</option>
																<?php if(!empty($gst)){
																	foreach($gst as $value){ 
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
															<a href="javascript:void(0)" class="add-invoice" id="btnAddEstimate"><i class="fas fa-plus m-0"></i></a>
														</td>
													</tr>
        										</tbody>
        									</table>
        								</div>
        							</div>
        							<div class="col-lg-6 mt-5">
        								<div class="form-group">
        									<label for="var_customer_note">Notes </label>
        									<textarea class="form-control" id="var_customer_note" name="var_customer_note"></textarea>
        								</div>
        							</div>
        							<div class="col-lg-6">
        								<div class="invoice-total-sec">
        									<div class="d-flex justify-content-between align-items-center mb-3">
        										<span>Sub Total</span>
        										<span id="var_subtotal_txt">0.00</span>
        									</div>
											<div id="sub_total_gst_div"></div>
        									<div class="d-flex justify-content-between align-items-center mb-3">
        										<div style="display: flex;align-items: center;gap: 15px;">
        											<span>Service Tax</span>
        											<input class="form-control" id="var_service_tax" name="var_service_tax" type="text" style="width: 100px; padding: 4px;" oninput="adjustmentTotal()" onkeyup="return isNumberKeyWithDot(event);">
        										</div>
        										<span id="var_service_tax_txt">0.00</span>
        									</div>
        									<div class="d-flex justify-content-between align-items-center mb-3">
        										<div style="display: flex;align-items: center;gap: 15px;">
        											<span>Adjustment</span>
													<input class="form-control" id="var_adjustment" name="var_adjustment" type="text" oninput="adjustmentTotal()" style="width: 100px; padding: 4px;" onkeyup="return isNumberKeyWithDot(event);">
        										</div>
        										<span id="var_adjustment_txt">0.00</span>
        									</div>
        									<div class="d-flex justify-content-between align-items-center">
        										<h6 class="m-0">Total</h6>
        										<span class="sub-total" id="var_final_amount_txt">0.00</span>
												<input type="hidden" name = "var_subtotal" id="var_subtotal" value="0" >
												<input type="hidden" name = "var_gst" id="var_gst" value="0">
												<input type="hidden" name = "var_final_amount" id="var_final_amount" value="0">
												<input type="hidden" name = "gst_type" id="gst_type" value="GST">
        									</div>
        								</div>
        							</div>
        						</div>.
        						<button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save & Continue</button>
                            </form>
                          </div>
                          
                          <div class="tab-pane fade" id="milestone" role="tabpanel" aria-labelledby="milestone-tab">
                            <form action="" id="projectmilestone" enctype="multipart/form-data" method="POST">
								<div class="form-group row">
									<label class="col-sm-3 col-lg-3 col-form-label d-flex align-items-center">Total Amount</label>
        							<div class="col-sm-7 col-lg-4">
										<input type="text" readonly="" id="total_amount" name="total_amount" class="form-control" value="0">
        							</div>
        						</div>
								<div class="form-group row">
									<label class="col-sm-3 col-lg-3 col-form-label d-flex align-items-center">Advance Amount</label>
        							<div class="col-sm-7 col-lg-4">
										<input type="text" id="advance_total_amount" name="advance_total_amount" class="form-control" value="" onkeyup="return isNumberKeyWithDot(event)" oninput="getAdvancePayPer()">
										<input type="hidden" id="advance_total_per" name="advance_total_per">
        							</div>
        						</div>
								<div class="form-group row">
									<label class="col-sm-3 col-lg-3 col-form-label d-flex align-items-center">No. of Milestone</label>
									<div class="col-sm-7 col-lg-4">
										<input type="text" class="form-control" id="milestone_no" name="milestone_no" value="" maxlength="6" onkeyup="return isNumberKey(event)">
									</div>
									<div class="col-sm-2 col-lg-2">
										<button class="btn btn-primary" type="button" onclick="creat_milestone_no()">Create</button>
									</div>
								</div>
        						<div class="col-12">
    								<!-- <h6>Item Details</h6> -->
    								<div class="table-responsive">
    									<table id="mainTable" class="table table-striped milestone-table">
    										<thead>
    											<tr>
    												<th>Discription</th>
    												<th>Percentage</th>
    												<th>Payment (<?= CURRENCY_ICON;?>)</th>
    												<th>Date</th>
    											</tr>
    										</thead>
    										<tbody class="addpayment"></tbody>
    									</table>
    								</div>
    							</div>
                                <button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save & Continue</button>
                            </form>
                          </div>
                          <div class="tab-pane fade" id="task" role="tabpanel" aria-labelledby="task-tab">
                                <form action="" id="projectTask" enctype="multipart/form-data" method="POST">
                                    
                                    <input type="hidden" name="no_row_T" value="1" id="no_row_T">
                                    <div class="addTask mb-3">
										<div class="row addtaskmore">
											<div class="col-12 d-flex justify-content-between">
												<h6 class="mt-2">Task 1</h6> <a href="javascript:void(0);" class="remove-invoice m-0" id="btntaskremove"><i class="fas fa-minus m-0"></i></a>
											</div>
											<div class="col-12 col-lg-12 col-md-6">
												<div class="form-group">
													<label for="var_title">Task Title <span class="text-danger">*</span></label>
													<input class="form-control" id="var_title1" name="var_title[]" required="" type="text">
												</div>
											</div>
											<div class="col-12 col-lg-12 col-md-6">
												<div class="form-group">
													<label for="var_title">Task Details <span class="text-danger">*</span></label>
													<textarea class="form-control summernote" id="var_details1" name="var_details[]" required></textarea>
												</div>
											</div>
										</div>
                                    </div>
									<button class="btn btn-warning pd-x-20 mg-t-10 task_save" id="btnAddtask">Add task</button>
                                    <button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save & Continue</button>
                                </form>
                          </div>
                          <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
						  	<div class="col-12">
								<div class="form-group row" id="showAllDocument"></div>
								<div class="form-group row" id="showAllDocuments"></div>
            				</div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>
<script src="<?=base_url()?>/public/assets/dist/js/addproject.js"></script>
<script>
	$(".fk_tax").select2();
	$("#fk_profile").select2({
		placeholder: 'Select Profile',
	});
</script>