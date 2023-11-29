<?php
$maxMilestone = $total_estimation_amount - $total_milestone_amount;
?>
<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/project/milestoneList/<?= base64_encode($data['int_glcode']) ?>">View Milestones</a></li>
					<li class="breadcrumb-item">Edit Milestone</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Edit Milestone of <?= $data['var_project'] ?></h4>
				</div>
				<div class="card-body">
                    <form action="" id="projectmilestone" enctype="multipart/form-data" method="POST">
                        <input type="hidden" id="var_date" name="var_date" value="<?=date('Y-m-d');?>">
                        <input type="hidden" id="fk_project" name="fk_project" value="<?=$data['int_glcode'];?>">
                        <input type="hidden" id="end_date" name="end_date" value="<?=$data['end_date'];?>">
                        <input type="hidden" id="fk_milestone" name="fk_milestone"  value="<?= $fk_milestone ?>">
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                <label>Milestone Amount</label>
                                <input type="text"  id="var_payment" name="var_payment" class="form-control" value="<?= $milestonedata['var_payment']; ?>"  onkeyup="return isNumberKeyWithDot(event)" max="<?= $maxMilestone ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Milestone Date</label>
                                <input type="text" id="var_date datepicker" name="var_date" class="form-control" value="<?= $milestonedata['var_date'] ?>">
                            </div>
                            <div class="col-md-3">
                                <label>No. of Milestone</label>
                                <input type="text" class="form-control" id="milestone_no" name="milestone_no" maxlength="6" onkeyup="return isNumberKey(event)" >
                                
                            </div>

                            <div class="col-md-2" style="margin-top: 33px;">
                                <label></label>
                                <button class="btn btn-primary" type="button" onclick="creat_milestone_no()">Create</button>
                            </div>
                        </div>
                                
                  
                        <button class="btn btn-primary pd-x-20 mg-t-10 project_save" type="submit">Save</button>
                    </form>
                </div>
			</div>
		</div>
	</div>
</section> 
<script src="https://www.citechnology.in/BDC/public/assets/dist/js/addproject.js"></script>