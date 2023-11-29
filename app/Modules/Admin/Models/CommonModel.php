<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class CommonModel extends Model{
	public $con = '';
	protected $request;
	function __construct(){
		$this->con = db_connect();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
	}
	public function getAllCountry(){
		$builder = $this->con->table('mst_country');
		$builder->select('var_title,int_glcode');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
	}
	
	public function getStateList(){
        $builder = $this->con->table('mst_state');
        $builder->select('*');
        $builder->orderBy('var_title','ASC');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
    public function getCityList($fk_state){
        $builder = $this->con->table('mst_city');
        $builder->select('*');
        $builder->where('fk_state', $fk_state);
        $builder->orderBy('var_title','ASC');
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
    public function getResultArray($table, $column="", $where = array(),$order_by = 'int_glcode', $short = 'desc'){
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
    public function getRowArray($table, $column="", $where = array(),$DESC = ''){
        $builder = $this->con->table($table);
        if($column!=""){
            $builder->select($column);
        }else{
            $builder->select('int_glcode');
        }
        if(!empty($where)){
            $builder->where($where);
        }
        
        if($DESC != '')
        {
            $builder->orderBy('int_glcode',$DESC);
            $builder->limit(1);
        }
        $query = $builder->get();
       
        $rows = $query->getRowArray();
        return $rows;
    }
    public function deleteRow($table, $where= array()){
        $builder = $this->con->table($table);
        if(!empty($where)){
            $builder->where($where);
        }
        return $builder->delete();
    }
	public function getAllCustomer(){
		$builder = $this->con->table('mst_customer');
		$builder->select('var_name,int_glcode');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
	}
    
    public function updatedisplay(){
        $table = $this->request->getVar('tablename');
        $builder = $this->con->table("$table");
        $data = array(
            $this->request->getVar('fieldname') => $this->request->getVar('value')
        );
        $builder->where('int_glcode', $this->request->getVar('id'));
        $a = $builder->update($data);
        echo ($a) ? "1" : "0";
        exit;
    }
    
    public function delete_multiple($table){
        $ids = $this->request->getVar('id');
        $i = 0;
        foreach ($ids as $key => $value) {
            $data = array(
                'chr_delete' => 'Y'
            );
            if($table == 'mst_estimate')
            {
                $bulider = $this->con->table('mst_estimate_items');
                $bulider->where('fk_estimate', $value);
                $bulider->delete();
            }
            $builder = $this->con->table("$table");
            $builder->where('int_glcode', $value);
            if ($builder->update($data)) {
                $i++;
                $smsg = $i . " Records successfully deleted...";
            }
        }
        return $smsg;
    }
	public function getValById($table, $column="", $where=array()){
		$builder = $this->con->table($table);
		if($column!=""){
            $builder->select($column);
        }else{
            $builder->select('int_glcode');
        }
		$builder->where($where);
		$query = $builder->get();
		$rows = $query->getRowArray();
        if(!empty($rows)){
		    return $rows[$column];
        }else{
            return false;
        }
	}
    public function getUniqueAutoId($table, $columns){
        $randumValue = rand(10000,99999);
        $builder = $this->con->table($table);
		$builder->select($columns);
        $builder->where($columns, $randumValue);
		$query = $builder->get();
		$row = $query->getRowArray();
        if(!empty($row)){
            $this->getUniqueAutoId($table, $columns);
        }else{
            return $randumValue;
        }
    }
    public function updateRows($table, $data, $where=array()){
        $builder = $this->con->table($table);
        $builder->where($where);
        $update =  $builder->update($data);
        return $update;
    }
    public function getGstType($fk_customer, $fk_profile){
		$projectState = $customerState = 0;
		if ($fk_customer != "" && $fk_customer > 0) {
			$customerState = $this->getValById('mst_customer', 'fk_state', array('int_glcode' => $fk_customer));
        }
		if ($fk_profile != "" && $fk_profile > 0) {
			$projectState = $this->getValById('mst_company_profile', 'fk_state', array('int_glcode' => $fk_profile));
		}
		$gstType = 'IGST';
		if ($projectState == 0 && $customerState == 0) {
			$gstType = 'GST';
		} else if ($customerState == $projectState) {
			$gstType = 'SGST';
		}
        return $gstType;
    }
    function getCompanyProfileList(){
        $builder = $this->con->table('mst_project p');
        $builder->select('cp.int_glcode, cp.var_name');
        $builder->join('mst_company_profile cp', 'cp.int_glcode = p.fk_profile', 'left');
        $builder->where('p.chr_delete', 'N');
        $builder->where('p.chr_publish', 'Y');
        $builder->where('cp.chr_delete', 'N');
        $builder->where('cp.chr_publish', 'Y');
        $builder->where('date_format(p.end_date,"%Y-%m-%d") >=', date('Y-m-d'));
        $builder->groupBy('cp.int_glcode');
        $builder->orderBy('cp.var_name', 'asc');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
    }
    function getProjectList(){
        $builder = $this->con->table('mst_project p');
        $builder->select('p.int_glcode, p.var_project');
        $builder->where('p.chr_delete', 'N');
        $builder->where('p.chr_publish', 'Y');
        $builder->where('date_format(p.end_date,"%Y-%m-%d") >=', date('Y-m-d'));
        $builder->groupBy('p.int_glcode');
        $builder->orderBy('p.var_project', 'asc');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
    }
    function getLaborList($project_id,$date){
        $builder = $this->con->table('mst_labor_assignment p');
        $builder->select('p.int_glcode, p.fk_project, p.fk_labor_type, p.var_number_of_assign, p.var_number_of_assign_expense, p.var_total_charge, p.var_due_charge, p.var_paid_charge, cp.var_type');
        $builder->join('mst_labor_type cp', 'cp.int_glcode = p.fk_labor_type', 'left');
        $builder->where('cp.chr_delete', 'N');
        $builder->where('cp.chr_publish', 'Y');
        $builder->where('p.fk_project', $project_id);
        $builder->where('p.var_due_charge >', '0');
        $builder->where('date_format(p.start_date,"%Y-%m-%d") <=', $date);
        $builder->where('date_format(p.end_date,"%Y-%m-%d") >=', $date);
        $builder->groupBy('p.fk_labor_type');
		$query = $builder->get();
		$rows = $query->getResultArray();
		return $rows;
    }
    
}
