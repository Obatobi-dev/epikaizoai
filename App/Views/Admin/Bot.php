<?php $this->view("inc/head");?>
<style type="text/css">
	
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
				<article class="row">
					<!-- First -->
					<div class="col-xl-5 col-lg-6">
						<div class="row">
							<div class="col-6">
		                        <div class="card bg-primary text-light h-100 d-block">
	                                <h5 class="" title="Number of Customers">Total subscription</h5>
	                                <h3 class="mt-3" data-insert="BOT_SUB.length">0</h3>
	                            </div>
		                    </div>

		                    <div class="col-6">
		                        <div class="card h-100 d-block">
	                                <h5 class="" title="Number of Customers">Running</h5>
	                                <h3 class="mt-3" id="runningBot_">0</h3>
		                        </div>
		                    </div>
		                </div>
		                    

						<div class="row">
		                    <div class="col-6">
		                        <div class="card bg-dark text-light h-100 d-block">
	                                <h5 class="" title="Number of Customers">Closed</h5>
	                                <h3 class="mt-3" id="closedBot_">0</h3>
		                        </div>
		                    </div>

		                    <div class="col-6">
		                        <div class="card h-100 d-block">
	                                <h5 class="" title="Number of Customers">Withdrawn</h5>
	                                <h3 class="mt-3" id="withdrawnBot_">0</h3>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <!-- APEX CHART-->
		            <div class="col-xl-7 col-lg-6">
		            	<div class="card h-100" style="min-height: ;">
		            		<div id="chart"></div>
		            	</div>
		            </div>
	            </article>

	            <!--  -->
	            <article class="card">
	            	<h4 class="text-dark"><?=APP_NAME?> bots</h4>
	            	<hr>
            		<div class="table-responsive no-shadow rounded-3">
			    		<table class="table table-light table-hover table-striped table-borderless">
        					<thead>
        						<tr>
									<th>Version</th>
									<th>Min</th>
									<th>Max</th>
									<th>Duration / Terminate</th>
									<th>Action</th>
								</tr>
        					</thead>
        					<tbody id="bot"></tbody>
        				</table>
        			</div>
	            </article>
	            <!--  -->

				<article class="" id="userHistory_">
			    	<div class="card">
		            	<h4><i class="fal fa-user-robot"></i> <?=APP_NAME?> bot subscription</h4>
				    	<hr>

				    	<div class="table-responsive no-shadow rounded-3">
				    		<table class="table table-light table-hover table-striped table-borderless" id="sortable">
	        					<thead>
	        						<tr>
										<th>Version</th>
										<th>Amount</th>
										<th>Profit</th>
										<th>Status</th>
										<th>Lock duration</th>
										<th>Matured</th>
										<th>Withdrawn</th>
										<th>Withdrawal date</th>
									</tr>
	        					</thead>
	        					<tbody id="tr"></tbody>
	        				</table>
	        			</div>
		            </div>
			    </article>
			    
			</main>
		</section>
	</aside>
</section>

