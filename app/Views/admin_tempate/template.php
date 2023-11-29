<?php if (isset($_SESSION['admin_id'])) {

	if ($_SESSION['admin_id'] == '') {

		header("Location: " . base_url() . "/admin");

		die();

	}

} else {

	header("Location: " . base_url() . "/admin");

	die();

}

$uri = service('uri'); ?>

<!DOCTYPE html>

<html lang="en">



<head>

	<meta charset="UTF-8">

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

	<title><?= (isset($title)?$title.' | ':'') ?>BDC Projects</title>

	<!-- General CSS Files -->

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/app.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/izitoast/css/iziToast.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/summernote/summernote-bs4.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/bootstrap-daterangepicker/daterangepicker.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/select2/dist/css/select2.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/jquery-selectric/selectric.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

	<!-- Template CSS -->

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/pretty-checkbox.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/style.css">

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/components.css">

	<!-- Custom style CSS -->

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/custom.css">

	<script src="<?= base_url() ?>/public/assets/plugins/jquery/jquery.min.js"></script>

	<link rel='shortcut icon' type='image/x-icon' href='<?= base_url() ?>/public/assets/img/favicon-icon.png' />

	<script src="<?= base_url() ?>/public/assets/bundles/sweetalert/sweetalert.min.js"></script>

	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/bundles/select2/dist/css/select2.min.css">

	<script src="<?= base_url() ?>/public/assets/bundles/select2/dist/js/select2.full.min.js"></script>

	<link rel="stylesheet" href="<?=base_url()?>/assets/bundles/datatables/datatables.min.css">

  <script src="<?=base_url()?>/assets/bundles/datatables/datatables.min.js"></script>	<script>

		sitepath = '<?= base_url() ?>';

		perPage = '<?= ADMIN_PER_PAGE_ROWS ?>';

		CURRENCY_ICON = '<?= CURRENCY_ICON ?>';

		UNIQUE_PHONE = '<?= UNIQUE_PHONE ?>';

		UNIQUE_GST = '<?= UNIQUE_GST ?>';

		UNIQUE_PAN = '<?= UNIQUE_PAN ?>';

		UNIQUE_EMAIL = '<?= UNIQUE_EMAIL ?>';

		UNIQUE_USERNAME = '<?= UNIQUE_USERNAME ?>';

		UNIQUE_AADHAR = '<?= UNIQUE_AADHAR ?>';

	</script>

</head>



