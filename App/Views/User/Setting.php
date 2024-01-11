<?php $this->view("inc/head");?>
<style type="text/css">
	.profile-card {
		text-align: center;
		border-radius: 45px;
	}
	.cover-pic {
		display: block;
		width: 100%;
		height: 200px;
		object-fit: cover;
		user-select: none;
	}
	#profile-pic, #reket {
		width: 140px;
		height: 140px;
		border-radius: 50%;
		margin-top: -80px;
		background: var(--color-light);
/*		outline: 4px dotted var(--color-primary);*/
		object-fit: cover;
		padding: 4px;
	}
	#reket {
	    margin: 15px !important;
	}
	#editPhotoBtn_ {
		position: absolute;
		right: 4px;
		bottom: 80px;
	}
</style>
<section id="wrapper" class="bg-light">
	<?php $this->view(USER_ROOT."/nav");?>
	<main class="container">
		<!-- User setting START -->
		<section class="row">
			<!-- Personal info begins -->
			<div class="col-12">
				<div class="card h-100 p-0 border-radius-lg bg-secondary-v">
					<div class="profile-card">
						<img src="<?=ROOT.BG1_IMAGE?>" class="cover-pic border-radius-lg">
						<img src="<?=ROOT.$image?>" id="profile-pic">

						<div data-image-upload>
							<label class="btn btn-secondary btn-sm" id="editPhotoBtn_" for="profileImageUpload_"><i class="fal fa-camera"></i> <span>Edit</span></label>
							<form>
								<input type="file" class="d-none" name="image" id="profileImageUpload_" accept="image/*">
								<input type="hidden" name="profile_image_helper">
							</form>
						</div>
						
						<div class="p-4">
							<h3 class="text-cap"><?=$fullname?></h3>
							<p><?=$email?></p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-7">
				<div class="card h-100">
					<div class="d-flex align-items-center justify-content-between">
						<h4 class="text-dark d-flex">Personal information</h4>
						<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#myModal">Upload KYC</button>
					</div>
					<hr>
					<div class="">
						<form id="personalInfo">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-dark">Full name</label>
										<input class="form-control" type="text" name="fullname" value="<?=$fullname?>" placeholder="Enter full name">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label class="text-dark">Email</label>
										<input class="form-control" type="text" name="email" value="<?=$email?>" placeholder="Enter email">
									</div>
								</div>
							</div>
									
							<div class="form-group">
								<label class="text-dark">Withdrawal wallet address</label>
								<input class="form-control" name="wallet" value="<?=$wallet?>" placeholder="Enter withdrawal wallet">
							</div>
							
							<div class="form-group">
								<label class="text-dark">Bio</label>
								<textarea class="form-control" name="bio" placeholder="Enter bio"><?=$bio?></textarea>
							</div>

							<div class="text-end">
								<a href="../dashboard/" class="btn btn-danger">cancel</a>
								<button class="btn btn-primary" form="personalInfo" onclick="APP.authentication(this)">
									<input type="hidden" name="update_personal_info">
									<span>Save change</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Personal info end -->


			<!-- Password reset BEGIN -->
			<div class="col-lg-5">
				<div class="card h-100">
					<h4 class="text-dark">Password reset</h4>
					<hr>
					<div class="">
						<form id="passwordReset">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="text-dark">Old password</label>
										<input class="form-control" type="password" name="old_password" placeholder="Enter old password">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label class="text-dark">New password</label>
										<input class="form-control" type="password" name="new_password" placeholder="Enter new password">
									</div>
								</div>

								<div class="form-group">
									<label class="text-dark">Repeat new password</label>
									<input class="form-control" type="password" name="new_password_rp" placeholder="Enter new password again">
								</div>
							</div>

							<div class="text-end">
								<a href="../dashboard/" class="btn btn-danger">cancel</a>
								<button class="btn btn-primary" form="passwordReset" onclick="APP.authentication(this)">
									<input type="hidden" name="change_password">
									<span>Save change</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Password reset END -->
		</section>
		<!-- User setting END -->
	</main>
</section>

<script>
	$(() => {
		$("#profileImageUpload_").change(function(){
			let file = this.files
			let profileImg = $("#profile-pic")

			if(!file.length){
				$("#profile-pic").attr({src: ORIGIN+"<?=$image?>"})
				APP.message("Upload an image");return;
			}

			file = file[0]
			// Preview the image
			$("#profile-pic").attr({src: URL.createObjectURL(file)})


			// Upload image to the server
			APP.authentication(this)
		})
	})
</script>

<!-- KYC modal BEGIN -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Upload your KYC detail <span id="botVersion_"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="kycFm_" autocomplete="off">
					<div class="text-center mb-2">
						<img src="<?=ROOT.$image?>" id="reket">
					</div>
					
					<!-- Full name -->
					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Full name</span>
						<input type="text" class="form-control bg-light" name="fullname" value="<?=ucfirst($fullname)?>" placeholder="Enter full name">
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Phone number</span>
						<input type="text" class="form-control bg-light" name="phone" value="<?=ucfirst($phone)?>" placeholder="Enter phone number">
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">DOB</span>
						<input type="date" class="form-control bg-light" name="dob" max="<?=YEAR_18_AGO?>">
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Full address</span>
						<input type="text" class="form-control bg-light" name="address" placeholder="Enter full address">
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Postal code</span>
						<input type="text" class="form-control bg-light" name="postal" placeholder="Enter postal code">
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Identity</span>
						<select class="form-control bg-light" name="identity_type">
							<option value="" disabled selected>Choose</option>
							<option value="dl">Driver's licence</option>
							<option value="nin">National id</option>
							<option value="ip">International Passport</option>
							<option value="gid">Government issue id</option>
						</select>
						<label for="frontAndBack_" class="input-group-text f-sm bg-light text-muted"><i class="fal fa-camera mx-2"></i> <span class="inner" data-default-text="ID front and back">ID front and back</span></label>
						<input type="file" class="form-control d-none" id="frontAndBack_" accept="image/*" name="identity_image[]" multiple>
					</div>

					<div class="form-group input-group">
						<span class="input-group-text f-sm bg-light text-muted">Address</span>
						<select class="form-control bg-light" name="proof_type">
							<option value="" disabled selected>Choose</option>
							<option>Utility</option>
							<option>Utility</option>
							<option>Utility</option>
							<option>Utility</option>
						</select>
						<label for="addressProof_" class="input-group-text f-sm bg-light text-muted"><i class="fal fa-camera mx-2"></i> <span class="inner" data-default-text="Proof">Proof</span></label>
						<input type="file" class="form-control d-none" name="proof_image" id="addressProof_">
					</div>

					<div class="text-end">
						<a data-bs-dismiss="modal" class="btn btn-danger">cancel</a>
						<button class="btn btn-primary" form="kycFm_" onclick="APP.authentication(this)">
							<input type="hidden" name="complete_kyc">
							<span>Complete KYC</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(()=>{
		$("#frontAndBack_").change(function(){
			let self = this
			file = self.files

			if(file)
			$("[for=frontAndBack_] span").html(`${file.length} image uploaded`)
		})
		
		$("#addressProof_").change(function(){
			let self = this
			file = self.files

			if(file)
			$("[for=addressProof_] span").html(`${file.length} image uploaded`)
		})
	})
</script>
<!-- KYC modal END -->