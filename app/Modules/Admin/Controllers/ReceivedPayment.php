<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\CommonModel;
use App\Modules\Admin\Models\ReceivedPaymentModel;
define('Views', 'App\Modules\Admin\Views\receivedPayment');
class ReceivedPayment extends BaseController{
	public $CommonModel;
	public $ReceivedPaymentModel;
	function __construct(){
		$this->CommonModel = new CommonModel();
		$this->ReceivedPaymentModel = new ReceivedPaymentModel();
	}

	/* List of received payments start */
	public function index(){
		$data['data'] = $this->ReceivedPaymentModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->ReceivedPaymentModel->total_records_count();
		$data['total_rows'] = $this->ReceivedPaymentModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Received Payment List';
		$data['view'] = Views . '\view_receivedPayment';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* List of received payments end */

	/* List of received payments with pagination, search start */
	public function loadData($rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}

		$data['total_rows'] = $allcount = $this->ReceivedPaymentModel->filter_records_count($search);		
		$data['total_data'] =  $this->ReceivedPaymentModel->total_records_count();
		$data['result'] = $this->ReceivedPaymentModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);
	}
	/* List of received payments with pagination, search end */

	/* add received payments page start */
	public function addReceivedPayment(){
	    $where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['customerData'] = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');
		$data['companyProfileData'] = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$data['autoReceivedID'] = 'REC_PAY_' . $this->CommonModel->getUniqueAutoId('mst_invoice', 'var_Invoice_id');
		$data['data'] = array();
		$data['title'] = 'Add Received Payment';
		$data['view'] = Views . '\add_receivedPayment';
		$data['heading'] = 'Welcome to BDC Project';
		
		return view('admin_tempate/template', $data);
	}
	/* add received payments page end */

	/* edit received payments page start */
	public function editReceivedPayment($id){
		$id = base64_decode($id);
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['data'] = $receivedPayment = $this->ReceivedPaymentModel->getReceivedPayment($id);
		$data['customerData'] = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');

		$data['projectData'] = $this->ReceivedPaymentModel->getProjects($receivedPayment['fk_customer']);
		$data['companyProfileData'] = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$data['title'] = 'Edit Received Payment';
		$data['view'] = Views . '\edit_receivedPayment';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* edit received payments page end */

	/* delete multiple rows of received payments start */
	public function delete_multiple(){
		if(!empty($_POST['id'])){
			foreach($_POST['id'] as $val){
				$recPayInvList = $this->CommonModel->getResultArray('mst_received_invoice', '*', array('fk_received_id' => $val));
				if(!empty($recPayInvList)){
					foreach($recPayInvList as $rp_val){
						$int_amt = $this->CommonModel->getRowArray('mst_invoice_amount', 'var_due_amount, var_paid_amount', array('fk_invoice' => $rp_val['fk_invoice']));
						$this->CommonModel->updateRows('mst_invoice_amount', array('var_due_amount' => $int_amt['var_due_amount'] + $rp_val['var_amount'], 'var_paid_amount' => $int_amt['var_paid_amount'] - $rp_val['var_amount']), array('fk_invoice' => $rp_val['fk_invoice']));
						// echo $this->con->getLastQuery();die();
						$checkInvoiceDueAmt = $this->CommonModel->getValById('mst_invoice_amount', 'var_due_amount', array('fk_invoice' => $rp_val['fk_invoice']));
						if($checkInvoiceDueAmt == 0){
							$payment_status = 'Paid';
						}else{
							$payment_status = 'Unpaid';
						}
						$this->CommonModel->updateRows('mst_invoice', array('var_payment_status' => $payment_status), array('int_glcode' => $rp_val['fk_invoice']));
						$this->CommonModel->deleteRow('mst_received_invoice', array('int_glcode' =>  $rp_val['int_glcode']));
					}
				}
				$this->CommonModel->deleteRow('mst_received_invoice', array('fk_received_id' => $val));
			}
		}
		$result = $this->CommonModel->delete_multiple('mst_received_payment');
		echo $result;
	}
	/* delete multiple rows of received payments end */

	/* add the invoice list on change of customer and project start */
	public function addinvoicelist(){
		$fk_project = $_POST['fk_project'];
		$fk_customer = $_POST['fk_customer'];
		
		$id = $_POST['id'];
		if($id > 0){
			$receivedPayment = $this->ReceivedPaymentModel->getReceivedPayment($id);
			$var_due_amount = $receivedPayment['var_received_amount'];
		}else{
			$var_due_amount = 0;
		}
		$invoice = $this->ReceivedPaymentModel->getInvoiceArray($fk_project, $fk_customer, $id);
		$html = '';
		foreach($invoice as $val){

			$var_due_amount += $val["var_due_amount"];
			$html.='<tr>
				<td>'.$val["var_Invoice_id"].'</td>
				<td>'.$val["var_invoice_date"].'</td>
				<td>'.$val["var_invoice_amount"].'</td>
				<td>'.$val["var_paid_amount"].'</td>
				<td>'.$val["var_due_amount"].'</td>
				<td>
					<input class="form-control var_payment" id="var_payment'.$val['int_glcode'].'" name="var_payment[]" type="text" maxlength="15" oninput="checkPaidAmount('.$val['int_glcode'].'), isNumberKeyWithDot(event);" value="'.$val['is_selected'].'" data-val="'.$val['is_selected'].'">
					<span class="text-danger" id="error_var_payment'.$val['int_glcode'].'"></span>
					<input type ="hidden" id="invoiceItemID'.$val['int_glcode'].'" name="invoiceItemId[]" value="'.$val['int_glcode'].'">
					<input type ="hidden" id="receivedInvoiceItemID'.$val['int_glcode'].'" name="receivedInvoiceItemId[]" value="'.$val['receivedInvoice_id'].'">
					<input type ="hidden" id="invoiceID'.$val['int_glcode'].'" name="invoiceId[]" value="'.$val['fk_invoice'].'">
					<input type ="hidden" id="invoiceDureAmount'.$val['int_glcode'].'" name="invoiceDureAmount[]" value="'.$val['var_due_amount'].'">
				</td>
			</tr>';
		}
		$html .='<input type ="hidden" id="total_due_amount" name="total_due_amount" value="'.$var_due_amount.'">';
		echo $html;
	}
	/* add the invoice list on change of customer and project end */

	/* on click of tabing get the tabview as per the tabing start */
	public function tabview(){
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$var_received_id = $_POST['var_received_id'];
		$id = $_POST['int_glcode'];
		$profiles = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
			
		$receivedPayment = $projectsData = array();
		if($id > 0){
			$receivedPayment = $this->ReceivedPaymentModel->getReceivedPayment($id);
			$projectsData = $this->ReceivedPaymentModel->getProjects($receivedPayment['fk_customer']);
			// $profiles = $this->ReceivedPaymentModel->getCompanyProfile($receivedPayment['fk_customer']);
		

		}
		$flag = $_POST['flag'];
		if($flag=='CA'){
			$tab = 'advance';
			$formId = 'ReceivedAdvance';
			$paymentType = 'Customer Advance';
		}else{
			$tab = 'invoice';
			$formId = 'ReceivedInvoice';
			$paymentType = 'Invoice Payment';
		}
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$customers = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');
		
		$html = '';
		$html .= '<div class="tab-pane fade show active" id="'.$tab.'" role="tabpanel" aria-labelledby="'.$tab.'-tab">
			<form action="javascript:void(0)" id="'.$formId.'" enctype="multipart/form-data" method="POST">
				<input type="hidden" name="var_payment_type" id="var_payment_type" value="'.$paymentType.'">';
				if(!empty($receivedPayment)){
					$html .= '<input type="hidden" name="reveived_payment_id" id="reveived_payment_id" value="'.$receivedPayment['int_glcode'].'">';
				}

				$html.='<div class="form-group row">
					<label for="fk_customer" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Customer <span class="text-danger">*</span></label>
					<div class="col-sm-9 col-lg-4">
						<select class="form-control fk_customer" name="fk_customer" id="fk_customer" onchange ="getProjects(), addinvoicelist(), getCompanyProfile()" required>
							<option value=""></option>';
							if(!empty($customers)){
								foreach($customers as $customer){
									$selected = '';
									if(!empty($receivedPayment) && $receivedPayment['fk_customer']==$customer['int_glcode']){
										$selected = 'selected';
									}
									$html .= '<option value="'.$customer['int_glcode'].'" '.$selected.'>'.$customer['var_displayname'].'</option>';
								}
							}
						$html .= '</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="fk_project" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Project </label>
					<div class="col-sm-9 col-lg-4">
						<select class="form-control fk_project" name="fk_project" id="fk_project" onchange ="addinvoicelist()">
							<option value=""></option>';
							if(!empty($projectsData)){
								foreach($projectsData as $val){
									$selected = "";
									if(!empty($receivedPayment) && $receivedPayment['fk_project']==$val['int_glcode']){
										$selected = 'selected';
									}
									$html .= '<option value="'.$val['int_glcode'].'" '.$selected.'>'.$val['var_project'].'</option>';
								}
							}
						$html .= '</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="fk_profile" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Company Profile <span class="text-danger">*</span></label>
					<div class="col-sm-9 col-lg-4">
						<select class="form-control fk_profile" name="fk_profile" id="fk_profile" required>
							<option value=""></option>';
							if(!empty($profiles)){
								foreach($profiles as $val){
									$selected = "";
									if(!empty($receivedPayment) && $receivedPayment['fk_profile']==$val['int_glcode']){
										$selected = 'selected';
									}
									$html .= '<option value="'.$val['int_glcode'].'" '.$selected.'>'.$val['var_name'].'</option>';
								}
							}
						$html .= '</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="var_received_id" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Payment Id <span class="text-danger">*</span></label>
					<div class="col-sm-9 col-lg-4">
						<input type="text" readonly="" name="var_received_id" id="var_received_id" class="form-control" value="'.$var_received_id.'" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="var_payment_date" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Date <span class="text-danger">*</span></label>
					<div class="col-sm-9 col-lg-4">';
						$var_payment_date = '';
						if(!empty($receivedPayment)){
							$var_payment_date = $receivedPayment['var_payment_date'];
						}
						$html .= '<input type="date" name="var_payment_date" id="var_payment_date" class="form-control datepicker" value="'.$var_payment_date.'" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="var_received_amount" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Amount <span class="text-danger">*</span></label>
					<div class="col-sm-9 col-lg-4">';
						$var_received_amount = 0;
						if(!empty($receivedPayment)){
							$var_received_amount = $receivedPayment['var_received_amount'];
						}
					
						$html.= '<input type="text" name="var_received_amount" id="var_received_amount" class="form-control" required oninput="checkTotalPaidAmount(), isNumberKeyWithDot(event);" maxlength="15" data-val="'.$var_received_amount.'" value="'.$var_received_amount.'">
						<span class="text-danger" id="error_var_received_amount"></span>
					</div>
				</div>';
				if($flag=='CA'){ 
					$html .='<div class="form-group row">
						<label for="var_payment_mode" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Payment Mode<span class="text-danger">*</span></label>
						<div class="col-sm-9 col-lg-4">
							<select class="form-control" name="var_payment_mode" id="var_payment_mode" required onchange="displayChequeNo()">
								<option value="">Select Payment Mode</option>
								<option value="Cash" ';
								if(!empty($receivedPayment) && $receivedPayment['var_payment_mode']=='Cash'){
									$html.='selected';
								}
								$html.='>Petty Cash</option>
								<option value="Online" ';
								if(!empty($receivedPayment) && $receivedPayment['var_payment_mode']=='Online'){
									$html.='selected';
								}
								$html.='>Online</option>
								<option value="RTGS" ';
								if(!empty($receivedPayment) && $receivedPayment['var_payment_mode']=='RTGS'){
									$html.='selected';
								}
								$html.='>RTGS</option>
								<option value="Cheque" ';
								if(!empty($receivedPayment) && $receivedPayment['var_payment_mode']=='Cheque'){
									$html.='selected';
								}
								$html.='>Cheque</option>
							</select>
						</div>
					</div>';
					if(!empty($receivedPayment) && $receivedPayment['var_payment_mode']=='Cheque'){
						$class= '';
						$var_cheque_no = $receivedPayment['var_cheque_no'];
					}else{
						$class='d-none';
						$var_cheque_no = '';
					}
					
					$html.='<div class="form-group row var_cheque_no '.$class.'">
						<label for="var_cheque_no" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Cheque No.</label>
						<div class="col-sm-9 col-lg-4">
							<input type="text" name="var_cheque_no" id="var_cheque_no" class="form-control" value="'.$var_cheque_no.'">
							<span class="text-danger" id="error_var_cheque_no"></span>
						</div>
					</div>';
				}else{
					$html .= '<div class="col-12">
						<h6>Unpaid Invoices</h6> 
						<div class="table-responsive">
							<table id="mainTable" class="table table-striped received-table">
								<thead>
									<tr>
										<th>Invoice Id</th>
										<th>Invoice Date</th>
										<th>Invoice Amount ('.CURRENCY_ICON.')</th>
										<th>Paid Amount ('.CURRENCY_ICON.')</th>
										<th>Due Amount ('.CURRENCY_ICON.')</th>
										<th>Payment ('.CURRENCY_ICON.')</th>
									</tr>
								</thead>
								<tbody class="addinvoicelist"><input type ="hidden" id="total_due_amount" name="total_due_amount" value="0"></tbody>
							</table>
						</div>
					</div>';
				}
				$html .= '<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Save</button>
			</form>
		</div>';

		echo $html; exit;
	}
	/* on click of tabing get the tabview as per the tabing end */

	/* insert received payment start */
	public function insertRecord(){
		$insert = $this->ReceivedPaymentModel->addRecord();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Received payment added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* insert received payment end */

	/* update received payment start */
	public function updateRecord($id){
		$insert = $this->ReceivedPaymentModel->updateRecord($id);
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Received payment added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* update received payment end */

	/* get customer existing projects on change of customer start */
	public function getProjects(){
		$projectsList = $this->ReceivedPaymentModel->getProjects($_POST['fk_customer']);
		$html = '<option value="">Select project</option>';
		if(!empty($projectsList)){
			foreach($projectsList as $val){
				$html .= '<option value="'.$val['int_glcode'].'">'.$val['var_project'].'</option>';
			}
		}
		echo $html; exit;
	}
	/* get customer existing projects on change of customer end */

	/* get company profile of invoice on change of customer start */
	public function getCompanyProfile(){
		$companyProfileList = $this->ReceivedPaymentModel->getCompanyProfile($_POST['fk_customer']);
		$html = '<option value="">Select profile</option>';
		if(!empty($companyProfileList)){
			foreach($companyProfileList as $val){
				$html .= '<option value="'.$val['int_glcode'].'">'.$val['var_name'].'</option>';
			}
		}
		echo $html; exit;
	}
	/* get company profile of invoice on change of customer end */

	/* Apply to invoice if the invoice is as a customer advance type  start */
	public function applyToInvoice($id){
		$id = base64_decode($id);
		$paymentdata = $this->CommonModel->getRowArray('mst_received_payment', 'fk_project, fk_customer,var_received_amount', array('int_glcode' => $id));
		$data['invoice'] = $this->ReceivedPaymentModel->getInvoiceArray($paymentdata['fk_project'], $paymentdata['fk_customer'], $id);
		$data['paymentId'] = $id;
		$data['fk_project'] = $paymentdata['fk_project'];
		$data['totalCredit'] = $paymentdata['var_received_amount'] - $this->ReceivedPaymentModel->totalUsedCreditOfPayment($id);
		$data['title'] = 'Payment Apply Io Invoice';
		$data['view'] = Views . '\apply_to_invoice';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* Apply to invoice if the invoice is as a customer advance type  start */

	/* save applied invoice with customer advance payment start */
	public function paymentApplyToInvoice(){
		$insert = $this->ReceivedPaymentModel->paymentApplyToInvoice();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Invoice successfully applied with payment.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* save applied invoice with customer advance payment end */




}

