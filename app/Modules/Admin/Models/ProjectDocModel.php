<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class ProjectDocModel extends Model{
    public $con = '';
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
    }
    public function getData($fk_project, $rowno, $rowperpage, $filter="", $_field='mpd.int_glcode', $_sort="desc")
    {
        $builder = $this->con->table('mst_projectdoc mpd');
        $builder->select('mpd.*');
        $builder->where('mpd.fk_project',$fk_project);
        if ($filter != '') {
			$builder->groupStart();
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;  
    }

    public function total_records_count($fk_project)
    {
        $builder = $this->con->table('mst_projectdoc');
        $builder->select('*');
        $builder->where('fk_project',$fk_project);
        $query =  $builder->get();
        return $query->getNumRows();
    }

    public function filter_records_count($fk_project,$filter = '')
    {
      
        $builder = $this->con->table('mst_projectdoc mpd');
        $builder->select('mpd.*');
        $builder->where('mpd.fk_project',$fk_project);
        if ($filter != '') {
			$builder->groupStart();
			$builder->groupEnd();
		}
        $query = $builder->get();
        $result = $query->getNumRows();
        return $result;
    }
    

    public function addRecode()
    {

        if ($_FILES['var_image']['name'] != '') {
            if (!is_dir('uploads/projectdoc')) {
				mkdir('uploads/projectdoc', 0777, TRUE);
			}
			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];
			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);
			$destination = 'uploads/projectdoc/';
			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);
        }else{
            $var_image = '';
        }

        $imageData = array(
            "var_image"=>$var_image,
            'fk_project' => $this->request->getVar('fk_project'),
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
      
        $this->con->table('mst_projectdoc')->insert($imageData);
        $insert_user_id = $this->con->insertID();
        return $insert_user_id;
    }

    public function getProjectDocDetailsById($Id)
    {
        $builder = $this->con->table('mst_projectdoc');
        $builder->select('*');
        $builder->where('int_glcode', $Id);
        $query = $builder->get();
        $rows = $query->getRowArray();
        return $rows;
    }

    public function updateRecord($id)
    {

        if ($_FILES['var_image']['name'] != '') {
            if (!is_dir('uploads/projectdoc')) {
				mkdir('uploads/projectdoc', 0777, TRUE);
			}
			$var_image = uniqid() . '-' . $_FILES['var_image']['name'];
			$var_image = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $var_image);
			$destination = 'uploads/projectdoc/';
			move_uploaded_file($_FILES['var_image']['tmp_name'], $destination . $var_image);
        }else{
            $var_image = $_POST['var_image_hidden'];
        }
            $imageData = array(
                'fk_project' => $this->request->getVar('fk_project'),
                'var_image' => $var_image,
                'var_ipaddress' => $_SERVER['REMOTE_ADDR']
            );
            
            $builder = $this->con->table('mst_projectdoc');
            $builder->where('int_glcode', $id);
            $update = $builder->update($imageData);
            return ($update) ? "1" : "0";  
       
    }
}

