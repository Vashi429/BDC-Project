<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class ToolsModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }

    public function getData($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_tools');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_tool_name',$filter);
			$builder->orLike('var_size',$filter);
			$builder->orLike('var_width',$filter);
			$builder->orLike('var_height',$filter);
			$builder->orLike('var_stock',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function getDataStock($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_tool_assign mta');
        $builder->select('mta.*,mp.var_project,mt.var_tool_name');
        $builder->join("mst_tools mt","mt.int_glcode = mta.fk_tool ","left");
        $builder->join("mst_project mp","mp.int_glcode = mta.fk_project ","left");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_tool_name',$filter);
			$builder->orLike('var_project',$filter);
			$builder->orLike('fk_project',$filter);
			$builder->orLike('fk_tool',$filter);
			$builder->orLike('var_stock',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function total_records_count(){
        $builder = $this->con->table('mst_tools');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    public function total_records_count_stock(){
        $builder = $this->con->table('mst_tool_assign');
        $builder->select('*');
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_tools');
		$builder->select('count(int_glcode) as total');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_tool_name',$filter);
			$builder->orLike('var_size',$filter);
			$builder->orLike('var_width',$filter);
			$builder->orLike('var_height',$filter);
			$builder->orLike('var_stock',$filter);
			$builder->groupEnd();
		}
		$builder->where('chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function filter_records_count_stock($filter = ''){
        $builder = $this->con->table('mst_tool_assign mta');
		$builder->select('count(mta.int_glcode) as total');
        $builder->join("mst_tools mt","mt.int_glcode = mta.fk_tool ","left");
        $builder->join("mst_project mp","mp.int_glcode = mta.fk_project ","left");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_tool_name',$filter);
			$builder->orLike('var_project',$filter);
			$builder->orLike('fk_project',$filter);
			$builder->orLike('fk_tool',$filter);
			$builder->orLike('var_stock',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function addRecord(){

        $userData = array(
            'var_tool_name' => $this->request->getVar('var_tool_name'),
            'var_size' => $this->request->getVar('var_size'),
            'var_width' => $this->request->getVar('var_width'),
            'var_height' => $this->request->getVar('var_height'),
            'var_stock' => $this->request->getVar('var_stock'),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_tools')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }
    public function addstockRecord(){

        $fk_tool = $this->request->getVar('fk_tool');
        $fk_project = $this->request->getVar('fk_project');
        $builder = $this->con->table('mst_tool_assign');
        $builder->select('*');
        $builder->where('fk_tool',$fk_tool);
        $builder->where('fk_project',$fk_project);
        $query =  $builder->get();
        $row_assign = $query->getResultArray();
        if(count($row_assign) > 0){
            $var_stock = $row_assign[0]['var_stock'] + $this->request->getVar('var_stock');
            $int_glcode = $row_assign[0]['int_glcode'];
            $userData = array(
                'var_stock' => $var_stock,
            );
            $builder = $this->con->table('mst_tool_assign');
            $builder->where('int_glcode', $int_glcode);
            $builder->update($userData);   
            $insert_user_id = $int_glcode;
        }else{
            $userData = array(
                'fk_project' => $this->request->getVar('fk_project'),
                'fk_tool' => $this->request->getVar('fk_tool'),
                'var_stock' => $this->request->getVar('var_stock'),
                'dt_createddate' => date('Y-m-d H:i:s'),
                'var_ipaddress' => $_SERVER['REMOTE_ADDR']
            );
            $this->con->table('mst_tool_assign')->insert($userData);
            $insert_user_id = $this->con->insertID();
        }
      

        $fk_tool = $this->request->getVar('fk_tool');
        $builder = $this->con->table('mst_tools');
        $builder->select('*');
        $builder->where('int_glcode',$fk_tool);
        $query =  $builder->get();
        $row = $query->getResultArray();
          
        if(count($row) > 0){
            $var_used_stock = $row[0]['var_used_stock'];
            $var_available_stock = $row[0]['var_available_stock'];

            $var_used_stock = $var_used_stock + $this->request->getVar('var_stock');
            $var_available_stock = $var_available_stock - $this->request->getVar('var_stock');

            $userData = array(
                'var_used_stock' => $var_used_stock,
                'var_available_stock' => $var_available_stock,
            );
    
            $builder = $this->con->table('mst_tools');
            $builder->where('int_glcode', $fk_tool);
            $builder->update($userData);   
        }        
        return $insert_user_id;
    }


    public function transferstock(){
        $fk_project = $this->request->getVar('fk_project');
        $var_stock = $this->request->getVar('var_stock');
        $id = $this->request->getVar('id');

        if($id != ''){
            $builder = $this->con->table('mst_tool_assign');
            $builder->select('*');
            $builder->where('int_glcode',$id);
            $query =  $builder->get();
            $row_assign = $query->getResultArray();

            $var_stock = $row_assign[0]['var_stock'] - $this->request->getVar('var_stock');
            $int_glcodes = $row_assign[0]['int_glcode'];
            if($var_stock > 0){
                $userData = array(
                    'var_stock' => $var_stock,
                );
                $builder = $this->con->table('mst_tool_assign');
                $builder->where('int_glcode', $int_glcodes);
                $builder->update($userData);   
                $insert_user_id = $int_glcodes;
            }
           

            $fk_tool = $row_assign[0]['fk_tool'];
            $builder = $this->con->table('mst_tool_assign');
            $builder->select('*');
            $builder->where('fk_tool',$fk_tool);
            $builder->where('fk_project',$fk_project);
            $query =  $builder->get();
            $row_assign = $query->getResultArray();

            if(count($row_assign) > 0){
                $var_stock = $row_assign[0]['var_stock'] + $this->request->getVar('var_stock');
                $int_glcode = $row_assign[0]['int_glcode'];
                $userData = array(
                    'var_stock' => $var_stock,
                );
                $builder = $this->con->table('mst_tool_assign');
                $builder->where('int_glcode', $int_glcode);
                $builder->update($userData);   
                $insert_user_id = $int_glcode;
            }else{
                $userData = array(
                    'fk_project' => $this->request->getVar('fk_project'),
                    'fk_tool' => $fk_tool,
                    'var_stock' => $this->request->getVar('var_stock'),
                    'dt_createddate' => date('Y-m-d H:i:s'),
                    'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                );
                $this->con->table('mst_tool_assign')->insert($userData);
                $insert_user_id = $this->con->insertID();
            }

            if($var_stock > 0){
                $builder = $this->con->table('mst_tool_assign');
                $builder->where('int_glcode', $int_glcodes);
                $builder->delete();
            }
        }

        return $insert_user_id;
    }

    public function updateRecord($id){
        
        $userData = array(
            'var_tool_name' => $this->request->getVar('var_tool_name'),
            'var_size' => $this->request->getVar('var_size'),
            'var_width' => $this->request->getVar('var_width'),
            'var_height' => $this->request->getVar('var_height'),
            'var_stock' => $this->request->getVar('var_stock'),
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        $builder = $this->con->table('mst_tools');
        $builder->where('int_glcode', $id);
        $builder->update($userData);
        return $id;
  
    }
    
    public function getToolsDetailsById($Id){
        $builder = $this->con->table('mst_tools');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }
    
    public function gettoolassigndata($Id){
        $builder = $this->con->table('mst_tool_assign');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }

    public function export_csv(){
        $builder = $this->con->table('mst_tools');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

}

