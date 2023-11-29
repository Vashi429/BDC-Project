<div class="contaiiner-fluid">
  <div class="bg-imageloginsignup">             
    <div class="py-5 forgot-width">
      <div class="my-5 login-bg p-4">                            
        <div class="text-center">                                      
          <h4 class="text-white mt-3"><?= lang('Blog.forgotPassword') ?></h4>
        </div>
        <span id="error_forget"></span>
        <form method="POST" id="email_form">
          <div class="p-2 mt-3">
            <div class="mb-3">
              <label for="var_email" class="form-label"><?= lang('Blog.formFieldEmailID') ?></label>
              <input name="var_email" id="var_email" minlength="3" maxlength="100" type="text" class="form-control" placeholder="<?= lang('Blog.enterYourEmailAddress') ?>" />
            </div>
            <div class="mt-4">
              <input type="submit" name="reset_submit" value="<?= lang('Blog.resetPassword') ?>" id="reset_submit" class="btn btn-primary w-100" />
            </div>
            <div class="mt-4">
              <p class="text-center text-white"><?= lang('Blog.newHere') ?> <a href="<?php echo base_url();?>/signUp" class="text-decoration-none text-white"><?= lang('Blog.btnSignUp') ?>.</a> </p>
            </div>
          </div>
        </form>                                                                
      </div>                
    </div>             
  </div>
</div>
    <script type="text/javascript">
      var site_path = '<?php echo base_url();?>';
      $( "#email_form" ).on( "submit", function( event ) {
        event.preventDefault();
        $('.error').remove();
        var valid = true;

        // Validate Email Address field
        var var_email = $('input[name="var_email"]').val().trim();
        if (var_email == '') {
            $('input[name="var_email"]').after('<span class="error"><?= lang('Blog.enterYourEmailAddress') ?></span>');
            valid = false;
        } else if (!isValidEmail(var_email)) {
            $('input[name="var_email"]').after('<span class="error"><?= lang('Blog.pleaseEnterValidEmail') ?></span>');
            valid = false;
        }

        if (valid) {
          $.ajax({
            url:site_path+'/login/ForgetPass',
            type:'POST',
            data:{var_email:var_email},
            success:function(response){
              if(response == 0){
                Swal.fire({
                  title: '',
                  text: "<?= lang('Blog.pleaseEnterYourRegisteredEmailAddress') ?>",
                  icon: 'warning',
                }).then((result) => {
                    $("#email_form")[0].reset();
                }); 
              }else{
                Swal.fire({
                  title: '',
                  text: "<?= lang('Blog.pleaseCheckYouMailAndUpdateThePassword') ?>",
                  icon: 'success',
                }).then((result) => {
                  $("#email_form")[0].reset();
                  window.location.href=site_path+"/login";
                }); 
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

    </script>   

