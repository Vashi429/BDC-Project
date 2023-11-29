<div class="property-box">
    <div class="container">
        <div class="property-all-tabs">
            <div class="property-tabs">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        <?= lang('Blog.profileSidebarMyProperty') ?>
                    </button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                        <?= lang('Blog.profileSidebarMyFavoriteProperty') ?>
                    </button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                        <?= lang('Blog.profileSidebarMyProfile') ?>
                    </button>
                </div>
            </div>
            <div class="property-contents">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row">
                            <?php if (!empty($userProperty)) {
                                foreach ($userProperty as $value) { ?>
                                    <div class="col-lg-4 col-md-6 mb-4 pb-2 position-relative" id="property_section_<?= $value['int_glcode'] ?>">
                                        <?php if ($value['is_varified'] == 'Y') {
                                            $href = base_url() . '/property/' . $value['var_slug'];
                                            $onclick = "";
                                        } else {
                                            $href = 'javascript:void(0)';
                                            $onclick = 'onclick="propertyNotVerified()"';
                                        } ?>
                                        <a href="<?= $href ?>" class="text-decoration-none global-div-href" <?= $onclick ?>></a>
                                        <div class="main_pro_box mainpro_boxprofileproperty">
                                            <div class="pro_img img-hover-card" style="background-image: url(<?= $value['var_image'] ?>);">
                                                <div class="p-2 hover-icon-myproperty">
                                                    <a href="<?= base_url() . '/editProperty/' . $value['var_slug']; ?>" class="fa fa-edit text-decoration-none me-3"></a>
                                                    <a href="javascript:void(0)" class="fa fa-trash text-decoration-none" onclick="removeProperty(<?= $value['int_glcode'] ?>)"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pro_content">
                                            <h3><?= $value['var_title'] ?></h3>
                                            <?php
                                            $address = '';
                                            if ($value['address_line1'] != "" && $value['address_line2'] != "") {
                                                $address .= $value['address_line1'].', ';
                                            }
                                            if ($value['address_line2'] != "" && $value['var_landmark'] != "") {
                                                $address .= $value['address_line2'].', ';
                                            }
                                            if ($value['var_landmark'] != "" && $value['city_name'] != "") {
                                                $address .= $value['var_landmark'].', ' ;
                                            }
                                            if ($value['city_name'] != "") {
                                                $address .= $value['city_name'];
                                            }
                                            ?>
                                            <p><?= $address ?></p>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <ul class="left-list">
                                                        <li><?= (isset($_SESSION['language']) && $_SESSION['language']=='pt'? $value['category_name_pt']:$value['category_name']) ?></li>
                                                        <?php if ($value['bedroom'] > 0) { ?>
                                                            <li><?= $value['bedroom'] ?> <?= lang('Blog.beds') ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6 col-12 ">
                                                    <ul class="right-list">
                                                        <li class="mb-0 mb-md-2"><?= str_replace(',',' ', number_format($value['var_price'])).'€' ?></li>
                                                        <?php if ($value['facility'] != "") { ?>
                                                            <li><?= $value['facility'] ?>+ <?= lang('Blog.facilities') ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php }
                            } else { ?>
                                <div class="col-lg-12 col-md-12">
                                    <span class="data-not-found"><?= lang('Blog.dataNotFound') ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="row">
                            <?php if (!empty($favouriteProperty)) {
                                foreach ($favouriteProperty as $value) { ?>
                                    <div class="col-lg-4 col-md-6 mb-4 pb-2">
                                        <a href="<?php echo base_url() . '/property/' . $value['var_slug']; ?>" title="<?= $value['var_title'] ?>" class="text-decoration-none">
                                            <div class="main_pro_box">
                                                <div class="pro_img img-hover-card" style="background-image: url(<?= $value['var_image'] ?>);"></div>
                                            </div>
                                            <div class="pro_content">
                                                <h3><?= $value['var_title'] ?></h3>
                                                <?php
                                                $address = '';
                                                if ($value['address_line1'] != "" && $value['address_line2'] != "") {
                                                    $address .= $value['address_line1'].', ';
                                                }
                                                if ($value['address_line2'] != "" && $value['var_landmark'] != "") {
                                                    $address .= $value['address_line2'].', ';
                                                }
                                                if ($value['var_landmark'] != "" && $value['city_name'] != "") {
                                                    $address .= $value['var_landmark'].', ' ;
                                                }
                                                if ($value['city_name'] != "") {
                                                    $address .= $value['city_name'];
                                                }
                                                ?>
                                                <p><?= $address ?></p>
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <ul class="left-list">
                                                            <li><?= (isset($_SESSION['language']) && $_SESSION['language']=='pt'? $value['category_name_pt']:$value['category_name']) ?></li>
                                                            <?php if ($value['bedroom'] > 0) { ?>
                                                                <li><?= $value['bedroom'] ?> <?= lang('Blog.beds') ?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6 col-12 ">
                                                        <ul class="right-list">
                                                            <li class="mb-0 mb-md-2"><?= str_replace(',',' ', number_format($value['var_price'])).'€' ?></li>
                                                            <?php if ($value['facility'] != "") { ?>
                                                                <li><?= $value['facility'] ?>+ <?= lang('Blog.facilities') ?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="col-lg-12 col-md-12">
                                    <span class="data-not-found"><?= lang('Blog.dataNotFound') ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <div class="login-box">
                            <h2 class="title"><?= lang('Blog.myProfile') ?></h2>
                            <form method="post" enctype="multipart/form-data" id="myform" action="<?= base_url() ?>/account/updateProfile">
                                <div class="row user-profile">
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="var_name" class="profile-label"><?= lang('Blog.formFieldFullName') ?></label>
                                        <input type="text" name="var_name" oninput="inputValidation('var_username')" id="var_username"  class="form-control" value="<?= $userData['var_name'] ?>" placeholder="<?= lang('Blog.enterYourFullName') ?>" />
                                    </div>
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="var_email" class="profile-label"><?= lang('Blog.formFieldEmailID') ?></label>
                                        <input type="email" name="var_email" class="form-control" value="<?= $userData['var_email'] ?>" placeholder="<?= lang('Blog.enterYourEmailAddress') ?>" readonly />
                                    </div>
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="phone" class="profile-label"><?= lang('Blog.formFieldNumber') ?></label>
                                        <input type="text" onkeypress="return isNumberKey(event);" value="<?= $userData['var_mobile_no'] ?>" class="form-control" name="phone" minlength="9" inputmode="numeric" pattern="[0-9]*" maxlength="9" placeholder="<?= lang('Blog.enterYourNumber') ?>" />
                                    </div>
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="var_house_no" class="profile-label"><?= lang('Blog.formFieldHourseNo') ?></label>
                                        <input type="text" class="form-control"  id="var_house_no" name="var_house_no" placeholder="<?= lang('Blog.enterYourHourseNo') ?>" value="<?php echo isset($userData['street_1']) ? $userData['street_1'] : ""; ?>">
                                    </div>
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="var_app_name" class="profile-label"><?= lang('Blog.formFieldAppartmentName') ?></label>
                                        <input type="text" class="form-control" id="var_app_name" oninput="inputValidation('var_app_name')"  name="var_app_name" placeholder="<?= lang('Blog.AppartmentName') ?>" value="<?php echo isset($userData['street_2']) ? $userData['street_2'] : ""; ?>">
                                    </div>
                                    <div class="mb-4 col-lg-6 col-md-6">
                                        <label for="var_landmark" class="profile-label"><?= lang('Blog.formFieldLandmark') ?></label>
                                        <input type="text" class="form-control" oninput="inputValidation('var_landmark')" id="var_landmark" name="var_landmark" placeholder="<?= lang('Blog.landmarkHere') ?>" value="<?php echo isset($userData['var_landmark']) ? $userData['var_landmark'] : ""; ?>">
                                    </div>
                                    <div class="col-lg-6 col-md-6 mb-4 custom-select-option">
                                        <label for="fk_city" class="profile-label"><?= lang('Blog.formFieldCity') ?></label>
                                        <select class="form-control" name="fk_city" id="fk_city" placeholder="City" class="form-control">
                                            <option value="-1" selected disabled><?= lang('Blog.pleaseSelectCity') ?></option>
                                            <?php if (!empty($city)) {
                                                foreach ($city as $ct) { ?>
                                                    <option value="<?= $ct['int_glcode']; ?>" <?= ($ct['int_glcode'] == $userData['fk_city']) ? 'selected' : ''; ?>><?= (isset($_SESSION['language']) && $_SESSION['language']=='pt'? $ct['name_pt']:$ct['name']) ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <label for="var_pincode" class="profile-label"><?= lang('Blog.formFieldZipCode') ?></label>
                                        <input type="text" name="var_pincode" onkeypress="return formatNumber(event);" pattern="[0-9]*" value="<?php echo isset($userData['var_pincode']) ? $userData['var_pincode'] : ""; ?>" inputmode="numeric" class="form-control" placeholder="<?= lang('Blog.enterYourZipCode') ?>" maxlength="8" minlength="8" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <label class="profile-label" for="var_image" class="text-right control-label col-form-label"><?= lang('Blog.formFieldProfileImage') ?> </label>
                                        <?php if ($userData['var_image'] != '') {
                                            $Image = base_url() . '/uploads/user/' . $userData['var_image'];
                                        } else {
                                            $Image = base_url() . '/public/front_assets/images/no_image.png';
                                        } ?>
                                        <input type="file" class="form-control" name="var_image" id="var_image">
                                        <?php if ($userData['var_image'] != "") { ?>
                                            <img src="<?= $Image ?>" width="100" alt="<?= lang('Blog.userProfileImage') ?>">
                                        <?php } ?>
                                        <input type="hidden" name="var_image_old" value="<?= isset($userData['var_image']) ? $userData['var_image'] : '' ?>">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary ps-4 pe-4"><?= lang('Blog.btnSaveChange') ?></button>
                                    <a href="<?= base_url() ?>" class="btn btn-primary ps-4 pe-4"><?= lang('Blog.btnBackToHome') ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>/public/assets/vendor_components/select2/dist/js/select2.full.js"></script>
<script>
    $(document).ready(function() {
        $("#btnSubmit").click(function(event) {
            event.preventDefault();
            var form = $('#myform')[0];
            var data = new FormData(form);
            console.log(data);
            $("#btnSubmit").prop("disabled", true);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: sitepath + "account/updateProfile",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                success: function(data) {
                    $("#btnSubmit").removeAttr("disabled");
                    var data = jQuery.parseJSON(data);
                    console.log(data.status);
                    if (data.status == 1) {
                        Swal.fire({
                            title: '',
                            text: "<?= lang('Blog.profileUpdatedSuccessfully') ?>",
                            icon: 'success',
                        }).then((result) => {
                            if (data.var_image == '' || data.var_image == 'undefined' || data.var_image == null) {
                                $("#var_image").html('');
                            } else {
                                $("var_image").html('');
                                var altText = "<?= lang('Blog.userProfileImage') ?>";
                                $("#var_image").append('<img src="' + sitepath + 'uploads/user/"' + data.var_image + ' alt="' + altText + '" width="100">');
                            }
                        });
                    } else if (data.status == 2) {
                        Swal.fire({
                            title: '',
                            text: "<?= lang('Blog.errorUserMobileExists') ?>",
                            icon: 'warning',
                        })
                    }

                },
                error: function(e) {
                    Swal.fire({
                        title: '',
                        text: "<?= lang('Blog.erroruserUpdateProfile') ?>",
                        icon: 'warning',
                    }).then((result) => {
                        $("#btnSubmit").removeAttr("disabled");
                        if (data.var_image == '' || data.var_image == 'undefined' || data.var_image == null) {
                            $("#var_image").html('');
                        } else {
                            var altText = "<?= lang('Blog.userProfileImage') ?>";
                            $("#var_image").append('<img src="' + sitepath + 'uploads/user/"' + data.var_image + ' alt="' + altText + '" width="100">');
                        }
                    });

                }
            });

        });
    });

    $("#fk_city").select2({
        placeholder: '<?= lang('Blog.pleaseSelectCity') ?>',
        allowClear: false,
    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function removeProperty(Id) {
        Swal.fire({
            title: '<?= lang('Blog.areYouSure') ?>',
            text: '<?= lang('Blog.youWantToDeleteThisProperty') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= lang('Blog.yesRemove') ?>',
            cancelButtonText: '<?= lang('Blog.btnCancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: sitepath + 'account/removeProperty',
                    type: 'post',
                    data: {
                        Id: Id
                    },
                    success: function(response) {
                        $("#property_section_" + Id).remove();
                    }
                });
            }
        });
    }

    function formatNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        }else{
            const input = evt.target;
            const value = input.value.replace(/\D/g, ""); // Remove non-digit characters
            if (value.length > 4) {
                input.value = value.slice(0, 4) + "-" + value.slice(4);
            }
        }
    }

    function propertyNotVerified() {
        Swal.fire({
            title: 'Oops!',
            text: "<?= lang('Blog.properyNotVerifiedByAdmin') ?>",
            icon: 'error',
        })
    }
</script>