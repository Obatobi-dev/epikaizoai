<style type="text/css">
    nav.top-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 99;
        transition: background 0.1s;
    }
    nav.top-nav li a, nav.top-nav a.nav-brand {
        display: block;
        padding: 12px 10px;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        text-transform: capitalize;
        transition: all 0.3s;
    }

    nav.top-nav li a:hover, nav.top-nav a.nav-brand:hover {
        opacity: 0.8;
    }
    nav.top-nav.scrolled {
        background: var(--color-white);
        box-shadow: 0 0 10px rgba(0 0 0 / 10%);
    }
    nav.top-nav.scrolled a.nav-brand {
        color: var(--color-primary);
    }
</style>
<nav class="p-3 top-nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="<?=ROOT?>/" class="nav-brand"><?=LOGO_IMAGE_HTML?></a>

        <div class="nav-2 cta">
            <?php if(!$isLogged = \Model\User_Auth::isLoggedIn(1)):?>
            <a href="<?=ROOT?>/auth/login/" class="btn btn-danger">Get started</a>
            <?php else:?>
            <a href="<?=ROOT?>/user/dashboard/" class="btn btn-danger"><i class="fal fa-wallet"></i> <span><?=ucfirst(explode(' ', $isLogged->fullname)[0])?></span> <i class="fas fa-caret-down"></i></a>
            <?php endif;?>
        </div>
    </div>
</nav>

<script type="text/javascript">
window.onscroll = function () {
    var header_navbar = document.querySelector(".top-nav");
    var sticky = header_navbar.offsetTop;

    if (window.pageYOffset > sticky) {
        header_navbar.classList.add("scrolled");
    } else {
        header_navbar.classList.remove("scrolled");
    }
}

var LOG = {

}
</script>