<?php

namespace App\Modules\Api\Models;

use App\Modules\Admin\Models\CommonModel;

use CodeIgniter\Model;



class SupervisorModel extends Model

{

	public $con = '';

	public $CommonModel;

	function __construct() {

		$this->con = db_connect();

		$this->CommonModel = new CommonModel();

	

	}

	public function projectOngoingList($fk_superviser,$limit = '')

	{

		$currentDate = date('Y-m-d');

		$builder = $this->con->table('mst_project');

        $builder->select('*');

        $builder->where('fk_supervisor',$fk_superviser);

        $builder->where('start_date <=',$currentDate);

        $builder->where('end_date >=',$currentDate);

        $builder->where('chr_delete',"N");

        $builder->where('chr_publish',"Y");

        $builder->orderBy('int_glcode',"DESC");

		if($limit != '')

		{

			$builder->limit($limit);

		}

        $query =  $builder->get();

        return $query->getResultArray();

	}

	public function RecentChallan($fk_superviser,$limit = '',$filter = '')

    {

        $builder = $this->con->table('mst_challan mc');

        $builder->select('mc.int_glcode,mc.var_challan_id,mc.var_image,mc.fk_project,mc.fk_item,mc.var_quantity,mc.var_amount,mc.var_status,mp.var_project,mp.var_Project_id, eb.fk_expense');

        $builder->join('mst_project mp','mp.int_glcode = mc.fk_project','left');

		$builder->join("mst_expence_bill eb", "eb.fk_challan = mc.int_glcode ", "left");

        $builder->join("mst_expense e", "eb.fk_expense = e.int_glcode ", "left");

        $builder->where('mc.fk_superviser',$fk_superviser);



        if ($filter != '') {

			$builder->groupStart();

			$builder->Like('mp.var_Project_id',$filter);

			$builder->orLike('mp.var_project',$filter);

			$builder->orLike('mc.var_quantity',$filter);

            $builder->orLike('mc.var_amount',$filter);

			$builder->groupEnd();

		}

        $builder->where('mc.chr_delete',"N");

		$builder->where('e.chr_delete', 'N');

        $builder->orderBy('mc.int_glcode',"DESC");

		if($limit != '')

		{

			$builder->limit($limit);

		}

        $query =  $builder->get();

		$row = $query->getResultArray();

	

		$data = array();

		foreach($row as $key => $value)

		{

			if($value['var_image'] != '')

			{

				$value['var_image'] = base_url()."/public/uploads/challan/".$value['var_image'];

			}else{

				$value['var_image'] = "";

			}

			$value['var_item'] = $this->GetItemById($value['fk_project']);

			$data[] = $value;

		}

		

        return $data;

    }

	public function GetItemById($fk_project)

	{

		$builder = $this->con->table('mst_project_item mpt');

        $builder->select('mm.int_glcode, mm.var_item, mm.var_unit, mpt.var_due_stock');

        $builder->join('mst_material mm', 'mm.int_glcode = mpt.fk_material', 'left');

        $builder->where('mpt.fk_project', $fk_project);

       

        $builder->where('mpt.chr_delete', 'N');

        $builder->where('mpt.chr_publish', 'Y');

        $query = $builder->get();

        $rows = $query->getRowArray();

        return $rows;

	}

	public function RecentExpenses($fk_supervisor,$limit = '')

	{

		$builder = $this->con->table('mst_project mp');

		$builder->select('me.*');

		$builder->join('mst_expense me','me.fk_project = mp.int_glcode','left');

		$builder->where('mp.fk_supervisor', $fk_supervisor);

		$builder->where('me.chr_delete', 'N');

		$builder->orderBy('me.int_glcode', 'DESC');

		if ($limit != '') {

			$builder->limit($limit);

		}

		$query = $builder->get();

        return $query->getResultArray();

	}



	public function getSupervisorProjectData($fk_supervisor){

        $builder = $this->con->table('mst_project_item pt');

        $builder->select('p.int_glcode, p.var_project');

        $builder->join('mst_project p', 'p.int_glcode = pt.fk_project', 'left');

        $builder->where('p.chr_delete', 'N');

        $builder->where('p.chr_publish', 'Y');

        $builder->where('pt.chr_delete', 'N');

        $builder->where('pt.chr_publish', 'Y');

        $builder->where('p.fk_supervisor', $fk_supervisor);

        $builder->where('date_format(p.end_date,"%Y-%m-%d") >=', date('Y-m-d'));

        $builder->orderBy('p.var_project', 'ASC');

        $builder->groupBy('p.int_glcode');

        $query = $builder->get();

        $rows = $query->getResultArray();

        return $rows;

    }



	function addUpdateChallan($id=""){

      

        if ($_FILES['var_image']['name'] != '') {

            if (!is_dir('uploads/challan')) {

				mkdir('uploads/challan', 0777, TRUE);

			}

			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];

			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);

