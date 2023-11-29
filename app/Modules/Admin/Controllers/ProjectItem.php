<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\ProjectItemModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\project_item');
class ProjectItem extends BaseController{
	public $ProjectItemModel;
	public $CommonModel;
	function __construct(){
		$this->ProjectItemModel = new ProjectItemModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}

	public function list($fk_project){
		$fk_project = base64_decode($fk_project);
		$data['data'] = $this->ProjectItemModel->getData($fk_project, 0,ADMIN_PER_PAGE_ROWS);
		$data['materials'] = $this->ProjectItemModel->getMaterialsList();
		$data['fk_project'] = $fk_project;
		$data['title'] = 'Project Item List';
		$data['view'] = Views . '\view_project_item';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}

	public function insertRecord(){
		$insert = $this->ProjectItemModel->addRecord();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Projects item added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}
	public function editProjectItem(){
	    $Id = $_POST['int_glcode'];
		$data['data'] = $this->ProjectItemModel->getProjectIemDetailsById($Id);
		$data['materials_edit'] = $this->ProjectItemModel->getMaterialsList($Id);
		$data['fk_project'] = $_POST['fk_project'];
		return view(Views . '\edit_projectitem', $data);
	}
	
	public function updateProjectItem($id){
		$update = $this->ProjectItemModel->updateRecord($id);
		if($update == 1){
			$this->session->setFlashdata('Success', 'Project Item updated successfully.');
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		
		echo json_encode($data);
		exit();
	}

	public function exportCSV($fk_project){
		$fk_project = base64_decode($fk_project);
		$customerData = $this->ProjectItemModel->export_csv($fk_project);
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"projectItem".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Material Name","Unit","Total Stock", "Due Stock", "Remaining Stock", "status", "Date"));
        $cnt=1;
		
        if(!empty($customerData)){
	        foreach ($customerData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}

				$data = array(
	            	$cnt++,
					$val['var_item'],
					$val['var_unit'],
	            	$val['var_stock'],
	            	$val['var_due_stock'],
	            	$val['var_used_stock'],	            	
					$status,
	            	$val['dt_createddate']
	            );
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}

	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_project_item');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
}

