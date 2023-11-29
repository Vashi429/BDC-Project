<?php
namespace App\Modules\Admin\Models;
use App\Modules\Admin\Models\CommonModel;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;

class BillModel extends Model{
    public $con = '';
    protected $request;
	public $CommonModel;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
		$this->CommonModel = new CommonModel();
    }
    
    /* list data for the bill Start */
    public function getData($rowno, $rowperpage, $filter="", $_field='b.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_bill b');
        $builder->select('b.*, cp.var_name as supervisor_name, v.var_name as vendor_name, p.var_project, bd.var_amount, bd.var_due_amount');
        $builder->join("mst_bill_due bd","b.int_glcode = bd.fk_bill ","left");
        $builder->join("mst_company_profile cp","cp.int_glcode = b.fk_profile ","left");
        $builder->join("mst_vendor v","v.int_glcode = b.fk_vendor","left");
        $builder->join("mst_project p","p.int_glcode = b.fk_project  ","left");
        $builder->where('b.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('b.var_challan_id',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $challan = $this->getchallanId($val['int_glcode']);
                if(!empty($challan)){
                    $val['var_challan'] = implode(', ',$challan);
                }else{
                    $val['var_challan'] = '-';
                }
                $val['dt_createddate'] = dateformate($val['dt_createddate']);
                $data[] = $val;
            }
        }
        return $data;
    }
    /* list data for the bill End */

    public function getchallanId($fk_bill){
        $builder = $this->con->table('mst_challan mc');
        $builder->select('mc.var_challan_id');
        $builder->where('mc.fk_bill', $fk_bill);
        $builder->where('mc.chr_delete', 'N');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return array_column($rows, 'var_challan_id');
    }
    /* total list data count for the bill Start */
    public function total_records_count(){
        $builder = $this->con->table('mst_bill');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    /* total list data count for the bill End */

    /* total list data count with filter for the bill Start */
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_bill c');
        $builder->select('count(c.int_glcode) as total');
        $builder->join("mst_company_profile cp","cp.int_glcode = c.fk_profile ","left");
        $builder->join("mst_vendor v","v.int_glcode = c.fk_vendor","left");
        $builder->join("mst_project p","p.int_glcode = c.fk_project  ","left");
        $builder->where('c.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('c.var_challan_id',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRowArray();
        return $result['total'];
    }
    /* total list data count with filter for the bill Start */
    
    /* Export CSV of bill Start */
    public function export_csv(){
        $builder = $this->con->table('mst_bill b');
        $builder->select('b.*, cp.var_name as profile_name, v.var_name as vendor_name, p.var_project, bd.var_amount, bd.var_due_amount');
        $builder->join("mst_company_profile cp","cp.int_glcode = b.fk_profile ","left");
        $builder->join("mst_bill_due bd","b.int_glcode = bd.fk_bill ","left");
        $builder->join("mst_vendor v","v.int_glcode = b.fk_vendor","left");
        $builder->join("mst_project p","p.int_glcode = b.fk_project  ","left");
        $builder->where('b.chr_delete',"N");
        $builder->orderBy('b.int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $challan = $this->getchallanId($val['int_glcode']);
                if(!empty($challan)){
                    $val['var_challan'] = implode(', ',$challan);
                }else{
                    $val['var_challan'] = '-';
                }
                $data[] = $val;
            }
        }
        return $data;
    }
    /* Export CSV of bill End */
  
    /* Add and update bill as per id (if id="" then insert else update)  Start */

    function UpdateRecord($id=""){
        $fk_vendor = $this->request->getVar('fk_vendor');
        $fk_profile = $this->request->getVar('fk_profile');
        $fk_project = $this->request->getVar('fk_project');
        $var_bill_no = $this->request->getVar('var_bill_no');
        $var_bill_date = $this->request->getVar('var_bill_date');
        $var_bill_amount = $this->request->getVar('var_bill_amount');
        $hvar_image = $this->request->getVar('hvar_image');
        $ids = $this->request->getVar('ids');
        if($ids){
            $ids = $ids;
        }else{
            $ids = array();
        }
        $totalChallanAmount = $this->request->getVar('totalChallanAmount');
        $expesnse_ids = $this->request->getVar('expesnse_ids');
        if($expesnse_ids){
            $expesnse_ids = $expesnse_ids;
        }else{
            $expesnse_ids = array();
        }
        $new_expesnse_ids = $this->request->getVar('new_expesnse_ids');
        if($new_expesnse_ids){
            $new_expesnse_ids = $new_expesnse_ids;
        }else{
            $new_expesnse_ids = array();
        }
        $totalExpesneAmount = $this->request->getVar('totalExpesneAmount');
        if ($_FILES['var_image']['name'] != '') {
            if (!is_dir('uploads/bill')) {
				mkdir('uploads/bill', 0777, TRUE);
			}
			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];
			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);
			$destination = 'uploads/bill/';
			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);
        }else{
            $var_image = $this->request->getVar('hvar_image');
        }
    
        
        $allChallan = $this->CommonModel->getResultArray('mst_challan', '*', array('fk_bill' => $id));
        $get_expense_id = $this->CommonModel->getResultArray('mst_bill_due', 'fk_expense', array('fk_bill' => $id));
        $expesnse_id_mst = $get_expense_id[0]['fk_expense'];
        $all_expence = $this->CommonModel->getResultArray('mst_expence_bill', 'int_glcode,fk_expense', array('fk_bill' => $id));
        $old_expense_ids = array_column($all_expence, 'int_glcode');
        if(count($expesnse_ids) > 0){
            $final_expenses=array_diff($old_expense_ids,$expesnse_ids);
        }else{
            $final_expenses=$old_expense_ids;
        }

        // if(count($expesnse_ids) == 0){

        // }
        
        if(count($final_expenses) > 0){
            foreach($final_expenses as $val_exp){
                $get_expense_details = $this->CommonModel->getResultArray('mst_expence_bill', '*', array('int_glcode' => $val_exp));
                if(count($get_expense_details) > 0){
                    $expenceBill = array(
                        'fk_profile' => $fk_profile,
                        'fk_project' => $fk_project,
                        'fk_vendor' => $fk_vendor,
                        'var_expense_type' => 'Vendor',
                        'var_amount' => $get_expense_details[0]['var_amount'],
                        'var_expense_mode' => 'Advance',
                        'var_payment_mode' => 'Cash',
                        'var_expense_date' => date('Y-m-d',strtotime($get_expense_details[0]['var_bill_expense_date'])),
                    );   
                    $this->con->table('mst_expense')->insert($expenceBill);  
                    $insert_expense_id = $this->con->insertID();
                    $this->CommonModel->deleteRow('mst_expence_bill', array('int_glcode' => $val_exp));
                }
            }
        }

        if(count($new_expesnse_ids) > 0){
            foreach($new_expesnse_ids as $val_new_exp){
                $get_new_expense_details = $this->CommonModel->getResultArray('mst_expense', '*', array('int_glcode' => $val_new_exp));
                $expenceBillNew = array(
                    'fk_project' => $fk_project,
                    'fk_expense' => $expesnse_id_mst,
                    'fk_bill' => $id,
                    'var_amount' => $get_new_expense_details[0]['var_amount'],
                );   
                $expenceBillNew['var_bill_expense_date'] = $get_new_expense_details[0]['var_expense_date'];
                $this->con->table('mst_expence_bill')->insert($expenceBillNew);
                $this->CommonModel->deleteRow('mst_expense', array('int_glcode' => $val_new_exp));
            }
        }
        
        if(count($allChallan) > 0){
            foreach($allChallan as $val){
                $challanData = array(
                    'fk_expense' => '0', 
                    'fk_bill' => '0',
                );
                $this->CommonModel->updateRows('mst_challan',$challanData, array('int_glcode'=>$val['int_glcode']));

                $expenceBill = array(
                    'fk_profile' => $fk_profile,
                    'fk_project' => $fk_project,
                    'fk_vendor' => $fk_vendor,
                    'var_expense_type' => 'Vendor',
                    'var_amount' => $val['var_amount'],
                    'var_expense_mode' => 'Challan',
                    'var_payment_mode' => 'Cash',
                    'var_expense_date' => date('Y-m-d',strtotime($val['dt_createddate'])),
                );   
                $this->con->table('mst_expense')->insert($expenceBill);  
                $insert_expense_id = $this->con->insertID();

                $challanData = array(
                    'fk_expense' => $insert_expense_id, 
                    'fk_bill' => '0',
                );
                $this->CommonModel->updateRows('mst_challan',$challanData, array('int_glcode'=>$val['int_glcode']));
            }

        }

        foreach ($ids as $val_challan) {
            $get_challan = $this->CommonModel->getResultArray('mst_challan', '*', array('int_glcode' => $val_challan));
            $challanData = array(
                'fk_bill' => $id,
                'fk_expense' => $expesnse_id_mst
            );
            $this->CommonModel->updateRows('mst_challan',$challanData, array('int_glcode'=>$val_challan));                
            $this->CommonModel->deleteRow('mst_expense', array('int_glcode' => $get_challan[0]['fk_expense']));
        }

        $all_current_expence = $this->CommonModel->getResultArray('mst_expence_bill', 'int_glcode,var_amount', array('fk_bill' => $id));
        $total_bill_amount = $this->CommonModel->getResultArray('mst_bill', 'var_bill_amount', array('int_glcode' => $id));
        $total_var_amount = 0;
        if(count($total_bill_amount) > 0){
            $total_var_amount = $total_bill_amount[0]['var_bill_amount'];
        }
        if(count($all_current_expence) > 0){
            $current_expense_amount = array_column($all_current_expence, 'var_amount');
            $total = array_sum($current_expense_amount);
            $final_due_amount = $total_var_amount - $total; 
            $challanData = array(
                'var_paid_amount' => $total, 
                'var_due_amount' => $final_due_amount,
            );
            $this->CommonModel->updateRows('mst_bill_due',$challanData, array('fk_bill'=>$id));
        }else{
            $challanData = array(
                'var_paid_amount' => 0, 
                'var_due_amount' => $total_var_amount,
            );
            $this->CommonModel->updateRows('mst_bill_due',$challanData, array('fk_bill'=>$id));
        }
        // if(!empty($this->request->getVar('ids'))){
        //     foreach($this->request->getVar('ids') as $key => $val){
        //         print_r($val); die;
        //     }
        // }
        
        return true;
        
    }
    function addUpdateRecord($id=""){
        if ($_FILES['var_image']['name'] != '') {
            if (!is_dir('uploads/bill')) {
				mkdir('uploads/bill', 0777, TRUE);
			}
			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];
			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);
			$destination = 'uploads/bill/';
			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);
        }else{
            $var_image = $this->request->getVar('hvar_image');
        }

        $billData = array(
            'var_bill_no' => $this->request->getVar('var_bill_no'), 
            'fk_profile' => $this->request->getVar('fk_profile'), 
            'fk_vendor' => $this->request->getVar('fk_vendor'), 
            'fk_project' => $this->request->getVar('fk_project'), 
            'var_bill_date' => $this->request->getVar('var_bill_date'), 
            'var_bill_amount' => $this->request->getVar('var_bill_amount'),
            'var_bill_image' => $var_image,
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );

        $expenseData = array(
            'fk_profile' => $this->request->getVar('fk_profile'), 
            'fk_vendor' => $this->request->getVar('fk_vendor'), 
            'fk_project' => $this->request->getVar('fk_project'), 
            'var_expense_type' => 'Vendor', 
            'var_amount' => $this->request->getVar('var_bill_amount'),
            'var_payment_mode' => 'Cash',
            'var_expense_mode' => 'Bill',
            'var_expense_date' => date('Y-m-d'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        if($id !="" && $id > 0){
            $this->CommonModel->updateRows('mst_bill',$billData, array('int_glcode'=>$id));
            $insert_user_id = $id;
            $this->CommonModel->updateRows('mst_challan', array('fk_bill' => 0), array('fk_bill'=>$id));

            $PreviousExpenceBill = $this->CommonModel->getResultArray('mst_expence_bill', '*', array('fk_bill' => $id));
            $PreviousChallan = $this->CommonModel->getResultArray('mst_challan', '*', array('fk_bill' => $id));
            
            $billDueData = $this->CommonModel->getRowArray('mst_bill_due','*', array('fk_bill' => $id));

            $fk_expense = $billDueData['fk_expense'];
            $this->CommonModel->updateRows('mst_expense', $expenseData, array('int_glcode'=>$fk_expense));
            
        }else{
            $this->con->table('mst_bill')->insert($billData);
            $insert_user_id = $this->con->insertID();
            $PreviousExpenceBill = $billDueData = $PreviousChallan = array();
            $this->con->table('mst_expense')->insert($expenseData);
            $fk_expense = $this->con->insertID();
        }
       
        if(!empty($this->request->getVar('ids'))){
            foreach($this->request->getVar('ids') as $key => $val){
                $preExpense = $this->CommonModel->getRowArray('mst_challan', '*', array('int_glcode'=>$val));
                $checkExpenseType = $this->CommonModel->getValById('mst_expense', 'var_expense_mode', array('int_glcode' => $preExpense['fk_expense']));
                if($preExpense['fk_bill']==0 && $checkExpenseType == 'Challan'){
                    $this->CommonModel->deleteRow('mst_expense', array('int_glcode' => $preExpense['fk_expense']));
                }
                $this->CommonModel->updateRows('mst_challan', array('fk_bill' => $insert_user_id, 'fk_expense' => $fk_expense), array('int_glcode'=>$val));
            }
        }
        
        $totalExpense = 0;
        
        if(!empty($this->request->getVar('expesnse_ids'))){
            foreach($this->request->getVar('expesnse_ids') as $key => $val){
                $checkExpenseBill = $this->CommonModel->getValById('mst_expence_bill', '*', array('int_glcode' => $val));
                if(empty($checkExpenseBill)){
                    $expenseAmount = $this->CommonModel->getValById('mst_expense', 'var_amount', array('int_glcode'=>$val));
                    $this->CommonModel->deleteRow('mst_expense', array('int_glcode' => $val));
                    $totalExpense += $expenseAmount;
                    $expenceBill = array(
                        'fk_project' => $this->request->getVar('fk_project'),
                        'fk_expense' => $fk_expense,
                        'fk_bill' => $insert_user_id,
                        'var_amount' => $expenseAmount,
                    );   
                    $expenceBill['var_bill_expense_date'] = date('Y-m-d');
                    $this->con->table('mst_expence_bill')->insert($expenceBill);  
                }                  
                
            }
        }
        
        $billDueDataArr = array(
            'fk_bill' => $insert_user_id,
            'fk_expense' => $fk_expense,
            'var_amount' => $this->request->getVar('var_bill_amount'),
            'var_due_amount' => $this->request->getVar('var_bill_amount') -  $totalExpense,
            'var_paid_amount' => $totalExpense,
        );
    
        if($id !="" && $id > 0){
            $this->CommonModel->updateRows('mst_bill_due', $billDueDataArr, array('int_glcode' => $billDueData['int_glcode']));
        }else{
            $this->con->table('mst_bill_due')->insert($billDueDataArr);
        }

        if(!empty($PreviousExpenceBill)){
            foreach($PreviousExpenceBill as $val){
                if(!empty($this->request->getVar('expesnse_ids'))){
                    if(in_array($val['fk_expense'], $this->request->getVar('expesnse_ids'))){
                        
                    }else{
                        $this->CommonModel->deleteRow('mst_expence_bill', array('int_glcode' => $val['int_glcode']));
                        $expenseData = array(
                            'fk_profile' => $this->request->getVar('fk_profile'), 
                            'fk_vendor' => $this->request->getVar('fk_vendor'), 
                            'fk_project' => $this->request->getVar('fk_project'), 
                            'var_expense_type' => 'Vendor', 
                            'var_amount' => $val['var_amount'],
                            'var_payment_mode' => 'Cash',
                            'var_expense_mode' => 'Advance',
                            'var_expense_date' => date('Y-m-d'),
                            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                        );
                        $this->con->table('mst_expense')->insert($expenseData);
                        $newExpense = $this->con->insertID();
                    }
                }
            }
        }
        if(!empty($PreviousChallan)){
            foreach($PreviousChallan as $value){
                if(in_array($value['fk_challan'], $this->request->getVar('ids'))){
                    // 
                }else{
                    if($val['fk_challan'] > 0){
                        $expenseData = array(
                            'fk_profile' => $this->request->getVar('fk_profile'), 
                            'fk_vendor' => $this->request->getVar('fk_vendor'), 
                            'fk_project' => $this->request->getVar('fk_project'), 
                            'var_expense_type' => 'Vendor', 
                            'var_amount' => $value['var_amount'],
                            'var_payment_mode' => 'Cash',
                            'var_expense_mode' => 'Challan',
                            'var_expense_date' => date('Y-m-d'),
                            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                        );
                        $this->con->table('mst_expense')->insert($expenseData);
                        $newExpense = $this->con->insertID();
                        $this->CommonModel->updateRows('mst_challan',array('fk_bill' => 0, 'fk_expense' => $newExpense), array('int_glcode' => $value['fk_challan']));
                    }
                }
            }
        }
        return $insert_user_id;
    }
    /* Add and update bill as per id (if id="" then insert else update)  End */

    /* Get bill data for the edit page Start */
    public function getBillData($int_glcode){
        $builder = $this->con->table('mst_bill b');
        $builder->select('b.*');
        $builder->where('b.int_glcode',$int_glcode);
        $query = $builder->get();
        $rows = $query->getRowArray();
        if(!empty($rows)){
            $rows['challanData'] = $this->getChallanList($rows['fk_project'], $rows['fk_vendor'], $rows['int_glcode']);
        }
        return $rows;
    }
    /* Get bill data for the edit page End */

    public function getChallanList($fk_project, $fk_vendor, $fk_bill=""){
        $builder = $this->con->table('mst_challan mc');
        $builder->select('mc.*, mm.var_item, mm.var_unit');
        $builder->join('mst_material mm', 'mm.int_glcode = mc.fk_item', 'left');
        $builder->where('mc.fk_project', $fk_project);
        $builder->where('mc.fk_vendor', $fk_vendor);
        if($fk_bill==""){
            $builder->where('mc.fk_bill', 0);
        }else{
            $builder->groupStart();
                $builder->where('mc.fk_bill', 0);
                $builder->orWhere('mc.fk_bill', $fk_bill);
            $builder->groupEnd();
        }
        $builder->where('mc.chr_delete', 'N');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }

    public function getAdvanceExpenseList($fk_project, $fk_vendor, $fk_bill=""){
        $builder = $this->con->table('mst_expense me');
        $builder->select('me.*, meb.fk_bill, meb.var_amount as bill_amount, meb.int_glcode as b_int_glcode');
        $builder->join('mst_expence_bill meb', 'meb.fk_expense = me.int_glcode', 'left');
        if($fk_bill!="" && $fk_bill > 0){
            $builder->groupStart();
            $builder->where('me.var_expense_mode', 'Advance');
            $builder->orWhere('meb.fk_bill', $fk_bill);
            $builder->groupEnd();
        }else{
            $builder->where('me.var_expense_mode', 'Advance');
        }
        $builder->where('me.fk_vendor', $fk_vendor);
        $builder->where('me.fk_project', $fk_project);
        $builder->where('me.var_expense_type', 'Vendor');
        // $builder->groupBy('meb.fk_expense');
        $query = $builder->get();
        $result = $query->getResultArray();
        // echo $this->con->getLastQuery();die();
        return $result;
    }
    public function getBillList($fk_project, $fk_vendor, $fk_bill=""){
        $builder = $this->con->table('mst_bill mc');
        $builder->select('mc.*');
        $builder->join('mst_bill_due meb', 'meb.fk_bill = mc.int_glcode', 'left');
        $builder->where('mc.fk_project', $fk_project);
        $builder->where('mc.fk_vendor', $fk_vendor);
        $builder->where('mc.chr_delete', 'N');
        $builder->where('meb.var_due_amount >', '0');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }


}

