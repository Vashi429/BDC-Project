<!DOCTYPE html>
<html lang="en">


<!-- contact.html  21 Nov 2019 04:05:04 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>BDC Project - Admin Login</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?= base_url() ?>/public/assets/img/favicon.ico' />
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 ">
                        <div class="login-brand">
                            <img src="<?= base_url() ?>/public/assets/img/bdc_logo.png" alt="">
                        </div>
                        <div class="card card-primary">
                            <div class="row m-0">
                                <div class="col-12 col-md-12 col-lg-7 p-0">
                                    <div class="login_img">
                                        <img src="<?= base_url() ?>/public/assets/img/welcome-img.svg" alt="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-5 p-0">
                                    <div class="card-header text-center">
                                        <h4>Login</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?= base_url() ?>/admin/login/user_signin" method="post">
                                            <div class="form-group floating-addon">
                                                <label>Email</label>
                                                <div class="input-group">
                                                    <input name="email" placeholder="Enter your email" class="form-control" type="text">
                                                    <span><i class="fas fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group floating-addon">
                                                <label>Password</label>
                                                <div class="input-group">
                                                    <input name="password" placeholder="Enter your password" class="form-control" id="password" type="password">
                                                    <span ><i class="fa fa-lock"></i></span>
                                                </div>
                                                <div class="form-check mt-2">
                                                  <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2" onclick="passworHide()">
                                                    <label class="custom-control-label" for="customCheck2">Show Password</label>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-round btn-lg btn-primary">
                                                    Sign In
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright
                            <?= date('Y') ?> by
                            <a href="javascript:void(0);">CA Assistant</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript">
        function passworHide() {
            var val = document.getElementById('password');

            if (val.type === 'password') {

                val.type = "text";
            } else {
                val.type = "password";
            }
        }

        // $('.input-group-prepend').click(function() {
        //     var $input = $(this).parent('div').find('input');
        //     if ($(this).hasClass('show')) {
        //         $(this).removeClass('show');
        //         $input.attr('type', 'password');
        //     } else {
        //         $(this).addClass('show');
        //         $input.attr('type', 'text');
        //     }
        // });
    </script>
    <!-- General JS Scripts -->
    <script src="<?= base_url() ?>/public/assets/js/app.min.js"></script>
    <!-- JS Libraies -->
    <script src="<?= base_url() ?>/public/<?= base_url() ?>/public/assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Bundle js -->
    <script src="<?= base_url() ?>/public/<?= base_url() ?>/public/assets/plugins/bootstrap/popper.min.js"></script>
    <script src="<?= base_url() ?>/public/<?= base_url() ?>/public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="<?= base_url() ?>/public/assets/js/page/contact.js"></script>
    <!-- Template JS File -->
    <script src="<?= base_url() ?>/public/assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="<?= base_url() ?>/public/assets/js/custom.js"></script>
</body>

</html>