<?php $this->view("inc/head");?>
<style type="text/css">
	.demacation {
		border: none;
		border-top: 1px solid #dee2e6;
		margin-bottom: 16px;
	}
	@media screen and (min-width: 992px){
		.demacation {
			border: none;
			border-left: 1px solid #dee2e6;
/*			margin-bottom: 0;*/
		}
	}
	/*th {
		padding: 20px !important;
	}
	td {
		color: var(--color-muted);
		padding: 20px !important;
		transition: all 0.133s;
	}*/
</style>
<section id="wrapper" class="bg-light">
	<?php $this->view(USER_ROOT."/nav");?>

	<main class="container">
		<!--  -->
		<article class="card large text-light" id="pageHero_">
			<div class="inner mw-600">
				<h2>Total balance</h2>
				<h3 class="mt-4"><?=number($balance)?> <small class="badge bg-primary f-sm"><?=APP_CURRENCY?></small></h3>
				<!-- <div class="pt-4">
					<a href="<?=ROOT?>/wallet/#deposit" class="btn btn-primary"><i class="far fa-caret-square-down"></i> Deposit</a>
					<a href="<?=ROOT?>/wallet/#withdraw" class="btn btn-danger"><i class="far fa-caret-square-up"></i> Withdraw</a>
				</div> -->
			</div>
		</article>

		<!-- Withdrawal and deposit form START -->
		<article class="card large mt-5">
			<div class="row">
				<div class="col-lg-6">
					<div class="card p-1 no-shadow bg-none" id="deposit">
						<h3>Deposit</h3>
						<form>
							<div class="form-group mt-0">
								<div class="input-group bg-light">
									<span class="input-group-text bg-light text-dark">Amount</span>
									<input class="form-control bg-light" type="number" placeholder="Enter amount" name="amount" step="any">
									<span class="input-group-text bg-light"><?=APP_CURRENCY?></span>
								</div>
							</div>

							<div class="form-group">
								<div class="input-group">
									<span class="input-group-text bg-light">
										<label for="proof" class=""><i class="fal fa-camera"></i> Proof</label>
									</span>
									<input class="form-control form-control-lg bg-light" type="file" name="payment_proof" id="proof">
								</div>
							</div>
							<div class="card border-radius-sm no-shadow bg-success text-dark bg-light">
								<p>Deposit wallet: <span><?=DEPOSIT_WALLET?></span> <span class="badge copy bg-primary text-light" onclick="copy('<?=DEPOSIT_WALLET?>')">copy</span></p>

								<p class=""><?=APP_CURRENCY?> 10.00 min <?=APP_CURRENCY?> unlimited max.</p>
								<p><?=APP_CURRENCY?> Unlimited daily deposit limit.</p>
								<p class="f-sm text-danger"><i class="fal fa-info-circle"></i> Enter the same amount you sent</p>
							</div>

							<button class="btn btn-primary w-100" onclick="APP.authentication(this)">
								<input type="hidden" name="create_deposit">
								<span><i class="far fa-caret-square-up"></i> Submit for review</span>
							</button>
						</form>
					</div>
				</div>

				<div class="col-lg-6 demacation">
					<div class="card p-1 no-shadow bg-none" id="withdraw">
						<h3>Withdrawal</h3>
						<form>
							<div class="form-group mt-0">
								<div class="input-group">
									<span class="input-group-text text-dark bg-light">Amount</span>
									<input class="form-control bg-light" type="number" placeholder="0" name="amount" step="any">
									<span class="input-group-text text-dark bg-light"><?=APP_CURRENCY?></span>
								</div>
							</div>

							<div class="form-group mt-0">
								<div class="input-group">
									<span class="input-group-text text-dark bg-light">Wallet address</span>
									<input class="form-control bg-light" type="text" placeholder="..." name="wallet" value="<?=$wallet?>" step="any">
									<span class="input-group-text text-dark bg-light"><i class="fal fa-wallet"></i></span>
								</div>
								
							</div>

							<div class="card no-shadow border-radius-sm bg-success text-dark bg-light">
								<p class=""><?=APP_CURRENCY?> 10.00 min <?=APP_CURRENCY?> 1,000.00 max.</p>
								<p><?=APP_CURRENCY?> 20,000.00 daily withdrawal limit.</p>
								<p class="f-sm text-danger"><i class="fal fa-info-circle"></i> Please make sure it is a USDT wallet</p>
							</div>

							<button class="btn btn-danger w-100" onclick="APP.authentication(this)">
								<input type="hidden" name="create_withdrawal">
								<span><i class="far fa-caret-square-down"></i> Submit for review</span>
							</button>
						</form>
					</div>
				</div>
			</div>
		</article>
		<!-- END -->

		<!-- Hostory of deposit and withdrawal -->
		<article class="card large mt-5">
			<h3 class="mb-3">History</h3>
			<!-- <h3 class="mb-3">Activities</h3> -->
			<ul class="nav nav-pills pb-4">
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="pill" href="#activeDepost">Deposit</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="pill" href="#activeWithdrawal">Withdrawal</a>
				</li>
			</ul>

			<div class="tab-content">
				<!-- Deposit history -->
				<div class="tab-pane container active p-0" id="activeDepost">
					<div class="table-responsive no-shadow rounded-3">
						<table class="table table-light table-hover table-striped table-borderless">
							<thead>
								<tr class="py-3">
									<th>Trx id</th>
									<th>Amount / Fee</th>
									<th>Address</th>
									<th>Status</th>
									<th>Date</th>
								</tr>
							</thead>

							<tbody id="depositLog">
								<tr class="no-result"><td colspan="5" class="text-center text-primary"><p class="fad fa-file-search fa-lg my-3" style="font-size: 60px;"></p><p>No result</p></td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- Deposit history END -->

				<!-- Withdrawal history START -->
				<div class="tab-pane container p-0" id="activeWithdrawal">
					<div class="table-responsive no-shadow rounded-3">
						<table class="table table-light table-hover table-striped table-borderless">
							<thead>
								<tr class="py-3">
									<th>Trx id</th>
									<th>Amount / Fee</th>
									<th>Address</th>
									<th>Status</th>
									<th>Date</th>
								</tr>
							</thead>

							<tbody id="withdrawalLog">
								<tr class="no-result"><td colspan="5" class="text-center text-primary"><p class="fad fa-file-search fa-lg my-3" style="font-size: 60px;"></p><p>No result</p></td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- Withdrawal history START -->
			</div>
		</article>
	</main>
