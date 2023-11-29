

<section class="section">

    <div class="row">

        <div class="col-12">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>

                    <li class="breadcrumb-item active"  aria-current="page">View Expense</li>

                </ol>

            </nav>

        </div>

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h4>Expense Management</h4>

                    <div class="card-header-form">

                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/expense/exportCSV">Export Expenses</a>

                        <a class="btn btn-primary text-right" href="<?= base_url() ?>/admin/expense/addExpense">Add Expense</a>

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

                        <table class="table table-striped" id="viewExpense">

                            <thead>

                                <tr>

                                    <th class="text-center">

                                        <div class="custom-checkbox custom-checkbox-table custom-control">

                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">

                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>

                                        </div>

                                    </th>

                                    <th>Date</th>

                                    <th><a href="javascript:void(0);" field="i.int_glcode" class="_sort">Id</a></th>

                                    <th><a href="javascript:void(0);" field="v.var_name" class="_sort">Vendor Name<a></th>

                                    <th><a href="javascript:void(0);" field="p.var_project" class="_sort">Project Name</a></th>

                                    <th><a href="javascript:void(0);" field="cp.var_name" class="_sort">Company Profile</a></th>

                                    <th>Expense Amount</th>

                                    <th>Expense Type</th>

                                    <th>Expense Mode</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if(!empty($data)){

                                    foreach ($data as $key => $value) { ?>

                                        <tr id="<?= $value['int_glcode'] ?>">

                                            <td class="p-0 text-center">

                                                <div class="custom-checkbox custom-control">

                                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="ids[]" value="<?= $value['int_glcode'] ?>" id="checkbox-<?= $value['int_glcode'] ?>">

                                                    <label for="checkbox-<?= $value['int_glcode'] ?>" class="custom-control-label">&nbsp;</label>

                                                </div>

                                            </td>  

                                            <td style="width:12%;"><?= $value['dt_createddate'] ?></td>

                                            <td><?= $value['int_glcode']?></td>

                                            <td><?= $value['vendor_name']?></td>

                                            <td><?= $value['var_project']?></td>

                                            <td><?= $value['company_name']?></td>

                                            <td><?= CURRENCY_ICON.$value['var_amount']?></td>

                                            <td><?= $value['var_expense_type'] ?></td>

                                            <td><?= $value['var_expense_mode'] ?></td>

                                            <?php if($value['var_expense_mode'] == 'Challan'){ ?>
                                                <td><div class="badge badge-danger">Unpaid</div></td>
                                            <?php } else if($value['var_expense_mode'] == 'Advance'){ ?>
                                                <td><div class="badge badge-success">Paid</div></td>
                                            <?php } else if($value['var_expense_mode'] == 'Bill'){ ?>
                                                <?php 
                                                    if($value['var_amount'] == $value['total_paid_amount']){
                                                        $status = '<div class="badge badge-success">Paid</div>';
                                                    }else if($value['total_paid_amount'] > 0 && $value['var_amount'] != $value['total_paid_amount']){
                                                        $status = '<div class="badge badge-warning">Partial Paid</div>';
                                                    }else{
                                                        $status = '<div class="badge badge-danger">Unpaid</div>';
                                                    }
                                                ?>
                                                <td><?= $status; ?></td>
                                            <?php } else if($value['var_expense_mode'] == 'Labor Payment'){ ?>
                                                <td><div class="badge badge-success">Paid</div></td>
                                            <?php } ?>
                                        </tr>

                                    <?php }

                                } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <input type="hidden" name="hfield" value="e.int_glcode">

                <input type="hidden" name="hsort" value="desc">

                <input type="hidden" name="hpageno" value="0">

                <input type="hidden" name="module" id="module" value="expense">

                <div class="card-footer custom_header">

                    <div>

                        <div class="page-limit page_pagination">

                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>

                        </div>

                        <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete_expense" id="btn_delete_expense"><i class="icon dripicons-trash color_change"></i>Delete</button>

                    </div>

                    <div id="pagination">

                        <?php echo $pager_links;?>

                    </div>

                  </div>

            </div>

        </div>

    </div>

</section>

<!-- Edit Labot Type Modal with form -->

<div class="modal fade" id="listExpens" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="formModal">Expense List</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body" id="list_expens">



      </div>

    </div>

  </div>

</div>
<script src= "<?= base_url() ?>/public/assets/dist/js/expense.js"></script>
<script>
    
function get_expense(expense_id) {
    $.ajax({
            url: sitepath + '/admin/expense/list_bill_expens/' + expense_id,
            type: 'get',
            success: function (response) {
                $('#listExpens').modal('show');
                $('#list_expens').html(response);
            }
        });
    }
</script>