<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Models\ToolsModel;

define('Views', 'App\Modules\Admin\Views\tools');

class Tools extends BaseController{

	public $ToolsModel;

	function __construct(){

		$this->ToolsModel = new ToolsModel();

	}



	public function index(){

		$data['data'] = $this->ToolsModel->getData(0,ADMIN_PER_PAGE_ROWS);

		$data['total_data'] = $total_data =  $this->ToolsModel->total_records_count();

		$data['total_rows'] = $this->ToolsModel->filter_records_count();

		$page    = (int) ($this->request->getGet('page') ?? 1);

        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);

		$data['pager_links'] = $pager_links;

		$data['title'] = 'Tools List';

		$data['view'] = Views . '\view_tools';

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



		$data['total_rows'] = $allcount = $this->ToolsModel->filter_records_count($search);		

		$data['total_data'] =  $this->ToolsModel->total_records_count();

		$data['result'] = $this->ToolsModel->getData($rowno,$rowperpage,$search,$_field,$_sort);

        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		

		$data['row'] = $rowno;

		// echo "<pre>";

		// print_r($data);die();

		echo json_encode($data);



	}



    public function insertRecord(){

		

		$id = $_POST['int_glcode'];

		if($id == ''){

		    $data = $this->ToolsModel->addRecord();

		}else{

		    $data = $this->ToolsModel->updateRecord($id);

		}

		if(isset($data)){

		    echo "Success";

		}else{

		    echo "Error";

		}

		exit;

		

	}


    public function insertStockRecord(){

		$data = $this->ToolsModel->addstockRecord();
		if(isset($data)){
		    echo "Success";
		}else{
		    echo "Error";
		}
		exit;
	}


    public function transferstock(){

		$data = $this->ToolsModel->transferstock();
		if(isset($data)){
		    echo "Success";
		}else{
		    echo "Error";
		}
		exit;
	}

	

	public function editTools(){

	    $Id = $_POST['int_glcode'];

		$data['data'] = $this->ToolsModel->getToolsDetailsById($Id);

		return view(Views . '\edit_tools', $data);

	}



	public function transfor_tool(){

	    $Id = $_POST['int_glcode'];

		$data = $this->ToolsModel->gettoolassigndata($Id);
		if(count($data) > 0){
			$where = array(
				'chr_delete' => 'N',
				'chr_publish' => 'Y',
				'date_format(end_date,"%Y-%m-%d") >=' => date('Y-m-d'),
				'int_glcode !=' => $data['fk_project']
			);
			$projectData = $this->CommonModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'int_glcode', 'ASC');

			$html ='<form class="">';
			$html .='<div class="row">';
			$html .='<div class="col-12 col-lg-12 col-md-12">';
			$html .='<div class="form-group">';
			$html .='<label for="fk_project_transfer">Project <span class="text-danger">*</span></label><br>';
			$html .='<select class="form-control" name="fk_project_transfer" id="fk_project_transfer" required>';
			$html .='<option value=""></option>';
			foreach($projectData as $value){
				$html .='<option value="'.$value['int_glcode'].'">'.$value['var_project'].'</option>';
			}
			$html .='</select>';
			$html .='<span id="emptyErrortoolTransfer" class="text-danger"></span>';
			$html .='</div>';
			$html .='</div>';

			$html .='<div class="col-12 col-lg-12 col-md-12">';
			$html .='<div class="form-group">';
			$html .='<label for="var_stock_transfore">Stock Assign<span class="text-danger">*</span></label>';
			$html .='<div class="input-group">';
			$html .='<input type="text" class="form-control" name="var_stock_transfore" id="var_stock_transfore" oninput="return isNumberKey(event)" pattern="[0-9]*" inputmode="numeric" onkeyup="check_stock(this.value)" value="'.$data['var_stock'].'">';
			$html .='</div>';
			$html .='<span id="emptyErrorStockTransfer" class="text-danger"></span>';
			$html .='</div>';
			$html .='</div>';

			$html .='<input type="hidden" name="hidden_stock_transfer" id="hidden_stock_transfer" value="'.$data['var_stock'].'">';

			$html .='</div>';
			$html .='<button type="button" onclick="transfertool('.$data['int_glcode'].')" class="btn btn-primary m-t-15 waves-effect submit_save">Save</button>';
			$html .='</form>';
		}else{
			$html = '';
		}
		echo json_encode(array('html' => $html)); die;
	}

	

	

	public function delete_multiple(){

		$result = $this->CommonModel->delete_multiple('mst_tools');

		echo $result;

	}



	public function UpdatePublish(){

		$this->CommonModel->updatedisplay();

	}

	

	public function exportCSV(){

		$vendorData = $this->ToolsModel->export_csv();

		

		header("Content-type: application/csv");

        header("Content-Disposition: attachment; filename=\"tools".time().".csv\"");

        header("Pragma: no-cache");

        header("Expires: 0");

	

        $handle = fopen('php://output', 'w');

        fputcsv($handle, array("No", "Tool Name", "Size", "Width", "Height", "Stock", "Is Publish", "Created Date"));

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

	            	$val['var_tool_name'],

	            	$val['var_size'],

					$val['var_width'],

					$val['var_height'],

					$val['var_stock'],

					$status,

	            	$val['dt_createddate']

	            );

	            fputcsv($handle, $data);

	        }

	    }

	    fclose($handle);

        exit;

	}


	public function stock_managment(){

		$data['data'] = $this->ToolsModel->getDataStock(0,ADMIN_PER_PAGE_ROWS);

		$data['total_data'] = $total_data =  $this->ToolsModel->total_records_count_stock();

		$data['total_rows'] = $this->ToolsModel->filter_records_count_stock();

		$page = (int) ($this->request->getGet('page') ?? 1);

        $pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);

		$data['pager_links'] = $pager_links;

		$data['title'] = 'Tools Stock List';

		$data['view'] = Views . '\view_tools_stock';

		$data['heading'] = 'Welcome to BDC Project';


		$where = array(
			'chr_delete' => 'N',
			'chr_publish' => 'Y',
			'date_format(end_date,"%Y-%m-%d") >=' => date('Y-m-d'),
		);
		$data['projectData'] = $this->CommonModel->getResultArray('mst_project', 'int_glcode, var_project', $where, 'int_glcode', 'ASC');

		$where = array(
			'chr_delete' => 'N',
			'chr_publish' => 'Y',
			'var_available_stock >' => 0,
		);
		$data['tools'] = $this->CommonModel->getResultArray('mst_tools', 'int_glcode, var_tool_name, var_available_stock', $where, 'int_glcode', 'ASC');

		$data['tools_available_stock'] = array_column($data['tools'],'var_available_stock');
		
		return view('admin_tempate/template', $data);
	}


	

	public function loadDataStock($rowno=0) { 

		$search = $_GET['append'];     

		$_field = $_GET['field'];    

		$_sort = $_GET['sort'];

		$rowperpage = $_GET['entries'];        

        $page = $rowno;

		if($rowno != 0){

			$rowno = ($rowno-1) * $rowperpage;

		}



		$data['total_rows'] = $allcount = $this->ToolsModel->filter_records_count_stock($search);		

		$data['total_data'] =  $this->ToolsModel->total_records_count_stock();

		$data['result'] = $this->ToolsModel->getDataStock($rowno,$rowperpage,$search,$_field,$_sort);

        $data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);		

		$data['row'] = $rowno;

		// echo "<pre>";

		// print_r($data);die();

		echo json_encode($data);



	}

}



