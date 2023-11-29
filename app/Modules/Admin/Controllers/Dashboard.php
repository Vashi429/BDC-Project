<?php 
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\DashboardModel;
use App\Modules\Admin\Models\CommonModel;
define('Views','App\Modules\Admin\Views');

class Dashboard extends BaseController
{
    public $DashboardModel;
    public $CommonModel;
    public function __construct(){
        $this->DashboardModel = new DashboardModel();
        $this->CommonModel = new CommonModel();
    }

    public function index(){
		
		$data = [
		    'title' => 'Dashboard Page',
            'view' => Views.'\home',
            'heading' => 'Welcome to BDC',
            'totalContacts' => 0,
        ];
        return view('admin_tempate/template',$data);
	}
	
	public function change_pass(){
	    $result = $this->DashboardModel->change_pass();
		echo $result;
		exit;
	}

   

     public function note(){
        $data = [
            'title' => 'Note Mangement',
            'view' => Views.'note\view_note',
            'heading' => 'Welcome to Note Mangement',
        ];
        return view('admin_tempate/template',$data);
    }
    
    public function getCityList(){
		$fk_state = $_POST['fk_state'];
		$cityList = $this->CommonModel->getCityList($fk_state);
		$html = '';
		if(!empty($cityList)){
            foreach($cityList as $val){
                $html .= '<option value="'.$val['int_glcode'].'">'.$val['var_title'].'</option>';
			}
		}
        $html .= '<option value="0">Other</option>';
		echo $html;exit;
	}

}
