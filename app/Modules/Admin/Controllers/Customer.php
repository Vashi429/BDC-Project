<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\CustomerModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\customer');
class Customer extends BaseController{
	public $CustomerModel;
	public $CommonModel;
	function __construct(){
		$this->CustomerModel = new CustomerModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}

	public function index(){
		$data['data'] = $this->CustomerModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->CustomerModel->total_records_count();
		$data['total_rows'] = $this->CustomerModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Customer List';
		$data['view'] = Views . '\view_Customer';
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
		$data['total_rows'] = $allcount = $this->CustomerModel->filter_records_count($search);		
		$data['total_data'] =  $this->CustomerModel->total_records_count();
		$data['result'] = $this->CustomerModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);

	}

	public function addCustomer(){
		$data = [
			'title' => 'Add Customer',
			'view' => Views . '\add_Customer',
			'heading' => 'Welcome to BDC Project',
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'data' => array(),
		];
		return view('admin_tempate/template', $data);
	}

	public function insertRecord(){
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');

		if(!empty($this->CommonModel->getRowArray('mst_customer', 'int_glcode', array('chr_delete'=>'N', 'var_email'=>$email)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_customer', 'int_glcode', array('chr_delete'=>'N', 'var_phone'=>$phone)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if($pancard!="" && !preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
				$data['status'] = 0;
				$data['msg'] = 'Please enter valid PAN No.';
		}else if($pancard!="" && !empty($this->CommonModel->getRowArray('mst_customer', 'int_glcode', array('chr_delete'=>'N', 'var_pan'=>$pancard)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if ($gst!="" && !preg_match("/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9A-Za-z]{1})([zZ]){1}([0-9A-Za-z]){1}?$/", $gst)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid GST No.';
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_customer','int_glcode',array('chr_delete'=>'N','var_gst'=>$gst)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$insert = $this->CustomerModel->addRecord();
			if($insert > 0){
				$data['status'] = 1;
				$this->session->setFlashdata('Success', 'Customer added successfully.');
			}else{
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
			}
		}
		echo json_encode($data);
		exit();
	}

	public function deleteAddress(){
		$result = $this->CustomerModel->deleteAddress();
		echo $result;
	}

	public function editCustomer($Id){
		$Id = base64_decode($Id);
		$result = $this->CustomerModel->getCustomerDetailsById($Id);
		$data = [
			'title' => 'Edit Customer',
			'view' => Views . '\edit_Customer',
			'heading' => 'Welcome to BDC Projects',
			'data' => $result,
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'city' => $this->CommonModel->getCityList($result['fk_state']),
			'mylibrary' => $this->mylibrary
		];
		return view('admin_tempate/template', $data);
	}

	public function updateCustomer($id){
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');

		if(!empty($this->CommonModel->getRowArray('mst_customer', 'int_glcode', array('chr_delete'=>'N', 'var_email'=>$email, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_customer', 'int_glcode', array('chr_delete'=>'N', 'var_phone'=>$phone, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if($pancard!="" && !preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if($pancard!=""  &&!empty($this->CommonModel->getRowArray('mst_customer','int_glcode',array('chr_delete'=>'N','var_pan'=>$pancard, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if($gst!="" && !preg_match("/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([zZ]){1}([0-9A-Za-z]){1}?$/", $gst)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid GST No.';
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_customer','int_glcode',array('chr_delete'=>'N','var_gst'=>$gst, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$update = $this->CustomerModel->updateRecord($id);
			if($update == 1){
				$this->session->setFlashdata('Success', 'Customer updated successfully.');
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
		$customerData = $this->CustomerModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"customerList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Name", "Display Name", "Email ID", "Phone","Alt. Phone", "Address",  "State", "City", "Country", "Pincode", "Pan Number", "GST Number", "Is Publish", "Created Date"));
        $cnt=1;
		
        if(!empty($customerData)){
	        foreach ($customerData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}

				if($val['var_alt_phone'] == ''){
					$val['var_alt_phone'] = 'N/A';
				}

				if($val['var_gst'] == ''){
					$val['var_gst'] = 'N/A';
				}

				if($val['var_pan'] == ''){
					$val['var_pan'] = 'N/A';
				}
				if($val['fk_state'] > 0){
					$fk_state = $this->CommonModel->getValById('mst_state', 'var_title', array('int_glcode'=>$val['fk_state']));
				}
				if($val['fk_city'] > 0){
					$fk_city = $this->CommonModel->getValById('mst_city', 'var_title', array('int_glcode'=>$val['fk_city']));
				}
				if($val['fk_country'] > 0){
					$fk_country = $this->CommonModel->getValById('mst_country', 'var_title', array('int_glcode'=>$val['fk_country']));
				}
				if(trim($val['var_office_address'])==''){
					$val['var_office_address'] = 'N/A';
				}
	            $data = array(
	            	$cnt++,
	            	$val['var_name'],
					$val['var_displayname'],
	            	$val['var_email'],
	            	$val['var_phone'],
	            	$val['var_alt_phone'],
	            	trim($val['var_office_address']),
	            	$fk_state,
					$fk_city,
	            	$fk_country,
	            	$val['var_pincode'],
	            	$val['var_pan'],
	            	$val['var_gst'],
					$status,
	            	$val['dt_createddate']
	            );
	            fputcsv($handle, $data);
	        }
	    }
		
	    fclose($handle);
        exit;
	}

	public function checkUniqueEmail(){
		$where = array(
			'chr_delete'=>'N',
			'var_email' => $this->request->getVar('var_email')
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$customeremail = $this->CommonModel->getRowArray('mst_customer','int_glcode',$where);
		if(!empty($customeremail)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function checkUniquePhone(){
		$where = array(
			'chr_delete'=>'N',
			'var_phone' => $this->request->getVar('var_phone')
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$customerPhone = $this->CommonModel->getRowArray('mst_customer','int_glcode',$where);
		if(!empty($customerPhone)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function checkUniquePanCard(){
		$where = array(
			'chr_delete'=>'N',
			'var_pan' => $this->request->getVar('inputvalues')
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$userPanCard = $this->CommonModel->getRowArray('mst_customer','int_glcode',$where);
		if(!empty($userPanCard)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function checkUniqueGST(){
		$where = array(
			'chr_delete'=>'N',
			'var_gst' => $this->request->getVar('inputvalues')
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$userGST = $this->CommonModel->getRowArray('mst_customer','int_glcode',$where);
		if(!empty($userGST)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function createdisplayname(){
	    $html = "";
        $html .= "<ul>" ;
            if($_POST['var_name'] != ''){
    	        $var_name = $_POST['var_name'];
    	        $html .= '<li onclick="selectedDisplayname(this)">'.$var_name.'</li>';
    	        $newname = str_replace(" ","_",$_POST['var_name']);
    	        if($var_name != $newname){
    	            $html .= '<li onclick="selectedDisplayname(this)">'.$newname.'</li>';
    	        }
    	        $newname1 = str_replace(" ","",$_POST['var_name']);
    	        if($var_name != $newname1){
    	            $html .= '<li onclick="selectedDisplayname(this)">'.$newname1.'</li>';
    	        }
    	    }
    	    if($_POST['var_email'] != "")  	     {
    	        $var_email = $_POST['var_email'];
    	        $html .= '<li onclick="selectedDisplayname(this)">'.$var_email.'</li>';
    	        
        	    $parts = explode('@', $var_email);
                $display1 = array_shift($parts);
                if($var_name != $display1)
                {
                    $html .= '<li onclick="selectedDisplayname(this)">'.$display1.'</li>';
                }
    	    }

			if($_POST['var_business_name'] != ''){
    	        $var_business_name = $_POST['var_business_name'];
    	        $html .= '<li onclick="selectedDisplayname(this)">'.$var_business_name.'</li>';
    	        $business_name = str_replace(" ","_",$_POST['var_business_name']);
    	        if($var_business_name != $business_name){
    	            $html .= '<li onclick="selectedDisplayname(this)">'.$business_name.'</li>';
    	        }
    	        $business_name1 = str_replace(" ","",$_POST['var_business_name']);
    	        if($var_business_name != $business_name1){
    	            $html .= '<li onclick="selectedDisplayname(this)">'.$business_name1.'</li>';
    	        }
    	    }
	    $html .= "</ul>" ;
	    
	    echo $html;
	}

	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_customer');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
}

