<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Models\InvoiceModel;
use App\Modules\Admin\Models\CommonModel;
use App\Libraries\Mylibrary; // Import library
use App\Libraries\PdfLibrary;

define('Views', 'App\Modules\Admin\Views\invoice');
class Invoice extends BaseController{
	public $InvoiceModel;
	public $CommonModel;
	protected $validation;
	protected $request;
	protected $session;
	function __construct(){
		$this->session = \Config\Services::session();
		$this->validation = \Config\Services::validation();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
		$this->InvoiceModel = new InvoiceModel();
		$this->CommonModel = new CommonModel();
	}
	/* List of invoice start */
	public function index(){
		$data['data'] = $this->InvoiceModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->InvoiceModel->total_records_count();
		$data['total_rows'] = $this->InvoiceModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Invoice List';
		$data['view'] = Views . '\list_Invoice';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* List of invoice end */

	/* List of invoice with pagination, search start */
	public function loadData($rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		$data['total_rows'] = $allcount = $this->InvoiceModel->filter_records_count($search);		
		$data['total_data'] =  $this->InvoiceModel->total_records_count();
		$data['result'] = $this->InvoiceModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);
	}
	/* List of invoice with pagination, search end */

	/* add invoce page start */
	public function addInvoice(){
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['customerData'] = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');
		$data['projectData'] = array();
		$data['companyProfileData'] = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$data['autoInvoiceID'] = 'INC_' . $this->CommonModel->getUniqueAutoId('mst_invoice', 'var_Invoice_id');
		$data['gstData'] = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');
		$data['data'] = array();
		$data['title'] = 'Add Invoice';
		$data['view'] = Views . '\add_Invoice';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* add invoce page end */

	/* add new item for the invoice start */
	public function addEstimateItems(){
		$no_row = $_POST['no_row'];
		$gstType = $this->CommonModel->getGstType($_POST['fk_customer'], $_POST['fk_profile']);

		$gstData = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');
		$html = '';
		$attribute = "searchhsn('var_hsn" . $no_row."')";
		$html .= '<tr class="addmoreInvoice">
			<td>
				<input class="form-control" id="var_item' . $no_row . '" name="var_item[]" required="" type="text" required placeholder="Item Name">
				<input type="text" class="form-control" id="var_hsn' . $no_row . '" name="var_hsn[]" class="hsn-small" placeholder="Search HSN Here" oninput="' . $attribute . '" value="">
				<input type="hidden" id="hide_var_hsn' . $no_row . '" name="hide_var_hsn[]" value="">
				<div id="hsn_suggestion_var_hsn' . $no_row . '"></div>
			</td>
			<td>
				<input class="form-control" id="var_quantity' . $no_row . '" name="var_quantity[]" oninput="return isNumberKey(event);" required="" type="text" value="0" maxlength="5" onfocusOut ="itemTotalAmount(' . $no_row . ')">
			</td>
			<td>
				<input class="form-control" id="var_rate' . $no_row . '" name="var_rate[]" oninput="return isNumberKeyWithDot(event);" required="" type="text" value="0" maxlength="15" onfocusOut ="itemTotalAmount(' . $no_row . ')">
			</td>
			<td>
				<select class="form-control fk_tax" id="fk_tax' . $no_row . '" name="fk_tax[]" onchange ="itemTotalAmount(' . $no_row . ')">
					<option value="" disabled>Tax</option>
					<option value="0">No ' . $gstType . '</option>';
						if (!empty($gstData)) {
							foreach ($gstData as $value) {
								if ($value['var_percent'] == 0) {
									continue;
								}
								$html .= '<option value="' . $value['int_glcode'] . '" data-val="' . $value['var_percent'] . '">' . $value['var_percent'] . '% ' . $gstType . '</option>';
							}
						}
				$html .= '</select>
				<input type="hidden" value="0" id="var_tax' . $no_row . '" name="var_tax[]">
			</td>
			<td>
				<input type="hidden" name="var_total[]" value="0" id="var_row_total' . $no_row . '">
				<span class="total-amount" id="var_row_total_txt' . $no_row . '">0.00</span>
			</td>
			<td>
				<a href="javascript():" class="remove-invoice" id="btnRemoveInvoice" title="remove item" onclick="removeInvoiceItemRow('.$no_row.')"><i class="fas fa-minus m-0"></i></a>
			</td>
		</tr>';
		echo $html;
	}
	/* add new item for the invoice end */

	/* get the gst type as per customer and company profile state start */
	public function getGSTDropDown(){
		$gstType = $this->CommonModel->getGstType($_POST['fk_customer'], $_POST['fk_profile']);
		$data['gstType'] = $gstType;
		echo json_encode($data);
	}
	/* get the gst type as per customer and company profile state end */

	/* get customer address on change of customer start */
	public function getCustomerAddress(){
		$fk_customer = $_POST['fk_customer'];
		$customerAddress = $this->CommonModel->getRowArray('mst_customer','int_glcode, var_office_address, fk_city , fk_state , fk_country , var_pincode',array('int_glcode'=>$fk_customer));

		if($customerAddress['fk_state'] > 0){
			$fk_state = $this->CommonModel->getValById('mst_state', 'var_title', array('int_glcode'=>$customerAddress['fk_state']));
		}
		if($customerAddress['fk_city'] > 0){
			$fk_city = $this->CommonModel->getValById('mst_city', 'var_title', array('int_glcode'=>$customerAddress['fk_city']));
		}
		if($customerAddress['fk_country'] > 0){
			$fk_country = $this->CommonModel->getValById('mst_country', 'var_title', array('int_glcode'=>$customerAddress['fk_country']));
		}
		$html = "";
		if(!empty($customerAddress)){
			$html .= '<div class="form-group row">
				<label for="fk_customer" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Customer Address<span class="text-danger">*</span></label>
				<div class="col-sm-9 col-lg-4">
					<label class="imagecheck mb-2">
						<input name="var_address" type="radio" value="'.$fk_customer.'" class="imagecheck-input">
						<span class="custom-imagecheck-figure form-control">'.$customerAddress['var_office_address'].', '.$fk_city.', '.$customerAddress['var_pincode'].', '.$fk_state.', '.$fk_country.'</span>
						<span class="address_btn d-none">
							<a href="javscript:void(0)" onclick ="editAddress('.$fk_customer.')" class="edit_btn"><i class="fas fa-edit"></i></a>
							<a href="javscript:void(0)" class="remove_btn"><i class="fas fa-trash"></i></a>
						</span>
					</label>
				</div>
			</div>';
		}
		echo $html;exit;
	}
	/* get customer address on change of customer end */

	/* get customer existing projects on change of customer start */
	public function getCustomerProject(){
		$fk_customer = $_POST['fk_customer'];
		$customerProjects = $this->CommonModel->getResultArray('mst_project','int_glcode, var_project', array('chr_delete' => 'N', 'chr_publish' => 'Y', 'fk_customer'=>$fk_customer),'var_project', 'ASC');
		$html = '<option value=""></option>';
		if(!empty($customerProjects)){
			foreach($customerProjects as $val){
				$html.= '<option value="'.$val['int_glcode'].'">'.$val['var_project'].'</option>' ;
			}
		}
		echo $html; exit;
	}
	/* get customer existing projects on change of customer end */

	/* get HSN list on key press of hsn fields start */
	public function searchhsn(){
		$inputId = "'" . $_POST['inputId'] . "'";
		$hsnlist = $this->InvoiceModel->HSNList($_POST['var_hsn']);
		$html = '<ul class="ul-list">';
		if(!empty($hsnlist)){
			foreach ($hsnlist as $key => $value) {
				$html .= '<li class="li-list" onclick="selecthsnfromlist(' . $value['hsn_code'] . ', ' . $inputId . ')">(' . $value['hsn_code'] . ') ' . $value['hsn_description'] . '</li>';
			}
		}else{
			$html .= '<li class="li-list">Result not found.</li>';
		}
		$html .= '</ul>';
		echo $html;
	}
	/* get HSN list on key press of hsn fields end */

	/* insert invoice start */
	public function insertRecord(){
		$insert = $this->InvoiceModel->addUpdateRecord();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Invoice added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* insert invoice end */

	/* Print/PDF of the invoice start */
	public function pdfViewer($Id){
		$Id = base64_decode($Id);
		$pdf_data['data'] = $this->InvoiceModel->viewInvoiceData($Id);
		$pdf = new PdfLibrary('P', 'mm', 'A4', true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator('BDC Peojects');
		$pdf->SetAuthor(base_url());
		$pdf->SetTitle('Invoice #'.$pdf_data['data']['var_Invoice_id']);
		$pdf->SetSubject('Invoice #'.$pdf_data['data']['var_Invoice_id']);
		$pdf->SetKeywords('TCPDF, PDF, MySQL, Codeigniter');
		// set default header data

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT, true);
		$pdf->SetHeaderMargin(10);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('times', '', 12);
		$html = view('App\Modules\Admin\Views\invoice\view_invoice_pdf',$pdf_data);
		
		$pdf->AddPage('L', 'A4');
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if (!is_dir('uploads/invoice')) {
            mkdir('uploads/invoice', 0777, TRUE);
        }
		
		$directory = FCPATH . 'uploads/invoice/'; // Path to the "uploads" folder
		$filename = md5($pdf_data['data']['var_Invoice_id']) . '.pdf'; // Generate a unique filename
		$this->CommonModel->updateRows('mst_invoice',array('var_final_invoice'=>$filename), array('int_glcode'=>$Id));
		$pdf->Output($directory . $filename, 'F');
		header("Location:".base_url().'/uploads/invoice/'.$filename);
	}
	/* Print/PDF of the invoice end */

	/* delete the invoice item from the database start */
	public function removeInvoiceItem(){
		$invoiceItemData = $this->CommonModel->getRowArray('mst_invoice_items', '*', array('int_glcode'=>$_POST['id']));
		if(!empty($invoiceItemData)){
			$itemSubtotal = $invoiceItemData['var_amount'];
			$itemGst = $invoiceItemData['var_tax'];
			$fk_invoice = $invoiceItemData['fk_invoice'];
			$invoiceData = $this->InvoiceModel->updateIvoiceAmount($fk_invoice, $itemSubtotal, $itemGst);
			
			$this->CommonModel->deleteRow('mst_invoice_items', array('int_glcode'=> $_POST['id']));
			$data['status'] = 200;
			$data['msg'] = 'Invoice item deleted successfully.';
			$data['var_subtotal'] = $invoiceData['var_subtotal'];
			$data['var_gst'] = $invoiceData['var_gst'];
			$data['var_final_amount'] = $invoiceData['var_final_amount'];
		}else{
			$data['status'] = 400;
			$data['msg'] = 'Item not found.';
		}
		echo json_encode($data);
		exit();
	}
	/* delete the invoice item from the database end */

	/* edit invoce page start */
	public function editInvoice($id){
		$id = base64_decode($id);
		$data['data'] = $invoieData = $this->InvoiceModel->getInvoiceData($id);
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['customerData'] = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');
		$data['projectData'] = $this->CommonModel->getResultArray('mst_project','int_glcode, var_project',array('chr_delete' => 'N', 'chr_publish' => 'Y','fk_customer'=>$invoieData['fk_customer']),'var_project', 'ASC');
		$data['companyProfileData'] = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$gstType = $this->CommonModel->getGstType($invoieData['fk_customer'], $invoieData['fk_profile']);
		$data['gstType'] = $gstType;
		$data['gstData'] = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');
		$data['title'] = 'Edit Invoice';
		$data['view'] = Views . '\edit_Invoice';
		$data['heading'] = 'Welcome to BDC Project';

		return view('admin_tempate/template', $data);
	}
	/* edit invoce page end */

	/* view invoice page start */
	public function viewInvoice($id){
		$id = base64_decode($id);
		$data['data'] = $invoieData = $this->InvoiceModel->viewInvoiceData($id);
		$data['title'] = 'View Invoice';
		$data['view'] = Views . '\view_Invoice';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* view invoice page end */

	/* update invoice start */
	public function updateRecord($id){
		$update = $this->InvoiceModel->addUpdateRecord($id);
		if($update > 0){
			$this->session->setFlashdata('Success', 'Invoice updated successfully.');
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* update invoice end */

	/* export invoice data start */
	public function exportCSV(){
		$invoiceData = $this->InvoiceModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"InvoiceList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Invoice Id", "Project", "Customer",  "Company Profile", "Invoice Date", "Subject", "Sub Total", "Total GST", "Adustment", "Total Amount",  "Payment Status", "Created Date"));
        $cnt=1;
        if(!empty($invoiceData)){
			foreach ($invoiceData as $val) {
	        	
				$fk_project  = $this->CommonModel->getValById('mst_project', 'var_project', array('int_glcode'=>$val['fk_project']));
				$fk_customer  = $this->CommonModel->getValById('mst_customer', 'var_name', array('int_glcode'=>$val['fk_customer']));
				$fk_profile  = $this->CommonModel->getValById('	mst_company_profile', 'var_name', array('int_glcode'=>$val['fk_profile']));
	            $data = array(
	            	$cnt++,
	            	$val['var_Invoice_id'],
	            	$fk_project,
					$fk_customer,
	            	$fk_profile,
	            	$val['var_invoice_date'],
	            	$val['var_subject'],
	            	$val['var_subtotal'],
					$val['var_gst'],
					$val['var_adjustment'],
					$val['var_final_amount'],
					$val['var_payment_status'],
	            	$val['dt_createddate']
	            );
			
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}
	/* export invoice data end */

	/* delete multiple rows of invoice start */
	public function delete_multiple(){
		if(!empty($_POST['id'])){
			foreach($_POST['id'] as $val){
				$receivedInvoice = $this->CommonModel->getResultArray('mst_received_invoice', 'fk_received_id, var_amount', array('fk_invoice' => $val));
				if(!empty($receivedInvoice)){
					foreach($receivedInvoice as $ri_val){
						/* remove received payment and if received payment is 0 then delete the received payment and delete the invoice payment Start*/
							$int_amt = $this->CommonModel->getRowArray('mst_received_payment', 'var_received_amount', array('fk_invoice' => $ri_val['fk_invoice']));

							$this->CommonModel->updateRows('mst_received_payment', array('var_received_amount' => $int_amt['var_received_amount'] - $ri_val['var_amount']), array('int_glcode' => $ri_val['fk_received_id']));
							$checkRecPayAmt = $this->CommonModel->getValById('mst_received_payment', 'var_received_amount', array('int_glcode' => $ri_val['fk_received_id']));
							if($checkRecPayAmt == 0){
								$this->CommonModel->updateRows('mst_received_payment', array('chr_delete' => 'N'), array('int_glcode' => $ri_val['fk_received_id']));
								$this->CommonModel->deleteRow('mst_received_invoice', array('fk_invoice' => $val));
							}
							$this->CommonModel->deleteRow('mst_invoice_items', array('fk_invoice' => $val));
							$this->CommonModel->deleteRow('mst_invoice_amount', array('fk_invoice' => $val));
						/* remove received payment and if received payment is 0 then delete the received payment and delete the invoice payment Start*/
					}
				}
			}
		}
		$result = $this->CommonModel->delete_multiple('mst_invoice');
		echo $result;
	}
	/* delete multiple rows of invoice end */

	/* get the unpaid invoice list for the direct entry in received payment if the payment type change with paid start */
	public function addUnpaidInvoicelist(){
		$fk_project = $_POST['fk_project'];
		$fk_customer = $_POST['fk_customer'];
		
		$var_due_amount = 0;
		
		$invoice = $this->InvoiceModel->getInvoiceArray($fk_project, $fk_customer);
		$html = '';
		if(!empty($invoice)){
			foreach($invoice as $val){
				$var_due_amount += $val["var_due_amount"];
				$html.='<tr>
					<td>'.$val["var_Invoice_id"].'</td>
					<td>'.$val["var_invoice_date"].'</td>
					<td>'.$val["var_invoice_amount"].'</td>
					<td>'.$val["var_paid_amount"].'</td>
					<td>'.$val["var_due_amount"].'</td>
					<td>
						<input class="form-control var_payment" id="var_payment'.$val['int_glcode'].'" name="var_payment[]" type="text" maxlength="15" oninput="checkPaidAmount('.$val['int_glcode'].'), isNumberKeyWithDot(event);" value="" data-val="0">
						<span class="text-danger" id="error_var_payment'.$val['int_glcode'].'"></span>
						<input type ="hidden" id="invoiceItemID'.$val['int_glcode'].'" name="invoiceItemId[]" value="'.$val['int_glcode'].'">
						<input type ="hidden" id="invoiceID'.$val['int_glcode'].'" name="invoiceId[]" value="'.$val['fk_invoice'].'">
						<input type ="hidden" id="invoiceDureAmount'.$val['int_glcode'].'" name="invoiceDureAmount[]" value="'.$val['var_due_amount'].'">
					</td>
				</tr>';
			}
			$html .='<input type ="hidden" id="total_due_amount" name="total_due_amount" value="'.$var_due_amount.'">';
			$data['status'] = 200;
			$data['html'] = $html;
		}else{
			$data['status'] = 400;
		}
		echo json_encode($data);
	}
	/* get the unpaid invoice list for the direct entry in received payment if the payment type change with paid end */

	public function recordPayment($id){
		$id = base64_decode($id);
		$data['invioceList'] = $this->InvoiceModel->getPaymentList($id);
		$data['invoiceId'] = $this->CommonModel->getValById('mst_invoice', 'var_Invoice_id', array('int_glcode' => $id));
		$data['title'] = 'Record Payment of Invoice';
		$data['view'] = Views . '\record_payment';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);

	}

}
