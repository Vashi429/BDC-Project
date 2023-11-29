<div class="contaiiner-fluid">
    <div class="bg-imageloginsignup">             
      <div class="py-5">
        <div class="my-5 login-bg p-4">                            
            <div class="text-center">                                      
              <h3 class="text-white mt-3"><?= lang('Blog.loginToYourAccount') ?></h3>
            </div>
            <div>
              <span id="error_login"></span>
            </div>
            <form method="POST" id="login_form">
              <div class="p-2 mt-3">
                <div class="mb-3">
                  <label for="var_email" class="form-label"><?= lang('Blog.formFieldEmailID') ?></label>
                  <input name="var_email" type="email" id="var_email" minlength="3" maxlength="100" class="form-control" placeholder="<?= lang('Blog.enterYourEmailAddress') ?>" autocomplete="off"/>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="password-input"><?= lang('Blog.formFieldPassword') ?></label>
                  <div class="position-relative auth-pass-inputgroup mb-3">
                      <input name="var_password" type="text" id="var_password" minlength="3" maxlength="100" class="form-control pe-5 password-input type-password" placeholder="<?= lang('Blog.enterPassword') ?>" autocomplete="off"/>
                      <button class="btn position-absolute text-decoration-none password-show-btn shadow-none" type="button" id="password-addon" onclick="hideShowPassword()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                          <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                          <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                        </svg>
                      </button>
                  </div>
                </div>
                <div class="form-check d-flex justify-content-between flex-wrap-responsive">
                  <div class="w-100">
                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check" />
                    <label class="form-check-label text-white" for="auth-remember-check"><?= lang('Blog.rememberMe') ?></label>
                  </div>
                  <div class="w-100">
                    <a href="<?php echo base_url();?>/forgotPassword" class="text-white text-decoration-none"><?= lang('Blog.questionForgotPassword') ?></a>
                  </div>
                </div>
                <div class="mt-4">
                  <input type="submit" name="btnlogin" value="Login" id="btnlogin" class="btn btn-primary w-100" />
                  <h6 class="text-center text-white my-2">OR</h6>
                  <a href="<?php echo base_url();?>/signUp"  name="btnlogin" value="<?= lang('Blog.createYourAccount') ?>" id="btnlogin" class="btn btn-primary w-100" ><?= lang('Blog.createYourAccount') ?></a>
                </div>
              </div> 
            </form>                                                                
        </div>                
      </div>             
    </div>
</div>
<script type="text/javascript">
  var site_path = '<?php echo base_url();?>';
  $( document ).ready(function() {
    $("#login_form")[0].reset();
  });
  $( "#login_form" ).on( "submit", function( event ) {
    event.preventDefault();
    var var_email = $('#var_email').val();
    var var_password = $('#var_password').val();
    if( var_email == ''){
      $('#var_email').addClass("errer-border"); 
      return false;
    }else if(var_password == ''){
      $('#var_password').addClass("errer-border");
      return false;
    }else{
      $('#var_email').removeClass("errer-border");
      $('#var_password').removeClass("errer-border");
      $.ajax({
        url:site_path+'/login/UserLogin',
        type:'POST',
        data:{var_email:var_email,var_password:var_password},
        success:function(response){
          var data = jQuery.parseJSON(response);
          if(data.res == 0){
            Swal.fire({
              title: '',
              text: "<?= lang('Blog.invalidEmailOrPassword') ?>",
              icon: 'warning',
            }).then((result) => {
                $("#login_form")[0].reset();
            }) 
          }else{
            Swal.fire({
              title: '',
              text: "<?= lang('Blog.loginSuccessfuily') ?>",
              icon: 'success',
            }).then((result) => {
                window.location.href=site_path+data.redirection;
            })  
          }
        }
      });
    }
  });
</script>   
