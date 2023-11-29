<?php

namespace App\Controllers;

class Home extends BaseController
{
	
	function __construct() {
		// echo 'ca';exit;	
	}
	
    public function index()
    {	
		$db = new \Config\Database();
		$con =  $db->connect();
		$tbl = $con->table('mst_user');
		$query = $tbl->get();
		$result = $query->getResult();  
        return view('welcome_message');
    }
	
	
}
