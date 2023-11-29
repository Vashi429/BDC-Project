<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\BillModel;
use App\Modules\Admin\Models\CommonModel;
use App\Libraries\Mylibrary; // Import library
use App\Libraries\PdfLibrary;
define('Views', 'App\Modules\Admin\Views\bill');
class Bill extends BaseController{
	public $BillModel;
	public $CommonModel;
	protected $validation;
	protected $request;
	protected $session;
	function __construct(){
		$this->session = \Config\Services::session();
		$this->validation = \Config\Services::validation();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
		$this->BillModel = new BillModel();
		$this->CommonModel = new CommonModel();
	}
	/* List of Bill start */
	public function index(){
		$data['data'] = $this->BillModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->BillModel->total_records_count();
		$data['total_rows'] = $this->BillModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Bill List';
		$data['view'] = Views . '\list_bill';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* List of Bill end */
	/* List of Bill with pagination, search start */
	public function loadData($rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		$data['total_rows'] = $allcount = $this->BillModel->filter_records_count($search);		
		$data['total_data'] =  $this->BillModel->total_records_count();
		$data['result'] = $this->BillModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);
	}
	/* List of Bill with pagination, search end */
	/* add Bill page start */
	public function addBill(){
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['vendorData'] = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$data['profileData'] = $this->CommonModel->getCompanyProfileList();
		$data['projectData'] = $this->CommonModel->getProjectList();
		$data['title'] = 'Add Bill';
		$data['view'] = Views . '\add_bill';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* add Bill page end */
	
	/* on change of project get the project's item list of the projects start */
	public function getChallanList(){
		$fk_project  = $_POST['fk_project'];
		$fk_vendor  = $_POST['fk_vendor'];
		$fk_bill  = $_POST['fk_bill'];
		$total = 0;
		$html ='';
		$status = 0;
		if(!empty($fk_vendor) && !empty($fk_project)){
			$challanList = $this->BillModel->getChallanList($fk_project, $fk_vendor, $fk_bill);
			if(!empty($challanList)){
				foreach($challanList as $val){
					$checked = "";
					if(($val['fk_bill'] == $fk_bill)){
						$checked = 'checked';
						$total += $val['var_amount'];
					}
					$html .= '<tr id="'.$val['int_glcode'].'">';
						$html .= '<td>'.$val['var_challan_id'].'</td>';
						$html .= '<td>'.$val['dt_createddate'].'</td>';
						$html .= '<td>'.$val['var_item'].' ('.$val['var_unit'].')</td>';
						$html .= '<td>'.$val['var_amount'].'</td>';
						$html .= '<td class="p-0 text-center">';
							$html .= '<div class="custom-checkbox custom-control">';
								$html .= '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input challancheckBox" name="ids[]" value="'.$val['int_glcode'].'" id="checkbox-c-'.$val['int_glcode'].'" data-val = "'.$val['var_amount'].'" '.$checked.'>';
								$html .= '<label for="checkbox-c-'.$val['int_glcode'].'" class="custom-control-label">&nbsp;</label>';
						$html .= '</td>';
					$html .= '</tr>';
				}
				$status = 1;
			}else{
				$html.='<tr><td colspan="5">No challan found.(please add challan for this vendor)</td></tr>';
			}
		}else{
			$html.='<tr><td colspan="5">No challan found.(please add challan for this vendor)</td></tr>';
		}
		$html .='<input type ="hidden" id="totalChallanAmount" name="totalChallanAmount" value="'.$total.'">';
		$data['html'] = $html;
		$data['status'] = $status;
		echo json_encode($data); exit;
	}
	public function getBillList(){
		$fk_project  = $_POST['fk_project'];
		$fk_vendor  = $_POST['fk_vendor'];
		$fk_bill  = $_POST['fk_bill'];
		$total = 0;
		$html ='';
		if(!empty($fk_vendor) && !empty($fk_project)){
			$challanList = $this->BillModel->getBillList($fk_project, $fk_vendor, $fk_bill);
			if(!empty($challanList)){
				foreach($challanList as $val){
					$where = array('fk_bill' => $val['int_glcode']);
						$bill_data = $this->CommonModel->getResultArray('mst_bill_due', 'var_amount, var_due_amount, var_paid_amount', $where);
						$checked = "";
						$html .= '<tr id="'.$val['int_glcode'].'">';
							$html .= '<td>'.$val['var_bill_no'].'</td>';
							$html .= '<td>'.$val['var_bill_date'].'</td>';
							if(count($bill_data) > 0){
								$html .= '<td>'.$bill_data[0]['var_amount'].'</td>';
								$html .= '<td>'.$bill_data[0]['var_paid_amount'].'</td>';
								$html .= '<td id="var_due_amount_'.$val['int_glcode'].'">'.$bill_data[0]['var_due_amount'].'</td>';
							}else{
								$html .= '<td>0</td>';
								$html .= '<td>0</td>';
								$html .= '<td>0</td>';
							}						
							if(count($bill_data) > 0){
								$html .= '<td><input type="text" class="form-control" name="amount[]" value="'.$bill_data[0]['var_due_amount'].'" id="amount-'.$val['int_glcode'].'" onkeyup="getBillAmount(this.value)" data-val = "'.$val['int_glcode'].'"></td>';
								$total = $total + $bill_data[0]['var_due_amount'];
							}else{
								$html .= '<td><input type="text" class="form-control" name="amount[]" value="" onkeyup="getBillAmount(this.value)" id="amount-'.$val['int_glcode'].'" data-val = "'.$val['int_glcode'].'"></td>';
							}
							$html .= '<td class="p-0 text-center" style="display:none;">';
							$html .= '<div class="custom-checkbox custom-control">';
									$html .= '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input challancheckBox" name="ids[]" value="'.$val['int_glcode'].'" id="checkbox-'.$val['int_glcode'].'" data-val = "'.$val['int_glcode'].'" onclick="getAmount('.$val['int_glcode'].')" checked>';
									$html .= '<label for="checkbox-'.$val['int_glcode'].'" class="custom-control-label">&nbsp;</label>';
							$html .= '</td>';
							
						$html .= '</tr>';
				}
			}else{
				$html.='<tr><td colspan="5">No Bill found.(please add bill for this vendor)</td></tr>';
			}
		}else{
			$html.='<tr><td colspan="5">No Bill found.(please add bill for this vendor)</td></tr>';
		}
		$html .='<input type ="hidden" id="totalBillAmount" name="totalBillAmount" value="'.$total.'">';
		echo $html; exit;
	}

	public function getAdvanceExpenseList(){
		$fk_project  = $_POST['fk_project'];
		$fk_vendor  = $_POST['fk_vendor'];
		$fk_bill  = $_POST['fk_bill'];

		$total = 0;
		$html ='';
		$status = 0;
		if(!empty($fk_vendor) && !empty($fk_project)){
			$advanceExpesne = $this->BillModel->getAdvanceExpenseList($fk_project, $fk_vendor, $fk_bill);
			if(!empty($advanceExpesne)){
				foreach($advanceExpesne as $val){
					$checked = "";
					if(($fk_bill != "" && $val['fk_bill'] == $fk_bill)){
						$checked = 'checked';
						$total += $val['bill_amount'];
						$var_amount = $val['bill_amount'];
						$int_glcode = $val['b_int_glcode'];
						$html .= '<tr id="'.$int_glcode.'">';
							$html .= '<td>'.$int_glcode.'</td>';
							$html .= '<td>'.$val['var_expense_date'].'</td>';
							$html .= '<td>'.$var_amount.'</td>';
							$html .= '<td class="p-0 text-center">';
								$html .= '<div class="custom-checkbox custom-control">';
									$html .= '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input challancheckBox" name="expesnse_ids[]" value="'.$int_glcode.'" id="checkbox-b-'.$int_glcode.'" data-val = "'.$var_amount.'" '.$checked.'>';
									$html .= '<label for="checkbox-b-'.$int_glcode.'" class="custom-control-label">&nbsp;</label>';
							$html .= '</td>';
						$html .= '</tr>';
					}else{
						$total += $val['var_amount'];
						$var_amount = $val['var_amount'];
						$int_glcode = $val['int_glcode'];
						$html .= '<tr id="'.$int_glcode.'">';
							$html .= '<td>'.$int_glcode.'</td>';
							$html .= '<td>'.$val['var_expense_date'].'</td>';
							$html .= '<td>'.$var_amount.'</td>';
							$html .= '<td class="p-0 text-center">';
								$html .= '<div class="custom-checkbox custom-control">';
									$html .= '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input challancheckBox" name="new_expesnse_ids[]" value="'.$int_glcode.'" id="checkbox-b-'.$int_glcode.'" data-val = "'.$var_amount.'" '.$checked.'>';
									$html .= '<label for="checkbox-b-'.$int_glcode.'" class="custom-control-label">&nbsp;</label>';
							$html .= '</td>';
						$html .= '</tr>';
					}
				}
				$status = 1;
			}else{
				$html.='<tr><td colspan="4">No Expense found.</td></tr>';
			}
		}else{
			$html.='<tr><td colspan="4">No Expense found.</td></tr>';
		}
		$html .='<input type ="hidden" id="totalExpesneAmount" name="totalExpesneAmount" value="'.$total.'">';
		$data['html'] = $html;
		$data['status'] = $status;
		echo json_encode($data); exit;
	}
	/* on change of project get the project's item list of the projects end */
	/* on change of company profile get the project list start */
	public function getProjects(){
		$fk_profile  = $_POST['fk_profile'];
		$where = array(
			'chr_delete' => 'N',
			'chr_publish' => 'Y',
			'date_format(end_date,"%Y-%m-%d") >=' => date('Y-m-d'),
			'fk_profile' => $fk_profile,
		);
		$projects = $this->CommonModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'var_project', 'ASC');
		
		$html = "<option value=''></option>";
		if(!empty($projects)){
			foreach($projects as $val){
				$html .= '<option value="'.$val['int_glcode'].'">'.$val['var_project'].'</option>';
			}
		}
		echo $html; exit;
	}
	/* on change of company profile get the project list end */
	/* insert Bill start */
	public function insertRecord(){
		$insert = $this->BillModel->addUpdateRecord();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Bill added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* insert Bill end */
	/* edit Bill page start */
	public function editBill($id){
		$id = base64_decode($id);
		$data['data'] = $this->BillModel->getBillData($id);
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$data['vendorData'] = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		$data['profileData'] = $this->CommonModel->getCompanyProfileList();
		$where['date_format(end_date,"%Y-%m-%d") >='] = date('Y-m-d');
		$data['projectData'] = $this->CommonModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'var_project', 'ASC');
		$data['title'] = 'Edit Bill';
		$data['view'] = Views . '\edit_bill';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	/* edit Bill page end */
	/* update Bill start */
	public function updateRecord($id){
		$update = $this->BillModel->UpdateRecord($id);
		if($update > 0){
			$this->session->setFlashdata('Success', 'Bill updated successfully.');
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	/* update Bill end */
	/* export Bill data start */
	public function exportCSV(){
		$BillData = $this->BillModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"BillList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
		fputcsv($handle, array("No", "Bill Id", "Challan ID", "Vendor", "Project", "Company Profile", "Bill Amount", "Bill Due Amount", "Created Date"));
        $cnt=1;
        if(!empty($BillData)){
			foreach ($BillData as $val) {
	            $data = array(
	            	$cnt++,
	            	$val['var_bill_no'],
	            	$val['var_challan'],
	            	$val['vendor_name'],
					$val['var_project'],
	            	$val['profile_name'],
	            	$val['var_amount'],
	            	$val['var_due_amount'],
	            	$val['dt_createddate']
	            );
			
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}
	/* export Bill data end */
	/* delete multiple rows of Bill start */
	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_bill');
		echo $result;
	}
	/* delete multiple rows of Bill end */

	public function checkBillNumber(){
		$bill_no = $_POST['bill_no'];
		$where = array('var_bill_no' => $bill_no);
		$bill = $this->CommonModel->getResultArray('mst_bill', 'int_glcode', $where, 'int_glcode', 'ASC');
		if(count($bill) > 0){
			return '0';
		}else{
			return '1';
		}
	}
}
