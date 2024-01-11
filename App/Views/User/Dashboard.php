<?php $this->view("inc/head");?>
<style>
	.card.analysis {
		display: block;
	}
	.analysis .icon {
		font-size: 1.2rem;
		width: 40px;
		height: 40px;
		line-height: 40px;
		border-radius: 50%;
		text-align: center;
		background: #f8feff; /*Powder blue variation*/
		color: var(--color-primary);
		transition: all 0.133s;
	}
	@media screen and (min-width: 768px){
		.analysis .icon {
			font-size: 1.6rem;
			width: 60px;
			height: 60px;
			line-height: 60px;
		}
	}
	.analysis:hover .icon {
		background: var(--color-primary);
		color: var(--color-white);
	}
</style>
<?php
$kyc = $verification->kyc;
$fname = explode(" ", $fullname);
$first_name = ucfirst($fname[0]);
$last_name = $fname[1] ?? '';
$last_name = substr($last_name, 0, 1);
$last_name = ucfirst($last_name);
?>
<section id="wrapper" class="bg-light">
	<?php $this->view(USER_ROOT."/nav");?>
	<main class="container">
		<!--  -->
		<article class="card large text-light mb-5" id="pageHero_">
			<div class="inner mw-600">
				<h4 class=""><small class="f-sm text-primary">Welcome, </small><?=($first_name)?> <?=($last_name)?>.</h4>
				<h2 class="my-3"><?=number($balance)?> <small class="badge bg-primary f-sm"><?=APP_CURRENCY?></small></h2>
				<div class="pt-4">
					<a href="<?=ROOT?>/<?=USER_ROOT?>/wallet/#deposit" class="btn btn-primary"><i class="far fa-caret-square-down"></i> Deposit</a>
					<a href="<?=ROOT?>/<?=USER_ROOT?>/wallet/#withdraw" class="btn btn-danger"><i class="far fa-caret-square-up"></i> Withdraw</a>
				</div>
			</div>
		</article>

		<!-- Statistic -->
		<section class="">
			<!-- Text -->
			<article class="row">

				<div class="col-6 col-md-6">
					<div class="card analysis">
						<div class="float-end icon"><i class="fa fa-chart-bar"></i></div>
						<h3 class="text-dark" id="openTrade_"><?=$meta_data->trade_count?></h3>
						<h5>Trades</h5>
					</div>
				</div>

				<div class="col-6 col-md-6">
					<div class="card analysis">
						<div class="float-end icon"><i class="fal fa-shopping-basket"></i></div>
						<h3 class="text-dark"><?=$meta_data->robot_count?></h3>
						<h5>Robots</h5>
					</div>
				</div>

				<div class="col-6 col-md-6">
					<div class="card analysis">
						<div class="float-end icon"><i class="fal fa-sync"></i></div>
						<h3 class="text-dark" id=""><?=$meta_data->referral_count?></h3>
						<h5>Referral</h5>
					</div>
				</div>

				<div class="col-6 col-md-6">
					<div class="card analysis">
						<div class="float-end icon"><i class="fal fa-hand-holding-usd"></i></div>
						<h3 class="text-dark"><?=$meta_data->deposit_count?></h3>
						<h5>Deposit</h5>
					</div>
				</div>

				<div class="col-12">
					<div class="card analysis bg-dark py-5 text-light">
						<div class="float-end icon bg-none"><i class="fal fa-envelope-open-dollar"></i></div>
						<h3 class=""><?=$meta_data->withdrawal_count?></h3>
						<h5>Withdrawal</h5>
					</div>
				</div>
			</article>

			<!-- Graph for Trade(s) START -->
			<article class="row">
				<div class="col-lg-12">
					<div class="card h-100">
						<h4 class="text-dark">Trade (chart)</h4>
						<hr>
						<div>
							<div id="chart"></div>
						</div>
					</div>
				</div>
				
				<div class="col-12">
					<div class="card h-100">
						<div class="d-flex justify-content-between">
							<h4 class="text-dark">Trade history</h4>
							<a href="../trade/" class="btn btn-sm btn-danger">view</a>
						</div>
						<hr>
						<div class="" id="tradeHistory_"></div>
					</div>
				</div>
			</article>
			<!-- Graph for Trade(s) END -->


			<!-- Analysis for deposit and withdrawal START -->
			<!-- <article class="row">
				<div class="col-md-6">
					<div class="card h-100">
						<div class="d-flex justify-content-between">
							<h4 class="text-dark">Deposit</h4>
							<a href="../deposit/" class="btn btn-sm btn-danger">view</a>
						</div>
						<hr>
						<div id="depositLog_"></div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="card h-100">
						<div class="d-flex justify-content-between">
							<h4 class="text-dark">Withdrawal</h4>
							<a href="../withdrawal/" class="btn btn-sm btn-danger">view</a>
						</div>
						<hr>
						<div id="withdrawalLog_"></div>
					</div>
				</div>
			</article> -->
			<!-- Analysis for deposit and withdrawal END -->


			<!-- Analysis for deposit and withdrawal START -->
			<!-- <article class="row">
				<div class="col-md-7">
					<div class="card h-100">
						<h4 class="text-dark">Referral</h4>
						<hr>
						<div>
							<p>No data</p>
						</div>
					</div>
				</div>

				<div class="col-md-5">
					<div class="card h-100">
						<h4 class="text-dark">Rebate</h4>
						<hr>
						<div>
							<p>No data</p>
						</div>
					</div>
				</div>
			</article> -->
			<!-- Analysis for deposit and withdrawal END -->


		</section>
		<!-- Statistics END -->
	</main>
