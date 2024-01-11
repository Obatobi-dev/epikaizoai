<?php $this->view("inc/head");?>
<style type="text/css">
	label {
		font-size: 0.8rem;
		margin-bottom: 2px;
	}
	.sm-btn {
		padding: 0.6rem;
	}

	.form-group {
		margin: 0.5rem 0;
	}
</style>
<section id="wrapper">
	<aside id="dash__wrapper">
		<!-- Aside nav -->
		<?php $this->view("admin/aside");?>

		<section class="w-100">
			<!-- Top nav -->
			<?php $this->view("admin/nav");?>

			<!-- MAIN -->
			<main id="main" class="w-100">
				<article class="row align-items-start">
					<div class="col-md-5">
						<div class="card h-100">
							<div class="text-center mb-5">
								<img src="<?=ROOT?><?=MALE_PROFILE_IMAGE?>" class="rounded-pill img-thumbnail" style="object-fit: cover;height: 100px;width:100px;">
								<h3 class="my-2">Welcome admin</h3>
								<p>Manage your account here.</p>
								<p class="f-sm text-muted">This is your <small class="badge bg-primary">power</small></p>
							</div>
						</div>
					</div>

					<div class="col-md-7">
						<div class="card tab-content h-100">
							<ul class="nav nav-pills" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-bs-toggle="tab" href="#basic">Basic</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#advance">Advance</a>
								</li>
							</ul>

							<div id="basic" class="tab-pane active mt-3">
								<h3>Basic site setting</h3>
								<hr>
								<form class="" autocomplete="off" id="siteSetting_">
									<div class="form-group">
										<label>Deposit address</label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm"><img src="<?=COIN_ICON_API.strtolower("/".APP_CURRENCY.".svg")?>" alt="<?=APP_CURRENCY?>" style="width: 18px; height: 18px;object-fit: contain;"></span>
											<input type="text" name="deposit_wallet" class="form-control bg-light" placeholder="<?=APP_CURRENCY?> deposit wallet address" value="<?=DEPOSIT_WALLET?>">
										</div>
									</div>

									<div class="form-group">
										<label>Rebate on <?=APP_NAME?></label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm">Rebate %</span>
											<input type="text" name="rebate" class="form-control bg-light" placeholder="<?=APP_NAME?> rebate" value="<?=REBATE_AMOUNT?>">
										</div>
									</div>

									<h4 class="my-3">Social links</h4>

									<div class="form-group">
										<label>Facebook</label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm"><i class="fab fa-facebook"></i></span>
											<input type="text" name="facebook_link" class="form-control bg-light" placeholder="facebook link" value="<?=FACEBOOK_LINK?>">
										</div>
									</div>

									<div class="form-group">
										<label>Youtube</label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm"><i class="fab fa-youtube"></i></span>
											<input type="text" name="youtube_link" class="form-control bg-light" placeholder="Youtube link" value="<?=YOUTUBE_LINK?>">
										</div>
									</div>

									<div class="form-group">
										<label>Twitter</label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm"><i class="fab fa-twitter"></i></span>
											<input type="text" name="twitter_link" class="form-control bg-light" placeholder="Twitter link" value="<?=TWITTER_LINK?>">
										</div>
									</div>

									<div class="form-group">
										<label>Telegram</label>
										<div class="input-group">
											<span class="input-group-text bg-light text-muted f-sm"><i class="far fa-paper-plane"></i></span>
											<input type="text" name="telegram_link" class="form-control bg-light" placeholder="telegram link" value="<?=TELEGRAM_LINK?>">
										</div>
									</div>

									<button class="btn btn-primary sm-btn mt-3" form="siteSetting_" onclick="APP.authentication(this)">
										<input type="hidden" name="site_setting">
										<span>Save change</span>
									</button>
								</form>
							</div>
							<!-- Deposit wallet END -->

							<!-- Password setting BEGIN -->
							<div id="advance" class="tab-pane fade mt-3">
								<h3>Advance site setting</h3>
								<hr>
								<form class="" autocomplete="off" id="advanceSetting_">
									<div class="input-group form-group">
										<span class="input-group-text bg-light text-muted f-sm"><i class="far fa-lock-alt"></i></span>
										<input type="password" name="old_password" class="form-control bg-light" placeholder="Old password">
									</div>

									<div class="input-group form-group">
										<span class="input-group-text bg-light text-muted f-sm"><i class="fal fa-key"></i></span>
										<input type="password" name="new_password" class="form-control bg-light" placeholder="New password">
									</div>

									<div class="input-group form-group">
										<span class="input-group-text bg-light text-muted f-sm"><i class="fas fa-key-skeleton"></i></span>
										<input type="password" name="newpassword_rp" class="form-control bg-light" placeholder="Repeat">
									</div>
									<button class="btn btn-primary sm-btn mt-3" form="advanceSetting_" onclick="APP.authentication(this)">
										<input type="hidden" name="advance_setting">
										<span>Save change</span>
									</button>
								</form>
							</div>
							<!-- Password setting END -->
						</div>
					</div>
				</article>
					
			</main>
		</section>
	</aside>
</section>