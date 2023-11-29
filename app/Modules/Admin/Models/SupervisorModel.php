<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class SupervisorModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }

    public function getData($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_supervisor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_username',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                if($val['var_email']==""){
                    $val['var_email'] = 'N/A';
                }
                $data[] = $val;
            }
        }
        return $data;
    }

    public function getDataattendance($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_superviser_attendance msa');
        $builder->select('msa.*,mp.var_project,ms.var_name');
        $builder->join("mst_project mp","mp.int_glcode = msa.fk_project ","left");
        $builder->join("mst_supervisor ms","ms.int_glcode = msa.fk_superviser ","left");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('ms.var_name',$filter);
			$builder->orLike('mp.var_project',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function total_records_count(){
        $builder = $this->con->table('mst_supervisor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function total_records_count_attendance(){
        $builder = $this->con->table('mst_superviser_attendance');
        $builder->select('*');
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_supervisor');
		$builder->select('count(int_glcode) as total');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_username',$filter);
			$builder->groupEnd();
		}
		$builder->where('chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function filter_records_count_attendance($filter = ''){
		$builder = $this->con->table('mst_superviser_attendance msa');
		$builder->select('count(msa.int_glcode) as total');
        $builder->join("mst_project mp","mp.int_glcode = msa.fk_project ","left");
        $builder->join("mst_supervisor ms","ms.int_glcode = msa.fk_superviser ","left");
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('ms.var_name',$filter);
			$builder->orLike('mp.var_project',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function export_csv(){
        $builder = $this->con->table('mst_supervisor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function export_csv_attendance(){
        $builder = $this->con->table('mst_superviser_attendance msa');
        $builder->select('msa.*,mp.var_project,ms.var_name');
        $builder->join("mst_project mp","mp.int_glcode = msa.fk_project ","left");
        $builder->join("mst_supervisor ms","ms.int_glcode = msa.fk_superviser ","left");
        $builder->orderBy('msa.int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    
    public function addRecord(){
        
        $userData = array(
            'var_name' => $this->request->getVar('var_name'),
            'var_username'=> $this->request->getVar('var_username'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_email' => $this->request->getVar('var_email'),
            'var_aadhar' => $this->request->getVar('var_aadhar'),
            'var_pancard' => $this->request->getVar('var_pancard'),
            'var_password' => $this->mylibrary->cryptPass($this->request->getVar('var_password')),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_supervisor')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }

    public function updateRecord($id){
        
        $userData = array(
            'var_name' => $this->request->getVar('var_name'),
            'var_username'=> $this->request->getVar('var_username'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_email' => $this->request->getVar('var_email'),
            'var_aadhar' => $this->request->getVar('var_aadhar'),
            'var_pancard' => $this->request->getVar('var_pancard'),
            'var_password' => $this->mylibrary->cryptPass($this->request->getVar('var_password')),
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        $builder = $this->con->table('mst_supervisor');
        $builder->where('int_glcode', $id);
        $update = $builder->update($userData);
        return ($update) ? "1" : "0";  
  
    }
    
    public function getSupervisorDetailsById($Id){
        $builder = $this->con->table('mst_supervisor');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }
}

