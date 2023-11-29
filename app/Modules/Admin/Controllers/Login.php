<?php

namespace App\Modules\Admin\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Admin\Models\AdminModel;
use App\Modules\Admin\Models\LoginModel;
use App\Libraries\Mylibrary; // Import library

class Login extends Controller
{
	public $LoginModel;
	public $AdminModel;
	protected $helpers = ['url', 'file', 'my_helper', 'form'];
	protected $validation;
	protected $request;
	protected $session;
	protected $email;
	function __construct(){
		$this->AdminModel = new AdminModel();
		$this->session = \Config\Services::session();
		$this->validation = \Config\Services::validation();
		$this->request = \Config\Services::request();
		$this->mylibrary = new Mylibrary();
		$this->email = \Config\Services::email();
		$this->LoginModel = new LoginModel();
	}

	public function index(){
		$data[] = '';
		return view('App\Modules\Admin\Views\login');
	}

	function user_signin(){
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
				return redirect()->to(base_url() . '/admin/dashboard');
			} else {
				// login failed
				$this->session->setFlashdata('Invalid', 'Invalid email or password');
				// send error to the view
				return redirect()->to(base_url() . '/admin');
			}
		}
	}

	public function logout(){
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == '1') {
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			// user logout ok
			$this->session->setFlashdata('Invalid', 'Logout Successfully');
			return redirect()->to(base_url() . '/admin');
		}
	}
}
