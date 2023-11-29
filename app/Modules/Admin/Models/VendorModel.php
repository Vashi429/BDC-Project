<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class VendorModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }

    public function getData($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_vendor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_business', $filter);
            $builder->orLike('var_gst',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function total_records_count(){
        $builder = $this->con->table('mst_vendor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_vendor');
		$builder->select('count(int_glcode) as total');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_business',$filter);
            $builder->orLike('var_business', $filter);
			$builder->groupEnd();
		}
		$builder->where('chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
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
            'var_name' => $this->request->getVar('var_name'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_business' => $this->request->getVar('var_business'),
            'var_pancard' => $this->request->getVar('var_pancard'),
            'var_email' => $this->request->getVar('var_email'),
            'var_gst' => $this->request->getVar('var_gst'),
            'var_gst_certi' => $gst_certi,
            'var_address' => $this->request->getVar('var_address'),
            'fk_state' => $this->request->getVar('fk_state'),
            'fk_city' => $this->request->getVar('fk_city'),
            'fk_country' => $this->request->getVar('fk_country'),
            'var_pincode' => $this->request->getVar('var_pincode'),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_vendor')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }

    public function updateRecord($id){
        
        if ($_FILES['var_gst_certi']['name'] != '') {
            if (!is_dir('uploads/vendor')) {
				mkdir('uploads/vendor', 0777, TRUE);
			}
			$gst_certi = uniqid() . '-' . $_FILES['var_gst_certi']['name'];
			$gst_certi = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $gst_certi);
			$destination = 'uploads/vendor/';
			move_uploaded_file($_FILES['var_gst_certi']['tmp_name'], $destination . $gst_certi);
        }else{
            $gst_certi = $this->request->getVar('var_hgst_certi');
        }

        $userData = array(
            'var_name' => $this->request->getVar('var_name'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_business' => $this->request->getVar('var_business'),
            'var_email' => $this->request->getVar('var_email'),
            'var_pancard' => $this->request->getVar('var_pancard'),
            'var_gst' => $this->request->getVar('var_gst'),
            'var_gst_certi' => $gst_certi,
            'var_address' => $this->request->getVar('var_address'),
            'fk_state' => $this->request->getVar('fk_state'),
            'fk_city' => $this->request->getVar('fk_city'),
            'fk_country' => $this->request->getVar('fk_country'),
            'var_pincode' => $this->request->getVar('var_pincode'),
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $builder = $this->con->table('mst_vendor');
        $builder->where('int_glcode', $id);
        $update = $builder->update($userData);
        return ($update) ? "1" : "0";  
    }
    
    
    public function getVendorDetailsById($Id){
        $builder = $this->con->table('mst_vendor');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }

    public function export_csv(){
        $builder = $this->con->table('mst_vendor');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
}

