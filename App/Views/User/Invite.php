<?php $this->view("inc/head");?>
<?php $unique = BASE.'/invite/'.$id.'/';?>
<style type="text/css">
	@media screen and (min-width: 992px){
		#pageHero_ {
			background-position: right;
		}
	}

	#howto .card, #howto .card img {
		border-radius: var(--border-radius-lg);
	}
</style>
<section id="wrapper" class="bg-light">
	<?php $this->view(USER_ROOT."/nav");?>
	<main class="container">
		<!-- Hero -->
		<article class="card large text-light" id="pageHero_" style="background-image: url('<?=ROOT?>/public/static/img/respiratory/refer.png');">
			<div class="inner mw-600">
				<h2 class="text-bolder">Refer Friends. &Earn Crypto Together.</h2>
				<p class="my-3" style="max-width: 70%;">Earn up to 2% commission on every trade across <?=APP_NAME?> subscriptions</p>
				<button class="btn btn-danger" onclick="copy('<?=$unique?>')">Refer a friend now</button>
			</div>
		</article>

		<!-- How to -->
		<article class="py-5" id="howto">
			<!-- Header -->
			<div class="my-3 mw-600 container text-center">
				<h2 class="text-dark">How to refer friends and earn commission</h2>
				<p>Quick start guide</p>
			</div>

			<div class="row">
				<div class="col-md-6 col-lg-4">
					<div class="card">
						<img src="<?=ROOT?>/public/static/img/respiratory/ref-a.jpg" class="img-fluid">
						<div class="mt-4">
							<h3>Get link</h3>
							<p>Get your unique affiliate link. Copy your <a href="javascript:void(0)" onclick="copy('<?=$unique?>')" class="btn btn-primary">link here</a></p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="card">
						<img src="<?=ROOT?>/public/static/img/respiratory/ref-b.jpg" class="img-fluid">
						<div class="mt-4">
							<h3>Share</h3>
							<p>Share the referral link with your friends or on social media.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="card">
						<img src="<?=ROOT?>/public/static/img/respiratory/ref-c.jpg" class="img-fluid" style="border-radius: 16px;">
						<div class="mt-4">
							<h3>Reward</h3>
							<p>Earn rewards from rebate of bot and trades</p>
						</div>
					</div>
				</div>
			</div>
		</article>

		<article class="card large">
			<h3 class="text-dark">Your invites rebate</h3>
			<div class="table-responsive no-shadow rounded-3">
				<table class="table table-light table-hover table-striped table-borderless">
					<thead>
						<tr class="py-3">
							<th>Subscription</th>
							<th>Day</th>
							<th>Rebate / Amount</th>
							<th>Date</th>
						</tr>
					</thead>

					<tbody>
						<tr class="no-result"><td colspan="4" class="text-center text-primary"><p class="fad fa-file-search fa-lg my-3" style="font-size: 60px;"></p><p>No result</p></td></tr>
					</tbody>
				</table>
		</article>
	</main>
</section>

<script type="text/javascript">
/*$(()=>{
	APP.getInfo("botsub", APP.CONFIG.GETTER){
		
	}
})
*/</script>