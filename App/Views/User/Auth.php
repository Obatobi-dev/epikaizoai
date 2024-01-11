<?php $this->view("inc/head");?>
<style type="text/css">
.otp-input-wrapper {
width: 240px;
text-align: left;
display: inline-block;
padding: 20px 0;
}
.otp-input-wrapper input {
padding: 0;
width: 264px;
/*width: 100%;*/
font-size: 22px;
display: block;
font-weight: 600;
color: var(--color-dark);
background-color: transparent;
border: 0;
margin-left: 2px;
letter-spacing: 30px;
font-family: sans-serif !important;
}
.otp-input-wrapper input:first-word {
	color: red;
}
.otp-input-wrapper input:focus {
box-shadow: none;
outline: none;
caret-color: var(--color-dark);
}
.otp-input-wrapper svg {
position: relative;
display: block;
width: 240px;
height: 3px;
stroke: var(--color-primary);
}
.breadcrumbs:before {
	content: '';
	background: rgba(80 0 0 / 70%);
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.breadcrumbs {
	padding-top: 160px;
	padding-bottom: 120px;
	z-index: 2;
	text-align: left;
	background-color: #24126A;
	background-image: url("<?=ROOT.BANNER2_IMAGE?>");
	background-size: cover;
	background-position: right;
	background-repeat: no-repeat;
	-webkit-box-shadow: 0px 7px 30px rgba(0, 0, 0, 0.075);
	box-shadow: 0px 7px 30px rgba(0, 0, 0, 0.075);
	color: var(--color-white);
}

/*#verifyBtn_ {
	pointer-events: none;
}
#verifyBtn_.active {
	pointer-events: auto;
}*/
.inputs {
	display:flex;
	align-items: center;
	justify-content: center;
	flex-direction: row;
	column-gap: 10px;
}
.inputs input {
	height: 34px;
	width: 34px;
	border-radius: 4px;
	outline: none;
	font-weight:900;
	text-align: center;
	border: 1px solid var(--color-primary);
}

.inputs input:focus {
	border: 3px solid var(--color-primary);
}

@media screen and (min-width: 768px){
	.inputs input {
		height: 40px;
		width: 40px;
	}
}
</style>
<?php
// Check mode and check if the OTP data's is still active
$mode_title = $mode;
$auth_key = \Model\Session::read(G2FA);

// show([$encrypted, base64_encode("Report"), base64_decode("UmVwb3J0")], 1);

if($mode === 'verify'){
	if($data = \Model\Session::read(VERIFICATION_DATA)){
		extract($data);
	} else {
		redirect('/auth/register/');
	}
} else if($mode === '2fa'){
	if(!$auth_key){
		redirect("/auth/login/");
	}

	$auth_key = (object) $auth_key;

	$mode_title = "2FA";
	// Auth secret key from the user account
	define("G2FA_SECRET", $auth_key->secret_key);
	define("G2FA_EMAIL", $auth_key->email);
} else if($mode === 'login'){
	if($auth_key){
		redirect("/auth/2fa/");
	} else if(\Model\Session::read(USER_COOKIE)){
		redirect(USER_ROOT_PATH."/dashboard/");
	}
}
?>
<section id="wrapper">
	<?php $this->view("inc/nav");?>
	<!-- Hero -->
	<div class="section breadcrumbs">
		<div class="container mw-400 mx-auto text-center">
			<h3>Authentication</h3>
			<p><a href="<?=ROOT?>/">Home</a> <i class="fas fa-caret-right mx-3"></i> <span><?=ucfirst(str_replace("-", " ", $mode))?></span></p>
		</div>
	</div>

	<section class="section bg-light">
		<div class="container mw-600 mx-auto text-center">
			<div class="card">
				<!-- Form header -->
				<?php if($mode !== 'verify'):?>
				<div class="my-3">
					<h3 class="text-dark text-bolder"><?=ucfirst(str_replace("-", " ", $mode_title))?> now</h3>
					<p>Use the form below to complete action</p>
					<!-- <p>Let's start with your email: Enter your email to log into your existing account, or click Create Account</p> -->
				</div>
				<?php endif;?>
				
				<?php if($mode === 'register'):?>
				<!-- Registeration BEGIN -->
				<form autocomplete="off">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-user"></i></span>
								<input type="text" name="first_name" placeholder="first name" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-user-alien"></i></span>
								<input type="text" name="last_name" placeholder="last name" class="form-control">
							</div>
						</div>

						<div class="col-12">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-at"></i></span>
								<input type="email" name="email" placeholder="email" class="form-control">
							</div>
						</div>

						<div class="col-12">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-phone-alt"></i></span>
								<input type="tel" name="phone" placeholder="phone" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-key"></i></span>
								<input type="password" name="password" placeholder="password" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="input-group form-group">
								<span class="input-group-text text-dark"><i class="fal fa-key"></i></span>
								<input type="password" name="password_rp" placeholder="password (repeat)" class="form-control">
							</div>
						</div>

						<div class="col-md-12">
							<div class="input-group form-group">
								<span class="input-group-text text-dark f-sm">Code</span>
								<input type="text" name="invite" placeholder="enter invitation code" class="form-control" value="<?=\Model\Cookie::read("invite") ?? ''?>">
							</div>

							<div class="form-check form-switch text-start f-sm">
								<input type="checkbox" class="form-check-input" name="privacy_policy" id="privacy-policy">
								<span><label class="form-check-label" for="privacy-policy">I've read and accepted the</label> <a href="<?=ROOT?>/page/privacy-policy/" target="blank">Privacy policy</a></span>
							</div>
						</div>
					</div>

					<p class="text-end my-2 f-sm">Already have an account? <a href="../login/">login</a></p>
					<button class="btn w-100 btn-primary" onclick="APP.authentication(this)">
						<input type="hidden" name="auth_signup">
						<span>Create a free account</span>
					</button>
				</form>
				<!-- Registration END -->
				<?php elseif($mode === 'login'):?>
				<!-- Login BEGIN -->
				<form autocomplete="off">
					<div class="input-group form-group">
						<span class="input-group-text text-dark"><i class="fal fa-at"></i></span>
						<input type="text" name="email" placeholder="Enter email" class="form-control">
					</div>

					<div class="input-group form-group">
						<span class="input-group-text text-dark"><i class="fal fa-key"></i></span>
						<input type="password" name="password" placeholder="Enter your password" class="form-control">
					</div>

					<div class="my-4">
						<div class="d-flex justify-content-between align-items-center">
							<div class="form-check form-switch">
								<input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe_">
								<label class="form-check-label" for="rememberMe_">Remeber me</label>
							</div>

							<a href="../reset-password/">Forgot password? </a>
						</div>

						<p class="text-center mt-4">Don't have an account ? <a href="../register/">Create free account</a></p>
					</div>

					<button class="btn w-100 btn-primary" onclick="APP.authentication(this)">
						<input type="hidden" name="auth_login">
						<span><?=ucfirst($mode)?> to your account</span>
					</button>
				</form>
				<!-- Login END -->
				<?php elseif($mode === 'reset-password'):?>
				<!-- Reset password BEGIN -->
				<form autocomplete="off">
					<div class="input-group form-group">
						<span class="input-group-text text-dark"><i class="fal fa-at"></i></span>
						<input type="text" name="email" placeholder="Enter email" class="form-control">
					</div>
					<button class="btn w-100 btn-primary" onclick="APP.authentication(this)">
						<input type="hidden" name="auth_reset_password">
						<span><?=ucfirst(str_replace("-", " ", $mode))?></span>
					</button>
				</form>
				<!-- Reset password end -->
				<?php elseif($mode === 'verify' || $mode === '2fa'):?>
				<!-- Verify email address BEGIN -->
				<form class="">
					<?php if($mode === 'verify'):?>
					<div>
						<img src="<?=ROOT.MALE_PROFILE_IMAGE?>" style="width: 110px; height: 110px;" class="img-fluid rounded-pill bg-light p-1">

						<div class="my-3">
							<h4>Hello, <?=ucfirst($fullname)?></h4>
							<p>Enter the OTP verification code sent to</p>
							<b><?=substr_replace($email, '****', 3, strlen($email) / 2.5)?></b>
						</div>
					</div>
					<?php elseif($mode === '2fa'):?>
					<div class="container">
						<img src="<?=\Sonata\GoogleAuthenticator\GoogleQrUrl::generate(G2FA_EMAIL, G2FA_SECRET, APP_NAME)?>" class="img-fluid my-3" style="width: 200px; height: 200px;">
						<p><b>Issuer:</b> <?=APP_NAME?> <span onclick="copy('<?=APP_NAME?>')" class="badge bg-primary cursor-pointer">copy</span></p>
						<p class="mb-3"><b>Secret key:</b> <?=G2FA_SECRET?> <span onclick="copy('<?=G2FA_SECRET?>')" class="badge bg-primary cursor-pointer">copy</span></p>
						<p>Please install <b>Authenticator app</b> on your phone or PC. open it and then scan the above QR code to complete your login</p>
						<p>After you have added this application then enter the <b>6 digit code</b> into the box below</p>
					</div>
					<?php endif;?>

					<section>
						<div class="otp-input-wrapper">
							<input type="text" id="codeBox" maxlength="6" pattern="[^0-9]*" autocomplete="off" oninvalid="return false;" onfocus="this.select();" onblur="doIt()" value="">
							<svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
								<line x1="0" y1="0" x2="240" y2="0" stroke-width="4" stroke-dasharray="20,22" />
							</svg>
						</div>
						<?php if($mode === 'verify'):?>
						<p>Didn't get any code?</p>
						<a href="">Resend</a>
						<?php endif;?>
					</section>

					<?php if($mode === 'verify'):?>
					<!-- Deletion warning -->
					<div class="card no-shadow d-block bg-light">
						<span class="spinner-border-sm spinner-border"></span>
						<span id="timeLeft">OTP expires in: 00h 00m 00s</span>
					</div>
					<?php endif?>

					<button class="btn d-none btn-primary w-100" id="verifyBtn_" onclick="APP.authentication(this)">
						<span>Verify</span>
						<input type="text" name="auth_<?=$mode?>">
						<input type="text" name="code" value="">
					</button>
				</form>
				<!-- Verify email address END -->
				<?php endif;?>
			</div>
		</div>
	</section>
</section>
<?php $this->view("inc/footer");?>
<?php if($mode === 'verify' || $mode === '2fa'):?>
<script>
const MAX = 6, button = document.querySelector("#verifyBtn_");
let Type = ''
function doIt(){
	let val = event.target.value;
	if(val.length == MAX){
		$("[name=code]").val(val)
		button.click();
	}
}

/*val = e.clipboardData.getData('Text');
val = val.split('')
val = val.splice(0, 6); // Get only first 6
val = val.join('')*/
<?php if($mode === 'verify'):?>
// Account deletion timer
function deleteTimer(){
	let diff = timeDiff('<?=$otp_expire?>', '<?=$timezone?>')

	if(diff.second < 0 && diff.minute <= 0){
		clearInterval(deleteAccountTimer)
		location = `${APP.CONFIG.ORIGIN}/auth/register/`
		return
	}

	$("#timeLeft").html(`OTP expires in: ${diff.hour}h ${diff.minute}m ${diff.second}s`)
}

let deleteAccountTimer = setInterval(deleteTimer, 1000)
deleteTimer();
<?php endif;?>
</script>
<?php endif;?>