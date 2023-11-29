<?php

namespace App\Modules\Admin\Controllers;
// use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Modules\Admin\Models\AdminModel;
use App\Libraries\Mylibrary; // Import library

class Admin extends Controller
{
	public $AdminModel;
	protected $helpers = ['url', 'file', 'my_helper', 'form'];
	protected $validation;
	protected $request;
	protected $session;
	protected $email;

	function __construct()
	{

		$this->AdminModel = new AdminModel();
		$this->session = \Config\Services::session();
		$this->validation = \Config\Services::validation();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
		$this->email = \Config\Services::email();
	}

	public function index(){
		
		return view('App\Modules\Admin\Views\login');
		
	}

	public function login(){
		$this->validation->setRule('email', 'Email Address', 'required');
		$this->validation->setRule('password', 'Password', 'required');
		if ($this->validation->withRequest($this->request)->run() == false) {
			$this->session->setFlashdata('Invalid', 'Please check your email');
			return view('App\Modules\Admin\Views\login');
		} else {
			$email = $this->request->getVar('email');
			$password = $this->mylibrary->cryptPass($this->request->getVar('password'));
			if ($this->AdminModel->resolveAdminLogin($email, $password) != false) {
				$admin_id = $this->AdminModel->resolveAdminLogin($email, $password);
				$admin    = $this->AdminModel->getAdmin($admin_id);
				$_SESSION['admin_id']      = (int)$admin->int_glcode;
				$_SESSION['email']     = (string)$admin->var_email;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['adminuser'] = (string)$admin->var_username;
				redirect('admin/dashboard');
			} else {
				$this->session->setFlashdata('Invalid', 'Invalid email or password');
				return view('App\Modules\Admin\Views\login');
			}
		}
	}

	

}