<script>
	$(() => {
		let total = all_running = all_closed = v2_1_total = v2_1_running = v2_1_closed = v4_0_total = v4_0_running = v4_0_closed = v7_0_total = v7_0_running = v7_0_closed = 0, v = ['2.1', '7.0', '4.0']
		if(BOT_SUB.length){
			BOT_SUB.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)

				// Data sorting for versions of bot on chart
				if(v.includes(DETAIL.version)){
					// Running and total
					let extract_version = `v${DETAIL.version.replace(".", "_")}`; //

					// Get total
					eval(`${extract_version}_total++`)

					// If status is running then increase running status
					if(DETAIL.status === 'running') eval(`${extract_version}_running++`);
					if(DETAIL.status === 'closed') eval(`${extract_version}_closed++`);
				}

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

	            let inner = `
	            <tr>
					<td>V${DETAIL.version}</td>
					<td>${DETAIL.amount} <small class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</small></td>
					<td>${DETAIL.profit} <small class="badge bg-danger">${APP.CONFIG.APP_CURRENCY}</small></td>
					<td><span class="badge bg-${APP.CONFIG.COLOR[DATA.status]} text-cap">${DATA.status}</span></td>
					<td>${DETAIL.lock_duration} day(s) <small class="f-sm text-muted">${day_left} day(s) left</small></td>
					<td><span class="badge bg-${APP.CONFIG.COLOR[DATA.status]}">${DETAIL.isMatured?'Yes':'No'}</span></td>
					<td><span class="badge bg-${APP.CONFIG.COLOR[DATA.status]}">${DETAIL.isWithdrawn?'Yes':'No'}</span></td>
					<td>--</td>
				</tr>`
	            $("#tr").append(inner)

	            total++
	            if(DETAIL.status === 'running') eval(`all_running++`);
	            if(DETAIL.status === 'closed') eval(`all_closed++`);
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
		        categories: ['All', '7.0', '4.0', '2.1'],
		        axisBorder: {
		            show: !1
		        }
		    },
		    colors: ["#0d6efd", "#24126A", "#dc3545"],
		    series: [{
		    	name: "All time",
		        data: [total, v7_0_total, v4_0_total, v2_1_total]
		    },
		    {
		    	name: "Running",
		        data: [all_running, v7_0_running, v4_0_running, v2_1_running]
		    },
		    {
		    	name: "Closed",
		        data: [all_closed, v7_0_closed, v4_0_closed, v2_1_closed]
		    }
		    ],
		    fill: {
		        opacity: 0.9,
		    },
		}

		var chart = new ApexCharts(document.querySelector("#chart"), options);
		chart.render();



		// <?=APP_NAME?> bots
		if(BOT.length){
			BOT = BOT.reverse()
			BOT.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)

				// Auto id for deposit
				let auto_id = `h${APP.unique(4)}`
				let inner = `
				<tr>
					<td>${DATA.version}</td>
					<td>${Number(DETAIL.min).toLocaleString()} <small class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</small></td>
					<td>${Number(DETAIL.max).toLocaleString()} <small class="badge bg-primary">${APP.CONFIG.APP_CURRENCY}</small></td>
					<td>${DETAIL.lock_duration} days</td>
					<td>
						<form class="d-flex align-items-center justify-content-between" style="gap: 1rem;">
							<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-bot='${JSON.stringify(DATA)}' onclick="updateBot(this)">Edit</a>
	        				<input type="hidden" name="id" value="${DATA.id}">
	        				<input type="hidden" name="epikaizo_helper">
	        				<input type="hidden" name="type" value="status">
	            			<div class="form-check form-switch">
	            				<input onchange="APP.authentication(this)" type="checkbox" class="form-check-input" id="${auto_id}" ${DATA.active == 1 ?'checked': null}>
	            				<label for="${auto_id}">Active</label>
	            			</div>
	        			</form>
					</td>
				</tr>
				`
				$("#bot").append(inner)
			})
		}
	})

function updateBot(self){
	let EPIKAIZO = JSON.parse(self.dataset.bot), DETAIL = JSON.parse(EPIKAIZO.detail);
	$("#botUpdateForm_ .inner").html(``)
	$("#idEye_").val(EPIKAIZO.id)
	for(let key in DETAIL){
		let type = "number", ext = "";
		if(key === 'lock_duration') ext = "(days)";
		if(key === 'return') ext = "%";

		let inner = `<div class="form-group">
			<div class="input-group">
				<span class="input-group-text bg-light">${ucfirst(key.replace("_", " "))} ${ext}</span>
				<input type="${type}" step="any" class="form-control bg-light" name="${key}" placeholder="Enter ${key.replace("_", " ")}" value="${DETAIL[key]}">
			</div>
		</div>`

		$("#botUpdateForm_ .inner").append(inner)
	}
}
</script>
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h3 class="modal-title">Update epikaizo<span id="botVersion_"></span></h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form autocomplete="off" id="botUpdateForm_">
					<div class="inner"></div>

					<button class="btn btn-primary w-100"onclick="APP.authentication(this)">
						<span>Update</span>
						<input type="hidden" name="id" id="idEye_" value="">
						<input type="hidden" name="type" value="detail">
						<input type="hidden" name="epikaizo_helper">
					</button>
				</form>
			</div>
		</div>
	</div>
</div>