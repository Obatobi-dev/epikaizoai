<?php $this->view("inc/head");?>
<?php
// User account Read helper
$userid = $page_1;
$USER = new \Model\User;
$user_found = false;
if($USER = $USER->read("id", $userid)){
	$user_found = true;
}
extract((array)$USER); // Extra user information
?>
<style type="text/css">
	.kyc-image {
		height: 100px;
		object-fit: contain;
		border-radius: 20px;
	}
	.profile-card {
		text-align: center;
		border-radius: 45px;
	}
	.cover-pic {
		display: block;
		width: 100%;
		height: 160px;
		object-fit: cover;
		user-select: none;
	}
	#profile-pic {
		width: 140px;
		height: 140px;
		border-radius: 50%;
		margin-top: -80px;
		background: var(--color-light);
		outline: 4px dotted var(--color-light);
		object-fit: cover;
		padding: 4px;
	}
	#editPhotoBtn_ {
		position: absolute;
		right: 4px;
		bottom: 80px;
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
			    <?php if($user_found):?>
			    <article>
			        <div class="row">
						<!-- Personal info begins -->
						<div class="col-12">
							<div class="card h-100 p-0 bg-secondary-v">
								<div class="profile-card">
									<img src="<?=ROOT.BG1_IMAGE?>" class="cover-pic">
									<img src="<?=ROOT.$image ?? ROOT.MALE_PROFILE_IMAGE?>" id="profile-pic">

									<div data-image-upload>
										<label class="btn btn-secondary btn-sm" id="editPhotoBtn_" for="profileImageUpload_"><i class="fal fa-camera"></i> <span>Edit</span></label>
										<form>
											<input type="file" class="d-none" name="image" id="profileImageUpload_" accept="image/*">
											<input type="hidden" name="profile_image_helper">
										</form>
									</div>
									
									<div class="p-4">
										<h3 class="text-cap" class=""><?=ucfirst($fullname)?></h3>
										<p><?=ucfirst($email)?></p>
										<p><i class="fal fa-phone-alt"></i> <?=ucfirst($phone)?></p>
										<p class="f-sm">Member since <?=$stamp?></p>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-lg-7">
							<div class="card bg-secondary-v h-100">
								<img src="<?=ROOT.RIGHT_DOT_IMAGE?>" class="img-fluid dot top left">
								<img src="<?=ROOT.RIGHT_DOT_IMAGE?>" class="img-fluid dot bottom right">
								<h4>Personal information</h4>
								<hr>
								<div class="">
									<form id="personalInfo">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="text-dark">Fullname</label>
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
											<label class="text-dark">Reset password</label>
											<input class="form-control" type="password" name="password" placeholder="Enter new password">
										</div>
										
										<div class="form-group">
											<label class="text-dark">Bio</label>
											<textarea rows="1" class="form-control" name="bio" placeholder="Enter bio"><?=$bio?></textarea>
										</div>

										<div class="text-end">
											<button class="btn btn-danger" form="personalInfo" onclick="APP.authentication(this)">
												<input type="hidden" name="update_personal_info">
												<input type="hidden" name="userid" value="<?=$id?>">
												<span>Save change</span>
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- Personal info end -->


						<!-- Password reset BEGIN -->
						<div class="col-md-6 col-lg-5">
							<div class="card bg-secondary-v h-100">
								<h4>KYC detail</h4>
			            		<hr>
			            		<div id="kycInner_">

			            		</div>
							</div>
						</div>
			            
			            <div class="col-12 col-md-6">
			                <div class="card h-100">
			                	<h4>Deposits</h4>
			            		<hr>
			                    <div class="" id="depositHistory_"></div>
			                </div>
			            </div>
			            
			            <div class="col-12 col-md-6">
			                <div class="card h-100">
			                	<h4>Withdrawals</h4>
			            		<hr>
			                    <div class="" id="withdrawalHistory_"></div>
			                </div>
			            </div>
                    </div>
			    </article>
			    <script>
			    	// Read kyc detail
                    APP.getInfo('kyc', '<?=$userid?>')
                    .then(Data => {
                    	if(Data.length){
                    		Data.map(DATA => {
                    			let DETAIL = JSON.parse(DATA.detail), front = DETAIL.identity_image[0], back = DETAIL.identity_image[1], proof = DETAIL.proof_image, proof_type = DETAIL.proof_type, identity_type = DETAIL.identity_type, status = DETAIL.status;
                    			let inner = `
                    			<div class="row">
                    				<div class="col-4">
                    					<img src="${APP.CONFIG.ORIGIN+front}" class="img-fluid kyc-image">
                    					<p>${ucfirst(identity_type)} front</p>
                    				</div>
                    				<div class="col-4">
                    					<img src="${APP.CONFIG.ORIGIN+back}" class="img-fluid kyc-image">
                    					<p>${ucfirst(identity_type)} back</p>
                    				</div>
                    				<div class="col-4">
                    					<img src="${APP.CONFIG.ORIGIN+proof}" class="img-fluid kyc-image">
                    					<p>${ucfirst(proof_type)}</p>
                    				</div>
                    			</div>
                    			<form class="mt-4">
                    				<label>Approve / Decline KYC</label>
                    				<input type="hidden" name="userid" value="<?=$userid?>">
                    				<input type="hidden" name="kyc_helper">
	                    			<div class="form-check form-switch">
	                    				<input onclick="APP.authentication(this)" type="checkbox" class="form-check-input" id="toggleKyc_" style="width: 80px;height: 40px;" ${status == 'complete'?'checked': ''}>
	                    			</div>
                    			</form>
                    			`
                    			$("#kycInner_").append(inner)
	                        })
                    	}
                    })
                    
                    APP.getInfo('deposit', '<?=$userid?>')
                    .then(Data => {
                    	if(Data.length){
                    		Data.map(DATA => {
                    			let DETAIL = JSON.parse(DATA.detail)
	                			let status = DETAIL.status
	                			let color = 'info'
	                			if(status == 'rejected'){
	                				color = 'danger'
	                			} else if(status == 'complete'){
	                				color = 'success'
	                			}
	                
	                			let inner = `
	                			<div class="mb-3"><div class="d-flex align-items-center justify-content-between text-${color}"><p>${ucfirst(status)}</p><p>${Number(DETAIL.amount).toLocaleString()} ${APP_CURRENCY}</p></div><div class="d-flex align-items-center justify-content-between f-sm text-muted"><p>${timeAgo(DATA.tzstamp, USER_TZ)} ago</p><p>Fee: ${DETAIL.fee ?? '0.000'}</p></div></div>`
	                			$("#depositHistory_").append(inner)
	                		})
                    	} else {
                    		$("#depositHistory_").append(`<p class="text-center">No data</p>`)
                    	}
                    })
                    
                    APP.getInfo('withdrawal', '<?=$userid?>')
                    .then(Data => {
                    	if(Data.length){
                    		Data.map(DATA => {
                    			let DETAIL = JSON.parse(DATA.detail)
	                			let status = DETAIL.status
	                			let color = 'info'
	                			icon = `<i class="fal fa-info-circle fa-lg"></i>`
	                			if(status == 'rejected'){
	                				color = 'danger'
	                				icon = `<i class="fal fa-times-circle fa-lg"></i>`
	                			} else if(status == 'complete'){
	                				color = 'success'
	                				icon = `<i class="fal fa-check-circle fa-lg"></i>`
	                			}
	                
	                			let inner = `
	                			<div class="mb-3"><div class="d-flex align-items-center justify-content-between text-${color}"><p>${ucfirst(status)}</p><p>${Number(DETAIL.amount).toLocaleString()} ${APP_CURRENCY}</p></div><div class="d-flex align-items-center justify-content-between f-sm text-muted"><p>${timeAgo(DATA.tzstamp, USER_TZ)} ago</p><p>Fee: ${DETAIL.fee ?? '0.000'}</p></div></div>`
	                			$("#withdrawalHistory_").append(inner)
	                		})
                    	} else {
                    		$("#withdrawalHistory_").append(`<p class="text-center">No data</p>`)
                    	}
                    })
                </script>
			    <?php else:?>
			    <?php // User not found mode ?>
			    <article class="" id="userHistory_">
			    	<div class="card">
		            	<h3>Clients</h3>
				    	<hr>
		            	<div class="" style="min-height: 280px;">
		            		<div id="chart"></div>
		            	</div>
		            </div>

			    	<div class="table-responsive no-shadow rounded-3">
			    		<table class="table table-light table-hover table-striped table-borderless" id="sortable">
        					<thead>
        						<tr><th class="d-none">Id</th><th>Fullname</th><th>Email</th><th>Balance</th><th>Verified</th><th>Date</th><th>Actions</th></tr>
        					</thead>
        					<tbody id="tr"></tbody>
        				</table>
        			</div>
			    </article>
			    <script>
			    	let user = verified = ban = revenue = 0;
			    	if(USER.length){
			    		USER.map(DATA => {
	                        let image = `${APP.CONFIG.ORIGIN}<?=PROFILE_IMAGE?>`
	                        show(DATA.image)
	                        if(DATA.image == null) DATA.image = image;
	                        let auto_id = `h${APP.unique(4)}`
	                        let Verification = JSON.parse(DATA.verification)
	                        let inner = `
	                        <tr>
	                        <td class="d-none">${DATA.id}</td>
	                        <td>${ucfirst(DATA.fullname)}</td>
	                        <td>${ucfirst(DATA.email)}</td>
	                        <td>${DATA.balance} <small class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</small></td>
	                        <td class="text-${Verification.kyc.status ? 'success': 'danger'}">${Verification.kyc.status ? 'Yes': 'No'}</td>
	                        <td>${DATA.stamp} (${timeAgo(DATA.stamp)})</td>
	                        <td>
	                        <div class="d-flex align-items-center justify-content-between" style="gap: 1rem;">
		                        <a href="${DATA.id}/" class="btn btn-sm btn-primary"><i class="fal fa-eye"></i></a>
		                        <button type="button" class="btn btn-sm btn-danger" onclick="$('#delete_user_id').val('${DATA.id}')" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fal fa-trash"></i></button>
		                        <form onsubmit="return false;">
		                        	<input type="hidden" name="userid" value="${DATA.id}">
		                        	<input type="hidden" name="disable_account">
		                        	<div class="form-check form-switch">
			            				<input onchange="APP.authentication(this)" type="checkbox" class="form-check-input" id="${auto_id}" ${Verification.ban.status ?'checked': ''}>
			            				<label for="${auto_id}" class="f-sm">Disable</label>
			            			</div>
		                        </form>
	            			</div>
	                        </td>
	                        </tr>
	                        `
	                        $("#tr").append(inner)

	                        // Detail for chart
	                        user++
	                        if(DATA.verified) verified++;
	                        if(DATA.ban) ban++;
	                        revenue += Number(DATA.balance)
	                    })
			    	}

                    let table1 = document.querySelector('#sortable');
                    let dataTable = new simpleDatatables.DataTable(table1);


                    var options = {
					    chart: {
					        type: 'bar',
					        toolbar: {
					            show: false,
					        },
					        height: '260px',
					        parentHeightOffset: 0,
					        stacked: !0,
					    },
					    grid: {
					        padding: {
					            left: 0,
					            right: 0,
					        },
					        strokeDashArray: 0,
					    },
					    plotOptions: {
					        bar: {
					            horizontal: !1,
					            columnWidth: "50%"
					        }
					    },
					    dataLabels: {
					        enabled: !1,
					    },
					    stroke: {
					        show: !0,
					        width: 0,
					        colors: ["transparent"]
					    },
					    zoom: {
					        enabled: !1
					    },
					    legend: {
					        show: !1
					    },
					    xaxis: {
					        categories: ['All', 'Verified'],
					        axisBorder: {
					            show: !1
					        }
					    },
					    colors: ["#d1d1d1", "#e3eaef"],
					    series: [{
					    	name: "All time",
					        data: [user, verified]
					    }],
					    fill: {
					        opacity: 0.7,
					    },
					}

					var chart = new ApexCharts(document.querySelector("#chart"), options);
					chart.render();
                </script>
                <?php endif;?>
			</main>
		</section>
	</aside>
</section>

<!-- Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Head -->
			<div class="modal-header">
				<h3 class="modal-title">Account action</h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Body -->
			<div class="modal-body">
				<p>No change can be made when you delete this account.</p>
				<p>Are you sure you want to delete it ??</p>
			</div>

			<div class="modal-footer">
				<form>
					<input type="hidden" name="userid" id="delete_user_id" value="">
					<button class="btn btn-primary" data-bs-dismiss="modal">Go back</button>
					<button class="btn btn-danger" onclick="APP.authentication(this)">
						<input type="hidden" name="delete_user">
						<span>Delete account</span>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>   