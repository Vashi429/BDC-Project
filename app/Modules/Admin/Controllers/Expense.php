<?php



namespace App\Modules\Admin\Controllers;



use App\Modules\Admin\Models\ExpenseModel;

use App\Modules\Admin\Models\CommonModel;

use App\Libraries\Mylibrary; // Import library

use App\Libraries\PdfLibrary;



define('Views', 'App\Modules\Admin\Views\expense');

class Expense extends BaseController{

	public $ExpenseModel;

	public $CommonModel;

	protected $validation;

	protected $request;

	protected $session;

	function __construct(){

		$this->session = \Config\Services::session();

		$this->validation = \Config\Services::validation();

		$this->request = \Config\Services::request();

		$this->mylibrary = new Mylibrary();

		$this->ExpenseModel = new ExpenseModel();

		$this->CommonModel = new CommonModel();

	}

	/* List of expense start */

	public function index(){
		
		$data['data'] = $this->ExpenseModel->getData(0,ADMIN_PER_PAGE_ROWS);

		$data['total_data'] = $total_data =  $this->ExpenseModel->total_records_count();

		$data['total_rows'] = $this->ExpenseModel->filter_records_count();

		$page    = (int) ($this->request->getGet('page') ?? 1);

        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);

		$data['pager_links'] = $pager_links;

		$data['title'] = 'Expense List';

		$data['view'] = Views . '\list_expense';

		$data['heading'] = 'Welcome to BDC Project';

