<section class="section">
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Received Payment</li>
        </ol>
      </nav>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Received Payment Management</h4>
          <div class="card-header-form">
            <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/receivedPayment/exportCSV">Export Received Payment</a>
            <a class="btn btn-primary text-right" href="<?= base_url() ?>/admin/receivedPayment/addReceivedPayment">Add Received Payment</a>
          </div>
        </div>
        <div class="card-header custom_header">
          <div class="dataTables_length">
            <label>
              Show:
              <select name="dp_entries" class="form-control">
                <option value="10" selected="">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              entries
            </label>
          </div>
          <div class="card-header-form">
            <form>
              <div class="input-group">
                <input type="search" class="form-control" placeholder="Search" id="search" name="search">
                <div class="input-group-btn">
                  <button class="btn btn-primary" id="searchbtn"><i class="fas fa-search"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped" id="viewReceivedPayment">
              <thead>
                <tr>
                  <th class="text-center">
                    <div class="custom-checkbox custom-checkbox-table custom-control">
                      <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                      <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                    </div>
                  </th>
                  <th>Received ID</th>
                  <th>Project Name</a></th>
                  <th>Company Profile</a></th>
                  <th>Customer Name</a></th>
                  <th>Received Amount</a></th>
                  <th>Remaining Amount</a></th>
                  <th>Payment Date</a></th>
                  <th>Payment Mode</a></th>
                  <th class="text-center">Option</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($data)) {
                  foreach ($data as $key => $value) {
                  ?>
                    <tr id="<?= $value['int_glcode'] ?>">
                      <td class="p-0 text-center">
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-<?= $value['int_glcode'] ?>" name="ids[]" value="<?= $value['int_glcode'] ?>" >
                          <label for="checkbox-<?= $value['int_glcode'] ?>" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td><a href="<?= base_url() ?>/admin/receivedPayment/editReceivedPayment/<?= base64_encode($value['int_glcode']) ?>"><?= $value['var_received_id'] ?></a></td>
                      <td><?= $value['var_project'] ?></td>
                      <td><?= $value['var_name'] ?></td>
                      <td><?= $value['customer_name'] ?></td>
                      <td><?= CURRENCY_ICON.$value['var_received_amount'] ?></td>
                      <td><?= CURRENCY_ICON.$value['unusedAmount'] ?></td>
                      <td><?= $value['var_payment_date'] ?></td>
                      <td><?= $value['var_payment_type'] ?></td>
                      <th class="text-center">
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option"></button>
                          <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/receivedPayment/editReceivedPayment/<?= base64_encode($value['int_glcode']) ?>"><i class="fas fa-edit"></i> Edit</a>
                            <!-- <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/receivedPayment/editReceivedPayment/<?= base64_encode($value['int_glcode']) ?>"><i class="fas fa-list"></i> View</a> -->
                            <?php if($value['var_payment_type']=='Customer Advance'){ ?>
                              <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/receivedPayment/applyToInvoice/<?= base64_encode($value['int_glcode']) ?>"><i class="fas fa-list"></i> apply To Invoice</a>
                            <?php } ?>
                          </div>
                        </div>
                      </th>
                    </tr>
                <?php }
                } ?>
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" name="hfield" value="int_glcode">
        <input type="hidden" name="hsort" value="desc">
        <input type="hidden" name="hpageno" value="0">
        <input type="hidden" name="module" id="module" value="receivedPayment">
        <div class="card-footer custom_header">
          <div>
            <div class="page-limit page_pagination">
              <label id="showing_"><?php echo 'Showing 1 to ' . count($data) . ' of ' . $total_data . ' entries'; ?></label>
            </div>
            <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
          </div>
          <div id="pagination">
          <?php echo $pager_links; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="<?= base_url() ?>/public/assets/dist/js/receivedPayment.js"></script>