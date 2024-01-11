<?php $this->view("inc/head");?>
<section id="wrapper">
	<?php $this->view("inc/nav");?>
	<?php $this->view("page/hero");?>


	<section class="container section">
		<article class="row">
			<div class="col-lg-7">
				<div class="card h-100 p-0">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d317716.6064394642!2d-0.4312427586114913!3d51.5286070149551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondon%2C%20UK!5e0!3m2!1sen!2sng!4v1698776217359!5m2!1sen!2sng" width="600" height="450" style="border:0;width: 100%; height: 100%;min-height: 350px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
			</div>

			<style type="text/css">
			ul#social li a {
				color: var(--color-primary);
				trasition: all 0.3s !important;
				padding: 4px 0;
				display: inline-block;
				text-transform: capitalize;
				text-decoration: none;
			}
			ul#social li a:hover {
				opacity: 0.7;
			}
			ul#social li a#youtube {
				color: var(--bs-danger);
			}
			ul#social li a#twitter {
				color: var(--bs-dark);
			}
			ul#social li a#telegram {
				color: var(--bs-info);
			}
			</style>
			<div class="col-lg-5">
				<div class="card h-100">
					<!-- <div>
						<h3>Get in youch !</h3>
						<ul class="list-group list-group-vertical social" id="social">
							<li><a href="<?=FACEBOOK_LINK?>" id="facebook"> <i class="fab fa-facebook"></i> facebook</a></li>
							<li><a href="<?=TELEGRAM_LINK?>" id="telegram"><i class="far fa-paper-plane"></i>telegram</a></li>
							<li><a href="<?=YOUTUBE_LINK?>" id="youtube"> <i class="fab fa-youtube"></i> youtube</a></li>
							<li><a href="<?=TWITTER_LINK?>" id="twitter"> <i class="fab fa-twitter"></i>twitter</a></li>
						</ul>
					</div> -->

					<form class="mt-4" autocomplete="off">
						<!-- <h3>Get in touch !</h3> -->
						<!-- <h3>Send email !</h3> -->
						<div class="form-group m-0">
							<input type="text" name="email" placeholder="Email address" class="form-control form-control-sm">
						</div>
						<div class="form-group m-0">
							<input type="text" name="subject" placeholder="reason / subject" class="form-control form-control-sm">
						</div>
						<div class="form-group m-0">
							<textarea class="form-control" placeholder="Enter message" name="message"></textarea>
						</div>
						<p class="bg-danger border-radius-sm p-2 text-light mb-2"><i class="fal fa-info-circle"></i> Response will be sent to your email !</p>
						<button class="btn w-100 btn-primary" onclick="APP.authentication(this)">
							<span>Send message</span>
							<input type="hidden" name="contact_us">
						</button>
					</form>
				</div>
			</div>
		</article>
	</section>
</section>
<?php $this->view("inc/footer");?>