<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<section class="section">
	<div class="row">
		<div class="col-12">
				<div class="">
					<h4><?=$data['var_project']?> - <?= $data['var_Project_id'] ?></h4>
					<p class="mb-0 text-muted tx-13"></p>
				</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <span><b>Total Project Amount</b> <br>
                                            <?= CURRENCY_ICON.' '.$data['totalProAmt'] ?></span>
                                            <p><?= dateformate(date('Y-m-d')) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-danger">
                                        <div class="card-body">
                                            <span><b>Total Expenses</b> <br>
                                            ₹ <?= $data['totalProExp'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-success">
                                        <div class="card-body">
                                            <span><b>Due Payment</b> <br>
                                            ₹ 100,000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <span><b>Total Payment</b> <br>
                                            ₹ 100,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-danger">
                                        <div class="card-body">
                                            <span><b>Total Expenses</b> <br>
                                            ₹ 100,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 col-sm-3">
                                    <div class="card card-success">
                                        <div class="card-body">
                                            <span><b>Due Payment</b> <br>
                                            ₹ 100,000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-12">
                            <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs " id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="Task-tab" data-toggle="tab" href="#Task" role="tab" aria-controls="Task" aria-selected="true">Task</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="Activites-tab" data-toggle="tab" href="#Activites" role="tab" aria-controls="Activites" aria-selected="false">Activites</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle"  id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option">sales</a>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="nav-link " id="Invoice-tab" data-toggle="tab" href="#Invoice" role="tab" aria-controls="Invoice" aria-selected="false">Invoice</a>
                                                                        
                                        <a class="nav-link " id="Payment-tab" data-toggle="tab" href="#Payment" role="tab" aria-controls="Payment" aria-selected="false">Payment</a>
                                    </div>
                                </li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle"  id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option">Purchase</a>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="nav-link " id="Bill-tab" data-toggle="tab" href="#Bill" role="tab" aria-controls="Bill" aria-selected="false">Bill</a>
                                                                        
                                        <a class="nav-link " id="Challan-tab" data-toggle="tab" href="#Challan" role="tab" aria-controls="Challan" aria-selected="false">Challan</a>
                                        <a class="nav-link " id="Expenses-tab" data-toggle="tab" href="#Expenses" role="tab" aria-controls="Expenses" aria-selected="false">Expenses</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="Estimation-tab" data-toggle="tab" href="#Estimation" role="tab" aria-controls="Estimation" aria-selected="false">Estimation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="Milestone-tab" data-toggle="tab" href="#Milestone" role="tab" aria-controls="Milestone" aria-selected="false">Milestone</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ProjectDocuments-tab" data-toggle="tab" href="#ProjectDocuments" role="tab" aria-controls="ProjectDocuments" aria-selected="false">Project Documents</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="LaborAssignment-tab" data-toggle="tab" href="#LaborAssignment" role="tab" aria-controls="LaborAssignment" aria-selected="false">Labor Assignment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#ProjectItems" role="tab" aria-controls="ProjectItems" aria-selected="false">Project items</a>
                                </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                <div class="tab-pane  fade show active" id="Task" role="tabpanel" aria-labelledby="Task-tab">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tbody><tr>
                                                            <th>#</th>
                                                            <th>Creation date</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Completion date</th>
                                                            <th>Option</th>
                                                        </tr>
                                                        <tr>
                                                           
                                                            <td>1</td>
                                                            <td>Irwansyah Saputra</td>
                                                            <td>Hello How are You</td>
                                                            
                                                            <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option"></button>
                                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjE="><i class="fa fa-eye"></i> View PO List</a>
                                                                    
                                                                    <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjE="><i class="fa fa-eye"></i> View Estimations</a>

                                                                    <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjE="><i class="fa fa-eye"></i> View Milestones</a>

                                                                    <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/projectItem/list/MjE="><i class="fa fa-eye"></i> View items</a>
                                                                    
                                                                </div>
                                                            </div>
                                                            </td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                                
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="Activites" role="tabpanel" aria-labelledby="Activites-tab">
                                <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tbody><tr>
                                                            <th>#</th>
                                                            <th>Activites Title</th>
                                                            <th>Activites Description</th>
                                                            <th>Option</th>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Irwansyah Saputra</td>
                                                            <td>Hello How are You</td>
                                                            <td>
                                                                <div class="dropdown d-inline">
                                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                        
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                                
                                            </div>
                                        </div>  
                                        </div>
                                </div>
                                <div class="tab-pane fade" id="Invoice" role="tabpanel" aria-labelledby="Invoice-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                               
                                                                <th>Invoice Id</th>
                                                                <th>Customer Name</th>
                                                                <th>Payment Status</th>
                                                                <th>Due Date</th>
                                                                <th>Invoice Amount (<?=CURRENCY_ICON?>)</th>
                                                                <th>Balance Due (<?=CURRENCY_ICON?>)</th>
                                                                <th>Option</th>

                                                            </tr>
                                                          

                                                            <?php 
                                                            if(!empty($data['invoice']))
                                                            {
                                                                foreach ($data['invoice'] as $key => $value) { ?>
                                                              <tr>
                                                                     <td><?=$value['var_Invoice_id'];?></td>
                                                                     <td><?=$value['customer_name'];?></td>
                                                                     <td><?=$value['var_payment_status'];?></td>
                                                                     <td><?=$value['var_invoice_date'];?></td>
                                                                     <td><?= CURRENCY_ICON.$value['var_invoice_amount'];?></td>
                                                                     <td><?=CURRENCY_ICON.$value['var_due_amount'];?></td>
                                                                     </tr> 
                                                            <?php } }?>
                                                            
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Payment" role="tabpanel" aria-labelledby="Payment-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                                
                                                                <th>Received ID</th>
                                                                <th>Company Profile</th>
                                                                <th>Customer Name</th>
                                                                <th>Received Amount (<?=CURRENCY_ICON?>)</th>
                                                                <th>Remaining Amount (<?=CURRENCY_ICON?>)</th>
                                                                <th>Payment Date</th>
                                                                <th>Payment Mode (<?=CURRENCY_ICON?>)</th>
                                                                <th>Option</th>

                                                            </tr>
                                                            <?php 
                                                            if(!empty($data['Payment']))
                                                            {

                                                            
                                                            foreach ($data['Payment'] as $key => $value) { ?>
                                                              <tr>
                                                                     <td><?=$value['var_received_id'];?></td>
                                                                     <td><?=$value['var_name'];?></td>
                                                                     <td><?=$value['customer_name'];?></td>
                                                                     <td><?=CURRENCY_ICON.$value['var_received_amount'];?></td>
                                                                     <td><?=CURRENCY_ICON.$value['unusedAmount'];?></td>
                                                                     <td><?=$value['var_payment_date'];?></td>
                                                                     <td><?=$value['var_payment_mode'];?></td>
                                                                     <td></td>


                                                                     </tr> 
                                                            <?php } }?>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Bill" role="tabpanel" aria-labelledby="Bill-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                                <th>#</th>
                                                                <th>Bill Id</th>
                                                                <th>Vendor Name</th>
                                                                <th>Supervisor Name</th>
                                                                <th>Bill Amount</th>
                                                                <th>Payment Status</th>
                                                                <th>Option</th>

                                                            </tr>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>C_56205</td>
                                                                <td>darshna</td>
                                                                <td>Akshay</td>
                                                                <td>₹5000</td>
                                                                <td>Unpaid</td>
                                                                <td>
                                                                    <div class="dropdown d-inline">
                                                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                            
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Challan" role="tabpanel" aria-labelledby="Challan-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                                <th>#</th>
                                                                <th>Challan Id</th>
                                                                <th>Vendor Name</th>
                                                                <th>Supervisor Name</th>
                                                                <th>Challan Amount</th>
                                                                <th>Payment Status</th>
                                                                <th>Option</th>

                                                            </tr>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>C_56205</td>
                                                                <td>darshna</td>
                                                                <td>Akshay</td>
                                                                <td>₹5000</td>
                                                                <td>Unpaid</td>
                                                                <td>
                                                                    <div class="dropdown d-inline">
                                                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                            
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Expenses" role="tabpanel" aria-labelledby="Expenses-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                                <th>#</th>
                                                                <th>Invoice Id</th>
                                                                <th>Customer Name</th>
                                                                <th>Company Profile</th>
                                                                <th>Invoice Amount</th>
                                                                <th>Payment Status</th>
                                                                <th>Option</th>

                                                            </tr>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>INC_56205</td>
                                                                <td>darshna</td>
                                                                <td>Akshay</td>
                                                                <td>₹5000</td>
                                                                <td>Unpaid</td>
                                                                <td>
                                                                    <div class="dropdown d-inline">
                                                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                            
                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                            <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Estimation" role="tabpanel" aria-labelledby="Estimation-tab">
                                    <div class="container">
                                            <div class="row">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                            <tbody><tr>
                                                                <th>Estimate Title</th>
                                                                <th>Estimate Date</th>
                                                                <th>Total Amount</th>
                                                                <th>Option</th>
                                                            </tr>

                                                            <?php 
                                                            if(!empty($data['Estimation']))
                                                            {
                                                                foreach ($data['Estimation'] as $key => $value) { ?>
                                                              <tr>
                                                                     <td><?=$value['var_estimate_title'];?></td>
                                                                     <td><?=$value['var_date'];?></td>
                                                                     <td><?= CURRENCY_ICON.$value['var_total_amount'];?></td>
                                                                     <td>
                                                                        <div class="dropdown d-inline">
                                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                                <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    </tr> 
                                                            <?php } }?>
                                                            
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div> 
                                            
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="Milestone" role="tabpanel" aria-labelledby="Milestone-tab">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tbody><tr>
                                                           
                                                            <th>Total Amount</th>
                                                            <th>Advance Amount(₹)</th>
                                                            <th>No.Of Milestone</th>
                                                            <th>Date</th>
                                                            <th>Option</th>

                                                        </tr>
                                                        <?php 
                                                            if(!empty($data['Milestone']))
                                                            {
                                                                foreach ($data['Milestone'] as $key => $value) { ?>
                                                              <tr>
                                                                     <td><?=CURRENCY_ICON.$value['var_amount'];?></td>
                                                                     <td><?=CURRENCY_ICON.$value['var_advance_amount'];?></td>
                                                                     <td><?=$value['var_no_of_milestone'];?></td>
                                                                     <td><?=$value['dt_createddate'];?></td>

                                                                     <td>
                                                                        <div class="dropdown d-inline">
                                                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                                <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    </tr> 
                                                            <?php } }?>
                                                            
                                                    </tbody>
                                                    </table>
                                                </div>
                                            
                                            </div>
                                        </div>  
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="ProjectDocuments" role="tabpanel" aria-labelledby="ProjectDocuments-tab">
                                    <div class="container">
                                        <div class="row">
                                        <div class="card-body">
                                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?=base_url()?>/public/assets/img/image-gallery/1.png" data-sub-html="Demo Description">
                                            <img class="img-responsive thumbnail" src="<?=base_url()?>/public/assets/img/image-gallery/thumb/thumb-1.png" alt="">
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?=base_url()?>/public/assets/img/image-gallery/2.png" data-sub-html="Demo Description">
                                            <img class="img-responsive thumbnail" src="<?=base_url()?>/public/assets/img/image-gallery/thumb/thumb-2.png" alt="">
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?=base_url()?>/public/assets/img/image-gallery/3.png" data-sub-html="Demo Description">
                                            <img class="img-responsive thumbnail" src="<?=base_url()?>/public/assets/img/image-gallery/thumb/thumb-3.png" alt="">
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?=base_url()?>/public/assets/img/image-gallery/4.png" data-sub-html="Demo Description">
                                            <img class="img-responsive thumbnail" src="<?=base_url()?>/public/assets/img/image-gallery/thumb/thumb-4.png" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="LaborAssignment" role="tabpanel" aria-labelledby="LaborAssignment-tab">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tbody><tr>
                                                            <th>#</th>
                                                            <th>Labor Type</th>
                                                            <th>No. Of Assign</th>
                                                            <th>Start date</th>
                                                            <th>Days</th>
                                                            <th>Amount</th>
                                                            <th>Option</th>

                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Irwansyah Saputra</td>
                                                            <td>12</td>
                                                            <td>2023-10-26</td>
                                                            <td>1</td>
                                                            <td>100</td>
                                                            <td>
                                                                <div class="dropdown d-inline">
                                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                        
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                            
                                            </div>
                                        </div>  
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="ProjectItems" role="tabpanel" aria-labelledby="ProjectItems-tab">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tbody><tr>
                                                            <th>#</th>
                                                            <th>Project Name</th>
                                                            <th>Material Name</th>
                                                            <th>Total Stock</th>
                                                            <th>Total Remaining Stock</th>
                                                            <th>Option</th>

                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Irwansyah Saputra</td>
                                                            <td>demo</td>
                                                            <td>5</td>
                                                            <td>3</td>
                                                            <td>
                                                                <div class="dropdown d-inline">
                                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Option"></button>
                                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -10px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/POList/MjI="><i class="fa fa-eye"></i>Edit</a>
                                                                        
                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/EstimateList/MjI="><i class="fa fa-eye"></i> View</a>

                                                                        <a class="dropdown-item has-icon" href="https://www.citechnology.in/BDC/admin/project/MilestoneList/MjI="><i class="fa fa-eye"></i> Delete</a>

                                                                    </div>
                                                                </div></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                            
                                            </div>
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
</script>