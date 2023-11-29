<?php



namespace App\Modules\Admin\Controllers;



use App\Modules\Admin\Models\ChallanModel;

use App\Modules\Admin\Models\CommonModel;

use App\Libraries\Mylibrary; // Import library

use App\Libraries\PdfLibrary;



define('Views', 'App\Modules\Admin\Views\challan');

class Challan extends BaseController{

	public $ChallanModel;

	public $CommonModel;

	protected $validation;

	protected $request;

	protected $session;

	function __construct(){

		$this->session = \Config\Services::session();

		$this->validation = \Config\Services::validation();

		$this->request = \Config\Services::request();

		$this->mylibrary = new Mylibrary();

		$this->ChallanModel = new ChallanModel();

		$this->CommonModel = new CommonModel();

	}

	/* List of Challan start */

	public function index(){

		$data['data'] = $this->ChallanModel->getData(0,ADMIN_PER_PAGE_ROWS);

		$data['total_data'] = $total_data =  $this->ChallanModel->total_records_count();

		$data['total_rows'] = $this->ChallanModel->filter_records_count();

		$page    = (int) ($this->request->getGet('page') ?? 1);

        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);

		$data['pager_links'] = $pager_links;

		$data['title'] = 'Challan List';

		$data['view'] = Views . '\list_challan';

		$data['heading'] = 'Welcome to BDC Project';

		return view('admin_tempate/template', $data);

	}

	/* List of Challan end */



	/* List of Challan with pagination, search start */

	public function loadData($rowno=0) { 

		$search = $_GET['append'];     

		$_field = $_GET['field'];    

		$_sort = $_GET['sort'];

		$rowperpage = $_GET['entries'];        

        $page = $rowno;

		if($rowno != 0){

			$rowno = ($rowno-1) * $rowperpage;

		}

		$data['total_rows'] = $allcount = $this->ChallanModel->filter_records_count($search);		

		$data['total_data'] =  $this->ChallanModel->total_records_count();

		$data['result'] = $this->ChallanModel->getData($rowno,$rowperpage,$search,$_field,$_sort);

        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		

		$data['row'] = $rowno;

		echo json_encode($data);

	}

	/* List of Challan with pagination, search end */



	/* add Challan page start */

	public function addChallan(){

		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$data['vendorData'] = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');

		$data['supervisorData'] = $this->ChallanModel->getSupervisorData();

		$data['projectData'] = $this->CommonModel->getProjectList();

		$data['itemData'] = array();

		$data['autoInvoiceID'] = 'C_' . $this->CommonModel->getUniqueAutoId('mst_challan', 'var_challan_id');

		$data['data'] = array();

		$data['title'] = 'Add Challan';

		$data['view'] = Views . '\add_challan';

		$data['heading'] = 'Welcome to BDC Project';

		return view('admin_tempate/template', $data);

	}

	/* add Challan page end */



	/* on change of project get the project's item list of the projects start */

	public function getProjectItem(){

		$fk_project  = $_POST['fk_project'];

		$projects = $this->ChallanModel->getProjectItem($fk_project);

		$html = "<option value=''></option>";

		if(!empty($projects)){

			foreach($projects as $val){

				$html .= '<option value="'.$val['int_glcode'].'" data-due-stock = "'.$val['var_due_stock'].'">'.$val['var_item'].' ('.$val['var_unit']. ')</option>';

			}

		}

		echo $html; exit;

	}

	/* on change of project get the project's item list of the projects end */



	/* insert Challan start */

	public function insertRecord(){

		$insert = $this->ChallanModel->addUpdateRecord();

		if($insert > 0){

			$data['status'] = 1;

			$this->session->setFlashdata('Success', 'Challan added successfully.');

		}else{

			$data['status'] = 0;

			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');

		}

		echo json_encode($data);

		exit();

	}

	/* insert Challan end */



	/* edit Challan page start */

	public function editChallan($id){

		$id = base64_decode($id);

		$data['data'] = $challanData = $this->ChallanModel->getChallanData($id);

		$where = array('chr_delete' => 'N', 'chr_publish' => 'Y');

		$data['vendorData'] = $this->CommonModel->getResultArray('mst_vendor', 'int_glcode, var_name', $where, 'var_name', 'ASC');

		$data['itemData'] = $this->ChallanModel->getProjectItem($challanData['fk_project']);

		$data['projectData'] = $this->ChallanModel->getProjectList();
		
		$data['title'] = 'Edit Challan';

		$data['view'] = Views . '\edit_challan';

		$data['heading'] = 'Welcome to BDC Project';



		return view('admin_tempate/template', $data);

	}

	/* edit Challan page end */



	/* update Challan start */

	public function updateRecord($id){

		$update = $this->ChallanModel->addUpdateRecord($id);

		if($update > 0){

			$this->session->setFlashdata('Success', 'Challan updated successfully.');

			$data['status'] = 1;

		} else {

			$data['status'] = 0;

			$this->session->setFlashdata('Invalid', 'Something went wrong, please try again later.');

		}

		echo json_encode($data);

		exit();

	}

	/* update Challan end */



	/* export Challan data start */

	public function exportCSV(){

		$challanData = $this->ChallanModel->export_csv();

		header("Content-type: application/csv");

        header("Content-Disposition: attachment; filename=\"ChallanList".time().".csv\"");

        header("Pragma: no-cache");

        header("Expires: 0");

	

        $handle = fopen('php://output', 'w');

		fputcsv($handle, array("No", "Challan Id", "Bill No", "Vendor", "Supervisor",  "Project", "Item", "Quantity", "Amount", "Payment Status", "Created Date"));

        $cnt=1;

        if(!empty($challanData)){

			foreach ($challanData as $val) {

	            $data = array(

	            	$cnt++,

	            	$val['var_challan_id'],

	            	$val['var_bill_no'],

	            	$val['vendor_name'],

					$val['supervisor_name'],

	            	$val['var_project'],

	            	$val['var_item']. ' ('.$val['var_unit'].')',

	            	$val['var_quantity'],

	            	$val['var_amount'],

					$val['var_status'],

	            	$val['dt_createddate']

	            );

			

	            fputcsv($handle, $data);

	        }

	    }

	    fclose($handle);

        exit;

	}

	/* export Challan data end */



	/* delete multiple rows of Challan start */

	public function delete_multiple(){

		$id = $this->request->getVar('id');

		if(!empty($id)){

			foreach($id as $val){

				$challanList = $this->CommonModel->getRowArray('mst_challan', '*', array('int_glcode' => $val));

				if(!empty($challanList)){

					if($challanList['fk_bill']==0){

						$this->CommonModel->updateRows('mst_expense', array('chr_delete' => 'Y', array('int_glcode' => $challanList['fk_expense'])));

					}

				}

			}

		}

		$result = $this->CommonModel->delete_multiple('mst_challan');

		echo $result;

	}

	/* delete multiple rows of Challan end */



}

