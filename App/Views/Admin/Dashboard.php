<?php $this->view("inc/head");?>
<style type="text/css">
	#userAnalysis {

	}
	#userAnalysis .inner .image {
		width: 60px;
	}
	#userAnalysis .inner .image img {
		object-fit: cover;
	}
	
	.apexcharts-tooltip {
		color: var(--color-primary) !important;
		font-family: "Nunito";
	}
</style>
<section id="wrapper" class="">
	<aside id="dash__wrapper">
		<!-- Aside nav -->
		<?php $this->view("admin/aside");?>

		<section class="w-100">
			<!-- Top nav -->
			<?php $this->view("admin/nav");?>

			<!-- MAIN -->
			<main id="main" class="w-100">
				<!-- ############## 1st SECTION ############## -->
				<section class="my-3">
					<article class="row">
						<!-- First -->
						<div class="col-xl-5 col-lg-6">
							<div class="row">
								<div class="col-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <div class="float-end">
			                                    <i class="fa fa-link w-icon"></i>
			                                </div>
			                                <h5 class="mt-0" title="Number of Customers">User</h5>
			                                <h3 class="my-3" data-insert="USER.length">0</h3>
			                                <h5 class="mb-0 text-muted" id="userToday_">
			                                </h5>
			                            </div>
			                        </div>
			                    </div>

			                    <div class="col-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <div class="float-end">
			                                    <i class="fa fa-link w-icon"></i>
			                                </div>
			                                <h5 class="mt-0" title="Number of Customers">Trade</h5>
			                                <h3 class="my-3" data-insert="TRADE.length">0</h3>
			                                <h5 class="mb-0 text-muted" id="tradeToday_">
			                                </h5>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			                    

							<div class="row">
			                    <div class="col-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <div class="float-end">
			                                    <i class="fa fa-link w-icon"></i>
			                                </div>
			                                <h5 class="mt-0" title="Number of Customers">Deposit</h5>
			                                <h3 class="my-3" data-insert="DEPOSIT.length">0</h3>
			                                <h5 class="mb-0 text-muted" id="depositToday_">
			                                </h5>
			                            </div>
			                        </div>
			                    </div>

			                    <div class="col-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <div class="float-end">
			                                    <i class="fas fa-users-class w-icon"></i>
			                                </div>
			                                <h5 class="mt-0" title="Number of Customers">Withdrawal</h5>
			                                <h3 class="my-3" data-insert="WITHDRAWAL.length">0</h3>
			                                <h5 class="mb-0 text-muted" id="withdrawalToday_">
			                                </h5>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>

			            <!-- APEX CHART-->
			            <div class="col-xl-7 col-lg-6">
			            	<div class="card h-100" style="min-height: 280px;">
			            		<div id="chart"></div>
			            	</div>
			            </div>
		            </article>

		            <!-- USERS ANALYSIS -->
		            <article class="">
	            		<div class="card h-100" id="userAnalysis">
	            			<div class="d-flex align-items-center justify-content-between">
	            				<h4 class="text-blue">Users</h4>
	            				<a href="../client/" class="btn btn-secondary btn-sm">View all</a>
	            			</div>
	            			<hr>

	            			<div class="inner" id="userLog_"></div>
	            			<!-- <div id="dohChart"></div> -->
		            	</div>
		            </article>
		            <!--  -->

		            <!-- TRADES ANALYSIS -->
		            <article class="row">
		            	<!-- Chart -->
		            	<div class="col-md-6">
		            		<div class="card h-100">
		            			<div class="d-flex align-items-center justify-content-between">
		            				<h4 class="text-blue">Bot</h4>
		            				
		            				<a href="../bot/" class="btn btn-secondary btn-sm">View all</a>
		            			</div>
		            			<hr>
		            			<!-- <div id="botLog"></div> -->
		            		</div>
		            	</div>

		            	<div class="col-md-6">
		            		<div class="card h-100">
		            			<div class="d-flex align-items-center justify-content-between">
		            				<h4 class="text-blue">Trade history</h4>
		            				
		            				<!-- <a href="../trade/" class="btn btn-secondary btn-sm">View all</a> -->
		            			</div>
		            			<hr>
		            			<div id="tradeLog_">
		            				
		            			</div>
		            			<div id="tradeRateLog_"></div>
		            		</div>
		            	</div>
		            </article>
		            <!--  -->
		        </section>
			</main>
		</section>
	</aside>
</section>

