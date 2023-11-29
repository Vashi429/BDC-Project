<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\ProjectDocModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\projectDoc');
class ProjectDoc extends BaseController{
	public $ProjectDocModel;
	public $CommonModel;
	function __construct(){
		$this->ProjectDocModel = new ProjectDocModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}
	public function listImages($fk_project)
	{
		$fk_project = base64_decode($fk_project);
		$data['data'] = $this->ProjectDocModel->getData($fk_project, 0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->ProjectDocModel->total_records_count($fk_project);
		$data['total_rows'] = $this->ProjectDocModel->filter_records_count($fk_project);
		$page  = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['fk_project'] = $fk_project;
		$data['title'] = 'Project Item List';
		$data['view'] = Views . '\view_projectdoc';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}

    public function loadData($fk_project, $rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		$data['total_rows'] = $allcount = $this->ProjectDocModel->filter_records_count($fk_project, $search);		
		$data['total_data'] =  $this->ProjectDocModel->total_records_count($fk_project);
       
		$data['result'] = $this->ProjectDocModel->getData($fk_project, $rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);
		$data['row'] = $rowno;
       
		echo json_encode($data);

	}

	public function insertRecord(){
		$insert = $this->ProjectDocModel->addRecode();
		if($insert > 0){
			$data['status'] = 1;
			$this->session->setFlashdata('Success', 'Projects image added successfully.');
		}else{
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		echo json_encode($data);
		exit();
	}

    public function editProjectDoc(){
	    $Id = $_POST['int_glcode'];
		$data['data'] = $this->ProjectDocModel->getProjectDocDetailsById($Id);
		$data['fk_project'] = $_POST['fk_project'];
		return view(Views . '\edit_projectDoc', $data);
	}
	
	public function updateProjectDoc($id){
		$update = $this->ProjectDocModel->updateRecord($id);
		if($update == 1){
			$this->session->setFlashdata('Success', 'Project Image updated successfully.');
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
		}
		
		echo json_encode($data);
		exit();
	}
}

