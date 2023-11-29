<?php
namespace App\Modules\Admin\Models;
use CodeIgniter\Model;

class LoginModel extends Model
{
	public $con = '';
	function __construct() {
		$this->con = db_connect();
	}
	
	public function getAllUsers(){
		$query  = $this->con->query('select * from mst_user');
		return  $query->getResult();
	}
}