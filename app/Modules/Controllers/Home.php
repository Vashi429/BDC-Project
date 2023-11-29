<?php
namespace App\Modules\Controllers;
use CodeIgniter\Controller;
use App\Modules\Models\HomeModel;
use App\Modules\Models\CommonModel;
define('Views', 'App\Modules\Views\home');

class Home extends BaseController{
	public $HomeModel;
	public $CommonModel;
	function __construct(){
		$this->HomeModel = new HomeModel();
		$this->CommonModel = new CommonModel();
	}

	public function index(){	
		$data['title'] = 'Home';
		$data['view'] = Views . '\homepage';
		$data['heading'] = 'Welcome';
	
		return view('front_template/template', $data);
	}
}
