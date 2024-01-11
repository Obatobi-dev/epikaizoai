<?php $ADMIN = new \Model\Admin;?>
<script type="">
	// Logs
	USER = <?=$ADMIN->findAll('user');?>;
	TRADE = <?=$ADMIN->findAll('trade');?>;
	WITHDRAWAL = <?=$ADMIN->findSingle('wallet', ['type' => "withdrawal"]);?>;
	DEPOSIT = <?=$ADMIN->findSingle('wallet', ['type' => "deposit"]);?>;
	KYC = <?=$ADMIN->findAll('kyc');?>;
	BOT_SUB = <?=$ADMIN->findAll('botsub');?>;
	BOT = <?=$ADMIN->findAll('bot');?>;
</script>
<style type="text/css">
/*==================== DASHBOARD ========================*/
#dash__wrapper {
	display: grid;
	grid-template-columns: 0 100%;
	overflow: hidden;
}

#main .card h5 {
	font-weight: 500;
}

#aside {
	height: 100vh;
	overflow: hidden;
}
.sidebar {
	position: fixed;
	left: 0;
	height: 100%;
	overflow: hidden;
	overflow-y: auto;
	width: 0;
	z-index: 6;
	transition: width 0.21s;
	white-space: nowrap;
	background: white;
	padding: 0;
	box-shadow: var(--box-shadow);
	background: url('<?=ROOT.BG3_IMAGE?>') no-repeat right;
	background-size: cover;
}

.sidebar.active {
	width: 16rem;

}

.sidebar .inner {
/*	margin-top: 20%;*/
}
.sidebar a {
	font-size: 1rem;
	display: flex;
	gap: 1rem;
	align-items: center;
	position: relative;
	transition: all 0.3s ease;
	color: var(--color-white);
	text-decoration: none;
/*	margin: 2% 0;*/
	padding: 12px;
}
.sidebar a span {
/*	font-size: 13px;*/
}
.sidebar a span:first-letter {
	text-transform: uppercase;
}
.sidebar a i {
	font-size:;
}
.sidebar a:hover, #link-active {
	background: var(--color-primary);
    color: var(--color-light) !important;
}

#dash__wrapper:nth-child(2){
	background: red;
	display: none !important;
}


/*==================== QUERIES ========================*/
/* Big screens */
@media screen and (min-width: 1020px){
	#dash__wrapper {
		grid-template-columns: 16rem auto;
	}
	.sidebar {
		width: 16rem;
	}

	#open-nav {
		display: none;
	}
}
#aside .sidebar .head {
	padding: 2rem 20px;
	color: var(--color-light);
	text-align: center;
	text-transform: capitalize;
}

</style>

<!-- Aside for nav -->
<aside id="aside">
	<ul class="sidebar no-shadow" id="sidebar">
		<div class="head">
			<h3><?=APP_NAME?></h3>
		</div>

		<div class="inner">
			<li>
				<a href="<?=ROOT;?>/"><i class="fal fa-home"></i><span>home</span></a>
			</li>
			<li>
				<a href="<?=ROOT;?>/myadmin/dashboard/"><i class="far fa-user"></i><span>dashboard</span></a>
			</li>
			<h5 class="text-white m-3">Manager</h5>
			<li>
				<a href="<?=ROOT;?>/myadmin/client/"><i class="fal fa-users"></i><span>user</span><small class="badge bg-danger" id=""></small></a>
			</li>
			<li>
				<a href="<?=ROOT;?>/myadmin/kyc/"><i class="fal fa-eye"></i><span>kyc</span><small class="badge bg-danger" id="asideTotalKyc_"></small></a>
			</li>
			<li>
				<a href="<?=ROOT;?>/myadmin/deposit/"><i class="fal fa-coin"></i><span>deposit</span><small class="badge bg-danger" id="asideTotalDeposit_"></small></a>
			</li>
			<li>
				<a href="<?=ROOT;?>/myadmin/withdrawal/"><i class="fal fa-university"></i><span>withdrawal</span><small class="badge bg-danger" id="asideTotalWithdrawal_"></small></a>
			</li>
			<li>
				<a href="<?=ROOT;?>/myadmin/bot/"><i class="fal fa-robot"></i><span>bot</span><small class="badge bg-danger" id="asideTotalBot_"></small></a>
			</li>
			<!-- <li>
				<a href="<?=ROOT;?>/myadmin/trade/"><i class="fal fa-theater-masks"></i><span>trade</span></a>
			</li> -->
			<h5 class="text-white m-3">Setting</h5>
			<li>
				<a href="<?=ROOT;?>/myadmin/setting/"><i class="fal fa-wrench"></i><span>Admin setting</span></a>
			</li>
		</div>
	</ul>
</aside>

<script type="text/javascript">
	$(() => {
		if(DEPOSIT.length){
			let total = 0;
			DEPOSIT.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)
				if(DETAIL.status === 'pending') total++;
			})

			if(total) $("#asideTotalDeposit_").html(total);
		}

		if(WITHDRAWAL.length){
			let total = 0;
			WITHDRAWAL.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)
				if(DETAIL.status === 'pending') total++;
			})

			if(total) $("#asideTotalWithdrawal_").html(total);
		}

		// User with pending kyc
		if(KYC.length){
			let total = 0;
			KYC.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)
				if(DETAIL.status === 'under_review') total++;
			})

			if(total) $("#asideTotalKyc_").html(total);
		}

		if(BOT_SUB.length){
			let total = 0;
			BOT_SUB.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)
				if(DETAIL.status === 'running') total++;
			})
			if(total) $("#asideTotalBot_").html(total);
		}

		// Open asside nav in ADMIN
	    $("#open-nav").click(function(){
	    	$("#sidebar").toggleClass("active")
	    })

	    // All aside a toogle for active
	    var aside_a = $("#aside a");
	    for(let i = 0; i < aside_a.length; i++){
	        let self = aside_a[i]
	        let href = aside_a[i].href
	        let locator = location.href

	        // If the href attr is equal to browser url. Then set active the href self
	        if(href == locator){
	            $(self).attr({id: "link-active"})
	        }
	    }
	})
</script>