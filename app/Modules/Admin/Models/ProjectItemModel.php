<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Modules\Admin\Models\ProjectModel;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class ProjectItemModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
        $this->CommonModel = new CommonModel();
    }

    public function getData($fk_project, $rowno, $rowperpage, $filter="", $_field='pt.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_project_item pt');
        $builder->select('pt.*, mp.var_project, mm.var_item, mm.var_unit');
        $builder->join('mst_project mp' , 'pt.fk_project = mp.int_glcode', 'left');
        $builder->join('mst_material mm' , 'pt.fk_material = mm.int_glcode', 'left');
        $builder->where('pt.chr_delete',"N");
        $builder->where('pt.fk_project',$fk_project);
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('mp.var_project',$filter);
			$builder->orLike('mm.var_item',$filter);
			$builder->orLike('mm.var_unit',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
       
        return $row;
    }

    public function getMaterialsList($id = ''){
        $newData = $this->getMaterialsofProjects($id);
        $builder = $this->con->table('mst_material mm');
        $builder->select('mm.*');
        $builder->where('mm.chr_delete',"N");
        $builder->where('mm.chr_publish',"Y");
        if(!empty($newData))
        {
          $builder->whereNotIn('mm.int_glcode',$newData);
        }
        $builder->orderBy('mm.int_glcode','asc');
        $query =  $builder->get();
        $row = $query->getResultArray();
          
        return $row; 
    }
    public function getMaterialsofProjects($id = ''){
        $builder = $this->con->table('mst_project_item pt');
        $builder->select('pt.*');
        $builder->where('pt.chr_delete',"N");
        $builder->where('pt.chr_publish',"Y");
        if($id != '')
        {
           $builder->where('pt.int_glcode !=',$id);
        }
        $builder->groupBy('pt.fk_material');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return array_column($row, 'fk_material');
    }
    public function total_records_count($fk_project){
        $builder = $this->con->table('mst_project_item');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->where('fk_project',$fk_project);
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($fk_project, $filter = ''){
		$builder = $this->con->table('mst_project_item pt');
        $builder->select('count(pt.int_glcode) as total');
        $builder->join('mst_project mp' , 'pt.fk_project = mp.int_glcode', 'left');
        $builder->join('mst_material mm' , 'pt.fk_material = mm.int_glcode', 'left');
        $builder->where('pt.chr_delete',"N");
        $builder->where('pt.fk_project',$fk_project);
        if($filter != '') {
			$builder->groupStart();
			$builder->Like('mp.var_project',$filter);
			$builder->orLike('mm.var_item',$filter);
			$builder->orLike('mm.var_unit',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function export_csv($fk_project){
        $builder = $this->con->table('mst_project_item pt');
        $builder->select('pt.*, mp.var_project, mm.var_item, mm.var_unit');
        $builder->join('mst_project mp' , 'pt.fk_project = mp.int_glcode', 'left');
        $builder->join('mst_material mm' , 'pt.fk_material = mm.int_glcode', 'left');
        $builder->where('pt.chr_delete',"N");
        $builder->where('pt.fk_project',$fk_project);
        $builder->orderBy('pt.int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function addRecord(){
        $userData = array(
            'fk_project' => $this->request->getVar('fk_project'),
            'fk_material' => $this->request->getVar('fk_material'),
            'var_stock'=> $this->request->getVar('var_stock'),
            'var_due_stock'=> $this->request->getVar('var_stock'),
            'var_used_stock' => 0,
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_project_item')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }

    public function updateRecord($id){
        $data = $this->getProjectIemDetailsById($id);
        $userData = array(
            'fk_project' => $this->request->getVar('fk_project'),
            'fk_material' => $this->request->getVar('fk_material'),
            'var_stock'=> $this->request->getVar('var_stock'),
            'var_due_stock'=> $this->request->getVar('var_stock')-$data['var_used_stock'],
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        $builder = $this->con->table('mst_project_item');
        $builder->where('int_glcode', $id);
        $update = $builder->update($userData);
        return ($update) ? "1" : "0";  
    }
    
    public function getProjectIemDetailsById($Id){
        $builder = $this->con->table('mst_project_item');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }
}

