<?php $this->view("inc/head");?>
<style type="text/css">
	.bot-sub-btn {
		position: absolute;
		bottom: 0;
		left: 0;
	}
	/*.nav-item {
		width: 50%;
		text-align: center;
	}
	.nav-link.active {
		background: var(--color-primary) !important;
		color: var(--color-white) !important;
	}*/
	th {
		font-size: 14px;
		font-weight: normal;
		color: var(--color-dark);
	}
	.progress {
		height: auto !important;
	}
	.table {
	}
</style>
<section id="wrapper" class="bg-light">
	<?php $this->view("user/nav");?>
	<main class="container">
		<!-- Analysis for deposit and withdrawal START -->
		<article class="card large text-light mb-5" id="pageHero_" style="background-image: url('<?=COIN_ICON."wbtc.svg"?>');">
			<div class="inner mw-600">
				<h3 class="">Your balance</h3>
				<h2 class="my-3"><?=number($balance)?> <small class="badge bg-primary f-sm"><?=APP_CURRENCY?></small></h2>
				<p>Subscribe to the epikaizon bot</p>
				<p class="text-muted">Start the journey now !!!</p>
			</div>
		</article>

		<section class="">
			<article class="row">
				<div class="col-12 col-md-6 col-lg-4">
					<div class="card h-100 d-block">
						<h3 class="text-dark"><i class="fal fa-robot"></i> <?=APP_NAME?> V2.1</h3>
						<div class="my-5">
							<p><i class="fal fa-badge-check"></i> 1%  return daily & compounding</p>
							<p><i class="fal fa-badge-check"></i> Release of capital every <b>24 hrs</b></p>
							<p class="text-dark">Investment capital (Per subscription)</p>
							<p class="text-bold"><i class="fal fa-badge-check"></i> <span></span><span>Min: <?=APP_CURRENCY?>20</span> <span>Max:  <?=APP_CURRENCY?>2,000</span></p>
							<p><i class="fal fa-badge-check"></i> Terminate after every 24hrs</p>
						</div>
						<button class="btn btn-primary bot-sub-btn w-100" type="button" data-bs-toggle="modal" data-bs-target="#myModal" data-bot="2.1">Subscribe now</button>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<div class="card h-100 d-block">
						<h3 class="text-dark"><i class="fal fa-robot"></i> <?=APP_NAME?> V4.0</h3>
						<div class="my-5">
							<p><i class="fal fa-badge-check"></i> 2%  return daily & compounding</p>
							<p><i class="fal fa-badge-check"></i> Capital / profit lockdown duration: <b>15 days</b></p>
							<p><i class="fal fa-badge-check"></i> Release of capital / profit <b>25% weekly for 4 weeks</b></p>
							<p><i class="fal fa-badge-check"></i> 1st release of weekly capital profit Release of capital / profit <b>25% weekly for 4 weeks</b></p>
							<p class="text-dark">Investment capital (Per subscription)</p>
							<p class="text-bold"><i class="fal fa-badge-check"></i> <span></span><span>Min: <?=APP_CURRENCY?>50</span> <span>Max:  <?=APP_CURRENCY?>20,000</span></p>
							<p><i class="fal fa-badge-check"></i> Terminate after every 24hrs</p>
						</div>
						<button class="btn btn-danger bot-sub-btn w-100" type="button" data-bs-toggle="modal" data-bs-target="#myModal" data-bot="4.0">Subscribe now</button>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<div class="card h-100 d-block">
						<h3 class="text-dark"><i class="fal fa-robot"></i> <?=APP_NAME?> V7.0</h3>
						<div class="my-5">
							<p><i class="fal fa-badge-check"></i> 2%  return daily & compounding</p>
							<p><i class="fal fa-badge-check"></i> Capital / profit lockdown duration: <b>60 days</b></p>
							<p><i class="fal fa-badge-check"></i> Release of capital / profit <b>25% weekly for 4 weeks</b></p>
							<p><i class="fal fa-badge-check"></i> 1st release of weekly capital profit Release of capital / profit <b>25% weekly for 4 weeks</b></p>
							<p class="text-dark">Investment capital (Per subscription)</p>
							<p class="text-bold"><i class="fal fa-badge-check"></i> <span></span><span>Min: <?=APP_CURRENCY?>50</span> <span>Max:  <?=APP_CURRENCY?>20,000</span></p>
							<p><i class="fal fa-badge-check"></i> Terminate after every 24hrs</p>
						</div>
						<button class="btn btn-primary bot-sub-btn w-100" type="button" data-bs-toggle="modal" data-bs-target="#myModal" data-bot="7.0">Subscribe now</button>
					</div>
				</div>
			</article>

			<article class="pt-5">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#activeBot">Running</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#closedBot">Closed</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="activeBot" class="tab-pane active">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Bot</th>
										<th>Trade amount</th>
										<th>Profit earned</th>
										<th>Trade status</th>
										<th>Withdrawal date</th>
										<th>Withdrawal status</th>
										<th>Action</th>
									</tr>
								</thead>
								
								<tbody id="activeHistory_"></tbody>
							</table>
						</div>
					</div>

					<div id="closedBot" class="tab-pane">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Bot</th>
										<th>Trade amount</th>
										<th>Profit earned</th>
										<th>Withdrawal date</th>
										<th>Withdrawal status</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</article>
				
		</section>
		<!-- Analysis for deposit and withdrawal END -->
	</main>
</section>


<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Subscribe to <?=APP_NAME?> <span id="botVersion_"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form autocomplete="off">
					<div class="form-group">
						<label>Enter amount</label>
						<div class="input-group">
							<input type="text" step="any" class="form-control" placeholder="Bal: <?=APP_CURRENCY?> <?=number_format($balance, 2)?>" name="amount">
						</div>
						<p class="text-muted f-sm">Min: <span id="botMin_">0</span> Max: <span id="botMax_">0</span></p>
					</div>

					<button class="btn btn-primary w-100"onclick="APP.authentication(this)">
						<span>Subscribe now</span>
						<input type="hidden" name="version" id="versionEye_" value="">
						<input type="hidden" name="subscribe_to_bot">
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(() => {
		let BOT = '<?=BOT_PLANS?>';
		BOT = JSON.parse(BOT)

		show(BOT)

		$(".bot-sub-btn").click(function(){
			let version = this.dataset.bot, Bot = BOT[version];
			Bot.version = version
			$("#versionEye_").val(version); // Place version

			// Get id of an html element then insert the value according to the set name of the html element
			for(let x in Bot){
				show(APP.CONFIG.APP_CURRENCY)
				$(`#bot${ucfirst(x)}_`).html(`${Bot[x].toLocaleString()}`)
			}
		})

		APP.getInfo('bot', '<?=$id?>')
		.then(Data => {
			if(Data.length){
				Data.map(DATA => {
					let DETAIL = JSON.parse(DATA.detail)

					let inner = `<tr>
						<td>V${DETAIL.version}</td>
						<td>${DETAIL.amount} ${APP_CURRENCY}</td>
						<td><span class="badge bg-danger">${DETAIL.profit}</span></td>
						<td class="text-center">${DETAIL.status}</td>
						<td>${DETAIL.lock_duration}</td>
						<td>${DETAIL.withdrawal_status}</td>
						<td><button class="btn btn-sm btn-danger">Close</button></td>
					</tr>`

					$("#activeHistory_").append(inner)
				})
			}
		})
	})
</script>