			$destination = 'uploads/challan/';

			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);

        }else{

            $var_image = $_POST['hvar_image'];

        }

		$var_challan_id = 'C_' . $this->CommonModel->getUniqueAutoId('mst_challan', 'var_challan_id');



        $ChallanData = array(

            'var_challan_id' => $var_challan_id, 

            'fk_project' => $_POST['fk_project'], 

            'fk_vendor' => $_POST['fk_vendor'], 

            'fk_superviser' => $_POST['fk_superviser'], 

            'fk_item' => $_POST['fk_item'], 

            'var_quantity' => $_POST['var_quantity'],

            'var_status' => "Unpaid", 

            'var_image' => $var_image,

            'var_amount' => $_POST['var_amount'],

            'var_ipaddress' => $_SERVER['REMOTE_ADDR']

        );



        $expenseData = array(

            'fk_profile' => '',

            'fk_project' => $_POST['fk_project'], 

            'fk_vendor' => $_POST['fk_vendor'], 

            'fk_superviser' => $_POST['fk_superviser'], 

            'var_expense_type' => 'Vendor',

            'var_amount' => $_POST['var_amount'], 

            'var_payment_mode' => 'Cash',

        );



		



        if($id !="" && $id > 0){

            $challanDataBeforeUpdate = $this->CommonModel->getRowArray('mst_challan', '*',array('int_glcode' => $id));

            $this->CommonModel->updateRows('mst_challan',$ChallanData, array('int_glcode'=>$id));

            $this->CommonModel->updateRows('mst_expense',$expenseData, array('int_glcode'=>$_POST['fk_expense']));

            $insert_user_id = $id;

        }else{  

            $challanDataBeforeUpdate = array('var_quantity' => 0);       

            $this->con->table('mst_challan')->insert($ChallanData);

            $insert_user_id = $this->con->insertID();

            $this->con->table('mst_expense')->insert($expenseData);

            $fk_expense_insert = $this->con->insertID();

        }

        $expenseBilldata = array(

            'fk_project' => $_POST['fk_project'],

            'fk_challan' => $insert_user_id,

            'var_amount' => $_POST['var_amount'],

        );

		

		

        if($_POST['fk_expense'] > 0){

            $this->CommonModel->updateRows('mst_expence_bill',$expenseBilldata, array('fk_expense'=>$_POST['fk_expense']));

        }else{

            $expenseBilldata['fk_expense'] = $fk_expense_insert;

            $this->con->table('mst_expence_bill')->insert($expenseBilldata);

        }

		

        $wherePIR = array(

            'fk_project' => $_POST['fk_project'],

            'fk_material' => $_POST['fk_item']

        );

        $projectItemRow = $this->CommonModel->getRowArray('mst_project_item','*',$wherePIR);

		

        $updateStock = array(

            'var_due_stock' => $projectItemRow['var_due_stock'] - $_POST['var_quantity'] + $challanDataBeforeUpdate['var_quantity'], 

            'var_used_stock' => $projectItemRow['var_used_stock'] + $_POST['var_quantity'] - $challanDataBeforeUpdate['var_quantity']

        );

		

        $this->CommonModel->updateRows('mst_project_item',$updateStock, $wherePIR);

			

        return $insert_user_id;

    }

	

	public function getProjectItem($fk_project){

        $builder = $this->con->table('mst_project_item mpt');

        $builder->select('mm.int_glcode, mm.var_item, mm.var_unit, mpt.var_due_stock');

        $builder->join('mst_material mm', 'mm.int_glcode = mpt.fk_material', 'left');

        $builder->where('mpt.fk_project', $fk_project);

        $builder->where('mpt.chr_delete', 'N');

        $builder->where('mpt.chr_publish', 'Y');

        $query = $builder->get();

        $rows = $query->getResultArray();

        return $rows;

    }



	public function TaskList($fk_project){

        $builder = $this->con->table('mst_task');

        $builder->select('*');

        $builder->where('fk_project', $fk_project);

        $builder->where('chr_delete', 'N');

        $builder->where('chr_publish', 'Y');

        $query = $builder->get();

        $rows = $query->getResultArray();

        return $rows;

    }



	public function AddTask($id = '')

	{

		$TaskanData = array(

            'fk_project' => $_POST['fk_project'], 

            'var_title' => $_POST['var_title'], 

            'var_details' => $_POST['var_details'], 

            'var_status' => "P", 

            'chr_publish' => "Y",

            'chr_delete' => "N",

			'dt_createddate'=>date('Y-m-d H:i:s'),

            'var_ipaddress' => $_SERVER['REMOTE_ADDR']

        );

		if($id > 0 && $id != '')

		{

            $this->CommonModel->updateRows('mst_task',$TaskanData, array('int_glcode'=>$id));

			$insert_task_id = $id;

		}else{

			$this->con->table('mst_task')->insert($TaskanData);

            $insert_task_id = $this->con->insertID();

		}	

		return $insert_task_id;



	}



    public function addRecord(){

        

        if ($_FILES['var_gst_certi']['name'] != '') {

            if (!is_dir('uploads/vendor')) {

				mkdir('uploads/vendor', 0777, TRUE);

			}

			$gst_certi = uniqid() . '-' . $_FILES['var_gst_certi']['name'];

			$gst_certi = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $gst_certi);

			$destination = 'uploads/vendor/';

			move_uploaded_file($_FILES['var_gst_certi']['tmp_name'], $destination . $gst_certi);

        }else{

            $gst_certi = '';

        }



        $userData = array(

            'var_name' => $_POST['var_name'],

            'var_phone' => $_POST['var_phone'],

            'var_business' => $_POST['var_business'],

            'var_pancard' => $_POST['var_pancard'],

            'var_email' => $_POST['var_email'],

            'var_gst' => $_POST['var_gst'],

            'var_gst_certi' => $gst_certi,

            'var_address' => $_POST['var_address'],

            'fk_state' => $_POST['fk_state'],

            'fk_city' => $_POST['fk_city'],

            'fk_country' => $_POST['fk_country'],

            'var_pincode' => $_POST['var_pincode'],

            'chr_publish' => 'Y',

            'chr_delete' => 'N',

            'dt_createddate' => date('Y-m-d H:i:s'),

            'var_ipaddress' => $_SERVER['REMOTE_ADDR']

        );

        $this->con->table('mst_vendor')->insert($userData);

        $insert_user_id = $this->con->insertID();

        return $insert_user_id;

    }



    public function attendance()

    {

        $attandance = array(

            "fk_project"=>$_POST['fk_project'],

            "fk_superviser"=>$_POST['fk_superviser'],

            "var_entry"=>time(),

            "dt_createddate"=>date('Y-m-d H:i:s'),

        );



        $this->con->table('mst_superviser_attendance')->insert($attandance);

        $insert_user_id = $this->con->insertID();

        return $insert_user_id; 

    }



    // public function AttendanceProjectList($fk_supervisor,$status)

    // {

    //     $builder = $this->con->table('mst_project mp');

	// 	$builder->select('mp.*');

	// 	$builder->where('mp.fk_supervisor', $fk_supervisor);

	// 	$builder->where('mp.chr_delete', 'N');

	// 	$builder->orderBy('mp.int_glcode', 'DESC');

	// 	$query = $builder->get();

    //     $row = $query->getResultArray(); 

    //    foreach ($row as $key => $value) {

        



    //    }

    //    exit;

    // }

    // public function getIdByprojectList($fk_project)

    // {

    //     $builder = $this->con->table('mst_project mp');

	// 	$builder->select('mp.*');

	// 	$builder->join('mst_superviser_attendance msa','msa.fk_project = mp.int_glcode','left');

	// 	$builder->where('msa.fk_project', $fk_project);

	// 	$query = $builder->get();

        

    //     $row = $query->getRowArray(); 

    // }



    
    public function getResultArray($table, $column="", $where = array(), $order_by = 'int_glcode', $short = 'desc'){
        $builder = $this->con->table($table);
        if($column!=""){
            $builder->select($column);
        }else{
            $builder->select('int_glcode');
        }
        if(!empty($where)){
            $builder->where($where);
        }
        $builder->orderBy($order_by,$short);
        $query = $builder->get();
        $rows = $query->getResultArray();
        // echo $this->con->getLastQuery();die();
        return $rows;
    }
	
    function addUpdateRecord(){
        if($_POST){
            $expense_mode = $_POST['expense_mode'];
            if($expense_mode == 'Bill'){
                $bill_ids = $_POST['bill_ids'];
                $bill_amount =  $_POST['bill_amount'];
                if(count($bill_ids) > 0){
                    $i = 0;
                    foreach ($bill_ids as $val) {
                        $builder = $this->con->table('mst_expence_bill');
                        $builder->select('fk_expense');
                        $builder->where('fk_bill', $val);
                        $query = $builder->get();
                        $rows = $query->getRowArray();
    
                        $builders = $this->con->table('mst_bill_due');
                        $builders->select('var_amount,var_due_amount,var_paid_amount,int_glcode');
                        $builders->where('fk_bill', $val);
                        $query = $builders->get();
                        $rows_bill = $query->getRowArray();
                        if(count($rows) > 0 && count($rows_bill) > 0){
                            $InvoiceDatas = array( 
                                'fk_expense' => $rows['fk_expense'], 
                                'fk_project' => $_POST['project_id'], 
                                'fk_bill' => $val, 
                                'fk_challan' => '0',
                                'var_amount' => $bill_amount[$i],
                                'var_bill_expense_date' => $_POST['expense_date'],
                            );
                            $this->con->table('mst_expence_bill')->insert($InvoiceDatas);
                            $insert_user_id = $this->con->insertID();
                            if($insert_user_id){
                                $var_amount = $rows_bill['var_amount'];
                                $var_due_amount = $rows_bill['var_due_amount'];
                                $var_paid_amount = $rows_bill['var_paid_amount'];
                                $int_glcode = $rows_bill['int_glcode'];
                                $data = array(
                                    'var_amount' => $var_amount,
                                    'var_due_amount' => $var_due_amount - $bill_amount[$i],
                                    'var_paid_amount' => $var_paid_amount + $bill_amount[$i],
                                );
                                $this->CommonModel->updateRows('mst_bill_due',$data, array('int_glcode'=>$int_glcode));
                            }  
                        }       
                        $i = $i + 1;         
                    }
                }    
                return 1;        
            }
            if($expense_mode == 'Advance'){
                $var_expense_type = $_POST['expense_type'];
                if($var_expense_type == 'labour'){
                    $InvoiceData = array( 
                        'fk_profile' => $_POST['profile'], 
                        'fk_project' => $_POST['project'],
                        'var_expense_date' => $_POST['expense_date'], 
                        'var_expense_type' => $_POST['expense_type'],
                        'fk_vendor' => '0', 
                        'var_payment_mode' => $_POST['payment_mode'],
                        'var_cheque_number' => $_POST['cheque_number'],
                        'var_amount' => $_POST['amount'],
                        'var_expense_mode' => $_POST['expense_mode'],
                        'var_against_to_bill' => 'N',
                        'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                    );
                    $InvoiceData['dt_createddate'] = date('Y-m-d H:i:s');
                    $InvoiceData['chr_delete'] = 'N';
                    $this->con->table('mst_expense')->insert($InvoiceData);
                    $insert_user_id = $this->con->insertID();
    
                    $mst_expence_labor_data = array( 
                        'var_number_of_assign' => $_POST['number_of_assign'], 
                        'fk_labor_assign' => $_POST['labor_assign'], 
                        'fk_expence' => $insert_user_id, 
                        'var_amount' => $_POST['amount'],
                    );
                    $this->con->table('mst_expence_labor')->insert($mst_expence_labor_data);
    
                    $labor_assignment = $this->con->table('mst_labor_assignment');
                    $labor_assignment->select('var_number_of_assign,var_number_of_assign_expense,var_total_charge,var_due_charge,var_paid_charge');
                    $labor_assignment->where('int_glcode', $_POST['labor']);
                    $query = $labor_assignment->get();
                    $rows_labor_assignment = $query->getRowArray();
                    if(count($rows_labor_assignment) > 0){
                        $datas = array(
                            'var_number_of_assign_expense' => $rows_labor_assignment['var_number_of_assign_expense'] + $_POST['no_assign_labor'],
                            'var_due_charge' => $rows_labor_assignment['var_due_charge'] - $_POST['amount'],
                            'var_paid_charge' =>$rows_labor_assignment['var_paid_charge'] + $_POST['amount'],
                        );
                        $this->CommonModel->updateRows('mst_labor_assignment',$datas, array('int_glcode'=>$_POST['labor']));
                    }
                    return $insert_user_id;
                }else{
                    if($_POST){
                        $InvoiceData = array( 
                            'fk_profile' => $_POST['profile'], 
                            'fk_project' => $_POST['project'], 
                            'var_expense_date' => $_POST['expense_date'], 
                            'var_expense_type' => $_POST['expense_type'],
                            'fk_vendor' => $_POST['vendor'], 
                            'var_payment_mode' => $_POST['payment_mode'],
                            'var_cheque_number' => $_POST['cheque_number'],
                            'var_amount' => $_POST['amount'],
                            'var_expense_mode' => $_POST['expense_mode'],
                            'var_against_to_bill' => 'N',
                            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                        );
                        $InvoiceData['dt_createddate'] = date('Y-m-d H:i:s');
                        $InvoiceData['chr_delete'] = 'N';
                        $this->con->table('mst_expense')->insert($InvoiceData);
                        $insert_user_id = $this->con->insertID();
                        return $insert_user_id;
                    }else{
                        return "";
                    }
                }
            }

            if($expense_mode == ''){
                return '';
            }
        }
    }


    public function getBillList($fk_project, $fk_vendor){
        $builder = $this->con->table('mst_bill mc');
        $builder->select('mc.*');
        $builder->where('mc.fk_project', $fk_project);
        $builder->where('mc.fk_vendor', $fk_vendor);
        $builder->where('mc.chr_delete', 'N');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
}

