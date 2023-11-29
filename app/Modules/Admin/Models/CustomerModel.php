<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class CustomerModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }

    public function getData($rowno, $rowperpage, $filter="", $_field='int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_customer');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_displayname',$filter);
			$builder->orLike('var_customer_type',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                if($val['var_pan'] == ''){
					$val['var_pan'] = 'N/A';
				}
                if($val['var_gst'] == ''){
					$val['var_gst'] = 'N/A';
				}
                $data[] = $val;
            }
        }
        return $data;
    }

    public function total_records_count(){
        $builder = $this->con->table('mst_customer');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_customer');
		$builder->select('count(int_glcode) as total');
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('var_name',$filter);
			$builder->orLike('var_email',$filter);
			$builder->orLike('var_phone',$filter);
            $builder->orLike('var_displayname',$filter);
			$builder->orLike('var_customer_type',$filter);
			$builder->groupEnd();
		}
		$builder->where('chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }

    public function export_csv(){
        $builder = $this->con->table('mst_customer');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }

    public function addRecord(){
        
        if ($_FILES['var_gst_certi']['name'] != '') {
            if (!is_dir('uploads/customer')) {
				mkdir('uploads/customer', 0777, TRUE);
			}
			$gst_certi = uniqid() . '-' . $_FILES['var_gst_certi']['name'];
			$gst_certi = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $gst_certi);
			$destination = 'uploads/customer/';
			move_uploaded_file($_FILES['var_gst_certi']['tmp_name'], $destination . $gst_certi);
        }else{
            $gst_certi = '';
        }

        $userData = array(
            'var_customer_type' => $this->request->getVar('var_customer_type'),
            'var_name' => $this->request->getVar('var_name'),
            'var_displayname'=> $this->request->getVar('var_displayname'),
            'var_business_name'=> $this->request->getVar('var_business_name'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_alt_phone' => $this->request->getVar('var_alt_phone'),
            'var_email' => $this->request->getVar('var_email'),
            'var_pan' => $this->request->getVar('var_pancard'),
            'var_gst' => $this->request->getVar('var_gst'),
            'var_gst_certi' => $gst_certi,
            'var_office_address' => $this->request->getVar('var_office_address'),
            'fk_state' => $this->request->getVar('fk_state'),
            'fk_city' => $this->request->getVar('fk_city'),
            'fk_country' => $this->request->getVar('fk_country'),
            'var_pincode' => $this->request->getVar('var_pincode'),
            'var_details' => $this->request->getVar('var_details'),
            'chr_publish' => 'Y',
            'chr_delete' => 'N',
            'dt_createddate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        $this->con->table('mst_customer')->insert($userData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
  
    }

    public function updateRecord($id){
        
        if ($_FILES['var_gst_certi']['name'] != '') {
            if (!is_dir('uploads/customer')) {
				mkdir('uploads/customer', 0777, TRUE);
			}
			$gst_certi = uniqid() . '-' . $_FILES['var_gst_certi']['name'];
			$gst_certi = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $gst_certi);
			$destination = 'uploads/customer/';
			move_uploaded_file($_FILES['var_gst_certi']['tmp_name'], $destination . $gst_certi);
        }else{
            $gst_certi = $this->request->getVar('var_hgst_certi');
        }

        $userData = array(
            'var_customer_type' => $this->request->getVar('var_customer_type'),
            'var_name' => $this->request->getVar('var_name'),
            'var_displayname'=> $this->request->getVar('var_displayname'),
            'var_business_name'=> $this->request->getVar('var_business_name'),
            'var_phone' => $this->request->getVar('var_phone'),
            'var_alt_phone' => $this->request->getVar('var_alt_phone'),
            'var_email' => $this->request->getVar('var_email'),
            'var_pan' => $this->request->getVar('var_pancard'),
            'var_gst' => $this->request->getVar('var_gst'),
            'var_gst_certi' => $gst_certi,
            'var_office_address' => $this->request->getVar('var_office_address'),
            'fk_state' => $this->request->getVar('fk_state'),
            'fk_city' => $this->request->getVar('fk_city'),
            'fk_country' => $this->request->getVar('fk_country'),
            'var_pincode' => $this->request->getVar('var_pincode'),
            'var_details' => $this->request->getVar('var_details'),
            'dt_modifydate' => date('Y-m-d H:i:s'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        
        $builder = $this->con->table('mst_customer');
        $builder->where('int_glcode', $id);
        $update = $builder->update($userData);
        return ($update) ? "1" : "0";  
    }
    
    public function getCustomerDetailsById($Id){
        $builder = $this->con->table('mst_customer');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }
}

