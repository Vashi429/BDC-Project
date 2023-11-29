<?php
namespace App\Modules\Admin\Models;
use App\Modules\Admin\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
class ReceivedPaymentModel extends Model{
    public $con = '';
	public $CommonModel;
    protected $request;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
		$this->CommonModel = new CommonModel();
    }

    /* list data for the received payments Start */
    public function getData($rowno, $rowperpage, $filter="", $_field='mrp.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_received_payment mrp');
        $builder->select('mrp.*, mp.var_project, mcp.var_name, mc.var_displayname as customer_name');
        $builder->join('mst_project mp','mp.int_glcode = mrp.fk_project','left');
        $builder->join('mst_company_profile mcp','mcp.int_glcode = mrp.fk_profile','left');
        $builder->join('mst_customer mc','mc.int_glcode = mrp.fk_customer','left');
        $builder->where('mrp.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
                $builder->like('mp.var_project',$filter);
                $builder->orLike('mcp.var_name',$filter);
                $builder->orLike('mc.var_displayname',$filter);
                $builder->orLike('mrp.var_received_id',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $totalCredit = $this->totalUsedCreditOfPayment($val['int_glcode']);
                $val['unusedAmount'] = $val['var_received_amount'] - $totalCredit;
                $data[] = $val;
            }
        }
        return $data;
    }
    /* list data for the received payments end */

    /* total list data count for the received payments Start */
    public function total_records_count(){
        $builder = $this->con->table('mst_received_payment');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    /* total list data count for the received payments End */

    /* total list data count with filter for the received payments Start */
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_received_payment mrp');
		$builder->select('count(mrp.int_glcode) as total');
        $builder->join('mst_project mp','mp.int_glcode = mrp.fk_project','left');
        $builder->join('mst_company_profile mcp','mcp.int_glcode = mrp.fk_profile','left');
        $builder->join('mst_customer mc','mc.int_glcode = mrp.fk_customer','left');
        $builder->where('mrp.chr_delete',"N");
		if ($filter != '') {
			$builder->groupStart();
                $builder->like('mp.var_project',$filter);
                $builder->orLike('mcp.var_name',$filter);
                $builder->orLike('mc.var_displayname',$filter);
                $builder->orLike('mrp.var_received_id',$filter);
			$builder->groupEnd();
		}
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }
    /* total list data count with filter for the received payments End */

    /* get iinvoice list which is not paid start */
    public function getInvoiceArray($fk_project, $fk_customer, $id){
        
        $builder = $this->con->table('mst_invoice_amount ma');
        $builder->select('ma.*,mi.var_Invoice_id, mi.var_invoice_date');
        $builder->join('mst_invoice mi','mi.int_glcode = ma.fk_invoice');
        if($id == 0){
            $builder->where('ma.var_due_amount !=',0);
        // }else{
        //     $builder->groupStart();
        //     $builder->where('ma.fk_invoice', $id);
        //     $builder->orWhere('ma.var_due_amount!=',0);
        //     $builder->groupEnd();

        }
        if($fk_project > 0){
            $builder->where('ma.fk_project',$fk_project);
        }
        $builder->where('mi.fk_customer',$fk_customer);
        $query =  $builder->get();
        // echo $this->con->getLastQuery();die();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $val['is_selected'] = $val['receivedInvoice_id'] = 0;
                if($id > 0){
                    $checkIsReceived = $this->checkIsReceivedInvoice($val['fk_invoice'], $id);
                    if(!empty($checkIsReceived)){
                        $val['is_selected'] = $checkIsReceived['var_amount'];
                        $val['receivedInvoice_id'] = $checkIsReceived['int_glcode'];
                    }
                }
                $data[] = $val;
            }
        }
        return $data;
    }
    /* get iinvoice list which is not paid end */

    /* check if the ivoice is received or not for the update invoice start */
    public function checkIsReceivedInvoice($fk_invoice, $id){
        $builder = $this->con->table('mst_received_invoice');
        $builder->select('int_glcode, var_amount');
        $builder->where('fk_invoice',$fk_invoice);
        $builder->where('fk_received_id',$id);
        $query =  $builder->get();
        $row = $query->getRowArray();
        return $row;
    }
    /* check if the ivoice is received or not for the update invoice end */

    /* checck invoce which due amount is 0 start */
    public function checkInvoice($fk_invoice){
        $builder = $this->con->table('mst_invoice_amount ma');
        $builder->select('ma.*');
        $builder->join('mst_invoice mi','mi.int_glcode = ma.fk_invoice');
        $builder->where('ma.var_due_amount',0);
        $builder->where('ma.fk_invoice',$fk_invoice);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* checck invoce which due amount is 0 end */

    /* get customer project list start */
    public function getProjects($fk_customer){
        $builder = $this->con->table('mst_project mp');
        $builder->select('mp.*');
        $builder->where('mp.fk_customer',$fk_customer);
        $builder->where('mp.chr_delete','N');
        $builder->where('mp.chr_publish','Y');
        $builder->orderBy('mp.var_project', 'ASC');
        $builder->groupBy('mp.int_glcode');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* get customer project list end */

    /* get customer company profile which invoice is create start */
    public function getCompanyProfile($fk_customer){
        $builder = $this->con->table('mst_invoice mi');
        $builder->select('mcp.*');
        $builder->join('mst_company_profile mcp','mcp.int_glcode = mi.fk_profile','lefy');
        $builder->where('mi.fk_customer',$fk_customer);
        $builder->where('mi.chr_delete','N');
        $builder->orderBy('mcp.var_name', 'ASC');
        $builder->groupBy('mcp.int_glcode');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* get customer company profile which invoice is create end */

    /* insert received payment start */
    public function addRecord(){
        $rp_data = array(
            'var_received_id' => $_POST['var_received_id'],
            'fk_project' => $_POST['fk_project'],
            'fk_profile' => $_POST['fk_profile'],
            'fk_customer' => $_POST['fk_customer'],
            'var_payment_type' => $_POST['var_payment_type'],
            'var_received_amount' => $_POST['var_received_amount'],
            'var_payment_date' => $_POST['var_payment_date'],
            'chr_delete' => 'N',
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        if($_POST['var_payment_type']=='Customer Advance'){
            $rp_data['var_payment_mode'] = $_POST['var_payment_mode'];
            if($_POST['var_payment_mode']=='Cheque'){
                $rp_data['var_cheque_no'] = $_POST['var_payment_mode'];
            }
        }
        $this->con->table('mst_received_payment')->insert($rp_data);
        $fk_received_id = $this->con->insertID();

        if($_POST['var_payment_type']=='Invoice Payment'){
            if(!empty($_POST['invoiceItemId'])){
                foreach($_POST['invoiceItemId'] as $key => $val){
                    if($_POST['var_payment'][$key] > 0){
                        $ri_data = array(
                            'fk_project' => $_POST['fk_project'],
                            'fk_invoice' => $_POST['invoiceId'][$key],
                            'fk_received_id' => $fk_received_id,
                            'var_amount' => $_POST['var_payment'][$key],
                        );
                        $this->con->table('mst_received_invoice')->insert($ri_data);
                        // $fk_received_id = $this->con->insertID();

                        $ia_data = $this->CommonModel->getRowArray('mst_invoice_amount','var_due_amount,var_paid_amount', array('int_glcode' => $_POST['invoiceItemId'][$key]));
                        $InvoiceData = array(
                            'var_due_amount' =>  $ia_data['var_due_amount']  - $_POST['var_payment'][$key],
                            'var_paid_amount' => $ia_data['var_paid_amount'] + $_POST['var_payment'][$key],
                        );
                        $this->CommonModel->updateRows('mst_invoice_amount', $InvoiceData, array('int_glcode'=>$_POST['invoiceItemId'][$key]));
                    }
                    $ispaidInvoce = $this->checkInvoice($_POST['invoiceId'][$key]);
                    if(!empty($ispaidInvoce)){
                        $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Paid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                    }else{
                        $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Unpaid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                    }
                }
            }
        } 
        return $fk_received_id;
    }
    /* insert received payment end */

    /* update received payment start */
    public function updateRecord($id){
       
        $rp_data = array(
            'var_received_id' => $_POST['var_received_id'],
            'fk_project' => $_POST['fk_project'],
            'fk_profile' => $_POST['fk_profile'],
            'fk_customer' => $_POST['fk_customer'],
            'var_payment_type' => $_POST['var_payment_type'],
            'var_received_amount' => $_POST['var_received_amount'],
            'var_payment_date' => $_POST['var_payment_date'],
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );
        if($_POST['var_payment_type']=='Customer Advance'){
            $rp_data['var_payment_mode'] = $_POST['var_payment_mode'];
            if($_POST['var_payment_mode']=='Cheque'){
                $rp_data['var_cheque_no'] = $_POST['var_payment_mode'];
            }
        }
        if($id > 0){
            $this->CommonModel->updateRows('mst_received_payment', $rp_data, array('int_glcode'=>$id));
            $fk_received_id = $id;
        }else{
            $rp_data['chr_delete'] = 'N';
            $this->con->table('mst_received_payment')->insert($rp_data);
            $fk_received_id = $this->con->insertID();
        }

        if($_POST['var_payment_type']=='Invoice Payment'){
            if(!empty($_POST['invoiceItemId'])){
                foreach($_POST['invoiceItemId'] as $key => $val){
                    if($_POST['var_payment'][$key] > 0){
                        $ri_data = array(
                            'fk_project' => $_POST['fk_project'],
                            'fk_invoice' => $_POST['invoiceId'][$key],
                            'fk_received_id' => $fk_received_id,
                            'var_amount' => $_POST['var_payment'][$key],
                        );
                        $receiveInvoiceId = $_POST['receivedInvoiceItemId'][$key];

                        $ia_data = $this->CommonModel->getRowArray('mst_invoice_amount','var_due_amount,var_paid_amount', array('int_glcode' => $_POST['invoiceItemId'][$key]));

                        $prevoiusAmount = $this->CommonModel->getValById('mst_received_invoice', 'var_amount', array('fk_received_id' => $fk_received_id));
                        $InvoiceData = array(
                            'var_due_amount' =>  $ia_data['var_due_amount']  - $_POST['var_payment'][$key] + $prevoiusAmount,
                            'var_paid_amount' => $ia_data['var_paid_amount'] + $_POST['var_payment'][$key] - $prevoiusAmount,
                        );
                        $this->CommonModel->updateRows('mst_invoice_amount', $InvoiceData, array('int_glcode'=>$_POST['invoiceItemId'][$key]));

                        if($receiveInvoiceId > 0){
                            $this->CommonModel->updateRows('mst_received_invoice', $ri_data, array('fk_received_id'=>$receiveInvoiceId));
                        }else{
                            $this->con->table('mst_received_invoice')->insert($ri_data);
                        }
                    }
                    $ispaidInvoce = $this->checkInvoice($_POST['invoiceId'][$key]);
                    if(!empty($ispaidInvoce)){
                        $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Paid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                    }else{
                        $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Unpaid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                    }
                }
            }
        }else if($_POST['var_payment_type']=='Customer Advance'){
            $this->CommonModel->deleteRow('mst_received_invoice', array('fk_received_id' => $fk_received_id));
        } 
        return $fk_received_id;
    }
    /* update received payment end */

    /* get received payment for edit start */
    public function getReceivedPayment($id){
        $builder = $this->con->table('mst_received_payment mrp');
        $builder->select('mrp.*');
        $builder->where('mrp.chr_delete',"N");
        $builder->where('int_glcode',$id);
        $query =  $builder->get();
        $row = $query->getRowArray();
        if(!empty($row)){
            $row['receivedInvoice'] = $this->receivedInvoiceItems($id); 
            
        }
        return $row;
    }
    /* get received payment for edit end */

    /* get received paymet's invoice items for edit start */
    public function receivedInvoiceItems($id){
        $builder = $this->con->table('mst_received_invoice');
        $builder->select('*');
        $builder->where('fk_received_id',$id);
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* get received paymet's invoice items for edit end */

    /* Save applied invoice with the customer advance payment start */
    public function paymentApplyToInvoice(){
        // echo "<pre>";
        // print_r($_POST);die();
        if(!empty($_POST['invoiceItemId'])){
            foreach($_POST['invoiceItemId'] as $key => $val){
                // if($_POST['var_payment'][$key] > 0){
                    $ri_data = array(
                        'fk_project' => $_POST['fk_project'],
                        'fk_invoice' => $_POST['invoiceId'][$key],
                        'fk_received_id' => $_POST['receivedInvoiceId'][$key],
                        'var_amount' => $_POST['var_payment'][$key],
                    );

                    if($_POST['receivedPaymentInvoice'][$key] > 0){
                        $prevoiusAmount = $this->CommonModel->getValById('mst_received_invoice', 'var_amount', array('fk_received_id' => $_POST['receivedInvoiceId'][$key], 'fk_invoice' => $_POST['invoiceId'][$key]));
                        
                        if($_POST['var_payment'][$key] > 0){
                            $this->CommonModel->updateRows('mst_received_invoice',$ri_data, array('int_glcode'=>$_POST['receivedPaymentInvoice'][$key]));
                        }else{
                            $this->CommonModel->deleteRow('mst_received_invoice', array('int_glcode'=>$_POST['receivedPaymentInvoice'][$key]));
                        }
                    }else{
                        $prevoiusAmount = 0;
                        $this->con->table('mst_received_invoice')->insert($ri_data);
                    }
                    
                    $ia_data = $this->CommonModel->getRowArray('mst_invoice_amount','var_due_amount,var_paid_amount', array('int_glcode' => $_POST['invoiceItemId'][$key]));
                    if($_POST['var_payment'][$key]==''){
                        $_POST['var_payment'][$key] = 0;
                    }
                    $InvoiceData = array(
                        'var_due_amount' =>  $ia_data['var_due_amount']  - $_POST['var_payment'][$key] + $prevoiusAmount,
                        'var_paid_amount' => $ia_data['var_paid_amount'] + $_POST['var_payment'][$key] - $prevoiusAmount,
                    );
                   
                    $this->CommonModel->updateRows('mst_invoice_amount', $InvoiceData, array('int_glcode'=>$_POST['invoiceItemId'][$key]));
                // }
                $ispaidInvoce = $this->checkInvoice($_POST['invoiceId'][$key]);
                if(!empty($ispaidInvoce)){
                    $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Paid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                }else{
                    $this->CommonModel->updateRows('mst_invoice',array('var_payment_status' => 'Unpaid'), array('int_glcode'=>$_POST['invoiceId'][$key]));
                }
            }
        }   
        return true;
    }
    
    /* Save applied invoice with the customer advance payment start */

    public function totalUsedCreditOfPayment($id){
        $builder = $this->con->table('mst_received_invoice');
        $builder->select('var_amount');
        $builder->where('fk_received_id',$id);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        $total_amt = 0;
        if(!empty($row)){
            foreach($row as $val){
                $total_amt += $val['var_amount'];
            }
        }
        return $total_amt;
    }
    

}

