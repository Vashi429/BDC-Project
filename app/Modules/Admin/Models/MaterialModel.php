<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class MaterialModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }

    public function getData($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_material');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_item',$filter);
			$builder->orLike('var_unit',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function total_records_count(){
        $builder = $this->con->table('mst_material');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_material');
		$builder->select('count(int_glcode) as total');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_item',$filter);
			$builder->orLike('var_unit',$filter);
			$builder->groupEnd();
		}
		$builder->where('chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function addRecord(){

        $userData = array(
            'var_item' => $this->request->getVar('var_item'),
            'var_unit' => $this->request->getVar('var_unit'),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_material')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }

    public function updateRecord($id){
        
        $userData = array(
            'var_item' => $this->request->getVar('var_item'),
            'var_unit' => $this->request->getVar('var_unit'),
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        $builder = $this->con->table('mst_material');
        $builder->where('int_glcode', $id);
        $builder->update($userData);
        return $id;
  
    }
    
    public function getMaterialDetailsById($Id){
        $builder = $this->con->table('mst_material');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }

    public function export_csv(){
        $builder = $this->con->table('mst_material');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

}

