<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;

class AdminModel extends Model
{
	public $con = '';
	function __construct()
	{
		$this->con = db_connect();
		$this->request = \Config\Services::request();
	}

	public function getAllUsers()
	{
		$query  = $this->con->query('select * from mst_user');
		return  $query->getResult();
	}

	public function resolveAdminLogin($email, $password)
	{
		$select = "select * from mst_admin where var_email = '" . $email . "' and var_password = '" . $password . "' ";
		$result = $this->con->query($select);
		$row = $result->getRowArray();
		if ($row) {
			if (count($row) > 0) {
				return $row['int_glcode'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getAdmin($admin_id)
	{
		$builder =  $this->con->table('mst_admin');
		$builder->select("*");
		$builder->where('int_glcode ', $admin_id);
		$query = $builder->get();
		return $query->getRow();
	}

	public function checkEmail($email)
	{
		$builder =  $this->con->table('mst_admin');
		$builder->select("*");
		$builder->where('var_email', $email);
		$builder->where('chr_delete ', 'N');
		$builder->get();
		return $builder->countAll();
	}

	public function updatePass($new_password, $email)
	{
		$builder = $this->con->table("mst_admin");
		$data = array(
			"var_password" => $new_password
		);
		$builder->where('var_email', $email);
		$a = $builder->update($data);
		return $a;
	}
}
