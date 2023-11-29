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
          <li class="breadcrumb-item">Edit Received Payment</li>
        </ol>
      </nav>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Update Received Payment</h4>
          <p class="mb-0 text-muted tx-13"></p>
        </div>
        <div class="card-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link <?= ($data['var_payment_type'] == 'Invoice Payment' ? 'active' : '') ?>" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="true">Invoice Payment</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($data['var_payment_type'] == 'Customer Advance' ? 'active' : '') ?>" onclick="paymentTypeTab('CA', <?= $data['int_glcode'] ?>)" id="advance-tab" data-toggle="tab" href="#advance" role="tab" aria-controls="advance" aria-selected="false">Customer Advance</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
              <form action="javascript:void(0)" id="ReceivedInvoice" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="var_payment_type" id="var_payment_type" value="<?= $data['var_payment_type'] ?>">
                <div class="form-group row">
                  <label for="fk_customer" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Customer <span class="text-danger">*</span></label>
                  <div class="col-sm-9 col-lg-4">
                    <select class="form-control fk_customer" name="fk_customer" id="fk_customer" onchange="getProjects(), addinvoicelist(), getCompanyProfile()" required>
                      <option value=""></option>
                      <?php if (!empty($customerData)) {
                        foreach ($customerData as $value) {
                          $selected = "";
                          if($data['fk_customer']==$value['int_glcode']){
                            $selected = 'selected';
                          } ?>
                          <option value="<?= $value['int_glcode'] ?>" <?= $selected ?>><?= $value['var_displayname'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="fk_project" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Project</label>
                  <div class="col-sm-9 col-lg-4">
                    <select class="form-control fk_project" name="fk_project" id="fk_project" onchange="addinvoicelist()">
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="fk_profile" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Company Profile <span class="text-danger">*</span></label>
                  <div class="col-sm-9 col-lg-4">
                    <select class="form-control fk_profile" name="fk_profile" id="fk_profile" required>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="var_received_id" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Payment Id <span class="text-danger">*</span></label>
                  <div class="col-sm-9 col-lg-4">
                    <input type="text" readonly name="var_received_id" id="var_received_id" class="form-control" value="<?= $data['var_received_id'] ?>" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="var_payment_date" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Date <span class="text-danger">*</span></label>
                  <div class="col-sm-9 col-lg-4">
                    <input type="date" name="var_payment_date" id="var_payment_date" class="form-control datepicker" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="var_received_amount" class="col-sm-3 col-lg-2 col-form-label d-flex align-items-center">Received Amount <span class="text-danger">*</span></label>
                  <div class="col-sm-9 col-lg-4">
                    <input type="text" name="var_received_amount" id="var_received_amount" class="form-control" required oninput="checkTotalPaidAmount(), isNumberKeyWithDot(event);">
                    <span class="text-danger" id="error_var_received_amount"></span>
                  </div>
                </div>
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
                      <tbody class="addinvoicelist"><input type="hidden" id="total_due_amount" name="total_due_amount" value="0"></tbody>
                    </table>
                  </div>
                </div>
                <button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Save</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="<?= base_url() ?>/public/assets/dist/js/addReceivedPayment.js"></script>
<script>
  var var_payment_type = '<?= $data['var_payment_type'] ?>';
  var id = '<?= $data['int_glcode'] ?>';
  $(document).ready(function(){
    if(var_payment_type=='Customer Advance'){
      paymentTypeTab('CA', id);
    }else{
      paymentTypeTab('IP', id, function() {
          addinvoicelist(id);
      });
    }
  });

  $("#invoice-tab").on('click', function(){
    paymentTypeTab('IP', id, function() {
        addinvoicelist(id);
    });
  })

  $(document).on('submit', '#ReceivedInvoice', function(e) {
    e.preventDefault();
    var sum = 0;
    $('.var_payment').each(function(){
        sum += parseFloat(this.value);
    });
    var var_received_amount = $('#var_received_amount').val();
    if(parseFloat(var_received_amount) != parseFloat(sum)){
      iziToast.error({
        title: '',
        message: 'Received amount is not matched with the total payment.',
        position: 'topRight'
      });
      $('.submit_save').attr("disabled", "disabled");
      return false;
    } else {
      $.ajax({
        type: 'post',
        url: sitepath + '/admin/receivedPayment/updateRecord/'+id,
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          $('.submit_save').attr("disabled", "disabled");
          $('#ReceivedInvoice').css("opacity", ".5");
        },
        success: function(response) {
          if (response.status > 0) {
            window.location.href = sitepath + "/admin/receivedPayment";
          } else {
            $('#ReceivedInvoice').css("opacity", "");
            $(".submit_save").removeAttr("disabled");
          }
        }
      });
    }
  });

  $(document).on('submit', '#ReceivedAdvance', function(e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: sitepath + '/admin/receivedPayment/updateRecord/'+id,
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        $('.submit_save').attr("disabled", "disabled");
        $('#ReceivedAdvance').css("opacity", ".5");
      },
      success: function(response) {
        if (response.status > 0) {
          window.location.href = sitepath + "/admin/receivedPayment";
        } else {
          $('#ReceivedAdvance').css("opacity", "");
          $(".submit_save").removeAttr("disabled");
        }
      }
    });
  });
</script>