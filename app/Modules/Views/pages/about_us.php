<main>
    <section class="home-banner inner-page bg_light overflow-hidden d-flex align-items-center pt-5 pb-5" style="background-image: url(<?= base_url(); ?>/public/front_assets/images/banner.png);">
        <div class="container">
            <div class="row align-items-center">
                <div class="content-part col-lg-12 pt-4 pb-4 pt-lg-0 pb-lg-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= lang('Blog.breadCumHome') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= lang('Blog.breadCumAboutUs') ?></li>
                        </ol>
                    </nav>
                    <div class="slider-des">
                        <h1 class="sl-title text-white"><?= lang('Blog.breadCumAboutUs') ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 pt-4 pb-4 pe-lg-5">
                    <h2 class="mb-3"><?= lang('Blog.aboutUsSection1Heading') ?></h2>
                    <p><?= lang('Blog.aboutUsSection1para1') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para2') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para3') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para4') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para5') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para6') ?></p>
                    <p><?= lang('Blog.aboutUsSection1para7') ?></p>
                    <div class="mt-5">
                        <div class="button-group">
                            <a href="<?= base_url() ?>/contactUs" class="btn btn-primary me-4 d-none"><?= lang('Blog.aboutUsBtnGetQuoteNow') ?></a>
                            <a href="<?= base_url() ?>/contactUs" class="btn btn-primary"><?= lang('Blog.aboutUsBtnLearnMore') ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pt-4 pb-4">
                    <div class="about_right"> <img src="<?= base_url(); ?>/public/front_assets/images/about-img.png" alt="about_img"> </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-5 pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12"> <!-- Swiper -->
                    <div class="swiper about_slider">
                        <div class="swiper-wrapper pb-5">
                            <div class="swiper-slide">
                                <div class="card p-4 sliderslide-card">
                                    <div class="card-body p-2">
                                        <div class="icon_title d-flex align-items-center mb-4 flex-wrap"> <img src="<?= base_url(); ?>/public/front_assets/images/icn_resize.svg" alt="slider-img">
                                            <h4><?= lang('Blog.aboutUsSlider1Heading1') ?></h4>
                                        </div>
                                        <p class="m-0 slidersslide-cardcontent"><?= lang('Blog.aboutUsSlider1para1') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card p-4 sliderslide-card">
                                    <div class="card-body p-2">
                                        <div class="icon_title d-flex align-items-center mb-4 flex-wrap"> <img src="<?= base_url(); ?>/public/front_assets/images/icn_resize.svg" alt="slider-img">
                                            <h4><?= lang('Blog.aboutUsSlider2Heading2') ?></h4>
                                        </div>
                                        <p class="m-0 slidersslide-cardcontent"><?= lang('Blog.aboutUsSlider2para2') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card p-4 sliderslide-card">
                                    <div class="card-body p-2">
                                        <div class="icon_title d-flex align-items-center mb-4 flex-wrap"> <img src="<?= base_url(); ?>/public/front_assets/images/icn_resize.svg" alt="slider-img">
                                            <h4><?= lang('Blog.aboutUsSlider3Heading3') ?></h4>
                                        </div>
                                        <p class="m-0 slidersslide-cardcontent"><?= lang('Blog.aboutUsSlider3para3') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card p-4 sliderslide-card">
                                    <div class="card-body p-2">
                                        <div class="icon_title d-flex align-items-center mb-4 flex-wrap"> <img src="<?= base_url(); ?>/public/front_assets/images/icn_resize.svg" alt="slider-img">
                                            <h4><?= lang('Blog.aboutUsSlider4Heading4') ?></h4>
                                        </div>
                                        <p class="m-0 slidersslide-cardcontent"><?= lang('Blog.aboutUsSlider4para4') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card p-4 sliderslide-card">
                                    <div class="card-body p-2">
                                        <div class="icon_title d-flex align-items-center mb-4 flex-wrap"> <img src="<?= base_url(); ?>/public/front_assets/images/icn_resize.svg" alt="slider-img">
                                            <h4><?= lang('Blog.aboutUsSlider5Heading5') ?></h4>
                                        </div>
                                        <p class="m-0 slidersslide-cardcontent"><?= lang('Blog.aboutUsSlider5para5') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-5 pb-5 bg_light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 pt-4 pb-4 pe-lg-5">
                    <div class="img"> <img src="<?= base_url(); ?>/public/front_assets/images/about-cover.png" alt="about us" class="w-100 border-radius-10"> </div>
                </div>
                <div class="col-lg-6 pt-4 pb-4">
                    <h3 class="mb-4"><?= lang('Blog.aboutUsSection2Heading') ?></h3>
                    <p><?= lang('Blog.aboutUsSection2para1') ?></p>
                    <p><?= lang('Blog.aboutUsSection2para2') ?></p>
                    <p><?= lang('Blog.aboutUsSection2para3') ?></p>
                    <p><?= lang('Blog.aboutUsSection2para4') ?></p>
                    <p><?= lang('Blog.aboutUsSection2para5') ?></p>

                </div>
            </div>
        </div>
    </section>
</main>