<script type="text/javascript">
	$(()=> {
		var userTotal = USER.length, tradeTotal = TRADE.length, depositTotal = DEPOSIT.length, withdrawalTotal = WITHDRAWAL.length;
// Log for today
userToday = tradeToday = depositToday = withdrawalToday = 0;

// Get today's date from TIMEZONE of admin
let NOW = new Date(new Date().toLocaleString('en-US', {timeZone: APP.CONFIG.SYSTEM_TZ})), month = NOW.getMonth() + 1; // In terms, to add leading zero
let DATE = `${NOW.getFullYear()}-${month < 10? `0${month}`: month}-${NOW.getDate()}`
let allLog = ['user', 'trade', 'deposit', 'withdrawal']
let LIMIT = 4;

for(let single of allLog){
	// CONSTANTS extract
	let CONSTANT = eval(single.toUpperCase()) // Turn into a function after extract
	if(CONSTANT.length){
		CONSTANT.map(DATA => {
			// Get DATA with today's date
			if(DATA.stamp.indexOf(DATE) > -1) {
				eval(`${single}Today++`) // Increment
			}
		})

		// Calc % for today so far
		let diff = eval(`${single}Total`) - eval(`${single}Today`);
		if(diff <= 0) diff = 1;
		let percentage = (eval(`${single}Today`) / diff) * 100;
		
		$(`#${single}Today_`).html(`<span class="text-success"><i class="fas fa-arrow-alt-up"></i> ${percentage.toFixed(1)}%</span><span class="text-nowrap"> Today so far </span>`)
		if(percentage <= 0) $(`#${single}Today_`).html(`<span class="text-danger"><i class="fas fa-arrow-alt-down"></i> 0.00%</span><span class="text-nowrap"> Today so far </span>`)
	} else {
		$(`#${single}Today_`).html(`<span class="text-danger"><i class="fas fa-arrow-alt-down"></i> 0.00%</span><span class="text-nowrap"> Today so far </span>`)
	}
}

// Add call log rom a function
// e.g ADMIN.userLog(limit = 5, ELEMENT (to append the element) = $()) THEn this function will return or save elements to ELEMENT self

// Create USER LOGS
if(userTotal){
	let i = 0;
	USER.map(DATA => {
		if(i == LIMIT) return;
		let inner = `
		<div class="d-flex align-items-center inner-2 mb-3" style="gap: 1rem;">
			<div class="image">
				<img src="${DATA.image ?? `${APP.CONFIG.ORIGIN+APP.CONFIG.PROFILE_IMAGE}`}" class="img-fluid rounded-pills">
			</div>
			<div class="w-100">
				<div class="d-flex justify-content-between">
					<div>
						<p class="text-dark f-sm">${ucfirst(DATA.fullname)}</p>
						<small class="first-cap d-block">${DATA.email}</small>
						<p class="f-sm">${timeAgo(DATA.stamp, APP.CONFIG.SYSTEM_TZ)} ago</p>
					</div>
					<div>
						<a href="../client/${DATA.id}/" class="btn btn-primary btn-sm text-center"><i class="fal fa-eye fa-sm"></i> View</a>
					</div>
				</div>
			</div>
		</div>`
		i++
		$("#userLog_").append(inner)
	})
}


///////////////// TRADESS
if(tradeTotal){
	// let st = ['open', 'cancelled', 'lost', 'won']
	let st = ['open', 'close']
	// let open = lost = won = cancelled = 0;
	let open = close = 0;

	// TRADE RATES For progress bar
	for(let is of st){
		TRADE.map(DATA => {
			let DETAIL = JSON.parse(DATA.detail)
			status = DETAIL.status
			if(status == is) eval(`${is}++`);
		})

		let count = eval(is); // This result count the number of trades and arranged them based on their status
		let percentage = ((count / tradeTotal) * 100).toFixed(1);

		let inner = `
		<div class="mb-4 inner">
			<p class="first-cap f-sm">${count} ${is}</p>
			<div class="progress bg-black">
				<div class="progress-bar progress-bar progress-bar-striped progress-bar-animated" style="width: ${percentage}%;">${percentage}%</div>
			</div>
		</div>`
		$("#tradeRateLog_").append(inner)
	}
	
	// TRADES
	let i = 0;
	TRADE.map(DATA => {
		if(i == LIMIT) return;
		let DETAIL = JSON.parse(DATA.detail)
		let color = "info"

        if(DETAIL.status == 'lost'){
            color = 'danger'
        } else if(DETAIL.status == "won") {
            color = 'success'
        }

        DETAIL.total_price = Number(DETAIL.total_price)

        let trade_type_color = 'success'
        if(DETAIL.order_type === 'sell') trade_type_color = 'danger';
		let inner = `
		<div class="mb-4">
			<div class="d-flex justify-content-between">
				<p class="text-${trade_type_color}" onclick="location = 'trade/${DATA.id}/'">${DETAIL.security_name.toUpperCase()} (${DETAIL.order_type})</p>
				<p class="d-block text-${color}">${DETAIL.total_price.toLocaleString()+" "+APP.CONFIG.APP_CURRENCY} <br> <small>${(DETAIL.status == 'won' || DETAIL.status == 'lost') ? DETAIL.profit.toLocaleString()+" "+APP.CONFIG.APP_CURRENCY: ''}</small></p>
			</div>

			<div class="d-flex align-items-center justify-content-between">
				<div>
					<small class="d-block first-cap text-${color} f-sm">${ucfirst(DETAIL.status)}</small>
					<small class="text-muted"><i class="fal fa-clock"></i> ${DATA.stamp} (${timeAgo(DATA.stamp, APP.CONFIG.SYSTEM_TZ)})</small>
				</div>
				

				<!--<div>
    				<button class="btn btn-danger btn-sm" onclick="ADMIN.updateTrade('lost', '${DATA.id}', '${DATA.userid}')">loss</button>
    				<button class="btn btn-success btn-sm" onclick="ADMIN.updateTrade('won', '${DATA.id}', '${DATA.userid}')">win</button>
    			</div>-->
			</div>
		</div>`
		$("#tradeLog_").append(inner)
		i++
	})

	// Calculate percentage of each
}

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
	        categories: ['User', 'Trade', 'Deposit', 'Withdrawal'],
	        axisBorder: {
	            show: !1
	        }
	    },
	    colors: ["#0d6efd", "#e3eaef"],
	    series: [{
	    	name: "All time",
	        data: [userTotal, tradeTotal, depositTotal, withdrawalTotal]
	    }, {
	        name: "Today",
	        data: [userToday, tradeToday, depositToday, withdrawalToday],
	    }],
	    fill: {
	        opacity: 0.8,
	    },
	}

	var chart = new ApexCharts(document.querySelector("#chart"), options);
	chart.render();
})
</script>