<?php

namespace App\Modules\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
	public $con = '';
	function __construct()
	{
		$this->con = db_connect();
	}
	
}
