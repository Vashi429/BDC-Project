<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\LaborTypeModel;
define('Views', 'App\Modules\Admin\Views\laborType');
class LaborType extends BaseController{
	public $LaborTypeModel;
	function __construct(){
		$this->LaborTypeModel = new LaborTypeModel();
	}

	public function index(){
		$data['data'] = $this->LaborTypeModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->LaborTypeModel->total_records_count();
		$data['total_rows'] = $this->LaborTypeModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'LaborType List';
		$data['view'] = Views . '\view_LaborType';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}

	public function loadData($rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		$data['total_rows'] = $allcount = $this->LaborTypeModel->filter_records_count($search);		
		$data['total_data'] =  $this->LaborTypeModel->total_records_count();
		$data['result'] = $this->LaborTypeModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);
	}

	public function exportCSV(){
		$vendorData = $this->LaborTypeModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"labourType".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Labor Type", "Skill Wages (Per Day)","Un-Skill Wages (Per Day)", "Is Publish", "Created Date"));
        $cnt=1;
		
        if(!empty($vendorData)){
	        foreach ($vendorData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}
	            $data = array(
	            	$cnt++,
	            	$val['var_type'],
	            	$val['var_skill_wages'],
	            	$val['var_unskill_wages'],
					$status,
	            	$val['dt_createddate']
	            );
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}


    public function insertRecord(){
		
		$id = $_POST['int_glcode'];
		$var_type = $_POST['var_type'];
		if(!empty($this->LaborTypeModel->checkUniqueLaborType($var_type, $id))){
			echo "Please enter unique labour type.";
		}else{
			if($id == ''){
				$this->LaborTypeModel->addRecord();
			}else{
				$this->LaborTypeModel->updateRecord($id);
			}
			echo 'Success';
		}
		exit;
	}
	
	public function editLaborType()
	{
	    $Id = $_POST['int_glcode'];
		$data['data'] = $this->LaborTypeModel->getLaborTypeDetailsById($Id);
		return view(Views . '\edit_LaborType', $data);
	}
	
	public function checkUniqueLaborType(){
		$int_glcode = "";
		if(isset($_POST['int_glcode'])){
			$int_glcode = $_POST['int_glcode'];
		}
		$inputvalues = $this->request->getVar('var_type');
		$userTAN = $this->LaborTypeModel->checkUniqueLaborType($inputvalues, $int_glcode);
		if(!empty($userTAN)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_labor_type');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
	
}

