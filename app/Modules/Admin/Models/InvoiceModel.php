<?php
namespace App\Modules\Admin\Models;
use App\Modules\Admin\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;

class InvoiceModel extends Model{
    public $con = '';
    protected $request;
	public $CommonModel;
    function __construct(){
        $this->con = db_connect();
        $this->request = \Config\Services::request();
        $this->mylibrary = new Mylibrary();
		$this->CommonModel = new CommonModel();
    }
    
    /* list data for the invoice Start */
    public function getData($rowno, $rowperpage, $filter="", $_field='i.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_invoice i');
        $builder->select('i.*, cp.var_name as company_name, c.var_displayname as customer_name, p.var_project, ia.var_due_amount, ia.var_invoice_amount');
        $builder->join("mst_company_profile cp","cp.int_glcode = i.fk_profile ","left");
        $builder->join("mst_invoice_amount ia","ia.fk_invoice = i.int_glcode ","left");
        $builder->join("mst_customer c","c.int_glcode = i.fk_customer","left");
        $builder->join("mst_project p","p.int_glcode = i.fk_project  ","left");
        $builder->where('i.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
			$builder->Like('i.var_Invoice_id',$filter);
			$builder->orLike('i.var_invoice_date',$filter);
            $builder->orLike('i.var_subject',$filter);
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('c.var_displayname',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();
        $data = array();
        if(!empty($row)){
            foreach($row as $val){
                $val['var_due_amount'] = number_format($val['var_due_amount'],2,'.','');
                $val['var_invoice_amount'] = number_format($val['var_invoice_amount'],2,'.','');

                $startTimeStamp = strtotime($val['var_invoice_date']);
                $endTimeStamp = strtotime(date('Y-m-d'));
                $timeDiff = abs($endTimeStamp - $startTimeStamp);
                $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                $numberDays = intval($numberDays);


                if($val['var_due_amount'] == $val['var_invoice_amount']){
                    if($numberDays == 0){
                        $val['var_payment_status'] = "Unpaid (today)";
                    }else{
                        $val['var_payment_status'] = "Unpaid (before ".$numberDays." days.)";
                    }
                }else if($val['var_due_amount'] == 0){
                    $val['var_payment_status'] = "Paid";
                }else{
                    $val['var_payment_status'] = 'Partially Paid';
                }
                $data[] = $val;
            }
        }
        return $data;
    }
    /* list data for the invoice End */

    /* total list data count for the invoice Start */
    public function total_records_count(){
        $builder = $this->con->table('mst_invoice');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    /* total list data count for the invoice End */

    /* total list data count with filter for the invoice Start */
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_invoice i');
		$builder->select('count(i.int_glcode) as total');
        $builder->join("mst_company_profile cp","cp.int_glcode = i.fk_profile ","left");
        $builder->join("mst_customer c","c.int_glcode = i.fk_customer","left");
        $builder->join("mst_project p","p.int_glcode = i.fk_project  ","left");
		if ($filter != '') {
			$builder->groupStart();
			$builder->Like('i.var_Invoice_id',$filter);
			$builder->orLike('i.var_invoice_date',$filter);
            $builder->orLike('i.var_subject',$filter);
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('c.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
		$builder->where('i.chr_delete','N');
		$query = $builder->get();
        $result = $query->getRow();
        return $result->total;
    }
    /* total list data count with filter for the invoice Start */
    
    /* Export CSV of invoice Start */
    public function export_csv(){
        $builder = $this->con->table('mst_invoice');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $builder->orderBy('int_glcode','desc');
        $query =  $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* Export CSV of invoice End */

    /* on key press get the HSN list as per search key Start */
    public function HSNList($var_hsn){
        $builder = $this->con->table('mst_hsn');
        $builder->select('*');
        $builder->like('hsn_code', $var_hsn);
        $builder->orLike('hsn_description',$var_hsn);
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
    /* on key press get the HSN list as per search key End */

    /* Add and update invoice as per id (if id="" then insert else update)  Start */
    function addUpdateRecord($id = ""){
        if (isset($_POST['var_payment_status']) && $_POST['var_payment_status'] == 'on') {
            $var_payment_status = 'Paid';
        }else{
            $var_payment_status = 'Unpaid';
        }
        $fk_project = $this->request->getVar('fk_project');
        $fk_customer = $this->request->getVar('fk_customer');
        $fk_profile = $this->request->getVar('fk_profile');
        $var_final_amount = $this->request->getVar('var_final_amount');

        $InvoiceData = array(
            'var_Invoice_id' => $this->request->getVar('var_Invoice_id'), 
            'fk_project' => $fk_project, 
            'fk_customer' => $fk_customer, 
            'fk_profile' => $fk_profile, 
            'var_invoice_date' => $this->request->getVar('var_invoice_date'), 
            'var_subject' => $this->request->getVar('var_subject'),
            'var_payment_status' => $var_payment_status, 
            'var_customer_note' => $this->request->getVar('var_customer_note'),
            'var_subtotal' => $this->request->getVar('var_subtotal'),
            'var_gst' => $this->request->getVar('var_gst'), 
            'var_adjustment' => $this->request->getVar('var_adjustment'), 
            'var_final_amount' => $var_final_amount, 
            'var_ipaddress' => $_SERVER['REMOTE_ADDR'],
        );
        if($id == ""){
            $this->con->table('mst_invoice')->insert($InvoiceData);
            $insert_user_id = $this->con->insertID();
        }else{
            $this->CommonModel->updateRows('mst_invoice',$InvoiceData, array('int_glcode'=>$id));
            $insert_user_id = $id;
        }
        if($insert_user_id > 0){
            $var_item = $this->request->getVar('var_item');
            if(!empty($var_item)){
                foreach($var_item as $key => $value){
                    $incItemData = array(
                        'fk_project' => $fk_project,
                        'fk_invoice' => $insert_user_id,
                        'var_item_name' => $value,
                        'var_hsn' => $this->request->getVar('hide_var_hsn')[$key],
                        'var_qty' => $this->request->getVar('var_quantity')[$key],
                        'fk_gst' => $this->request->getVar('fk_tax')[$key],
                        'var_rate' => $this->request->getVar('var_rate')[$key],
                        'var_tax' => $this->request->getVar('var_tax')[$key],
                        'var_amount' => $this->request->getVar('var_total')[$key]+$this->request->getVar('var_tax')[$key],
                    );
                    if(isset($this->request->getVar('invoice_item')[$key])){
                        $this->CommonModel->updateRows('mst_invoice_items',$incItemData, array('int_glcode'=>$this->request->getVar('invoice_item')[$key]));
                    }else{
                        $this->con->table('mst_invoice_items')->insert($incItemData);
                    }
                }
            }
            $invAmountData = array(
                'fk_project' => $this->request->getVar('fk_project'),
                'fk_invoice' => $insert_user_id,
                'var_invoice_amount' => $this->request->getVar('var_final_amount'),
            );
            $invoicePaidAmout = $this->CommonModel->getValById('mst_invoice_amount', 'var_paid_amount', array('fk_invoice' => $id));
            $dueAmount = $var_final_amount - $invoicePaidAmout;
            if($var_payment_status=='Paid'){
                $invAmountData['var_due_amount'] = 0;
                $invAmountData['var_paid_amount'] = $this->request->getVar('var_final_amount');
                /* add received payment is invoice update with paid and due amount is more then 0 start */ 
                if($dueAmount > 0){
                    $this->insertReceivedPayment($fk_project, $fk_profile, $fk_customer, $var_final_amount, $insert_user_id);
                }
                /* add received payment is invoice update with paid and due amount is more then 0 end */ 
            }

            if($id>0){
                if($var_payment_status=='Unpaid'){
                    $invAmountData['var_due_amount'] = $dueAmount;
                }
                $this->CommonModel->updateRows('mst_invoice_amount',$invAmountData, array('fk_invoice'=>$id));    
            }else{
                if($var_payment_status=='Unpaid'){
                    $invAmountData['var_due_amount'] = $this->request->getVar('var_final_amount');
                }
                $this->con->table('mst_invoice_amount')->insert($invAmountData);
            }
            
        }
        return $insert_user_id;
    }
    /* Add and update invoice as per id (if id="" then insert else update)  end */

    public function insertReceivedPayment($fk_project, $fk_profile, $fk_customer, $var_final_amount, $fk_invoice){
        $var_received_id = 'REC_PAY_'.$this->CommonModel->getUniqueAutoId('mst_received_payment', 'var_received_id');
        $rec_pay_data = array(
            'fk_project' => $fk_project,
            'fk_profile' => $fk_profile,
            'fk_customer' => $fk_customer,
            'var_received_amount' => $var_final_amount,
            'var_received_id' => $var_received_id,
            'var_payment_date' => date('Y-m-d'),
            'var_payment_mode' => 'Cash',
            'chr_delete' => 'N',
            'var_payment_type' => 'Customer Advance',
            'var_ipaddress' => $_SERVER['REMOTE_ADDR']
        );  
        $this->con->table('mst_received_payment')->insert($rec_pay_data);
        $receivedId = $this->con->insertID();

        $receivedInvoiceData = array(
            'fk_received_id' => $receivedId,
            'fk_project' => $fk_project,
            'fk_invoice' => $fk_invoice,
            'var_amount' => $var_final_amount,
        );
        $this->con->table('mst_received_invoice')->insert($receivedInvoiceData);
    }
    /* Get invoice data for the edit page Start */
    public function getInvoiceData($int_glcode){
        $builder = $this->con->table('mst_invoice i');
        $builder->select('i.*, mia.var_due_amount, mia.var_paid_amount, mia.var_invoice_amount');
        $builder->join('mst_invoice_amount mia', 'mia.fk_invoice = i.int_glcode', 'left');
        $builder->where('i.int_glcode',$int_glcode);
        $query = $builder->get();
        $rows = $query->getRowArray();
        if(!empty($rows)){
            $rows['invoice_item'] = $this->getInvoiceItems($int_glcode);
            $rows['fk_receivedId'] = $this->getReceivedInvoiceId($int_glcode);
        }
        return $rows;
    }
    /* Get invoice data for the edit page End */

    /* Get invoice data for the view page Start */
    public function viewInvoiceData($int_glcode){
        $builder = $this->con->table('mst_invoice i');
        $builder->select('i.*, mia.var_due_amount, mia.var_paid_amount, mia.var_invoice_amount');
        $builder->join('mst_invoice_amount mia', 'mia.fk_invoice = i.int_glcode', 'left');
        $builder->where('i.int_glcode',$int_glcode);
        $query = $builder->get();
        $rows = $query->getRowArray();
        if(!empty($rows)){
            $rows['companyAddress'] = $this->getCompanyAddress($rows['fk_profile']);
            $rows['customerAddress'] = $this->getCustomerAddress($rows['fk_customer']);
            $rows['invoice_item'] = $this->getInvoiceItems($int_glcode);
            $rows['fk_receivedId'] = $this->getReceivedInvoiceId($int_glcode);
        }
        return $rows;
    }
    /* Get invoice data for the edit page End */

    /* Get invoice Items list Start */
    public function getInvoiceItems($fk_invoice){
        $builder = $this->con->table('mst_invoice_items mii');
        $builder->select('mii.*, mg.var_percent');
        $builder->join('mst_gst mg', 'mg.int_glcode = mii.fk_gst', 'left');
        $builder->where('mii.fk_invoice',$fk_invoice);
        $query = $builder->get();
        $rows = $query->getResultArray();
        return $rows;
    }
    /* Get invoice Items list Start */

    /* check the payment received against the invoice Start */
    public function getReceivedInvoiceId($fk_invoice){
        $builder = $this->con->table('mst_received_invoice');
        $builder->select('fk_received_id');
        $builder->where('fk_invoice', $fk_invoice);
        $query = $builder->get();
        $rows = $query->getRowArray();
        if(!empty($rows)){
            return $rows['fk_received_id'];
        }else{
            return 0;
        }
    }
    /* check the payment received against the invoice End */

    /* Update invoice amount if remove the invoice item row Start */
    public function updateIvoiceAmount($fk_invoice, $itemSubtotal, $itemGst){
        $invoiceData = $this->getInvoiceData($fk_invoice);

        /* Update total amount in mst invoice table Start*/
            $data = array(
                'var_final_amount' => $invoiceData['var_final_amount'] - $itemSubtotal,
                'var_subtotal' => $invoiceData['var_subtotal'] - ($itemSubtotal - $itemGst),
                'var_gst' => $invoiceData['var_gst'] - $itemGst,
            );
            $this->CommonModel->updateRows('mst_invoice',$data, array('int_glcode'=>$fk_invoice));
        /* Update total amount in mst invoice table End*/
        
        /* update the amount in the mst invoice amount Start */
            $invoiceAmountData = $this->CommonModel->getRowArray('mst_invoice_amount','*', array('fk_invoice'=>$fk_invoice));
            $iaData = array(
                'var_invoice_amount' => $invoiceAmountData['var_invoice_amount'] - $itemSubtotal,
                'var_due_amount' => $invoiceAmountData['var_due_amount'] - $itemSubtotal,
            );
            $this->CommonModel->updateRows('mst_invoice_amount',$iaData, array('fk_invoice'=>$fk_invoice));
        /* update the amount in the mst invoice amount End */
        
        /* if ivoice is paid and their payment is received then update the receivable amount Start */
        
        return $this->getInvoiceData($fk_invoice);
    }
    /* Update invoice amount if remove the invoice item row Start */

    /* Get the invoice list array which payment is not completed Start */
    public function getInvoiceArray($fk_project, $fk_customer){
        $builder = $this->con->table('mst_invoice_amount ma');
        $builder->select('ma.*,mi.var_Invoice_id, mi.var_invoice_date');
        $builder->join('mst_invoice mi','mi.int_glcode = ma.fk_invoice');
        if($fk_project > 0){
            $builder->where('ma.fk_project',$fk_project);
        }
        $builder->where('ma.var_due_amount!=',0);
        $builder->where('mi.fk_customer',$fk_customer);
        $query =  $builder->get();
        $row = $query->getResultArray();        
        return $row;
    }
    /* Get the invoice list array which payment is not completed End */

    /* Get the company proifile address Start */
    public function getCompanyAddress($int_glcode){
        $builder = $this->con->table('mst_company_profile mcp');
        $builder->select('mcp.*, mc.var_title as cityName, ms.var_title as stateName, mst_country.var_title as countryName');
        $builder->join('mst_city mc', 'mc.int_glcode = mcp.fk_city', 'left');
        $builder->join('mst_state ms', 'ms.int_glcode = mcp.fk_state', 'left');
        $builder->join('mst_country', 'mst_country.int_glcode = mcp.fk_country', 'left');
        $builder->where('mcp.int_glcode', $int_glcode);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row;
    }
    /* Get the company proifile address End */

    /* Get tjhe cusatomer address Start */
    public function getCustomerAddress($int_glcode){
        $builder = $this->con->table('mst_customer mcp');
        $builder->select('mcp.*, mc.var_title as cityName, ms.var_title as stateName, mst_country.var_title as countryName');
        $builder->join('mst_city mc', 'mc.int_glcode = mcp.fk_city', 'left');
        $builder->join('mst_state ms', 'ms.int_glcode = mcp.fk_state', 'left');
        $builder->join('mst_country', 'mst_country.int_glcode = mcp.fk_country', 'left');
        $builder->where('mcp.int_glcode', $int_glcode);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row;
    }
    /* Get tjhe cusatomer address Start */
    
    /* get the payment received againt the invoice start  */
    public function getPaymentList($fk_invoice){
        $builder = $this->con->table('mst_received_invoice mri');
        $builder->select('mrp.var_payment_date, mrp.var_received_id, mrp.var_payment_type, mrp.var_received_amount, mp.var_project ');
        $builder->join('mst_received_payment mrp', 'mrp.int_glcode = mri.fk_received_id', 'left');
        $builder->join('mst_project mp', 'mp.int_glcode = mri.fk_project', 'left');
        $builder->where('mri.fk_invoice', $fk_invoice);
        $query = $builder->get();
        $row = $query->getResultArray();
        return $row;
    }
    /* get the payment received againt the invoice start  */



}

