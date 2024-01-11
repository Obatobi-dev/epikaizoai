<?php $this->view("inc/head");?>
<style>
.bot-sub-btn {
    position: absolute;
    bottom: 0;
    left: 0;
}
</style>

<section id="wrapper">
    <?php $this->view("inc/nav");?>
    <!-- Start Hero Area -->
    <section class="hero-area">
        <img class="hero-shape" src="<?=ROOT?>/public/static/img/respiratory/hero-shape.svg" alt="#">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="hero-content">
                        <h4 class="wow fadeInUp" data-wow-delay=".2s">Start Investing & Earn Money</h4>
                        <h1 class="wow fadeInUp" data-wow-delay=".4s">Say goodbye
                            to <br>idle
                            <span>
                                <img class="text-shape" src="<?=ROOT?>/public/static/img/respiratory/text-shape.svg" alt="#">
                                money.
                            </span>
                        </h1>
                        <p class="wow fadeInUp" data-wow-delay=".6s">Invest your spare change in Bitcoin and save
                            with<br> crypto to earn interest in real time.
                        </p>
                        <div class="button wow fadeInUp" data-wow-delay=".8s">
                            <a href="<?=ROOT?>/auth/login/" class="btn btn-danger">Discover More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="hero-image">
                        <img class="main-image" src="<?=ROOT?>/public/static/img/respiratory/home2-bg.png" alt="#">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->

    <!-- Start Feature Area -->
    <section class="feature section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Why choose us</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Our features</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">See how to get started</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".2s">
                    <div class="feature-box">
                        <div class="tumb">
                            <img src="<?=ROOT?>/public/static/img/respiratory/feature-icon-1.png" alt="">
                        </div>
                        <h4 class="text-title">Instant Exchange</h4>
                        <p class="f-sm">Invest in Bitcoin on the regular or save with one of the highest interest rates on the
                            market.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="feature-box">
                        <div class="tumb">
                            <img src="<?=ROOT?>/public/static/img/respiratory/feature-icon-2.png" alt="">
                        </div>
                        <h4 class="text-title">Safe & Secure</h4>
                        <p class="f-sm">We merge our apex security practices to help you have a seemless transaction security in-app.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".6s">
                    <div class="feature-box">
                        <div class="tumb">
                            <img src="<?=ROOT?>/public/static/img/respiratory/feature-icon-3.png" alt="">
                        </div>
                        <h4 class="text-title">Instant Trading</h4>
                        <p class="f-sm">Create an account to start to trade on our platform with low risk.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Features Area -->

    <!-- Start now -->
    <section class="section bg-light start-now">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Ready To Get Started?</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">3 steps to start</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">Easy to read</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card steps">
                        <div class="number">1.</div>
                        <h3 class="text-dark">Create an account</h3>
                        <p>Create your private and unique account by clicking <a href="<?=ROOT?>/auth/register/">here</a></p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card steps">
                        <div class="number">2.</div>
                        <h3 class="text-dark">Deposit</h3>
                        <p>After registeration login <a href="<?=ROOT?>/auth/login/">here</a>. Navigate to deposit and complete it</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card steps">
                        <div class="number">3.</div>
                        <h3 class="text-dark">Purchase a plan</h3>
                        <p>Purchase a plan and Begin to trade with our friendly and easy to navigate platform</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans -->
    <section class="section start-now">
        <div class="container">
            <article class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h3 class="wow zoomIn" data-wow-delay=".2s">Bot</h3>
                        <h2 class="wow fadeInUp" data-wow-delay=".4s"><?=count(json_decode(BOT_PLANS))?> <?=APP_NAME?> for you</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s"><?=APP_NAME?> get traded on your behalf.</p>
                    </div>
                </div>
                
                <!-- Bot -->
                <?php $this->view('inc/bot')?>
            </article>
        </div>
    </section>
</section>
<?php $this->view("inc/footer");?>