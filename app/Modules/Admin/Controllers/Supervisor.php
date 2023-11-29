<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\SupervisorModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\supervisor');
class Supervisor extends BaseController{
	public $SupervisorModel;
	public $CommonModel;
	function __construct(){
		$this->SupervisorModel = new SupervisorModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}

	public function index(){
		$data['data'] = $this->SupervisorModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->SupervisorModel->total_records_count();
		$data['total_rows'] = $this->SupervisorModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Supervisor List';
		$data['view'] = Views . '\view_Supervisor';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	public function supervisor_attendance(){
		$data['data'] = $this->SupervisorModel->getDataattendance(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->SupervisorModel->total_records_count_attendance();
		$data['total_rows'] = $this->SupervisorModel->filter_records_count_attendance();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Supervisor Attendance List';
		$data['view'] = Views . '\view_Supervisor_Attendance';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}


	
	public function loadDataattendance($rowno=0) { 
		$search = $_GET['append'];     
		$_field = $_GET['field'];    
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];        
        $page = $rowno;
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}

		$data['total_rows'] = $allcount = $this->SupervisorModel->filter_records_count_attendance($search);		
		$data['total_data'] =  $this->SupervisorModel->total_records_count_attendance();
		$data['result'] = $this->SupervisorModel->getDataattendance($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		// echo "<pre>";
		// print_r($data);die();
		echo json_encode($data);

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

		$data['total_rows'] = $allcount = $this->SupervisorModel->filter_records_count($search);		
		$data['total_data'] =  $this->SupervisorModel->total_records_count();
		$data['result'] = $this->SupervisorModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		// echo "<pre>";
		// print_r($data);die();
		echo json_encode($data);

	}

	public function checkUniquePanCard(){
		$inputvalues = $this->request->getVar('inputvalues');
		$where = array(
			'chr_delete'=>'N',
			'var_pancard' => $inputvalues
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$userPanCard = $this->CommonModel->getRowArray('mst_supervisor','*',$where);
		if(!empty($userPanCard)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function checkUniqueAadharCard(){
		$inputvalues = $this->request->getVar('inputvalues');
		$where = array(
			'chr_delete'=>'N',
			'var_aadhar' => $inputvalues
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$userAadharCard = $this->CommonModel->getRowArray('mst_supervisor','*',$where);
		if(!empty($userAadharCard)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function addSupervisor(){
		$data = [
			'title' => 'Add Supervisor',
			'view' => Views . '\add_Supervisor',
			'heading' => 'Welcome to BDC Project',
			'data' => array(),
		];
		return view('admin_tempate/template', $data);
	}

	public function insertRecord(){
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$username = $this->request->getVar('var_username');
		$aadhar = $this->request->getVar('var_aadhar');
		$pancard = $this->request->getVar('var_pancard');

		if($email!="" && !empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_email'=>$email)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_phone'=>$phone)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_username'=>$username)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_USERNAME;
		}else if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
				$data['status'] = 0;
				$data['msg'] = 'Please enter valid PAN No.';
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_pancard'=>$pancard)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_aadhar'=>$aadhar)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_AADHAR;
		}else {
			$insert = $this->SupervisorModel->addRecord();
			if($insert > 0){
				$data['status'] = 1;
				$this->session->setFlashdata('Success', 'Supervisor added successfully.');
			}else{
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
			}
		}
		echo json_encode($data);
		exit();
		
	}

	public function deleteAddress(){
		$result = $this->SupervisorModel->deleteAddress();
		echo $result;
	}

	public function editSupervisor($Id){
		$Id = base64_decode($Id);
		$result = $this->SupervisorModel->getSupervisorDetailsById($Id);
		$data = [
			'title' => 'Edit Supervisor',
			'view' => Views . '\edit_Supervisor',
			'heading' => 'Welcome to Property',
			'data' => $result,
			'mylibrary' => $this->mylibrary
		];
		return view('admin_tempate/template', $data);
	}

	public function updateSupervisor($id){
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$username = $this->request->getVar('var_username');
		$aadhar = $this->request->getVar('var_aadhar');
		$pancard = $this->request->getVar('var_pancard');

		if($email!="" && !empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_email'=>$email, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_phone'=>$phone, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_username'=>$username, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_USERNAME;
		}else if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_pancard'=>$pancard, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if(!empty($this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_delete'=>'N','var_aadhar'=>$aadhar, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_AADHAR;
		}else {
			$update  = $this->SupervisorModel->updateRecord($id);
			if($update == 1){
				$this->session->setFlashdata('Success', 'Supervisor updated successfully.');
				$data['status'] = 1;
			} else {
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
			}
		}
		echo json_encode($data);
		exit();
	}

	public function exportCSV(){
		$SupervisorData = $this->SupervisorModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"SupervisorList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Name", "User Name", "Email ID", "Phone", "Password", "Is Publish", "Created Date"));
        $cnt=1;
		
        if(!empty($SupervisorData)){
	        foreach ($SupervisorData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}
				
				$password = $this->mylibrary->decryptPass($val['var_password']);

	            $data = array(
	            	$cnt++,
	            	$val['var_name'],
					$val['var_username'],
	            	$val['var_email'],
	            	$val['var_phone'],
	            	$password,
					$status,
	            	$val['dt_createddate']
	            );
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}
	public function exportCSVAttendance(){
		$SupervisorData = $this->SupervisorModel->export_csv_attendance();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"SupervisorAttendanceList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No","ID", "Project Name", "Superviser Name", "Punch Time"));
        $cnt=1;
		
        if(!empty($SupervisorData)){
	        foreach ($SupervisorData as $val) {				
	            $data = array(
	            	$cnt++,
	            	$val['int_glcode'],
					$val['var_project'],
	            	$val['var_name'],
	            	$val['var_entry'],
	            );
	            fputcsv($handle, $data);
	        }
	    }
	    fclose($handle);
        exit;
	}

