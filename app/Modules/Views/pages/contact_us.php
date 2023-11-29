<main>
    <section class="home-banner inner-page bg_light overflow-hidden d-flex align-items-center pt-5 pb-5" style="background-image: url(<?= base_url(); ?>/public/front_assets/images/banner.png);">
        <div class="container">
            <div class="row align-items-center">
                <div class="content-part col-lg-12 pt-4 pb-4 pt-lg-0 pb-lg-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= lang('Blog.breadCumHome') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= lang('Blog.breadCumHomeContactUs') ?></li>
                        </ol>
                    </nav>
                    <div class="slider-des">
                        <h1 class="sl-title text-white"><?= lang('Blog.breadCumHomeContactUs') ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 bg_light">
                    <div class="p-lg-5 p-4">
                        <div class="row mb-4">
                            <h2 class="title text-center"><?= lang('Blog.getInTouch') ?></h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 order-lg-3 pt-4 pb-4">
                                <div class="contact_details mb-4">
                                    <ul>
                                        <li class="d-flex align-items-center mb-4">
                                            <div class="contact_icons"> <img src="<?= base_url(); ?>/public/front_assets/images/map.svg" /> </div>
                                            <p>R GONÇALVES CRESPO, 9 4 ESQ, 1150-182 LISBOA</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-4">
                                            <div class="contact_icons"> <img src="<?= base_url(); ?>/public/front_assets/images/phone.svg" /> </div>
                                            <p><a href="tel:<?= getSettingValueByKey('var_contact_number') ?>"><?= getSettingValueByKey('var_contact_number') ?> </a></p>
                                        </li>
                                        <li class="d-flex align-items-center mb-4">
                                            <div class="contact_icons"> <img src="<?= base_url(); ?>/public/front_assets/images/email.svg" /> </div>
                                            <p><a href="mailto:<?= getSettingValueByKey('var_contact_email') ?>"><?= getSettingValueByKey('var_contact_email') ?></a></p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="contact_details mb-4">
                                    <ul class="d-flex align-items-center">
                                        <li class="d-flex align-items-center justify-content-center">
                                            <div class="social_icons"><a href="<?= getSettingValueByKey('var_facebook_link') ?>"><img src="<?= base_url(); ?>/public/front_assets/images/Facebook_contact.svg" alt="<?= lang('Blog.imgAttrFacebook') ?>"/></a></div>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-center">
                                            <div class="social_icons"><a href="<?= getSettingValueByKey('var_youtube_href') ?>"><img src="<?= base_url(); ?>/public/front_assets/images/Youtube_contact.svg" alt="<?= lang('Blog.imgAttrYoutube') ?>"/></a></div>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-center">
                                            <div class="social_icons"><a href="<?= getSettingValueByKey('var_twitter_href') ?>"><img src="<?= base_url(); ?>/public/front_assets/images/Twitter_contact.svg" alt="<?= lang('Blog.imgAttrTwitter') ?>"/></a></div>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-center">
                                            <div class="social_icons"><a href="<?= getSettingValueByKey('var_instagram_href') ?>"><img src="<?= base_url(); ?>/public/front_assets/images/Instagram_contact.svg" alt="<?= lang('Blog.imgAttrInstagram') ?>"/></a> </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="map">
                                    <?php 
                                    $address = 'R GONÇALVES CRESPO, 9 4 ESQ, 1150-182 LISBOA';
                                    echo '<iframe frameborder="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=' . str_replace(",", "", str_replace(" ", "+", $address)) . '&z=14&output=embed"></iframe>'; ?>
                                    </div>
                            </div>
                            <div class="col-lg-1 order-lg-2"> </div>
                            <div class="col-lg-6 order-lg-1 pt-4 pb-4">
                                <h5 class="mb-5"><?= lang('Blog.leaveUsMessage') ?></h5>
                                <form id="contact_us_form">
                                    <div class="mb-4 pb-2">
                                        <label for="var_name"><?= lang('Blog.formFieldFullName') ?><span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="var_name" id="var_name" placeholder="<?= lang('Blog.enterYourFullName') ?>" aria-describedby="" oninput="inputValidation('var_name')">
                                    </div>
                                    <div class="mb-4 pb-2">
                                        <label for="var_email"><?= lang('Blog.formFieldEmailID') ?><span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="var_email" placeholder="<?= lang('Blog.enterYourEmailAddress') ?>">
                                    </div>
                                    <div class="mb-4 pb-2">
                                        <label for="var_phone"><?= lang('Blog.formFieldNumber') ?><span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="var_phone"  onkeypress="return isNumberKey(event);" minlength="9" maxlength="9" placeholder="<?= lang('Blog.enterYourNumber') ?>">
                                    </div>
                                    <div class="mb-4 pb-2">
                                        <label><?= lang('Blog.message') ?><span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message" rows="4" placeholder="<?= lang('Blog.pleaseEnterMessage') ?>"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary ps-4 pe-4 pt-2 pb-2"><?= lang('Blog.btnSubmit') ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    
    $(document).on('submit', '#contact_us_form', function(event) {
        event.preventDefault();
        $('#contact_us_form .error').remove();
        var formData = $(this).serialize();
        var valid = true;
        if (!$('#contact_us_form input[name="var_name"]').val()) {
            $('#contact_us_form input[name="var_name"]').after('<span class="error"><?= lang('Blog.enterYourFullName') ?></span>');
            valid = false;
        } else if (!$('#contact_us_form input[name="var_name"]').val().match(/^[A-Za-z\s]+$/)) {
            $('#contact_us_form input[name="var_name"]').after('<span class="error"><?= lang('Blog.pleaseEnterValidFullNameLettersAndSpacesOnly') ?></span>');
            valid = false;
        }
        var email = $('#contact_us_form input[name="var_email"]').val();
        if (!email) {
            $('#contact_us_form input[name="var_email"]').after('<span class="error"><?= lang('Blog.pleaseEnterValidEmail') ?></span>');
            valid = false;
        } else if (!isValidEmail(email)) {
            $('#contact_us_form input[name="var_email"]').after('<span class="error"><?= lang('Blog.pleaseEnterValidEmail') ?></span>');
            valid = false;
        }
        var phone = $('#contact_us_form input[name="var_phone"]').val();
        if (!phone) {
            $('#contact_us_form input[name="var_phone"]').after('<span class="error"><?= lang('Blog.enterYourNumber') ?></span>');
            valid = false;
        }

        if (!$('#contact_us_form textarea[name="message"]').val()) {
            $('#contact_us_form textarea[name="message"]').after('<span class="error"><?= lang('Blog.pleaseEnterMessage') ?></span>');
            valid = false;
        }
        if (valid) {
            $.ajax({
                type: 'POST',
                url: sitepath + 'pages/save_contact_us',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: "",
                        text: '<?= lang('Blog.contactUsSuccessMessage') ?>',
                        icon: "success",
                    })
                    .then((result) => {
                        $('#contact_us_form')[0].reset();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire({
                        title: "",
                        text: '<?= lang('Blog.contactUsErrorMessage') ?>',
                        icon: "success",
                    })
                    .then((result) => {
                        $('#contact_us_form')[0].reset();
                    });
                }
            });
        }
    });

    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

</script>