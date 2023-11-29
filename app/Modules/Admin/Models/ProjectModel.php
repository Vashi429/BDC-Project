<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
use App\Modules\Admin\Models\CommonModel;
class ProjectModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
        $this->CommonModel = new CommonModel();
        
    }
    
    public function getData($rowno, $rowperpage, $filter="", $_field='mp.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_project mp');
        $builder->select('mp.var_project,mp.var_Project_id, mp.int_glcode, mp.start_date,mp.end_date, mp.chr_publish, mc.var_name as var_customer, ms.var_name as var_supervisor,ms.int_glcode as fk_supervisor');
        $builder->join('mst_customer mc','mc.int_glcode = mp.fk_customer','left');
        $builder->join('mst_supervisor ms','ms.int_glcode = mp.fk_supervisor','left');
        $builder->where('mp.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('mp.var_project',$filter);
			$builder->orLike('mc.var_name',$filter);
			$builder->orLike('ms.var_name',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        if($rowno != '' && $rowperpage != ''){
            $builder->limit($rowperpage , $rowno);
        }
        
        $query =  $builder->get();
        $row = $query->getResultArray();
        
        $data = array();
        foreach($row as $val){
            $val['start_date'] = dateformate($val['start_date']);
            if($val['var_supervisor'] == ''){
                $val['var_supervisor'] = '';
            }
            
            $val['total_estimation_amount'] = $this->CommonModel->getValById('mst_estimate', 'sum(var_total_amount)', array('fk_project' => $val['int_glcode']));;
            $val['total_milestone_amount'] = $this->CommonModel->getValById('mst_milestone', 'sum(var_payment)', array('fk_project' => $val['int_glcode']));;
             $val['duration'] =  dateformate($val['start_date'])." To ".dateformate($val['end_date']);
            $data[] = $val;
        }
        return $data;
    }
    public function total_records_count(){
        $builder = $this->con->table('mst_project');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_project mp');
		$builder->select('count(mp.int_glcode) as total');
		$builder->join('mst_customer mc','mc.int_glcode = mp.fk_customer','left');
        $builder->join('mst_supervisor ms','ms.int_glcode = mp.fk_supervisor','left');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('mp.var_project',$filter);
			$builder->orLike('mc.var_name',$filter);
			$builder->orLike('ms.var_name',$filter);
			$builder->groupEnd();
		}
		$builder->where('mp.chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }
    public function addProjectInfo(){
        $Data = array(
            'fk_customer' => $this->request->getVar('fk_customer'),
            'fk_profile' => $this->request->getVar('fk_profile'),
            'var_Project_id' => $this->request->getVar('var_Project_id'),
            'var_project' => $this->request->getVar('var_project'),
            'var_address' => $this->request->getVar('var_address'),
            'var_pincode' => $this->request->getVar('var_pincode'),
            'fk_city' => $this->request->getVar('fk_city'),
            'fk_state' => $this->request->getVar('fk_state'),
            'fk_country' => $this->request->getVar('fk_country'),
            'var_project_type' => $this->request->getVar('var_project_type'),
            'start_date' => $this->request->getVar('start_date'),
            'end_date' => $this->request->getVar('end_date'),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_project')->insert($Data);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
    }
    public function addprojectPOdocs(){
        $uploaded_plans = explode(',',$this->request->getVar('uploaded_plans'));
        if(!empty($uploaded_plans[0])){
            foreach($uploaded_plans as $key => $val){
                $data = array(
                    'fk_project' => $this->request->getVar('fk_project'),
                    'var_document' => $val,
                    'var_scan' => 'N',
                    'dt_createddate' => date('Y-m-d H:i:s'),
                    'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                );
                $this->con->table('mst_other_documents')->insert($data);
            }
            return true;
        }else{
            return false;
        }
    }
    public function addprojectEstimates(){
        $id =$this->request->getVar('fk_estimation');
        $EstimateData = array(
            'fk_project' => $this->request->getVar('fk_project'), 
            'var_estimate_title' => $this->request->getVar('var_estimate_title'),
            'var_adjustment' => $this->request->getVar('var_adjustment'),
            'var_date' => $this->request->getVar('var_date'), 
            'var_sub_total' => $this->request->getVar('var_subtotal'),
            'var_servicetax' => $this->request->getVar('var_service_tax'),
            'var_total_amount' => $this->request->getVar('var_final_amount'),
            'var_note' => $this->request->getVar('var_customer_note'), 
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        if($id== 0){
            $this->con->table('mst_estimate')->insert($EstimateData);
            $insert_user_id = $this->con->insertID();
        }else{
            $this->CommonModel->updateRows('mst_estimate',$EstimateData, array('int_glcode'=>$id));
            $insert_user_id = $id;
        }
        if($insert_user_id > 0){
            $var_item = $this->request->getVar('var_item');
            
            if(!empty($var_item)){
                foreach($var_item as $key => $value){
                    $estItemData = array(
                        'fk_project' => $this->request->getVar('fk_project'),
                        'fk_estimate' => $insert_user_id,
                        'var_item' => $value,
                        'var_quantity' => $this->request->getVar('var_quantity')[$key],
                        'fk_gst' => $this->request->getVar('fk_tax')[$key],
                        'var_rate' => $this->request->getVar('var_rate')[$key],
                        'var_tax' => $this->request->getVar('var_tax')[$key],
                        'var_total' => $this->request->getVar('var_total')[$key],
                    );
                  
                    if(isset($this->request->getVar('var_item_id')[$key]) && $this->request->getVar('var_item_id')[$key] > 0){
                        $this->CommonModel->updateRows('mst_estimate_items',$estItemData, array('int_glcode'=>$this->request->getVar('var_item_id')[$key]));
                    }else{
                        $this->con->table('mst_estimate_items')->insert($estItemData);
                    }
                }
            }
        }
        return $insert_user_id;
    }
    public function addprojectMilestones(){
        $advance_total_amount = $this->request->getVar('advance_total_amount');
        if($advance_total_amount!="" && $advance_total_amount > 0){
            $milestoneData = array(
                'fk_project' => $this->request->getVar('fk_project'),
                'var_payment' => $advance_total_amount, 
                'var_percentage' => $this->request->getVar('advance_total_per'), 
                'var_date' => date('Y-m-d H:i:s'),
                'var_description' => 'Advance Payment',
                'dt_createddate' => date('Y-m-d H:i:s'),
                'var_ipaddress' => $_SERVER['REMOTE_ADDR']
            );
            $this->con->table('mst_milestone')->insert($milestoneData);
        }
        $var_payment = $this->request->getVar('var_payment');
        if(!empty($var_payment)){
            foreach($var_payment as $key => $value){
                $milestoneData = array(
                    'fk_project' => $this->request->getVar('fk_project'), 
                    'var_payment' => $value, 
                    'var_percentage' => $this->request->getVar('var_percentage')[$key], 
                    'var_date' => $this->request->getVar('var_date')[$key],
                    'var_description' => $this->request->getVar('var_description')[$key],
                    'dt_createddate' => date('Y-m-d H:i:s'),
                    'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                );
                $this->con->table('mst_milestone')->insert($milestoneData);
            }
        }
        return true;
    }
    public function addprojectTasks(){
        $var_title = $this->request->getVar('var_title');
        if(!empty($var_title)){
            foreach($var_title as $key => $value){
                $taskData = array(
                    'fk_project' => $this->request->getVar('fk_project'), 
                    'var_title' => $value, 
                    'var_details' => $this->request->getVar('var_details')[$key],
                    'var_status' => 'P',
                    'chr_publish' => 'Y',
                    'chr_delete' => 'N',
                    'dt_createddate' => date('Y-m-d H:i:s'),
                    'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                );
                $this->con->table('mst_task')->insert($taskData);
            }
        }
        return true;
    }

    public function getEstimationData($fk_estimation){
        $row = $this->CommonModel->getRowArray('mst_estimate', '*', array('int_glcode' => $fk_estimation));
        if(!empty($row)){
            $row['estimationItems'] = $this->getEsitmationItem($fk_estimation);
            $row['var_sub_total'] = $row['tax'] = 0;
            if(!empty($row['estimationItems'])){
                foreach($row['estimationItems'] as $val){
                    $row['var_sub_total'] += $val['var_quantity'] * $val['var_rate'];
                    $row['tax'] += $val['var_tax'];
                }
            }
        }
        return $row;
    }
    public function getEsitmationItem($Id){
        $builder = $this->con->table('mst_estimate_items mei');
        $builder->select('mei.*,mg.var_percent');
        $builder->join('mst_gst mg','mg.int_glcode = mei.fk_gst', 'left');
        $builder->where('fk_estimate',$Id);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function projectDetails($fk_project){
        $builder = $this->con->table('mst_project');
        $builder->select('int_glcode,var_project,var_Project_id');
        $builder->where('chr_delete',"N");
        $builder->where('int_glcode',$fk_project);
        $query =  $builder->get();
        $row = $query->getRowArray();
        $row['totalProAmt'] = $this->CommonModel->getValById('mst_estimate', 'sum(var_total_amount)', array('fk_project' => $fk_project, 'chr_publish' => 'Y', 'chr_delete' => 'N'));
        
        $row['totalProExp'] = $this->CommonModel->getValById('mst_expense', 'sum(var_amount)', array('fk_project' => $fk_project, 'chr_delete' => 'N'));
        $row['invoice'] = $this->getInvoiceList($row['int_glcode']);
        $row['Payment'] = $this->getPaymentList($row['int_glcode']);
        $row['Estimation'] = $this->CommonModel->getResultArray('mst_estimate', '*', array('fk_project' => $row['int_glcode'],'chr_delete'=>'N'));
        $row['Milestone'] = $this->CommonModel->getResultArray('mst_milestone', '*', array('fk_project' => $row['int_glcode']));
        
        return $row;
       
    }
    public function getInvoiceList($fk_project){
        $builder = $this->con->table('mst_invoice i');
        $builder->select('i.*, cp.var_name as company_name, c.var_displayname as customer_name, p.var_project, ia.var_due_amount, ia.var_invoice_amount');
        $builder->join("mst_company_profile cp","cp.int_glcode = i.fk_profile ","left");
        $builder->join("mst_invoice_amount ia","ia.fk_invoice = i.int_glcode ","left");
        $builder->join("mst_customer c","c.int_glcode = i.fk_customer","left");
        $builder->join("mst_project p","p.int_glcode = i.fk_project  ","left");
        $builder->where('i.chr_delete',"N");
        $builder->where('i.fk_project',$fk_project);
        $builder->orderBy('i.int_glcode','DESC');
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $val['var_due_amount'] = number_format($val['var_due_amount'],2,'.','');
                $val['var_invoice_amount'] = number_format($val['var_invoice_amount'],2,'.','');

                $startTimeStamp = strtotime($val['var_invoice_date']);
                $endTimeStamp = strtotime(date('Y-m-d'));
                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                $numberDays = intval($numberDays);


                if($val['var_due_amount'] == $val['var_invoice_amount']){
                    if($numberDays == 0){
                        $val['var_payment_status'] = "Unpaid (today)";
                    }else{
                        $val['var_payment_status'] = "Unpaid (before ".$numberDays." days.)";
                    }
                }else if($val['var_due_amount'] == 0){
                    $val['var_payment_status'] = "Paid";
                }else{
                    $val['var_payment_status'] = 'Partially Paid';
                }
                $data[] = $val;
            }
        }
        return $data;
    }

    public function getPaymentList($fk_project){
        $builder = $this->con->table('mst_received_payment mrp');
        $builder->select('mrp.*, mp.var_project, mcp.var_name, mc.var_displayname as customer_name');
        $builder->join('mst_project mp','mp.int_glcode = mrp.fk_project','left');
        $builder->join('mst_company_profile mcp','mcp.int_glcode = mrp.fk_profile','left');
        $builder->join('mst_customer mc','mc.int_glcode = mrp.fk_customer','left');
        $builder->where('mrp.chr_delete',"N");
        $builder->where('mrp.fk_project',$fk_project);
        $builder->orderBy('mrp.int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $totalCredit = $this->totalUsedCreditOfPayment($val['int_glcode']);
                $val['unusedAmount'] = $val['var_received_amount'] - $totalCredit;
                $data[] = $val;
            }
        }
        return $data;
    }
     
    /* Save applied invoice with the customer advance payment start */

    public function totalUsedCreditOfPayment($id){
        $builder = $this->con->table('mst_received_invoice');
        $builder->select('var_amount');
        $builder->where('fk_received_id',$id);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        $total_amt = 0;
        if(!empty($row)){
            foreach($row as $val){
                $total_amt += $val['var_amount'];
            }
        }
        return $total_amt;
    }
    
}

