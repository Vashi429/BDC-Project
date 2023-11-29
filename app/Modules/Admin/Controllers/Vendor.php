<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\VendorModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\vendor');
//For Excel sheet
require(dirname(__DIR__, 3) . '/ThirdParty/Excel/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Vendor extends BaseController{
	public $VendorModel;
	public $CommonModel;
	function __construct(){
		$this->VendorModel = new VendorModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}

	public function index(){
		$data['data'] = $this->VendorModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->VendorModel->total_records_count();
		$data['total_rows'] = $this->VendorModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Vendor List';
		$data['view'] = Views . '\view_Vendor';
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

		$data['total_rows'] = $allcount = $this->VendorModel->filter_records_count($search);		
		$data['total_data'] =  $this->VendorModel->total_records_count();
		$data['result'] = $this->VendorModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);

	}
	public function addVendor(){
		$data = [
			'title' => 'Add Vendor',
			'view' => Views . '\add_Vendor',
			'heading' => 'Welcome to BDC Project',
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'data' => array(),
		];
		return view('admin_tempate/template', $data);
	}

	public function exportCSV(){
		$vendorData = $this->VendorModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"vendorList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Name", "Email ID", "Phone","Business Name", "Address",  "State", "City", "Country", "Pincode", "GST Number", "Is Publish", "Created Date"));
        $cnt=1;
		
        if(!empty($vendorData)){
	        foreach ($vendorData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}

				
				$fk_state = $this->CommonModel->getValById('mst_state','var_title',array('int_glcode'=>$val['fk_state']));
				$fk_city = $this->CommonModel->getValById('mst_city','var_title',array('int_glcode'=>$val['fk_city']));
				$fk_country = $this->CommonModel->getValById('mst_country','var_title',array('int_glcode'=>$val['fk_country']));
	            $data = array(
	            	$cnt++,
	            	$val['var_name'],
	            	$val['var_email'],
	            	$val['var_phone'],
	            	$val['var_business'],
					$val['var_address'],
	            	$fk_state,
					$fk_city,
	            	$fk_country,
	            	$val['var_pincode'],
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

	public function insertRecord(){
		
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');

		if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_email'=>$email)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_phone'=>$phone)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_pancard'=>$pancard)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_gst'=>$gst)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$insert = $this->VendorModel->addRecord();
			if($insert > 0){
				$data['status'] = 1;
				$this->session->setFlashdata('Success', 'Vendor added successfully.');
			}else{
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
			}
		}
		echo json_encode($data);
		exit();
	}

	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_vendor');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}

	public function editVendor($Id){
		$Id = base64_decode($Id);
		$result = $this->VendorModel->getVendorDetailsById($Id);
		$data = [
			'title' => 'Edit Vendor',
			'view' => Views . '\edit_Vendor',
			'heading' => 'Welcome to Property',
			'data' => $result,
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'city' => $this->CommonModel->getCityList($result['fk_state']),
			'mylibrary' => $this->mylibrary
		];
		return view('admin_tempate/template', $data);
	}

	public function updateVendor($id){
		$email = $this->request->getVar('var_email');
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');

		if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_email'=>$email, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_EMAIL;
		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_phone'=>$phone, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_pancard'=>$pancard, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_gst'=>$gst, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$update = $this->VendorModel->updateRecord($id);
			if($update == 1){
				$this->session->setFlashdata('Success', 'Vendor updated successfully.');
				$data['status'] = 1;
			} else {
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');
			}
		}
		echo json_encode($data);
		exit();
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
		$Supervisoremail = $this->CommonModel->getRowArray('mst_vendor','int_glcode',$where);
		if(!empty($Supervisoremail)){
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
		
		$VendorPhone = $this->CommonModel->getRowArray('mst_vendor', 'int_glcode', $where);
		if(!empty($VendorPhone)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
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
		$userPanCard = $this->CommonModel->getRowArray('mst_vendor','*',$where);
		if(!empty($userPanCard)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
	
	public function checkUniqueGST(){

		$inputvalues = $this->request->getVar('inputvalues');
		$where = array(
			'chr_delete'=>'N',
			'var_gst' => $inputvalues
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$userPanCard = $this->CommonModel->getRowArray('mst_vendor','*',$where);
		if(!empty($userPanCard)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

}

