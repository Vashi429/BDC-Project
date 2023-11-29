<div class="contaiiner-fluid">
  <div class="bg-imageloginsignup">             
    <div class="py-5 forgot-width">
      <div class="my-5 login-bg p-4">                            
        <div class="text-center">                                      
          <h4 class="text-white mt-3"><?= lang('Blog.changePassword') ?></h4>
        </div>
        <span id="error_chang_pass"></span>
        <form method="POST" id="chang_pass_form">
          <div class="p-2 mt-3">
            <div class="position-relative auth-pass-inputgroup mb-3">
              <label for="var_password" class="form-label"><?= lang('Blog.newPassword') ?></label>
              <input name="var_password" minlength="3" maxlength="100" type="text" id="var_password" class="form-control pe-5 password-input type-password" placeholder="<?= lang('Blog.enterPassword') ?>" />
              <span class="validatePassword text-warning"></span>
              <button class="btn position-absolute text-decoration-none password-show-btn shadow-none" type="button" id="password-addon" onclick="hideShowPassword()" style="top: 38px">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                  <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                  <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                </svg>
              </button>
            </div>
            <div class="mb-3">
              <label for="var_confirmm_pass" class="form-label"><?= lang('Blog.confirmPassword') ?></label>
              <input name="var_confirmm_pass" type="text" id="var_confirmm_pass" class="form-control password-input type-password" placeholder="<?= lang('Blog.enterConfirmPassword') ?>" />
              <button class="btn position-absolute text-decoration-none password-show-btn shadow-none" type="button" id="confirm-password-addon" onclick="hideShowCPassword()" style="top: -44px;position: relative !important;float: right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                  <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                  <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                </svg>
              </button>
            </div>
            <div class="mt-4">
              <input type="submit" name="change_pass" value="<?= lang('Blog.btnSubmit') ?>" id="change_pass" class="btn btn-primary w-100" />
            </div>
          </div>
        </form>                                                                
      </div>                
    </div>             
  </div>
</div>
<script type="text/javascript">
    var site_path = '<?php echo base_url();?>';
    $( "#chang_pass_form" ).on( "submit", function( event ) {
      event.preventDefault();
      $('.error').remove();
      var valid = true;

      var var_confirmm_pass = $('#var_confirmm_pass').val();
      var var_auth = '<?= $token ?>';

      var var_new_password = $('input[name="var_password"]').val();
      if (var_new_password.trim() == '') {
          $('input[name="var_password"]').after('<span class="error"><?= lang('Blog.pleaseEnterPassword') ?></span>');
          valid = false;
      }else if(var_new_password.length<8){
        $('input[name="var_password"]').after('<span class="error"><?= lang('Blog.pleaseEnterMinimumCharacter') ?></span>');
          valid = false;
      } else if (!validatePassword()) {
            valid = false;
        }

      var var_confirmm_pass = $('input[name="var_confirmm_pass"]').val();
      if (var_confirmm_pass.trim() == '') {
          $('input[name="var_confirmm_pass"]').after('<span class="error"><?= lang('Blog.pleaseEnterComfirmPassword') ?></span>');
          valid = false;
      }else if(var_confirmm_pass.length<8){
        $('input[name="var_confirmm_pass"]').after('<span class="error"><?= lang('Blog.pleaseEnterMinimumCharacter') ?></span>');
          valid = false;
      }else if(var_new_password.trim() != var_confirmm_pass.trim()){
        $('input[name="var_confirmm_pass"]').after('<span class="error"><?= lang('Blog.passwordNotMatch') ?></span>');
          valid = false;
      }


      if (valid) {
        jQuery.ajax({
          url:site_path+'/login/Updatepassword',
          type:'POST',
          data:{var_new_password:var_new_password,var_auth:var_auth},
          success:function(response){
            Swal.fire({
              title: '',
              text: "<?= lang('Blog.yourNewPasswordSuccessfullyCreated') ?>",
              icon: 'success',
            }).then((result) => {
              window.location.href=site_path+"/login";
            }) 
              
          }
        });
      }
    });

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

   