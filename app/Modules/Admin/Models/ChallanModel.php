<?php
namespace App\Modules\Admin\Models;
use App\Modules\Admin\Models\CommonModel;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;

class ChallanModel extends Model{
    public $con = '';
    protected $request;
	public $CommonModel;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
		$this->CommonModel = new CommonModel();
    }
    
    /* list data for the invoice Start */
    public function getData($rowno, $rowperpage, $filter="", $_field='c.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_challan c');
        $builder->select('c.*, s.var_name as supervisor_name, b.var_bill_no, v.var_name as vendor_name, p.var_project');
        $builder->join("mst_project p","p.int_glcode = c.fk_project","left");
        $builder->join("mst_supervisor s","s.int_glcode = p.fk_supervisor ","left");
        $builder->join("mst_vendor v","v.int_glcode = c.fk_vendor","left");
        $builder->join("mst_bill b","b.int_glcode = c.fk_bill","left");
        $builder->where('c.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('c.var_challan_id',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('s.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
            $builder->orLike('b.var_bill_no',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                if($val['var_bill_no']=="" || $val['var_bill_no']==0){
                    $val['var_bill_no'] = '-';
                }
                $val['dt_createddate'] = dateformate($val['dt_createddate']);
                $data[] = $val;
            }
        }
        return $data;
    }
    /* list data for the invoice End */

    /* total list data count for the invoice Start */
    public function total_records_count(){
        $builder = $this->con->table('mst_challan');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    /* total list data count for the invoice End */

    /* total list data count with filter for the invoice Start */
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_challan c');
        $builder->select('count(c.int_glcode) as total');
        $builder->join("mst_project p","p.int_glcode = c.fk_project  ","left");
        $builder->join("mst_supervisor s","s.int_glcode = p.fk_supervisor ","left");
        $builder->join("mst_vendor v","v.int_glcode = c.fk_vendor","left");
        $builder->join("mst_bill b","b.int_glcode = c.fk_bill","left");
        $builder->where('c.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('c.var_challan_id',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('s.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
            $builder->orLike('b.var_bill_no',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRowArray();
        return $result['total'];
    }
    /* total list data count with filter for the invoice Start */
    
    /* Export CSV of invoice Start */
    public function export_csv(){
        $builder = $this->con->table('mst_challan c');
        $builder->select('c.*, s.var_name as supervisor_name, v.var_name as vendor_name, p.var_project, mm.var_item, mm.var_unit, b.var_bill_no');
        $builder->join("mst_project p","p.int_glcode = c.fk_project  ","left");
        $builder->join("mst_supervisor s","s.int_glcode = p.fk_superviser ","left");
        $builder->join("mst_vendor v","v.int_glcode = c.fk_vendor","left");
        $builder->join("mst_material mm","mm.int_glcode = c.fk_item  ","left");
        $builder->join("mst_bill b","b.int_glcode = c.fk_bill","left");
        $builder->where('c.chr_delete',"N");
        $builder->orderBy('c.int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                if($val['var_bill_no']=="" || $val['var_bill_no']==0){
                    $val['var_bill_no'] = '-';
                }
                $data[] = $val;
            }
        }
        return $data;
    }
    /* Export CSV of invoice End */
  
    /* Add and update invoice as per id (if id="" then insert else update)  Start */
    function addUpdateRecord($id=""){
        if ($_FILES['var_image']['name'] != '') {
            if (!is_dir('uploads/challan')) {
				mkdir('uploads/challan', 0777, TRUE);
			}
			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];
			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);
			$destination = 'uploads/challan/';
			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);
        }else{
            $var_image = $this->request->getVar('hvar_image');
        }

        $ChallanData = array(
            'var_challan_id' => $this->request->getVar('var_challan_id'), 
            'fk_project' => $this->request->getVar('fk_project'), 
            'fk_vendor' => $this->request->getVar('fk_vendor'), 
            'fk_item' => $this->request->getVar('fk_item'), 
            'var_quantity' => $this->request->getVar('var_quantity'),
            'var_image' => $var_image,
            'var_amount' => $this->request->getVar('var_amount'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $fk_profile = $this->CommonModel->getValById('mst_project', 'fk_profile', array('int_glcode' => $this->request->getVar('fk_project')));
        $expenseData = array(
            'fk_profile' => $fk_profile,
            'fk_project' => $this->request->getVar('fk_project'), 
            'fk_vendor' => $this->request->getVar('fk_vendor'), 
            'var_expense_type' => 'Vendor',
            'var_amount' => $this->request->getVar('var_amount'), 
            'var_payment_mode' => 'Cash',
            'var_expense_mode' => 'Challan',
            'var_expense_date' => date('Y-m-d'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        if($id !="" && $id > 0){
            $challanDataBeforeUpdate = $this->CommonModel->getRowArray('mst_challan', '*',array('int_glcode' => $id));
            $ChallanData['fk_expense'] = $this->request->getVar('fk_expense');
            $this->CommonModel->updateRows('mst_challan',$ChallanData, array('int_glcode'=>$id));
            $this->CommonModel->updateRows('mst_expense',$expenseData, array('int_glcode'=>$this->request->getVar('fk_expense')));
            $insert_user_id = $id;
        }else{  
            $challanDataBeforeUpdate = array('var_quantity' => 0);       
            $this->con->table('mst_expense')->insert($expenseData);
            $fk_expense_insert = $this->con->insertID();

            $ChallanData['fk_expense'] = $fk_expense_insert;
            $this->con->table('mst_challan')->insert($ChallanData);
            $insert_user_id = $this->con->insertID();
        }

        $wherePIR = array(
            'fk_project' => $this->request->getVar('fk_project'),
            'fk_material' => $this->request->getVar('fk_item')
        );
        $projectItemRow = $this->CommonModel->getRowArray('mst_project_item', '*',$wherePIR);
        $updateStock = array(
            'var_due_stock' => $projectItemRow['var_due_stock'] - $this->request->getVar('var_quantity') + $challanDataBeforeUpdate['var_quantity'], 
            'var_used_stock' => $projectItemRow['var_used_stock'] + $this->request->getVar('var_quantity') - $challanDataBeforeUpdate['var_quantity']
        );
        $this->CommonModel->updateRows('mst_project_item',$updateStock, $wherePIR);


        return $insert_user_id;
    }
    /* Add and update invoice as per id (if id="" then insert else update)  End */

    /* Get invoice data for the edit page Start */
    public function getChallanData($int_glcode){
        $builder = $this->con->table('mst_challan c');
        $builder->select('c.*');
        $builder->join("mst_expense e", "c.fk_expense = e.int_glcode ", "left");
        $builder->where('c.int_glcode',$int_glcode);
        // $builder->where('eb.fk_bill', 0);
        $builder->where('e.chr_delete', 'N');
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }
    /* Get invoice data for the edit page End */

    /* get the supervisor list which project is exists start */
    public function getSupervisorData(){
        $builder = $this->con->table('mst_project p');
        $builder->select('s.*');
        $builder->join('mst_supervisor s', 's.int_glcode = p.fk_supervisor', 'left');
        $builder->where('s.chr_delete', 'N');
        $builder->where('s.chr_publish', 'Y');
        $builder->where('p.chr_delete', 'N');
        $builder->where('p.chr_publish', 'Y');
        $builder->orderBy('s.var_name', 'ASC');
        $builder->groupBy('s.int_glcode');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
    /* get the supervisor list which project is exists end */

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

    
    function getProjectList(){
        $builder = $this->con->table('mst_project p');
        $builder->select('p.int_glcode, p.var_project');
        $builder->join('mst_company_profile cp', 'cp.int_glcode = p.fk_profile', 'left');
        $builder->where('p.chr_delete', 'N');
        $builder->where('p.chr_publish', 'Y');
        $builder->where('cp.chr_delete', 'N');
        $builder->where('cp.chr_publish', 'Y');
        $builder->where('date_format(p.end_date,"%Y-%m-%d") >=', date('Y-m-d'));
        $builder->groupBy('p.int_glcode');
        $builder->orderBy('p.var_project', 'asc');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
    }

}

