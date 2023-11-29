<style>
    .select2-container {
        width: 100% !important;
    }
</style>

<section class="section">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb p-0">
					<li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></li>
					<li class="breadcrumb-item active " aria-current="page"><a href="<?= base_url() ?>/admin/receivedPayment">View Received Payment</a></li>
					<li class="breadcrumb-item">Apply to Invoice</li>
				</ol>
			</nav>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4>Apply to Invoice</h4>
                    <?php if($totalCredit > 0){ ?>
					    <p class="mb-0 text-muted tx-13">Available Credit :- <?= CURRENCY_ICON.' '.$totalCredit ?></p>
                    <?php } ?>
				</div>
				<div class="card-body">
                    <form action="javascript:void(0)" id="applyToInvoice" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="var_payment_type" id="var_payment_type" value="Invoice Payment">
                        <div class="col-12">
                            <h6>Unpaid Invoices</h6> 
                            <div class="table-responsive">
                                <table id="mainTable" class="table table-striped received-table">
                                    <thead>
                                        <tr>
                                            <th>Invoice Id</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice Amount (<?= CURRENCY_ICON ?>)</th>
                                            <th>Paid Amount (<?= CURRENCY_ICON ?>)</th>
                                            <th>Due Amount (<?= CURRENCY_ICON ?>)</th>
                                            <th>Payment (<?= CURRENCY_ICON ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="addinvoicelist">
                                        <?php $var_due_amount = $payment = 0;
                                        if(!empty($invoice)){
                                            foreach($invoice as $val){
                                                $var_due_amount += $val["var_due_amount"];
                                                $payment += $val['is_selected'];
                                                ?>
                                                <tr>
                                                    <td><?= $val["var_Invoice_id" ]?></td>
                                                    <td><?= $val["var_invoice_date" ]?></td>
                                                    <td><?= $val["var_invoice_amount" ]?></td>
                                                    <td><?= $val["var_paid_amount" ]?></td>
                                                    <td><?= $val["var_due_amount" ]?></td>
                                                    <td>
                                                        <input class="form-control var_payment" id="var_payment<?= $val['int_glcode'] ?>" name="var_payment[]" type="text" maxlength="15" oninput="checkPaidAmount(<?= $val['int_glcode'] ?>), isNumberKeyWithDot(event);" value="<?= $val['is_selected'] ?>" data-val="<?= $val['is_selected'] ?>">
                                                        <span class="text-danger" id="error_var_payment<?= $val['int_glcode'] ?>"></span>

                                                        <input type ="hidden" id="invoiceItemID<?= $val['int_glcode'] ?>" name="invoiceItemId[]" value="<?= $val['int_glcode'] ?>">
                                                        
                                                        <input type ="hidden" id="receivedInvoiceId<?= $val['int_glcode'] ?>" name="receivedInvoiceId[]" value="<?= $paymentId ?>">

                                                        <input type ="hidden" id="receivedPaymentInvoice<?= $val['int_glcode'] ?>" name="receivedPaymentInvoice[]" value="<?= $val['receivedInvoice_id'] ?>">

                                                        <input type ="hidden" id="invoiceID<?= $val['int_glcode'] ?>" name="invoiceId[]" value="<?= $val['fk_invoice'] ?>">

                                                        <input type ="hidden" id="invoiceDureAmount<?= $val['int_glcode'] ?>" name="invoiceDureAmount[]" value="<?= $val['var_due_amount'] ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="5" class="align-right"><strong>Total Used Credit (<?= CURRENCY_ICON ?>)</strong></td>
                                                <td><span id="totalUsedCredit"><?= $payment ?></span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="align-right"><strong>Total Remaining Credit (<?= CURRENCY_ICON ?>)</strong></td>
                                                <td><span id="totalRemainingCredit"><?= $totalCredit ?></span></td>
                                            </tr>
                                        <?php }else{ ?>
                                            <tr><td colspan="6"></td>No unpaid invoice available.</td></tr>
                                        <?php } ?>
                                        <input type ="hidden" id="total_due_amount" name="total_due_amount" value="<?= $var_due_amount ?>">
                                        <input type ="hidden" id="fk_project" name="fk_project" value="<?= $fk_project ?>">
                                        <input type ="hidden" id="totalCredit" name="totalCredit" value="<?= $totalCredit ?>">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Save</button>
                    </form>
                </div>
			</div>
		</div>
	</div>
</section>
<script>
    var Payment = 0;
    function checkPaidAmount(id){
        var var_payment = parseFloat($("#var_payment"+id).val());
        var invoiceDureAmount = $("#invoiceDureAmount"+id).val();
        var hidden_var_payment = $("#var_payment"+id).data('val');
        var totalCredit = $("#totalCredit").val();
        totalDueAmount = parseFloat(invoiceDureAmount) + parseFloat(hidden_var_payment);
        var totalPayment = 0;
        console.log(var_payment);
        $(".var_payment").each(function (){
            totalPayment+= parseFloat($(this).val()) || 0;
            totalCredit= parseFloat(totalCredit) + parseFloat($(this).data('val')) || 0;
        });
        

        if(parseFloat(var_payment) > parseFloat(totalDueAmount)){
            $("#var_payment"+id).focus();
            $("#error_var_payment"+id).text('Payable amount is not more then '+totalDueAmount+' due amount.');
            $(".submit_save").attr("disabled", true);
        }else if(parseFloat(var_payment) > parseFloat(totalCredit)){
            $("#var_payment"+id).focus();
            $("#error_var_payment"+id).text('Payable amount is not more then total available credit of '+totalCredit +'.');
            $(".submit_save").attr("disabled", true);
        }else if(parseFloat(totalPayment) > parseFloat(totalCredit)){
            iziToast.error({
                title: '',
                message: 'Total payable amount is not more then total available credit '+totalCredit +'.',
                position: 'topRight'
            });
            $(".submit_save").attr("disabled", true);
        }else{
            var totalRemainingCredit = parseFloat(totalCredit) - parseFloat(totalPayment);
            $("#totalRemainingCredit").text(parseFloat(totalRemainingCredit).toFixed(2));
            $("#totalUsedCredit").text(totalPayment.toFixed(2));

            $(".submit_save").attr("disabled", false);
            $("#error_var_payment"+id).text('');
        }
    }

	
	$(document).on('submit','#applyToInvoice', function (e) {
        e.preventDefault();
        var totalPayment = 0;
        var totalCredit = $("#totalCredit").val();
        $(".var_payment").each(function (){
            totalPayment+= parseFloat($(this).val()) || 0;
            totalCredit= parseFloat(totalCredit) +parseFloat($(this).data('val')) || 0;
        });
        if(parseFloat(totalPayment) > parseFloat(totalCredit)){
            iziToast.error({
                title: '',
                message: 'Total payable amount is not more then total available credit '+totalCredit+'.',
                position: 'topRight'
            });
            $(".submit_save").attr("disabled", "disabled");
        }else {
            $.ajax({
                type: 'post',
                url: sitepath+'/admin/receivedPayment/paymentApplyToInvoice',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submit_save').attr("disabled","disabled");
                    $('#applyToInvoice').css("opacity",".5");
                },	
                success: function(response){
                    if(response.status > 0){
                        window.location.href = sitepath+"/admin/receivedPayment";
                    }else{
                        $('#applyToInvoice').css("opacity","");
                        $(".submit_save").removeAttr("disabled");
                    }
                }
            });
        }
	});
    
</script>