<body>



	<!-- <div class="loader"></div> -->

	<div id="app">

		<div class="main-wrapper main-wrapper-1">



			<!-- Header Section Start -->

			<div class="navbar-bg"></div>

			<nav class="navbar navbar-expand-lg main-navbar sticky">

				<div class="form-inline mr-auto">

					<ul class="navbar-nav mr-3">

						<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i data-feather="align-justify"></i></a></li>

						<li><a href="#" class="nav-link nav-link-lg fullscreen-btn" id="fullScreenBtn">

								<i data-feather="maximize"></i>

							</a></li>

						<!--<li>-->

						<!--	<form class="form-inline mr-auto">-->

						<!--		<div class="search-element">-->

						<!--			<input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">-->

						<!--			<button class="btn" type="submit">-->

						<!--				<i class="fas fa-search"></i>-->

						<!--			</button>-->

						<!--		</div>-->

						<!--	</form>-->

						<!--</li>-->

					</ul>

				</div>

				<ul class="navbar-nav navbar-right">



					<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="<?= base_url() ?>/public/assets/img/user.png" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>

						<div class="dropdown-menu dropdown-menu-right pullDown">

							<div class="dropdown-title">Hello <?= $_SESSION['adminuser']; ?></div>

							<a href="profile.html" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile </a>

							<!--<a href="timeline.html" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i> Activities </a>-->

							<a href="#" class="dropdown-item has-icon"> <i class="fas fa-cog"></i> Settings </a>

							<a class="dropdown-item has-icon" href="javascript:void(0)" data-toggle="modal" data-target=".changepassword"> <i class="fas fa-lock"></i>Change Password</a>

							<div class="dropdown-divider"></div>

							<a href="<?= base_url() ?>/admin/login/logout" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>

								Logout

							</a>

						</div>

					</li>

				</ul>

			</nav>

			<div class="main-sidebar sidebar-style-2">

				<aside id="sidebar-wrapper">

					<div class="sidebar-brand">

						<a href="<?= base_url() ?>/admin/dashboard"> <img alt="image" src="<?= base_url() ?>/public/assets/img/bdc_logo.png" class="header-logo desk-logo" /></a>

						<a href="<?= base_url() ?>/admin/dashboard"> <img alt="image" src="<?= base_url() ?>/public/assets/img/favicon-icon.png" class="header-logo mob-logo" /></a>

					</div>

					<ul class="sidebar-menu">

						<li class="dropdown <?php if ($uri->getSegment(2) == 'dashboard') {echo "active";} ?>">

							<a class="nav-link" href="<?= base_url() ?>/admin/dashboard"><i data-feather="home"></i><span>Dashboard</span></a>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'customer') {echo "active";} ?>">

							<a class="nav-link" href="<?= base_url() ?>/admin/customer"><i data-feather="user"></i><span>Customer Management</span></a>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'vendor') {echo "active";} ?>">

							<a class="nav-link" href="<?= base_url() ?>/admin/vendor"><i data-feather="users"></i><span>Vendor Management</span></a>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'supervisor' || $uri->getSegment(2) == 'supervisor-attendance') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="user"></i><span>Supervisor Management</span></a>

							<ul class="dropdown-menu">

								<li class="<?php if ($uri->getSegment(2) == 'supervisor') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/supervisor"><span>Manage</span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'supervisor-attendance') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/supervisor-attendance"><span>Attendance</span></a>

								</li>

							</ul>

						</li>
						
						<li class="dropdown <?php if ($uri->getSegment(2) == 'project') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Project Management</span></a>

							<ul class="dropdown-menu">

								<li class="<?php if ($uri->getSegment(3) == 'addProject') {echo "active";} ?>"><a class="nav-link" href="<?= base_url() ?>/admin/project/addProject">Create Project</a></li>

								<li class="<?php if ($uri->getSegment(2) == 'project') {echo "active";} ?>"><a class="nav-link" href="<?= base_url() ?>/admin/project">Project List</a></li>

							</ul>

						</li>


						<li class="dropdown <?php if ($uri->getSegment(2) == 'invoice' || $uri->getSegment(2) == 'receivedPayment') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Sales Management</span></a>

							<ul class="dropdown-menu">

								<li class="<?php if ($uri->getSegment(2) == 'invoice') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/invoice"><i data-feather="file"></i><span>Invoice Management</span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'receivedPayment') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/receivedPayment"><i data-feather="layout"></i><span>Received Payment </span></a>

								</li>

							</ul>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'bill' || $uri->getSegment(2) == 'expense' || $uri->getSegment(2) == 'challan') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Purchase Management</span></a>

							<ul class="dropdown-menu">

								

								<li class="<?php if ($uri->getSegment(2) == 'expense') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/expense"><i data-feather="layout"></i><span>Expense Management </span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'bill') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/bill"><i data-feather="layout"></i><span>Bill Management </span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'challan') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/challan"><i data-feather="book"></i><span>Challan Management </span></a>

								</li>

							</ul>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'companyProfile' || $uri->getSegment(2) == 'laborType' || $uri->getSegment(2) == 'material') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="settings"></i><span>Settings</span></a>

							<ul class="dropdown-menu">

								<li class="<?php if ($uri->getSegment(2) == 'companyProfile') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/companyProfile"><span>Company Profile </span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'laborType') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/laborType"><span>Labor Type Management</span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'material') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/material"><span>Material Management</span></a>

								</li>



							</ul>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'consultancy') {echo "active";} ?>">

							<a class="nav-link" href="<?= base_url() ?>/admin/consultancy"><i data-feather="user"></i><span>Consultancy</span></a>

						</li>

						<li class="dropdown <?php if ($uri->getSegment(2) == 'tools' || $uri->getSegment(2) == 'tools-stock-management') {echo "active";} ?>">

							<a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="settings"></i><span>Tools & Equipment</span></a>

							<ul class="dropdown-menu">

								<li class="<?php if ($uri->getSegment(2) == 'tools') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/tools"><span>Manage</span></a>

								</li>

								<li class="<?php if ($uri->getSegment(2) == 'tools-management') {echo "active";} ?>">

									<a class="nav-link" href="<?= base_url() ?>/admin/tools-stock-management"><span>Stock Management</span></a>

								</li>

							</ul>

						</li>

					</ul>

				</aside>

			</div>

			<!-- Header Section End -->



			<!-- Main Content Start -->

			<div class="main-content">

				<?php try {

					echo view($view);

				} catch (Exception $e) {

					echo "<pre><code>$e</code></pre>";

				} ?>

			</div>

			<!-- Main Content End -->





			<!-- Footer Section Start -->



			<footer class="main-footer">

				<div class="footer-left">

					<!--<a href="templateshub.net">Templateshub</a></a>-->

				</div>

				<div class="footer-right">

					<span> Copyright © <?= date('Y') ?> <a href="javascript:void(0);" class=""> All rights reserved.</span>

				</div>

			</footer>



			<!-- Footer Section End -->

		</div>

	</div>



	<!-- Change Password Model -->

	<div class="modal fade changepassword show" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-modal="true" style="display: none;">

		<div class="modal-dialog modal-md">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>

					<button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">×</span>

					</button>

				</div>

				<div class="modal-body">

					<div id="msgSubmit_changep"></div>

					<form role="form" method="post" id="change_password" class="f-form" data-toggle="validator">

						<div class="form-group">

							<label class="sr-only" for="l-form-username">Old Password</label>

							<input type="password" name="var_opassword" placeholder="Old Password" class="form-control change_password_field" id="var_opassword" required="">

							<span class="eye_icon" onclick="hideShowPassword('var_opassword')"><i class="far fa-eye m-0"></i></span>

						</div>

						<div class="form-group">

							<label class="sr-only" for="l-form-username">New Password</label>

							<input type="password" name="var_npassword" placeholder="New Password" class="form-control change_password_field" id="var_npassword" required="">

							<span class="eye_icon" onclick="hideShowPassword('var_npassword')"><i class="far fa-eye m-0"></i></span>

						</div>

						<div class="form-group">

							<label class="sr-only" for="l-form-username">Conform Password</label>

							<input type="password" name="var_rpassword" placeholder="Conform Password" class="form-control change_password_field" id="var_rpassword" required="">

							<span class="eye_icon" onclick="hideShowPassword('var_rpassword')"><i class="far fa-eye m-0"></i></span>

						</div>

						<button type="button" onclick="change_password()" class="btn btn-primary">Save</button>

					</form>

				</div>

			</div>

		</div>

	</div>

	<!-- End Change Password Model -->

	<script src="<?= base_url() ?>/public/assets/dist/js/ajax.js"></script>

	<script src="<?= base_url() ?>/public/assets/js/app.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/izitoast/js/iziToast.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/jquery-validation/dist/jquery.validate.min.js"></script>

	<!-- JS Libraies -->

	<script src="<?= base_url() ?>/public/assets/bundles/summernote/summernote-bs4.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/cleave-js/dist/cleave.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/cleave-js/dist/addons/cleave-phone.us.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/select2/dist/js/select2.full.min.js"></script>

	<script src="<?= base_url() ?>/public/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>

	<!-- Page Specific JS File -->

	<script src="<?= base_url() ?>/public/assets/js/scripts.js"></script>

	<script>

		<?php if (isset($_SESSION['Invalid'])) : ?>

			var message = "<?= $_SESSION['Invalid'] ?>";

			iziToast.error({

				title: '',

				message: message,

				position: 'topRight'

			});

		<?php endif; ?>

		<?php if (isset($_SESSION['Success'])) : ?>

			var message = "<?= $_SESSION['Success'] ?>";

			iziToast.success({

				title: '',

				message: message,

				position: 'topRight'

			});

		<?php endif; ?>



		$("#fullScreenBtn").on('click',function(){

			<?php if (isset($_SESSION['fullScreenView'])) {

				unset($_SESSION['fullScreenView']);

			}else{

				$_SESSION['fullScreenView'] = true;

			} ?>

		})

		$("#change_password").on("submit", function(event) {

			change_password();

		});



		$(document).ready(function() {

			$('.summernote').summernote();

		});



		function change_password() {

			var opass = $("#var_opassword").val();

			var npass = $("#var_npassword").val();

			var rpass = $("#var_rpassword").val();

			if (npass == '' || rpass == '' || opass == '') {

				var msg = "Please fill up all details.";

				var msgClasses = "h5 text-center text-danger";

				$("#msgSubmit_changep").removeClass().addClass(msgClasses).text(msg);

				exit();

			}

			if (npass != rpass) {

				var msg = "New and Confirm Password does not match.";

				var msgClasses = "h5 text-center text-danger";

				$("#msgSubmit_changep").removeClass().addClass(msgClasses).text(msg);

				exit();

			}

			$.ajax({

				type: "POST",

				url: sitepath + "/admin/dashboard/change_pass",

				data: "opassword=" + opass + "&npassword=" + npass + "&rpassword=" + rpass,

				success: function(response) {

					if (response == 1) {

						var msg = "Your Password Changed Successfully.";

						var msgClasses = "h5 text-center tada sanimated text-success";

						$("#msgSubmit_changep").removeClass().addClass(msgClasses).text(msg);

						window.location.href = '';

					} else {

						var msg = response;

						var msgClasses = "h5 text-center text-danger";

						$("#msgSubmit_changep").removeClass().addClass(msgClasses).text(msg);

					}

				}

			});

		}



		$("[data-checkboxes]").each(function() {

			var me = $(this),

				group = me.data('checkboxes'),

				role = me.data('checkbox-role');

			me.change(function() {

				var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),

					checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),

					dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),

					total = all.length,

					checked_length = checked.length;



				if (role == 'dad') {

					if (me.is(':checked')) {

						all.prop('checked', true);

					} else {

						all.prop('checked', false);

					}

				} else {

					if (checked_length >= total) {

						dad.prop('checked', true);

					} else {

						dad.prop('checked', false);

					}

				}

			});

		});



		$('.eye_icon').click(function() {

			var $input = $(this).parent('div').find('input');

			if ($(this).hasClass('show')) {

				$(this).removeClass('show');

				$input.attr('type', 'password');

			} else {

				$(this).addClass('show');

				$input.attr('type', 'text');

			}

		});

		

	</script>

</body>



</html>