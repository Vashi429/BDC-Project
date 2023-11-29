<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\CompanyProfileModel;
use App\Modules\Admin\Models\CommonModel;
define('Views', 'App\Modules\Admin\Views\company_profile');
class CompanyProfile extends BaseController{
	public $CompanyProfileModel;
	public $CommonModel;
	function __construct(){
		$this->CompanyProfileModel = new CompanyProfileModel();
		$this->CommonModel = new CommonModel();
		$this->email = \Config\Services::email();
	}

	public function index(){
		$data['data'] = $this->CompanyProfileModel->getData(0,ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->CompanyProfileModel->total_records_count();
		$data['total_rows'] = $this->CompanyProfileModel->filter_records_count();
		$page    = (int) ($this->request->getGet('page') ?? 1);
        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Company Profile List';
		$data['view'] = Views . '\view_company_profile';
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
		$data['total_rows'] = $allcount = $this->CompanyProfileModel->filter_records_count($search);		
		$data['total_data'] =  $this->CompanyProfileModel->total_records_count();
		$data['result'] = $this->CompanyProfileModel->getData($rowno,$rowperpage,$search,$_field,$_sort);
        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		
		$data['row'] = $rowno;
		echo json_encode($data);

	}

	public function addCompanyProfile(){
		$data = [
			'title' => 'Add Company Profile',
			'view' => Views . '\add_company_profile',
			'heading' => 'Welcome to BDC Project',
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'data' => array(),
		];
		return view('admin_tempate/template', $data);
	}

	public function insertRecord(){
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');
		
		if(!empty($this->CommonModel->getRowArray('mst_company_profile', 'int_glcode', array('chr_delete'=>'N', 'var_phone'=>$phone)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if($pancard!="" && !preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if($pancard!="" && !empty($this->CommonModel->getRowArray('mst_company_profile', 'int_glcode', array('chr_delete'=>'N', 'var_pan'=>$pancard)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if($gst!="" && !preg_match("/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([zZ]){1}([0-9A-Za-z]){1}?$/", $gst)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid GST No.';
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_company_profile', 'int_glcode', array('chr_delete'=>'N', 'var_gst'=>$gst)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$insert = $this->CompanyProfileModel->addRecord();
			if($insert > 0){
				$data['status'] = 1;
				$this->session->setFlashdata('Success', 'Company profile added successfully.');
			}else{
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', SOMETHING_WRONG);
			}
		}
		echo json_encode($data);
		exit();
	}

	public function deleteAddress(){
		$result = $this->CompanyProfileModel->deleteAddress();
		echo $result;
	}

	public function editCompanyProfile($Id){
		$Id = base64_decode($Id);
		$result = $this->CompanyProfileModel->getDetailsById($Id);
		$data = [
			'title' => 'Edit Company Profile',
			'view' => Views . '\edit_company_profile',
			'heading' => 'Welcome to Property',
			'data' => $result,
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'city' => $this->CommonModel->getCityList($result['fk_state']),
			'mylibrary' => $this->mylibrary
		];
		return view('admin_tempate/template', $data);
	}

	public function updateCompanyProfile($id){
		$phone = $this->request->getVar('var_phone');
		$pancard = $this->request->getVar('var_pancard');
		$gst = $this->request->getVar('var_gst');
		
		if(!empty($this->CommonModel->getRowArray('mst_company_profile', 'int_glcode', array('chr_delete'=>'N','var_phone'=>$phone, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PHONE;
		}else if($pancard!="" && !preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $pancard)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid PAN No.';
		}else if($pancard!="" && !empty($this->CommonModel->getRowArray('mst_company_profile', 'int_glcode', array('chr_delete'=>'N', 'var_pan'=>$pancard, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_PAN;
		}else if($gst!="" && !preg_match("/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([zZ]){1}([0-9A-Za-z]){1}?$/", $gst)) {
			$data['status'] = 0;
			$data['msg'] = 'Please enter valid GST No.';
		}else if($gst!="" && !empty($this->CommonModel->getRowArray('mst_company_profile','int_glcode',array('chr_delete'=>'N','var_gst'=>$gst, 'int_glcode!='=>$id)))){
			$data['status'] = 0;
			$data['msg'] = UNIQUE_GST;
		}else {
			$update = $this->CompanyProfileModel->updateRecord($id);
			if($update == 1){
				$this->session->setFlashdata('Success', 'Company profile updated successfully.');
				$data['status'] = 1;
			} else {
				$data['status'] = 0;
				$this->session->setFlashdata('Invalid', SOMETHING_WRONG);
			}
		}
		echo json_encode($data);
		exit();
	}

	public function exportCSV(){
		$customerData = $this->CompanyProfileModel->export_csv();
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"CompanyProfileList".time().".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
	
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Name", "Phone", "Address",  "State", "City", "Country", "Pincode", "Pan Number", "GST Number", "Is Publish", "Created Date"));
        $cnt=1;
		
        if(!empty($customerData)){
	        foreach ($customerData as $val) {
	        	if($val['chr_publish'] == 'Y'){
					$status = 'Publish';
				}else{
					$status = 'Unpublish';
				}
				if($val['var_pan']==""){
                    $val['var_pan'] = 'N/A';
                }
                if($val['var_gst']==''){
                    $val['var_gst'] = 'N/A';
                }
				$fk_state = $this->CommonModel->getValById('mst_state','var_title',array('int_glcode'=>$val['fk_state']));
				$fk_city = $this->CommonModel->getValById('mst_city','var_title',array('int_glcode'=>$val['fk_city']));
				$fk_country = $this->CommonModel->getValById('mst_country','var_title',array('int_glcode'=>$val['fk_country']));
	            $data = array(
	            	$cnt++,
	            	$val['var_name'],
	            	$val['var_phone'],
	            	$val['var_address'],
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
	
	public function checkUniquePhone(){
		$where = array(
			'chr_delete'=>'N',
			'var_phone' => $this->request->getVar('var_phone')
		);
		if(isset($_POST['int_glcode']) && $_POST['int_glcode'] > 0){
			$where['int_glcode!='] = $_POST['int_glcode'];
		}
		$customerPhone = $this->CommonModel->getRowArray('mst_company_profile','int_glcode',$where);
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
		$userPanCard = $this->CommonModel->getRowArray('mst_company_profile','int_glcode',$where);
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
		$userGST = $this->CommonModel->getRowArray('mst_company_profile','int_glcode',$where);
		if(!empty($userGST)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_company_profile');
		echo $result;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
}

