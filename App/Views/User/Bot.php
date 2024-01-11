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
	<?php $this->view(USER_ROOT."/nav");?>
	<main class="container">
		<!-- Analysis for deposit and withdrawal START -->
		<article class="card large text-light mb-5" id="pageHero_" style="background-image: url('<?=COIN_ICON_API."wbtc.svg"?>');">
			<div class="inner mw-600">
				<h3 class="f-sm">Your balance</h3>
				<h2 class="my-3"><?=number($balance)?> <small class="badge bg-primary f-sm"><?=APP_CURRENCY?></small></h2>
				<p class="f-sm">Subscribe to the <?=APP_NAME?> bot</p>
				<p>Get <?=APP_NAME?> traded on your behalf.</p>
			</div>
		</article>

		<section class="">
			<!-- Bot -->
			<?php $this->view('inc/bot')?>

			<article class="pt-5 card large">
				<ul class="nav nav-pills pb-4" role="tablist">
					<li class="nav-item">
						<a class="btn btn-primary f-sm active" data-bs-toggle="tab" href="#activeBot">Running</a>
						<a class="btn btn-danger f-sm" data-bs-toggle="tab" href="#closedBot">Closed</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="activeBot" class="tab-pane active">
						<div class="table-responsive no-shadow rounded-3">
							<table class="table table-light table-hover table-striped table-borderless">
								<thead>
									<tr>
										<th>Bot</th>
										<th>Trade amount</th>
										<th>Profit earned</th>
										<th>Trade status</th>
										<th>Trade progress</th>
										<th>Date</th>
										<th>Withdraw</th>
									</tr>
								</thead>
								
								<tbody id="activeHistory_"></tbody>
							</table>
						</div>
					</div>

					<div id="closedBot" class="tab-pane">
						<div class="table-responsive no-shadow rounded-3">
							<table class="table table-light table-hover table-striped table-borderless">
								<thead>
									<tr>
										<th>Bot</th>
										<th>Trade amount</th>
										<th>Profit earned</th>
										<th>Trade status</th>
										<th>Withdrawal date</th>
									</tr>
								</thead>

								<tbody id="closedHistory_">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</article>
				
		</section>
		<!-- Analysis for deposit and withdrawal END -->
	</main>
</section>

<script type="text/javascript">
$(() => {
	APP.getInfo('botsub', APP.CONFIG.GETTER)
	.then(Data => {
		if(Data.length){
			Data.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail);
				let day = 0;
				if(typeof DETAIL.log !== 'string'){
					if(!DETAIL.isMatured){
						for(let days in DETAIL.log){
							DETAIL.profit += DETAIL.log[days].compound_profit
							day++;
						}
					} else {
						day = DETAIL.lock_duration
					}
				}

				let day_left = DETAIL.lock_duration - day;
				let progress = number((day / DETAIL.lock_duration) * 100, 2); // in percentage


				// Two status
				let inner = `<tr>
					<td>V${DETAIL.version}</td>
					<td>${Number(DETAIL.amount).toLocaleString()} <small class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</small></td>
					<td>${(DETAIL.profit).toLocaleString()} <span class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</span></td>
					<td><small class="badge bg-${APP.CONFIG.COLOR[DATA.status]} text-cap">${DATA.status}</small></td>
					${DETAIL.isWithdrawn?`<td>${DETAIL.withdrawn_date} (${timeAgo(DETAIL.withdrawn_date, APP.CONFIG.USER_TZ)})</td>`:`
					<td><div class="progress bg-info mt-1"><div class="progress-bar progress-bar-striped progress-bar-animated" style="width:${progress}%;height: 19px;">${progress}%</div></div></td>
					<td>${DETAIL.sub_date} (${timeAgo(DETAIL.sub_date, APP.CONFIG.USER_TZ)})</td>
					<td><form onsubmit="return false"><input type="hidden" name="bot_withdrawal" value="${DATA.id}"><button type="submit" onclick="APP.authentication(this)"class="btn btn-danger btn-sm" ${!DETAIL.isMatured?'disabled':''}>${!DETAIL.isMatured?`${day_left} day(s) left`:`Withdraw now`}</button></form></td>`}
				</tr>`

				if(!DETAIL.isWithdrawn){
					$("#activeHistory_").append(inner)
				} else {
					$("#closedHistory_").append(inner)
				}
			})
		}
	})
})
</script>