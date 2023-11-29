<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">View Invoice</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Invoice Management</h4>
                    <div class="card-header-form">
                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/invoice/exportCSV">Export Invoices</a>
                        <a class="btn btn-primary text-right" href="<?= base_url() ?>/admin/invoice/addInvoice">Add Invoice</a>
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
                        <table class="table table-striped" id="viewInvoice">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th><a href="javascript:void(0);" field="i.var_Invoice_id" class="_sort">Invoice Id</a></th>
                                    <th><a href="javascript:void(0);" field="c.var_name" class="_sort">Customer Name<a></th>
                                    <th><a href="javascript:void(0);" field="p.var_project" class="_sort">Project Name</a></th>
                                    <th>Payment Status</th>
                                    <th><a href="javascript:void(0);" field="p.var_invoice_date" class="_sort">Due Date</a></th>
                                    <th>Invoice Amount</th>
                                    <th>Balance Due</th>
                                    <th>Option</th>
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
                                            <td><a href="<?= base_url() ?>/admin/invoice/viewInvoice/<?= base64_encode($value['int_glcode']) ?>"><?= $value['var_Invoice_id']?></a></td>
                                            <td><?= $value['customer_name']?></td>
                                            <td><?= $value['var_project']?></td>
                                            <td><?= $value['var_payment_status'] ?></td>
                                            <td><?= $value['var_invoice_date'] ?></td>
                                            <td><?= CURRENCY_ICON.$value['var_invoice_amount']?></td>
                                            <td><?= CURRENCY_ICON.$value['var_due_amount']?></td>
                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option"></button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/invoice/editInvoice/<?= base64_encode($value['int_glcode']) ?>"><i class="far fa-edit"></i> Edit</a>
                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/invoice/pdfViewer/<?= base64_encode($value['int_glcode']) ?>" target="_blank"><i class="fas fa-print"></i>Print/PDF</a>
                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/invoice/recordPayment/<?= base64_encode($value['int_glcode']) ?>" target="_blank"><i class="fas fa-list"></i>Record Payments</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="hfield" value="i.int_glcode">
                <input type="hidden" name="hsort" value="desc">
                <input type="hidden" name="hpageno" value="0">
                <input type="hidden" name="module" id="module" value="invoice">
                <div class="card-footer custom_header">
                    <div>
                        <div class="page-limit page_pagination">
                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>
                        </div>
                        <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
                    </div>
                    <div id="pagination">
                        <?php echo $pager_links;?>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</section>
<script src= "<?= base_url() ?>/public/assets/dist/js/invoice.js"></script>
