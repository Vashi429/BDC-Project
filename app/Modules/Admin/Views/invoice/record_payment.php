<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/invoice">Invoice</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">Record Payment of Invoice</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Record Payments of #<?= $invoiceId ?></h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="viewInvoice">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Payment Id<a></th>
                                    <th>Project Name</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($invioceList)){
                                    foreach ($invioceList as $key => $value) { ?>
                                        <tr>
                                            <td><?= $value['var_payment_date'] ?></td>
                                            <td><?= $value['var_received_id']?></td>
                                            <td><?= $value['var_project']?></td>
                                            <td><?= $value['var_payment_type'] ?></td>
                                            <td><?= CURRENCY_ICON.$value['var_received_amount']?></td>
                                            <td></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