	public function checkUniqueEmail(){
		$email = $this->request->getVar('var_email');
		$where = array(
			'chr_delete'=>'N',
			'var_email' => $email
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$Supervisoremail = $this->CommonModel->getRowArray('mst_supervisor','int_glcode',$where);
		if(!empty($Supervisoremail)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function checkUniqueUsername(){

		$username = $this->request->getVar('var_username');
		$where = array(
			'chr_delete'=>'N',
			'var_username' => $username
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$SupervisorUsername = $this->CommonModel->getRowArray('mst_supervisor','int_glcode',$where);
		if(!empty($SupervisorUsername)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function checkUniquePhone(){
		$phone = $this->request->getVar('var_phone');
		$where = array(
			'chr_delete'=>'N',
			'var_phone' => $phone
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$SupervisorPhone = $this->CommonModel->getRowArray('mst_supervisor','int_glcode',$where);
		if(!empty($SupervisorPhone)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function createdisplayname(){
	    $html = "";
        $html .= "<ul>" ;
            if($_POST['var_name'] != '')
    	    {
    	        $var_name = $_POST['var_name'];
    	        $html .= '<li onclick="selectedDisplayname()">'.$var_name.'</li>';
    	        $newname = str_replace(" ","_",$_POST['var_name']);
    	        if($var_name != $newname)
    	        {
    	            $html .= '<li onclick="selectedDisplayname()">'.$newname.'</li>';
    	        }
    	        $newname1 = str_replace(" ","",$_POST['var_name']);
    	        if($var_name != $newname1)
    	        {
    	            $html .= '<li onclick="selectedDisplayname()">'.$newname1.'</li>';
    	        }
    	    }
    	    if($_POST['var_email'] != "")  	    
    	    {
    	        $var_email = $_POST['var_email'];
    	        $html .= '<li onclick="selectedDisplayname()">'.$var_email.'</li>';
    	        
        	    $parts = explode('@', $var_email);
                $display1 = array_shift($parts);
                if($var_name != $display1)
                {
                    $html .= '<li onclick="selectedDisplayname()">'.$display1.'</li>';
                }
    	    }
	    $html .= "</ul>" ;
	    
	    echo $html;
	}

	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_supervisor');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
}

