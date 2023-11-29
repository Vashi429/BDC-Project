<?php
namespace App\Modules\Admin\Models;
use App\Modules\Admin\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Mylibrary; // Import library
use CodeIgniter\Model;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;

class ExpenseModel extends Model{
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
    public function getData($rowno, $rowperpage, $filter="", $_field='e.int_glcode', $_sort="desc"){
        $builder = $this->con->table('mst_expense e');
        $builder->select('e.*,DATE_FORMAT(e.dt_createddate,"%d-%m-%Y") as dt_createddate, cp.var_name as company_name, v.var_name as vendor_name, p.var_project');
        $builder->join("mst_project p","p.int_glcode = e.fk_project  ","left");
        $builder->join("mst_company_profile cp","cp.int_glcode = p.fk_profile ","left");
        $builder->join("mst_vendor v","v.int_glcode = e.fk_vendor","left");
        $builder->where('e.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
        $builder->orderBy($_field,$_sort);
        $builder->limit($rowperpage , $rowno);
        $query =  $builder->get();
        $row = $query->getResultArray();

        $res = array();
		foreach ($row as $key => $value) {
            $exp = $this->con->table('mst_expence_bill meb');
            $exp->select('SUM(meb.var_amount) as total_paid_amount');
            $exp->where('meb.fk_expense',$value['int_glcode']);
            $query_exp =  $exp->get();
            $res_exp = $query_exp->getResultArray();
            $total_paid_amount = 0;
            if($res_exp[0]['total_paid_amount'] != ''){
                $total_paid_amount = $res_exp[0]['total_paid_amount'];
            }

			$value['total_paid_amount'] = $total_paid_amount;
            $value['dt_createddate'] = dateformate($value['dt_createddate']);
			
            $res[] = $value;
		}
        return $res;
    }
    /* list data for the invoice End */

    /* total list data count for the invoice Start */
    public function total_records_count(){
        $builder = $this->con->table('mst_expense');
        $builder->select('*');
        $builder->where('chr_delete',"N");
        $query =  $builder->get();
        return $query->getNumRows();
    }
    /* total list data count for the invoice End */

    /* total list data count with filter for the invoice Start */
    public function filter_records_count($filter = ''){
		$builder = $this->con->table('mst_expense e');
        $builder->select('count(e.int_glcode) as total');
        $builder->join("mst_project p","p.int_glcode = e.fk_project  ","left");
        $builder->join("mst_company_profile cp","cp.int_glcode = p.fk_profile ","left");
        $builder->join("mst_vendor v","v.int_glcode = e.fk_vendor","left");
        $builder->where('e.chr_delete',"N");
        if ($filter != '') {
			$builder->groupStart();
            $builder->orLike('cp.var_name',$filter);
            $builder->orLike('v.var_name',$filter);
            $builder->orLike('p.var_project',$filter);
			$builder->groupEnd();
		}
		$builder->where('e.chr_delete','N');
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
    function addUpdateRecord($id=""){
        $check_type = $this->request->getVar('radio1');
        if($check_type == 'Against to bill'){
            $bill_ids = $this->request->getVar('ids');
            $bill_amount = $this->request->getVar('amount');
            if(count($bill_ids) > 0){
                $i = 0;
                foreach ($bill_ids as $val) {
                    $builder = $this->con->table('mst_expence_bill');
                    $builder->select('fk_expense');
                    $builder->where('fk_bill', $val);
                    $query = $builder->get();
                    $rows = $query->getRowArray();

                    $builders = $this->con->table('mst_bill_due');
                    $builders->select('var_amount,var_due_amount,var_paid_amount,int_glcode');
                    $builders->where('fk_bill', $val);
                    $query = $builders->get();
                    $rows_bill = $query->getRowArray();
                    if(count($rows) > 0 && count($rows_bill) > 0 && $bill_amount[$i] > 0){
                        $InvoiceDatas = array( 
                            'fk_expense' => $rows['fk_expense'], 
                            'fk_project' => $this->request->getVar('fk_project'), 
                            'fk_bill' => $val, 
                            'fk_challan' => '0',
                            'var_amount' => $bill_amount[$i],
                            'var_bill_expense_date' => $this->request->getVar('var_expense_date'),
                            'var_expense_desription' => $this->request->getVar('var_expense_desription'),
                        );
                        $this->con->table('mst_expence_bill')->insert($InvoiceDatas);
                        $insert_user_id = $this->con->insertID();
                        if($insert_user_id){
                            $var_amount = $rows_bill['var_amount'];
                            $var_due_amount = $rows_bill['var_due_amount'];
                            $var_paid_amount = $rows_bill['var_paid_amount'];
                            $int_glcode = $rows_bill['int_glcode'];
                            $data = array(
                                'var_amount' => $var_amount,
                                'var_due_amount' => $var_due_amount - $bill_amount[$i],
                                'var_paid_amount' => $var_paid_amount + $bill_amount[$i],
                            );
                            $this->CommonModel->updateRows('mst_bill_due',$data, array('int_glcode'=>$int_glcode));
                        }  
                    }       
                    $i = $i + 1;         
                }
            }    
            return 1;        
        }else if($check_type == 'Advance'){
            $var_expense_type = $this->request->getVar('var_expense_type');
                $InvoiceData = array( 
                    'fk_profile' => $this->request->getVar('fk_profile'), 
                    'fk_project' => $this->request->getVar('fk_project'), 
                    'var_expense_date' => $this->request->getVar('var_expense_date'), 
                    'var_expense_type' => $this->request->getVar('var_expense_type'),
                    'fk_vendor' => $this->request->getVar('fk_vendor'), 
                    'var_payment_mode' => $this->request->getVar('var_payment_mode'),
                    'var_cheque_number' => $this->request->getVar('var_cheque_number'),
                    'var_amount' => $this->request->getVar('fk_expense'),
                    'var_expense_mode' => $this->request->getVar('radio1'),
                    'var_against_to_bill' => 'N',
                    'var_expense_desription' => $this->request->getVar('var_expense_desription'),
                    'var_ipaddress' => $_SERVER['REMOTE_ADDR']
                );
                $InvoiceData['dt_createddate'] = date('Y-m-d H:i:s');
                $InvoiceData['chr_delete'] = 'N';
                $this->con->table('mst_expense')->insert($InvoiceData);
                $insert_user_id = $this->con->insertID();
                return $insert_user_id;
        }else if($check_type == 'Labour'){
            $fk_profile = $this->request->getVar('fk_profile');
            $fk_project = $this->request->getVar('fk_project');
            $var_expense_date = $this->request->getVar('var_expense_date');
            $var_payment_mode = $this->request->getVar('var_payment_mode');
            $var_expense_desription = $this->request->getVar('var_expense_desription');
            $fk_expense = $this->request->getVar('fk_expense_adv');
            $var_cheque_number = $this->request->getVar('var_cheque_number');
            $ids_labour = $this->request->getVar('ids_labour');
            $amount_labour = $this->request->getVar('amount_labour');
            $i = 0;
            $InvoiceData = array( 
                'fk_profile' => $fk_profile, 
                'fk_project' => $fk_project, 
                'var_expense_date' => $var_expense_date, 
                'var_expense_type' => 'Labor',
                'var_payment_mode' => $var_payment_mode,
                'var_cheque_number' => $var_cheque_number,
                'var_amount' => $fk_expense,
                'var_expense_mode' => 'Labor Payment',
                'var_against_to_bill' => 'N',
                'var_expense_desription' => $var_expense_desription,
                'var_ipaddress' => $_SERVER['REMOTE_ADDR']
            );
            $InvoiceData['dt_createddate'] = date('Y-m-d H:i:s');
            $InvoiceData['chr_delete'] = 'N';
            $this->con->table('mst_expense')->insert($InvoiceData);
            $insert_id_labor = $this->con->insertID();

            foreach($ids_labour as $val){
                $amount = $amount_labour[$i];
                if($amount > 0){
                    $InvoiceDataLabor = array( 
                        'fk_project' => $fk_project, 
                        'fk_expense' => $insert_id_labor, 
                        'fk_labour_assignment' => $val,
                        'var_amount' => $amount,
                        'var_expense_desription' => $var_expense_desription,
                        'var_bill_expense_date' => $var_expense_date,
                    );
                    $this->con->table('mst_expence_labour')->insert($InvoiceDataLabor);
                    
                    $labor_assignment = $this->con->table('mst_labor_assignment');
                    $labor_assignment->select('*');
                    $labor_assignment->where('int_glcode', $val);
                    $query = $labor_assignment->get();
                    $rows_labor = $query->getRowArray();

                    if(count($rows_labor) > 0){
                        $var_amount = $rows_labor['var_amount'];
                        $var_paid_amount = $rows_labor['var_paid_amount'];
                        $var_due_amount = $rows_labor['var_due_amount'];

                        $final_var_due_amount = $var_due_amount - $amount;
                        $final_var_paid_amount = $var_paid_amount + $amount;

                        $data = array(
                            'var_amount' => $var_amount,
                            'var_due_amount' => $final_var_due_amount,
                            'var_paid_amount' => $final_var_paid_amount,
                        );
                        $this->CommonModel->updateRows('mst_labor_assignment',$data, array('int_glcode'=>$val));
                    }

                }
                $i++;
            }
            return $insert_id_labor;
        }
        
    }
    /* Add and update invoice as per id (if id="" then insert else update)  End */

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


}

