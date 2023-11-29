<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\MaterialModel;
define('Views', 'App\Modules\Admin\Views\material');
class Material extends BaseController{
	public $MaterialModel;
	function __construct(){
		$this->MaterialModel = new MaterialModel();
	}

	public function index(){
		$data['data'] = $this->MaterialModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->MaterialModel->total_records_count();
		$data['total_rows'] = $this->MaterialModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Material List';
		$data['view'] = Views . '\view_material';
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

		$data['total_rows'] = $allcount = $this->MaterialModel->filter_records_count($search);		
		$data['total_data'] =  $this->MaterialModel->total_records_count();
		$data['result'] = $this->MaterialModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);

	}

    public function insertRecord(){
		
		$id = $_POST['int_glcode'];
		if($id == ''){
		    $data = $this->MaterialModel->addRecord();
		}else{
		    $data = $this->MaterialModel->updateRecord($id);
		}
		if(isset($data)){
		    echo "Success";
		}else{
		    echo "Error";
		}
		exit;
		
	}
	
	public function editMaterial(){
	    $Id = $_POST['int_glcode'];
		$data['data'] = $this->MaterialModel->getMaterialDetailsById($Id);
		return view(Views . '\edit_material', $data);
	}
	
	
	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_material');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
	
	public function exportCSV(){
		$vendorData = $this->MaterialModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"material".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Item Name", "Units", "Is Publish", "Created Date"));
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
	            	$val['var_item'],
	            	$val['var_unit'],
					$status,
	            	$val['dt_createddate']
	            );
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}

}