</section>
<script type="text/javascript">
$(()=> {
	// Trades
	APP.getInfo("trade", APP.CONFIG.GETTER)
	.then(Dy => {
		if(Dy.length){
			let open = close = profit = loss = 0; // Info for trade chart
			Dy.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)

				// Profit and loss
				if(DETAIL.profit < 0){
					loss -= DETAIL.profit
				} else if(DETAIL.profit >= 0) {
					profit += DETAIL.profit
				}

				if(DETAIL.status === 'open') open++;
				if(DETAIL.status === 'close') close++;

				// eval(`${DETAIL.status++}`); // Increment trade status, profit and loss

				let color = "danger"
				if(DETAIL.order_type === 'buy') color = 'info'
				let inner = `
				<div class="mb-3">
					<div class="d-flex align-items-center justify-content-between text-${color}">
						<p>${DETAIL.volume} ${DETAIL.security_name} (${DETAIL.order_type})</p>
						<p>${DETAIL.total_price} <small class="f-sm text-muted">${APP.CONFIG.APP_CURRENCY}</small></p>
					</div>

					<div class="d-flex align-items-center justify-content-between f-sm text-muted">
						<p>${DATA.tzstamp}</p>
						<p>Profit: ${DETAIL.profit}</p>
					</div>
				</div>`
				$("#tradeHistory_").append(inner)
			})

			// tradeChart(Dy.length, open, close, profit, loss)
			tradeChart(Dy.length, open, close, profit, loss) // Call chart fn
			$("#openTrade_").html(open)
		} else {
			$("#tradeHistory_").append(`<p class="text-center">No data</p>`)
		}
	})

	// Deposit and withdrawal
	let de_wi = ['deposit', 'withdrawal']
	for(let x of de_wi){
		APP.getInfo(x, APP.CONFIG.GETTER)
		.then(Dy => {
			if(Dy.length){
				// Show counts
				$(`#${x}Count_`).html(Dy.length);

				let MAX = 5, BREAK = 0;
				Dy.map(DATA => {
					let DETAIL = JSON.parse(DATA.detail)
					if(BREAK === MAX) return;
					let status = DETAIL.status, color = 'info'
					if(status == 'rejected'){
						color = 'danger'
					} else if(status == 'complete'){
						color = 'success'
					}

					let inner = `
					<div class="mb-3">
						<div class="d-flex align-items-center justify-content-between text-${color}">
							<p>${ucfirst(status)}</p>
							<p>${Number(DETAIL.amount).toLocaleString()} <small class="f-sm text-muted">${APP_CURRENCY}</small></p>
						</div>

						<div class="d-flex align-items-center justify-content-between f-sm text-muted">
							<p>${timeAgo(DATA.tzstamp, USER_TZ)} ago</p>
							<p>Fee: ${DETAIL.fee ?? '0.000'}</p>
						</div>
					</div>`

					$(`#${x}Log_`).append(inner);
					BREAK++
				})
			} else {
				$(`#${x}Log_`).append(`<p class="text-center">No data</p>`);
			}
		})
	}

	function tradeChart(total, open, close, profit, loss){

		var options = {
		    chart: {
		        type: 'bar',
		        toolbar: {
		            show: false,
		        },
		        height: '100%',
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
		            columnWidth: "20%"
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
		        categories: ['Traded', 'Open', 'Close', 'Profit', 'Loss'],
		        axisBorder: {
		            show: !1
		        }
		    },
		    colors: ["#0d6efd", "#e3eaef"],
		    series: [{
		    	name: "All time",
		        data: [total, open, close, profit, loss]
		    }],
		    fill: {
		        opacity: 0.8,
		    },
		}

		var chart = new ApexCharts(document.querySelector("#chart"), options);
		chart.render();
	}
})
</script>