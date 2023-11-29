<?php

namespace App\Modules\Api\Controllers;



use CodeIgniter\Controller;

use App\Modules\Api\Models\SupervisorModel;

use App\Libraries\Mylibrary; // Import library

use App\Modules\Admin\Models\CommonModel;

class Supervisor extends Controller

{

	public $SupervisorModel;

	protected $helpers = ['url', 'file', 'my_helper', 'form'];

	protected $validation;

	protected $request;

	protected $session;

	protected $email;

	function __construct(){

		$this->session = \Config\Services::session();

		$this->validation = \Config\Services::validation();

		$this->request = \Config\Services::request();

		$this->mylibrary = new Mylibrary();

		$this->email = \Config\Services::email();

		$this->SupervisorModel = new SupervisorModel();

		$this->CommonModel = new CommonModel();





	}



	public function login(){

		header("content-Type:application/json");

		$pass = $this->mylibrary->cryptPass($_POST['var_password']);

		$checkemail = $this->CommonModel->getRowArray('mst_supervisor','int_glcode',array('chr_publish' => 'Y','chr_delete'=>'N','var_username'=>$_POST['var_username']));

		

		$checkPass = $this->CommonModel->getRowArray('mst_supervisor','*',array('chr_publish' => 'Y','chr_delete'=>'N','var_password'=>$pass,'var_username'=>$_POST['var_username']));



		$checkPass['role'] = "Engineer";

		if($_POST['var_username'] !="" && empty($checkemail)){

			$response['success'] = 0;

			$response['message'] = "Invalid User name";

		}else if(empty($checkPass) || $checkPass == ''){

			$response['success'] = 0;

			$response['message'] = "Invalid credentials";

		}else{

			$response['success'] = 1;

			$response['message'] = $checkPass;

		}

		echo json_encode($response);

		exit;

	}

