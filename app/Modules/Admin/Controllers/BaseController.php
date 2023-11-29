<?php

namespace App\Modules\Admin\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Libraries\Mylibrary; // Import library
use App\Modules\Admin\Models\CommonModel;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'file', 'my_helper', 'form'];
	public $CommonModel;
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->con = db_connect();
		$this->session = \Config\Services::session();
		$this->validation = \Config\Services::validation();
		$this->request = \Config\Services::request();
		$this->pager = \Config\Services::pager();
		$this->mylibrary = new Mylibrary();
		$this->CommonModel = new CommonModel();
		
		// redirect(base_url()."admin/".$_SESSION['roll_model']);
	}
}
