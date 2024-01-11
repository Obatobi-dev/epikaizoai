<style>
footer a {
	text-decoration: none;
}
footer ul {
	list-style: none;
}
footer ul.solution li a {
	color: var(--color-white);
	trasition: color 0.3s;
	padding: 8px 0;
	display: block;
	transition: all 0.3s;
}
footer ul.solution li a:hover {
	color: var(--color-primary);
}
footer ul.social li a {
	color: var(--color-white);
	trasition: all 0.3s;
	font-size: 1.4rem;
	display: inline-block;
	padding: 0 12px 0 0;
}
footer ul.social li a:hover {
	color: var(--color-primary);
}
</style>
<footer class="bg-black pt-5 p-1 text-light">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-6 col-12">
				<div class="card bg-none">
					<div class="">
						<h4 class="text-bolder">About <?=APP_NAME_ABBR?></h4>
						<p class="f-sm"><?=APP_NAME?> is a software program based on <?=APP_CURRENCY?> exchange market price movements that helps trade without human assistance.</p>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-6 col-12">
				<div class="card bg-none">
					<h4 class="text-bolder">Solution</h4>
					<ul class="list-group solution">
						<li><a href="<?=ROOT?>/">Home</a></li>
						<li><a href="<?=ROOT?>/page/about/">About <?=APP_NAME_ABBR?></a></li>
						<li><a href="<?=ROOT?>/page/contact/">Contact <?=APP_NAME_ABBR?></a></li>
						<li><a href="<?=ROOT?>/page/privacy-policy/">Privacy policy</a></li>
						<li><a href="">User agreement</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-5 col-md-6 col-12">
				<div class="card bg-none">
					<h4 class="text-bolder">Subscribe</h4>
					<div class="input-group">
						<input type="" class="form-control" placeholder="Enter email">
						<button class="btn btn-primary" style="min-width: 100px;"><i class="fal fa-paper-plane"></i></button>
					</div>
					<p class="mt-2">Be the first person to receive <b>first-hand News</b> from <b><?=APP_NAME?></b></p>
				</div>
			</div>

			<div class="col-12">
				<div class="card bg-none">
					<div class="d-md-flex align-items-center justify-content-between">
						<p class="text-cap">© <?=APP_NAME?> - All Rights Reserved</p>
						<!-- <p>Developed by <a href="">OTP</a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>