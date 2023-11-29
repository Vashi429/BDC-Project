<div class="contaiiner-fluid">
  <div class="bg-imageloginsignup">             
    <div class="py-5">
      <div class="my-5 login-bg p-4">                            
        <div class="text-center">                                      
          <h3 class="text-white mt-3"><?= lang('Blog.SignUpYourAccount') ?></h3>
        </div>
        <span id="error_sigup"></span>
        <form method="POST" id="signup_form">
          <div class="p-2 mt-3">
            <div class="mb-3">
              <label for="var_username" class="form-label"><?= lang('Blog.formFieldFullName') ?></label>
              <input name="var_username" id="var_username" type="text" class="form-control" minlength="3" maxlength="100" placeholder="<?= lang('Blog.enterYourFullName') ?>" oninput="inputValidation('var_username')" />
            </div>
            <div class="mb-3">
              <label for="var_email" class="form-label"><?= lang('Blog.formFieldEmailID') ?></label>
              <input name="var_email" type="email" class="form-control" minlength="3" maxlength="100" placeholder="<?= lang('Blog.enterYourEmailAddress') ?>" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="var_password"><?= lang('Blog.formFieldPassword') ?></label>
              <div class="position-relative auth-pass-inputgroup mb-3">
                  <input name="var_password" minlength="3" maxlength="100" type="text" id="var_password" class="form-control pe-5 password-input type-password" placeholder="<?=  lang('Blog.enterPassword') ?>"/>
                  <span class="validatePassword text-warning"></span>
                  <button class="btn position-absolute text-decoration-none password-show-btn shadow-none" type="button" id="password-addon" onclick="hideShowPassword()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                      <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                      <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                    </svg>
                  </button>
              </div>
            </div>
            <div class="mt-4">
              <input type="submit" name="signup_submit" value="<?= lang('Blog.btnSubmit') ?>" id="signup_submit" class="btn btn-primary w-100" />
              <h6 class="text-center text-white my-2">OR</h6>
              <a href="<?php echo base_url();?>/login" name="btnlogin" value="" id="btnlogin" class="btn btn-primary w-100"><?= lang('Blog.loginToYourAccount') ?></a>
            </div>
          </div>
        </form>                                                                 
      </div>                
    </div>             
  </div>
</div>

    <script type="text/javascript">
     
      var site_path = '<?php echo base_url();?>';
      $( "#signup_form" ).on( "submit", function( event ) {
        event.preventDefault();
        $('.error').remove();
        var valid = true;

        // Validate Full Name field
        var var_username = $('input[name="var_username"]').val().trim();
        if (var_username == '') {
            $('input[name="var_username"]').after('<span class="error"><?= lang('Blog.pleaseEnterUsername') ?></span>');
            valid = false;
        } else if (/\d/.test(name)) {
            $('input[name="var_username"]').after('<span class="error"><?= lang('Blog.usernameShouldNotCcontainNumbers') ?></span>');
            valid = false;
        }

        // Validate Email Address field
        var var_email = $('input[name="var_email"]').val().trim();
        if (var_email == '') {
            $('input[name="var_email"]').after('<span class="error">Please enter your email</span>');
            valid = false;
        } else if (!isValidEmail(var_email)) {
            $('input[name="var_email"]').after('<span class="error">Please enter a valid email</span>');
            valid = false;
        }

        var var_password = $('input[name="var_password"]').val();
        if (var_password.trim() == '') {
            $('input[name="var_password"]').after('<span class="error">Please enter a password</span>');
            valid = false;
        }else if(var_password.length<8){
          $('input[name="var_password"]').after('<span class="error">Please enter minimum 8 character</span>');
            valid = false;
        } else if (!validatePassword()) {
            valid = false;
        }
         
        if (valid) {
          jQuery.ajax({
            url:site_path+'/login/addUser',
            type:'POST',
            data:{var_username:var_username,var_email:var_email,var_password:var_password},
            success:function(response){
              var data = jQuery.parseJSON(response);
              if(data.res == 0){
                Swal.fire({
                  title: '',
                  text: "<?= lang('Blog.thisEmailAlredyRegisteredPleaseTryWithAnotherEmail') ?>",
                  icon: 'warning',
                }).then((result) => {
                    $("#signup_form")[0].reset();
                }) 
                return false;
              }else{
                Swal.fire({
                  title: '',
                  text: "<?= lang('Blog.thankYouForRegisteringWithEasyAffordable') ?>",
                  icon: 'success',
                }).then((result) => {
                    $("#signup_form")[0].reset();
                    window.location.href=site_path+data.redirection;
                }) 
                
              }
              
            }
          });
        }
      });
      // Helper function to validate email address
      function isValidEmail(email) {
          var pattern = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;
          return pattern.test(email);
      }

      function validatePassword() { 
        var password = $("#var_password").val();
        if(password.length <8){
            $(".validatePassword").text('<?= lang('Blog.PasswordMustBeAtLeast8Characters') ?>').removeClass('d-none');
            return false;
        } else if(password.search(/[a-z]/) < 0) { 
            $(".validatePassword").text('<?= lang('Blog.PasswordMustContainAtLeastOneLowercaseLetter') ?>').removeClass('d-none'); 
            return false;
        } else if(password.search(/[A-Z]/) < 0) { 
            $(".validatePassword").text('<?= lang('Blog.PasswordMustContainAtLeastOneUppercaseLetter') ?>').removeClass('d-none'); 
            return false;
        } else if(password.search(/[0-9]/) < 0) { 
            $(".validatePassword").text('<?= lang('Blog.PasswordMustContainAtLeastOneNumber') ?>').removeClass('d-none');
            return false; 
        }else if(password.search(/^[^\W_]*[\W_]/) < 0) { 
            $(".validatePassword").text('<?= lang('Blog.PasswordMustContainAtLeastOneSpecialCharacter') ?>').removeClass('d-none');
            return false; 
        } else { 
            $(".validatePassword").addClass('d-none');
            return true;
        } 
      }



    </script>





    