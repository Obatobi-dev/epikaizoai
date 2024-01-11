<?php $this->view("inc/head");?>
<section id="wrapper" class="d-flex align-items-center justify-content-center">
	<article class="container-fluid w-100 mw-500 my-5">
		<form class="" autocomplete="off">
			<div class="mb-4">
				<h2>Admin login</h2>
				<p class="text-primary">Hello, admin. please enter your details</p>
			</div>
			<div class="form-group mt-0">
				<input type="text" class="form-control form-control-lg" name="username" placeholder="Enter username">
			</div>

			<div class="form-group">
				<input type="password" class="form-control form-control-lg" name="password" placeholder="Enter Password">
			</div>

			<button class="btn btn-primary w-100" onclick="APP.authentication(this)">
				<input type="hidden" name="admin_auth" value="<?=TIME?>">
				<span>Login</span>
			</button>
		</form>
	</article>
</section>
<?php $this->view("inc/footer");?>