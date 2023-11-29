
<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/invoice">Invoice List</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">View Invoice</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-color1">
                    <h4>Invoice Management</h4>
                    <div class="card-header-form">
                        <div class="float-lg-left mb-lg-0 mb-3">
                            <a class="btn btn-primary btn-icon icon-left" href="<?= base_url() ?>/admin/invoice/editInvoice/<?= base64_encode($data['int_glcode']) ?>"><i class="fas fa-edit"></i> Edit</a>
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-envelope"></i> Send</button>
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-share"></i> Share</button>
                            <a class="btn btn-danger btn-icon icon-left" href="<?= base_url() ?>/admin/invoice/pdfViewer/<?= base64_encode($data['int_glcode']) ?>" target="_blank"><i class="fas fa-print"></i> PDF/Print</a>
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-email"></i> Record Payment</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <div class="invoice-number pb-2">
                                    <span class="float-lg-right text-right">INVOICE #<?= $data['var_Invoice_id'] ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row border-bottom_1">
                                <div class="col-md-6">
                                    <address>
                                        <strong class="brand-color1">Billed To:</strong><br>
                                        <?= $data['companyAddress']['var_name'] ?><br>
                                        <?= ($data['companyAddress']['var_address']!=""?$data['companyAddress']['var_address'].',<br>':''); ?>
                                        <?= ($data['companyAddress']['cityName']!=""?$data['companyAddress']['cityName'].',<br>':''); ?>
                                        <?= ($data['companyAddress']['stateName']!=""?$data['companyAddress']['stateName'].',<br>':''); ?>
                                        <?= ($data['companyAddress']['countryName']!=""?$data['companyAddress']['countryName'].',<br>':''); ?>
                                        <?= ($data['companyAddress']['var_pincode']!=""?$data['companyAddress']['var_pincode'].'<br>':''); ?>
                                    </address>
                                    <?= ($data['companyAddress']['var_gst']!=""?'<br><strong class="brand-color1">GST:</strong><br>'.$data['companyAddress']['var_gst']:'') ?>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong class="brand-color1">Shipped To:</strong><br>
                                        <?= $data['customerAddress']['var_displayname'] ?><br>
                                        <?= ($data['customerAddress']['var_office_address']!=""?$data['customerAddress']['var_office_address'].',<br>':''); ?>
                                        <?= ($data['customerAddress']['cityName']!=""?$data['customerAddress']['cityName'].',<br>':''); ?>
                                        <?= ($data['customerAddress']['stateName']!=""?$data['customerAddress']['stateName'].',<br>':''); ?>
                                        <?= ($data['customerAddress']['countryName']!=""?$data['customerAddress']['countryName'].',<br>':''); ?>
                                        <?= ($data['customerAddress']['var_pincode']!=""?$data['customerAddress']['var_pincode'].'<br>':''); ?>
                                        <?= ($data['customerAddress']['var_gst']!=""?'<br><strong class="brand-color1">GST:</strong><br>'.$data['customerAddress']['var_gst']:'') ?>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong class="brand-color1">Order Date:</strong><br>
                                        <?= date('M d, Y', strtotime($data['var_invoice_date'])) ?><br><br>
                                    </address>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="section-title brand-color1">Invoice Summary</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tbody>
                                        <tr>
                                            <th data-width="40" style="width: 40px;">#</th>
                                            <th>Item Name</th>
                                            <th>HSN</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">TAX</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                        <?php if(!empty($data['invoice_item'])){
                                            $count = 1;
                                            foreach($data['invoice_item'] as $val){ ?>
                                                <tr>
                                                    <td data-width="40" style="width: 40px;"><?= $count++; ?></td>
                                                    <td><?= $val['var_item_name'] ?></td>
                                                    <td><?= $val['var_hsn'] ?></td>
                                                    <td class="text-right"><?= CURRENCY_ICON.$val['var_rate'] ?></td>
                                                    <td class="text-right"><?= $val['var_qty'] ?></td>
                                                    <td class="text-right"><?= $val['var_percent'] ?>%</td>
                                                    <td class="text-right"><?= CURRENCY_ICON.$val['var_amount'] ?></td>
                                                </tr>
                                            <?php }
                                        } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item  mb-2">
                                        <div class="invoice-detail-name"><strong class="brand-color1">Subtotal</strong></div>
                                        <div class="invoice-detail-value"><?= CURRENCY_ICON.$data['var_subtotal'] ?></div>
                                    </div>
                                    <?php if($data['var_gst'] > 0){
                                        if($data['companyAddress']['fk_state']!=$data['customerAddress']['fk_state']){ ?>
                                            <div class="invoice-detail-item mb-2">
                                                <div class="invoice-detail-name"><strong class="brand-color1">IGST</strong></div>
                                                <div class="invoice-detail-value"><?= CURRENCY_ICON.$data['var_gst'] ?></div>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="invoice-detail-item  mb-2">
                                                <div class="invoice-detail-name"><strong class="brand-color1">SGST</strong></div>
                                                <div class="invoice-detail-value"><?= CURRENCY_ICON.$data['var_gst']/2 ?></div>
                                            </div>
                                            <div class="invoice-detail-item  mb-2">
                                                <div class="invoice-detail-name"><strong class="brand-color1">CGST</strong></div>
                                                <div class="invoice-detail-value"><?= CURRENCY_ICON.$data['var_gst']/2 ?></div>
                                            </div>
                                        <?php }
                                    } 
                                    if($data['var_adjustment'] > 0){ ?>
                                        <div class="invoice-detail-item  mb-2">
                                            <div class="invoice-detail-name"><strong class="brand-color1">Adjustment</strong></div>
                                            <div class="invoice-detail-value"><?= CURRENCY_ICON.$data['var_adjustment'] ?></div>
                                        </div>
                                    <?php } ?>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item  mb-2">
                                        <div class="invoice-detail-name"><strong class="brand-color1">Total</strong></div>
                                        <div class="invoice-detail-value invoice-detail-value-lg"><?= CURRENCY_ICON.$data['var_final_amount'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>