</section>

<script type="text/javascript">
	APP.getInfo("wallet", APP.CONFIG.GETTER)
	.then(RES => {
		RES.map(DATA => {
			let DETAIL = JSON.parse(DATA.detail)
			
			let inner = `
			<tr>
				<td>${DATA.id}</td>
				<td><small class="badge bg-primary">${Number(DETAIL.amount).toLocaleString()} ${APP.CONFIG.APP_CURRENCY}</small> <small class="badge bg-danger">${DETAIL.fee} ${APP.CONFIG.APP_CURRENCY}</small></td>
				<td>${DETAIL.wallet ?? 'tQwer34fg4tgw34t34t6fvgs'}</td>
				<td><small class="badge bg-${APP.CONFIG.COLOR[DETAIL.status]} text-cap">${DETAIL.status}</small></td>
				<td>${DATA.tzstamp}</td>
			</tr>
			`

			if(DATA.type === 'deposit'){$("#depositLog .no-result").html(``); $("#depositLog").append(inner);}
			if(DATA.type === 'withdrawal'){$("#withdrawalLog .no-result").html(``); $("#withdrawalLog").append(inner);}
		})
	})
</script>
<!-- Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Head -->
			<div class="modal-header">
				<h4 class="modal-title">${Mode}</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Body -->
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>