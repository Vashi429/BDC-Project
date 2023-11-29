<div class="row ">
	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="card">
			<div class="card-statistic-4">
				<div class="align-items-center justify-content-between">
					<div class="row ">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
							<div class="card-content">
								<h5 class="font-15">Total Amount</h5>
								<h2 class="mb-3 font-18"><?= CURRENCY_ICON.$data['data_bill_due'][0]['var_amount']; ?></h2>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
							<div class="banner-img">
								<img src="https://www.citechnology.in/BDC/public/assets/img/banner/4.png" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="card">
			<div class="card-statistic-4">
				<div class="align-items-center justify-content-between">
					<div class="row ">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
							<div class="card-content">
								<h5 class="font-15">Paid Amount</h5>
								<h2 class="mb-3 font-18"><?= CURRENCY_ICON.$data['data_bill_due'][0]['var_paid_amount']; ?></h2>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
							<div class="banner-img">
								<img src="https://www.citechnology.in/BDC/public/assets/img/banner/4.png" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="card">
			<div class="card-statistic-4">
				<div class="align-items-center justify-content-between">
					<div class="row ">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
							<div class="card-content">
								<h5 class="font-15">Due Amount</h5>
								<h2 class="mb-3 font-18"><?= CURRENCY_ICON.$data['data_bill_due'][0]['var_due_amount']; ?></h2>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
							<div class="banner-img">
								<img src="https://www.citechnology.in/BDC/public/assets/img/banner/4.png" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>Bill Wise Expense List</h4>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-striped">
						<tr>
							<th></th>
							<th>Bill ID</th>
							<th>Expense ID</th>
							<th>Expense Date</th>
							<th>Amount</th>
						</tr>
                        <?php foreach ($data['expence_data'] as $key => $value) { ?>
                            <tr>
                                <td></td>
                                <td><?= $value['fk_bill']; ?></td>
                                <td><?= $value['fk_expense']; ?></td>
                                <td><?= $value['var_bill_expense_date']; ?></td>
                                <td><?= CURRENCY_ICON.$value['var_amount']; ?></td>
                            </tr>
                        <?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url() ?>/public/assets/bundles/apexcharts/apexcharts.min.js"></script>
<!-- Page Specific JS File -->
<script src="<?= base_url() ?>/public/assets/js/page/index.js"></script>