	public function home(){

		header("content-Type:application/json");

	    $project_list = $this->SupervisorModel->projectOngoingList($_POST['fk_superviser'],$_POST['var_limit']);

        $recent_expance = $this->SupervisorModel->RecentExpenses($_POST['fk_superviser'],'2');

        $recent_challan = $this->SupervisorModel->RecentChallan($_POST['fk_superviser'],'2');

		if($_POST['fk_superviser'] != '' && isset($_POST))

		{

			$data = array(

				"ongoin_project" => $project_list,

				"recent_expance" => $recent_expance,

				"recent_challan" => $recent_challan,

			);

			$response['success'] = 1;

			$response['data'] = $data;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}



	public function SupervisorProjectList()

	{

		$projectData = $this->SupervisorModel->getSupervisorProjectData($_POST['fk_superviser']);

		if(!empty($projectData))

		{

			$response['success'] = 1;

			$response['data'] = $projectData;

		}else{

			$response['success'] = 0;

			$response['massage'] = "Data Not Found";

		}

		echo json_encode($response);

		exit;

	}



	public function ItemList()

	{

		$fk_project  = $_POST['fk_project'];

		$ItemList = $this->SupervisorModel->getProjectItem($fk_project);

		if(!empty($ItemList))

		{

			$response['success'] = 1;

			$response['data'] = $ItemList;



		}else{

			$response['success'] = 0;

			$response['massage'] = "Data Not Found";

		}

		echo json_encode($response);

		exit;

	}



	public function VendorList()

	{

		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$vendorData = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');

		if(!empty($vendorData))

		{

			$response['success'] = 1;

			$response['data'] = $vendorData;



		}else{

			$response['success'] = 0;

			$response['massage'] = "Data Not Found";

		}

		echo json_encode($response);

		exit;

	}

	public function insertChallanReocde()

	{

		$addUpdateChallan = $this->SupervisorModel->addUpdateChallan();

		if(!empty($addUpdateChallan)){

			$response['success'] = 1;

			$response['data'] = $addUpdateChallan;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}



	public function updateChallanReocde()

	{

		$addUpdateChallan = $this->SupervisorModel->addUpdateChallan($_POST['fk_challan']);

		if(!empty($addUpdateChallan)){

			$response['success'] = 1;

			$response['data'] = $addUpdateChallan;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}





	public function ChallanList()

	{

		$ChallanList = $this->SupervisorModel->RecentChallan($_POST['fk_superviser']);

		if(!empty($ChallanList)){

			$response['success'] = 1;

			$response['data'] = $ChallanList;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}

	public function TaskList()

	{

		$TaskList = $this->SupervisorModel->TaskList($_POST['fk_project']);

		if(!empty($TaskList)){

			$response['success'] = 1;

			$response['data'] = $TaskList;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}



	public function AddTask()

	{

		$AddTask = $this->SupervisorModel->AddTask();

		if(!empty($AddTask)){

			$response['success'] = 1;

			$response['data'] = $AddTask;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}



	

	public function UpdateTask()

	{

		$UpdateTask = $this->SupervisorModel->AddTask($_POST['fk_task']);

		if(!empty($UpdateTask)){

			$response['success'] = 1;

			$response['data'] = $UpdateTask;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}





	public function searchChallan()

	{

		$searchChallan = $this->SupervisorModel->RecentChallan($_POST['fk_superviser'],'',$_POST['search']);

		if(!empty($searchChallan)){

			$response['success'] = 1;

			$response['data'] = $searchChallan;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}



	public function getState()

	{

		$allState = $this->CommonModel->getStateList();

		$allState['all_country'] = $this->CommonModel->getAllCountry();

		if(!empty($allState)){

			$response['success'] = 1;

			$response['data'] = $allState;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;



	}



	public function getCity()

	{

		$fk_state = $_POST['fk_state'];

		$cityList = $this->CommonModel->getCityList($fk_state);

		$cityList['other'] = "Other";

		if(!empty($cityList)){

			$response['success'] = 1;

			$response['data'] = $cityList;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;



	}



	public function add_vendor()

	{

		

		if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_email'=>$_POST['var_email'])))){

			$response['success'] = 0;

			$response['masage'] = "Please enter uniq email";

		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_phone'=>$_POST['var_phone'])))){

			$response['success'] = 0;

			$response['masage'] = "Please enter uniq phone";

		}else if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $_POST['var_pancard'])) {

			$response['success'] = 0;

			$response['masage'] = 'Please enter valid PAN No.';

		}else if(!empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_pancard'=>$_POST['var_pancard'])))){

			$response['success'] = 0;

			$response['masage'] =  "Please enter uniq PAN No";

		}else if($_POST['var_gst']!="" && !empty($this->CommonModel->getRowArray('mst_vendor','int_glcode',array('chr_delete'=>'N','var_gst'=>$_POST['var_gst'])))){

			$response['success'] = 0;

			$response['masage'] =  "Please enter uniq GST NO.";

		}else {

			$insert = $this->SupervisorModel->addRecord();

			if($insert > 0){

				$response['success'] = 1;

				$response['masage'] = 'Vendor added successfully.';

				$response['data'] = $insert;



			}else{

				$response['success'] = 0;

				$response['masage'] = "Data Not found";

			}

		}

		echo json_encode($response);

		exit();

	}



	public function checkUniqueEmail(){

		if($_POST['var_email'] != '')

		{

			if($_POST['var_flag'] == 'V')

			{

				$tabel = 'mst_vendor';

			}

			$result = $this->CommonModel->getRowArray($tabel,'int_glcode',array('chr_delete'=>'N','var_email'=>$_POST['var_email']));

			if(!empty($result))

			{

				$response['success'] = 0;

				$response['masage'] = 'Plese enter unique email';

			}else{

				$response['success'] = 1;

				$response['masage'] = "Success";

			}

		}else{

			$response['success'] = 0;

			$response['masage'] = 'Plese enter email';

		}

		echo json_encode($response);

		exit();



	}



	public function checkUniquePhone(){

		if($_POST['var_phone'] != '')

		{

			if($_POST['var_flag'] == 'V')

			{

				$tabel = 'mst_vendor';

			}

			$result = $this->CommonModel->getRowArray($tabel,'int_glcode',array('chr_delete'=>'N','var_phone'=>$_POST['var_phone']));

			if($result > 0)

			{

				$response['success'] = 0;

				$response['masage'] = 'Plese enter unique Phone';

			}else{

				$response['success'] = 1;

				$response['masage'] = "Success";

			}

		}else{

			$response['success'] = 0;

			$response['masage'] = 'Plese enter Phone no';

		}

		

		echo json_encode($response);

		exit();



	}

	public function checkUniquePanCard(){



		if($_POST['var_pancard'] != '')

		{

				if($_POST['var_flag'] == 'V')

				{

					$tabel = 'mst_vendor';

				}

					if (!preg_match("/^([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}?$/", $_POST['var_pancard'])) {

						$response['success'] = 0;

						$response['masage'] = 'Please enter valid PAN No.';



					}else{

						$result = $this->CommonModel->getRowArray($tabel,'int_glcode',array('chr_delete'=>'N','var_pancard'=>$_POST['var_pancard']));

						if(!empty($result))

						{

							$response['success'] = 0;

							$response['masage'] = "Please enter Uniq PAN No.";

						}else{

							$response['success'] = 1;

							$response['masage'] = "Success";

						}

						

					}

		}else{

			$response['success'] = 0;

			$response['masage'] = "Please enter PAN No.";

		}

		

		echo json_encode($response);

		exit();

	}



	public function checkUniqueAdharCard(){

		if($_POST['var_adhaar'] != '')

		{

			// if($_POST['var_flag'] == 'V')

			// {

			// 	$tabel = 'mst_vendor';

			// }else{

			// 	$tabel = '';

			// }

				if (strlen($_POST['var_adhaar']) !== 12) {

					$response['success'] = 0;

					$response['masage'] = 'Please enter valid Adhaar No.';



				}else{

					

					// $result = $this->CommonModel->getRowArray($tabel,'int_glcode',array('chr_delete'=>'N','var_adhaar'=>$_POST['var_adhaar']));



				

					// if(!empty($result))

					// {

					// 	$response['success'] = 0;

					// 	$response['masage'] = "Please enter Uniq PAN No.";

					// }else{

						$response['success'] = 1;

						$response['masage'] = "success";

					// }

					

				}

		}else{

			$response['success'] = 0;

			$response['masage'] = "Please enter Adhaar No.";

		}

		

		echo json_encode($response);

		exit();

	}



	public function checkUniqueGstNumber(){



		if(isset($_POST['var_gst']))

		{

			if($_POST['var_flag'] == 'V')

			{

				$tabel = 'mst_vendor';

			}

			if (!preg_match("/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([zZ]){1}([0-9A-Za-z]){1}?$/", $_POST['var_gst'])) {

				$response['success'] = 0;

				$response['masage'] = 'Please enter valid GST NO.';

			}else{

					$result = $this->CommonModel->getRowArray($tabel,'int_glcode',array('chr_delete'=>'N','var_gst'=>$_POST['var_gst']));

					if(!empty($result))

					{

						$response['success'] = 0;

						$response['masage'] = "Please enter Uniq GST NO.";

					}else{

						$response['success'] = 1;

						$response['masage'] = "Success";

					}

			}

		}else{

			$response['success'] = 0;

			$response['masage'] = "Plese enter GST NO.";

		}

		

		echo json_encode($response);

		exit();

	}





	public function attendance()

	{

		$result = $this->SupervisorModel->attendance();

		if(!empty($result)){

			$response['success'] = 1;

			$response['data'] = $result;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}	

		echo json_encode($response);

		exit();

	}



	// public function AttendanceProjectList()

	// {

	// 	$result= $this->SupervisorModel->AttendanceProjectList($_POST['fk_supervisor'],'P');

	// 	if(!empty($result)){

	// 		$response['success'] = 1;

	// 		$response['data'] = $result;

	// 	}else{

	// 		$response['success'] = 0;

	// 		$response['masage'] = "Data Not found";

	// 	}	

	// 	echo json_encode($response);

	// 	exit();

	// }




	

	public function ExpenseList(){
		$ExpenseList = $this->SupervisorModel->RecentExpenses($_POST['fk_superviser']);

		if(!empty($ExpenseList)){

			$response['success'] = 1;

			$response['data'] = $ExpenseList;

		}else{

			$response['success'] = 0;

			$response['masage'] = "Data Not found";

		}

		echo json_encode($response);

		exit;

	}


	public function expens_list_bill(){
		$expense_id = $_POST['expense_id'];
		$where = array(
			'fk_expense' => $expense_id,
		);
		$get_all_data = $this->SupervisorModel->getResultArray('mst_expence_bill', 'int_glcode, var_bill_expense_date, fk_expense, var_amount, fk_bill', $where, 'int_glcode', 'ASC');
		if(count($get_all_data) > 0){
			$bill_expence_data = array();
			foreach ($get_all_data as $key => $value) {
				$all_data = array();
				$all_data['fk_bill'] = $value['fk_bill'];
				$all_data['fk_expense'] = $value['fk_expense'];
				$all_data['var_bill_expense_date'] = $value['var_bill_expense_date'];
				$all_data['var_amount'] = $value['var_amount'];
				$bill_expence_data[] = $all_data;
			}
			$bill_id = $get_all_data[0]['fk_bill'];
			$data_bill_due = array();
			if($bill_id != '' && $bill_id != '0'){
				$where = array(
					'int_glcode' => $bill_id,
				);
				$data_bill_due = $this->SupervisorModel->getResultArray('mst_bill_due', 'var_amount, var_due_amount, var_paid_amount', $where, 'int_glcode', 'ASC');
			}
			$data = array('bill_expence_data' => $bill_expence_data,'data_bill_due' => $data_bill_due);
			$response['success'] = 1;
			$response['data'] = $data;
		}else{
			$response['success'] = 0;
			$response['masage'] = "Data Not found";
		}

		echo json_encode($response);
		exit;
	}
	

	public function profile_list_expense(){
		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');
		$companyProfileData = $this->SupervisorModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'ASC');
		if(!empty($companyProfileData)){
			$response['success'] = 1;
			$response['data'] = $companyProfileData;
		}else{
			$response['success'] = 0;
			$response['masage'] = "Data Not found";
		}
		echo json_encode($response);
		exit;
	}

	public function project_list_using_profile_expense(){
		$fk_profile = $_POST['fk_profile'];
		$where = array(
			'chr_delete' => 'N',
			'chr_publish' => 'Y',
			'fk_profile' => $fk_profile,
		);
		$projectData = $this->SupervisorModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'var_project', 'ASC');
		if(!empty($projectData)){
			$response['success'] = 1;
			$response['data'] = $projectData;
		}else{
			$response['success'] = 0;
			$response['masage'] = "Data Not found";
		}
		echo json_encode($response);
		exit;
	}
	public function get_labour_using_project(){
		$project_id = $_POST['project_id'];
		$date = $_POST['date'];
		$projectData = $this->CommonModel->getLaborList($project_id,$date);
		if(!empty($projectData)){
			$response['success'] = 1;
			$response['data'] = $projectData;
		}else{
			$response['success'] = 0;
			$response['masage'] = "Data Not found";
		}
		echo json_encode($response);
		exit;
	}

	public function add_expens(){
		$addUpdateChallan = $this->SupervisorModel->addUpdateRecord();
		if(!empty($addUpdateChallan)){
			$response['success'] = 1;
			$response['data'] = $addUpdateChallan;
		}else{
			$response['success'] = 0;
			$response['masage'] = "Something went wronge.";
		}
		echo json_encode($response);
		exit;
	}

	
	public function getBillList(){
		$fk_project  = $_POST['project'];
		$fk_vendor  = $_POST['vendor'];
		$total = 0;
		$html ='';
		if(!empty($fk_vendor) && !empty($fk_project)){
			$challanList = $this->SupervisorModel->getBillList($fk_project, $fk_vendor);
			$bill_list = array();
			foreach($challanList as $val){				
				$where = array('fk_bill' => $val['int_glcode']);
				$bill_data = $this->SupervisorModel->getResultArray('mst_bill_due', 'var_amount, var_due_amount, var_paid_amount', $where);

				$all_data = array();
				$all_data['int_glcode'] = $val['int_glcode'];
				$all_data['var_bill_no'] = $val['var_bill_no'];
				$all_data['var_bill_date'] = $val['var_bill_date'];
				if(count($bill_data) > 0){
					$all_data['var_due_amount'] = $bill_data[0]['var_due_amount'];
					$all_data['var_amount'] = $bill_data[0]['var_amount'];
					$all_data['var_paid_amount'] = $bill_data[0]['var_paid_amount'];
				}else{
					$all_data['var_due_amount'] = '';
					$all_data['var_amount'] = '';
					$all_data['var_paid_amount'] = '';
				}
				$bill_list[] = $all_data;
			}
			if(count($bill_list) > 0){
				$response['success'] = 1;
				$response['data'] = $bill_list;
			}else{
				$response['success'] = 0;
				$response['masage'] = "Something went wronge.";
			}
		}else{
			$response['success'] = 0;
			$response['masage'] = "Something went wronge.";
		}
		echo json_encode($response);
		exit;
	}

}