		return view('admin_tempate/template', $data);

	}

	/* List of expense end */



	/* List of expense with pagination, search start */

	public function loadData($rowno=0) { 

		$search = $_GET['append'];     

		$_field = $_GET['field'];    

		$_sort = $_GET['sort'];

		$rowperpage = $_GET['entries'];        

        $page = $rowno;

		if($rowno != 0){

			$rowno = ($rowno-1) * $rowperpage;

		}

		$data['total_rows'] = $allcount = $this->ExpenseModel->filter_records_count($search);		

		$data['total_data'] =  $this->ExpenseModel->total_records_count();

		$data['result'] = $this->ExpenseModel->getData($rowno,$rowperpage,$search,$_field,$_sort);

        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		

		$data['row'] = $rowno;

		echo json_encode($data);

	}

	/* List of expense with pagination, search end */



	/* add expense page start */

	public function addExpense(){

		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$data['projectData'] = $this->CommonModel->getProjectList();
		
		$data['companyProfileData'] = $this->CommonModel->getCompanyProfileList();

		$data['vendorData'] = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');

		$data['data'] = array();

		$data['title'] = 'Add Expense';

		$data['view'] = Views . '\add_expense';

		$data['heading'] = 'Welcome to BDC Project';

		return view('admin_tempate/template', $data);

	}

	/* add expense page end */



	/* insert expense start */

	public function insertRecord(){

		$insert = $this->ExpenseModel->addUpdateRecord();

		if($insert > 0){

			$data['status'] = 1;

			$this->session->setFlashdata('Success', 'Expense added successfully.');

		}else{

			$data['status'] = 0;

			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');

		}

		echo json_encode($data);

		exit();

	}

	/* insert expense end */



	/* edit expense page start */

	public function editExpense($id){

		$id = base64_decode($id);

		$data['data'] = $invoieData = $this->ExpenseModel->getInvoiceData($id);

		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$data['customerData'] = $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', 'ASC');

		$data['projectData'] = $this->CommonModel->getResultArray('mst_project','int_glcode, var_project',array('chr_delete' => 'N', 'chr_publish' => 'Y','fk_customer'=>$invoieData['fk_customer']),'var_project', 'ASC');

		$data['companyProfileData'] = $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');

		$gstType = $this->CommonModel->getGstType($invoieData['fk_customer'], $invoieData['fk_profile']);

		$data['gstType'] = $gstType;

		$data['gstData'] = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');

		$data['title'] = 'Edit Expense';

		$data['view'] = Views . '\edit_expense';

		$data['heading'] = 'Welcome to BDC Project';



		return view('admin_tempate/template', $data);

	}

	/* edit expense page end */



	/* update expense start */

	public function updateRecord($id){

		$update = $this->ExpenseModel->addUpdateRecord($id);

		if($update > 0){

			$this->session->setFlashdata('Success', 'Expense updated successfully.');

			$data['status'] = 1;

		} else {

			$data['status'] = 0;

			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');

		}

		echo json_encode($data);

		exit();

	}

	/* update expense end */



	/* export expense data start */

	public function exportCSV(){

		$invoiceData = $this->ExpenseModel->export_csv();

		

		header("Content-type: application/csv");

        header("Content-Disposition: attachment; filename=\"expenseList".time().".csv\"");

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

	/* export expense data end */



	/* delete multiple rows of expense start */

	public function delete_multiple(){

		$ids = $this->request->getVar('id');
		$not_deleted_ids = array();
		foreach ($ids as $key => $value) {
			$where = array('int_glcode' => $value);
			$checkExpense = $this->CommonModel->getResultArray('mst_expense', 'int_glcode, var_expense_mode', $where, 'int_glcode', 'ASC');
			if($checkExpense[0]['var_expense_mode'] == 'Advance'){
				$data = array(
					'chr_delete' => 'Y'
				);
				$builder = $this->con->table("mst_expense");
				$builder->where('int_glcode', $value);
				$builder->update($data);
			}else if($checkExpense[0]['var_expense_mode'] == 'Challan'){
				$data = array(
					'chr_delete' => 'Y'
				);
				$builder = $this->con->table("mst_expense");
				$builder->where('int_glcode', $value);
				$builder->update($data);

				$data = array(
					'chr_delete' => 'Y'
				);
				$builder = $this->con->table("mst_challan");
				$builder->where('fk_expense', $value);
				$builder->update($data);
			}else{
				$not_deleted_ids[] = $value;
			}
		}

		$List = implode(',', $not_deleted_ids); 
		if(count($not_deleted_ids) > 0){
			$this->session->setFlashdata('Success', 'All expenses are deleted successfully except ('.$List.') because you need to delete the bill first.');
		}else{
			$this->session->setFlashdata('Success', 'All expenses are deleted successfully.');
		}

	}

	/* delete multiple rows of expense end */



	/* Get project list on the select of company profile start */

	public function getProjects(){
		$fk_profile = $_POST['fk_profile'];
		$where = array(

			'chr_delete' => 'N',

			'chr_publish' => 'Y',

			'fk_profile' => $fk_profile,

		);
		$projectData = $this->CommonModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'var_project', 'ASC');
		$html = '<div class="form-group">';
		$html .= '<label for="fk_project">Project <span class="text-danger">*</span></label>';
		$html .= '<select class="form-control" name="fk_project" id="fk_project" required onchange="getLabors(this.value)">';
		$html .= '<option value=""></option>';
		if(!empty($projectData)){
			foreach($projectData as $val){
				$html .= '<option value="'.$val['int_glcode'].'">'.$val['var_project'].'</option>';
			}
		}
		$html .= '</select>';
		$html .= '</div>';
		echo $html; exit;

	}

	public function getLabor(){
		$fk_project  = $_POST['project_id'];
		$total = 0;
		$html ='';
		if(!empty($fk_project)){
			$where = array(
				'fk_project' => $fk_project,
				'var_due_amount >' => '0'
			);
			$projectData = $this->CommonModel->getResultArray('mst_labor_assignment', '*', $where, 'int_glcode', 'ASC');
			if(count($projectData) > 0){
				foreach($projectData as $val){
						$checked = "";
						$html .= '<tr id="'.$val['int_glcode'].'">';
							$html .= '<td>'.$val['int_glcode'].'</td>';
							$html .= '<td>'.$val['var_date'].'</td>';
							$html .= '<td>'.$val['var_amount'].'</td>';
							$html .= '<td>'.$val['var_paid_amount'].'</td>';
							$html .= '<td id="var_due_amount_labour'.$val['int_glcode'].'">'.$val['var_due_amount'].'</td>';		
							$html .= '<td><input type="text" class="form-control" name="amount_labour[]" value="'.$val['var_due_amount'].'" id="amount_labour-'.$val['int_glcode'].'" data-val = "'.$val['int_glcode'].'"  onkeyup="getLaborAmount(this.value)" ></td>';
							$html .= '<td class="p-0 text-center" style="display:none;">';
							$html .= '<div class="custom-checkbox custom-control">';
							$html .= '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="ids_labour[]" value="'.$val['int_glcode'].'" id="checkbox_labour-'.$val['int_glcode'].'" data-val = "'.$val['int_glcode'].'" checked>';
							$total = $total + $val['var_due_amount'];
							$html .= '<label for="checkbox-'.$val['int_glcode'].'" class="custom-control-label">&nbsp;</label>';
							$html .= '</td>';
							
						$html .= '</tr>';
				}
			}else{
				$html.='<tr><td colspan="5">No Labor Expense found.(please add Labor Expense for this project)</td></tr>';
			}
		}else{
			$html.='<tr><td colspan="5">No Labor Expense found.(please add Labor Expense for this Project)</td></tr>';
		}
		$html .='<input type ="hidden" id="totalLaborAmount" name="totalLaborAmount" value="'.$total.'">';
		echo $html; exit;
	}

	/* Get project list on the select of company profile end */

	/****************************  Expense all list in popup code By sajid on 2023 Nov 21 Code Start ****************************/
	public function list_bill_expens($expense_id = ''){
		$expense_id = base64_decode($expense_id);
		$where = array(
			'fk_bill' => $expense_id,
		);
		$get_all_data = $this->CommonModel->getResultArray('mst_expence_bill', 'int_glcode, var_bill_expense_date, fk_expense, var_amount, fk_bill', $where, 'int_glcode', 'ASC');
		$bill_id = $get_all_data[0]['fk_bill'];
		$data_bill_due = array();
		if($bill_id != '' && $bill_id != '0'){
			$where = array(
				'int_glcode' => $bill_id,
			);
			$data_bill_due = $this->CommonModel->getResultArray('mst_bill_due', 'var_amount, var_due_amount, var_paid_amount', $where, 'int_glcode', 'ASC');
		}
		$data['data'] = array('expence_data' => $get_all_data,'data_bill_due' => $data_bill_due);
		$data['title'] = 'Expense List';

		$data['view'] = Views . '\listExpense';

		$data['heading'] = 'Bill Expense List';

		// print_r($data); die;
		return view('admin_tempate/template', $data);
	}

	/****************************  Expense all list in popup code By sajid on 2023 Nov 21 Code end ****************************/


	public function getassignmentprice(){
		$id = $_POST['id'];
		$where = array(
			'int_glcode' => $id,
		);
		$labor_assignment_data = $this->CommonModel->getResultArray('mst_labor_assignment', 'var_number_of_assign, var_number_of_assign_expense, var_total_charge, var_due_charge, var_paid_charge', $where, 'int_glcode', 'ASC');
		$total_number_assign = ''; 
		$final_labor_charge = '';
		if(count($labor_assignment_data) > 0){
			$var_number_of_assign = $labor_assignment_data[0]['var_number_of_assign'];
			$var_number_of_assign_expense = $labor_assignment_data[0]['var_number_of_assign_expense'];
			$var_total_charge = $labor_assignment_data[0]['var_total_charge'];
			$var_due_charge = $labor_assignment_data[0]['var_due_charge'];
			$var_paid_charge = $labor_assignment_data[0]['var_paid_charge'];
			$labor_charge = $var_total_charge / $var_number_of_assign;
			$total_number_assign = $var_number_of_assign - $var_number_of_assign_expense; 
			$final_labor_charge = $labor_charge * $total_number_assign;
		}
		$final_res = array('final_total_number_assign' => $total_number_assign, 'final_labor_charge' => $final_labor_charge);

		echo json_encode($final_res); die;
	}

}

