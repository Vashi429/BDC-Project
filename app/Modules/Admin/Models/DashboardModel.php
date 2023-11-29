<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;

class DashboardModel extends Model
{
	
	public $con = '';
	protected $request;
	function __construct() {
		$this->con = db_connect();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
	}

  
    public function change_pass(){
      $opass = $_POST['opassword'];
      $opass = $this->mylibrary->cryptPass($opass);
      $builder = $this->con->table('mst_admin');
      $builder->select('var_password');
      $builder->where('int_glcode',$_SESSION['admin_id']);
      $query = $builder->get();
      $res = $query->getRowArray();
      
      $npass = $_POST['npassword'];    
      $npass = $this->mylibrary->cryptPass($npass);
      if($opass == $res['var_password']){
        if($opass != $npass){
          $data = array(
            "var_password" => $npass,
          );
          $builder = $this->con->table("mst_admin");
          $builder->where('int_glcode', $_SESSION['admin_id']);
          $update = $builder->update($data);
          if($update){
            return 1;
          }else{
            return "error! occured in change password.";
          }
        }else{
          return "old and new password are same";
        }
      }else{
        return "incorrect old password.";
      }
    }

   
    
}