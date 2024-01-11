<style type="text/css">
	.user-nav {
		background: var(--color-white);
		box-shadow: 0 0 5px rgba(0 0 0 / 40%);
		position: sticky;
		top: 0;
		z-index: 4;
	}

	.card {
/*		border-radius: var(--border-radius-lg);*/
	}

	.user-nav .nav-link, .user-nav .brand {
		padding: 28px 16px;
		font-size: 1.2rem;
		color: rgba(0 0 0 / 60%);
	}
	.user-nav .drp-menu li a {
		transition: all 0.3s;
	}
	.user-nav .drp-menu li a i {
		margin-right: 12px;
	}
	.user-nav .drp-menu li a span {
		font-size: 14px;
		text-transform: capitalize;
	}
	.user-nav .drp-menu li a:hover {
		background: none !important;
		color: var(--color-primary);
	}
	.dropdown-divider {
		color: rgba(0 0 0 / 0.6%) !important;
	}
	#pageHero_ {
		background-color:  var(--color-dark);
		background-image: url('https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1a63530be6e374711a8554f31b17e4cb92c25fa5/svg/color/eth.svg');
		background-repeat: no-repeat;
		background-position: right;
		background-position: right 150px;
		background-size: contain;
	}
	@media screen and (min-width: 768px){
		#pageHero_ {
			background-position: right 100px;
		}
	}
</style>
<nav class="user-nav text-black">
	<section class="container">
		<div class="d-flex align-items-center justify-content-between">
			<div class="brand">
				<a href="<?=ROOT?>/" class=""><?=LOGO_IMAGE_HTML?></a>
			</div>

			<div class>
				<ul class="list-group list-group-horizontal">
					<li><a href="<?=ROOT.USER_ROOT_PATH?>/dashboard/" class="nav-link"><i class="fal fa-user-circle"></i></a></li>
					<!-- <li><a href="javascript:void(0)" class="nav-link"><div class="form-check form-switch"><input type="checkbox" class="form-check-input"></div></a></li> -->
					<li><a href="<?=ROOT.USER_ROOT_PATH?>/wallet/" class="nav-link"><i class="fal fa-wallet"></i></a></li>
					<li class="drp-menu">
						<a href="" class="dropdown-toggle nav-link drp-btn" data-bs-toggle="dropdown"><i class="fal fa-user-alien"></i></a>
						<ul class="dropdown-menu p-3 drp-menu">
							<!-- <h4 class="text-cap"><?=$fullname?></h4> -->
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/dashboard/"><i class="fal fa-user-circle"></i> <span>Dashboard</span></a></li>
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/wallet/"><i class="fal fa-wallet"></i> <span>wallet</span></a></li>
							
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/bot/"><i class="fal fa-robot"></i> <span>bot</span></a></li>
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/trade/"><i class="fal fa-chart-bar"></i> <span>trade</span></a></li>
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/setting/"><i class="fal fa-cog"></i> <span>account setting</span></a></li>
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/invite/"><i class="fal fa-share"></i> <span>affiliate</span></a></li>
							<li><hr class="dropdown-divider text-dark"></li>
							<li><a class="dropdown-item" href="<?=ROOT.USER_ROOT_PATH?>/logout/"><i class="fal fa-sign-out"></i> <span>log out</span></a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</section>
</nav>