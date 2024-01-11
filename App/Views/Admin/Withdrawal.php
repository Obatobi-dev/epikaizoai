<?php $this->view("inc/head");?>
<style type="text/css">
	.w-icon {
		font-size: 36px;
	}
	table {
		margin-bottom: 0 !important;
	}
	table::scrollbar {
		width: 4px;
	}

	thead th {
		font-size: 14px;
	}
	tbody td {
		font-size: 16px;
	}
	.btn-xs {
	    padding: 0.1875rem;
	    width: 1.625rem;
	    height: 1.625rem;
	    min-width: 1.625rem;
	    min-height: 1.625rem;
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
				<article class="row">
					<!-- First -->
					<div class="col-xl-5 col-lg-6">
						<div class="row">
							<div class="col-6">
		                        <div class="card">
		                            <div class="card-body">
		                                <div class="float-end">
		                                    <i class="fal fa-info-square text-warning w-icon"></i>
		                                </div>
		                                <h5 class="text-muted mt-0" title="Number of Customers">Total</h5>
		                                <h3 class="mt-3" id="total">0</h3>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="col-6">
		                        <div class="card">
		                            <div class="card-body">
		                                <div class="float-end">
		                                    <i class="fal fa-info-square text-info w-icon"></i>
		                                </div>
		                                <h5 class="text-muted mt-0" title="Number of Customers">Pend</h5>
		                                <h3 class="mt-3" id="pending">0</h3>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                    

						<div class="row">
		                    <div class="col-6">
		                        <div class="card">
		                            <div class="card-body">
		                                <div class="float-end">
		                                    <i class="fal fa-times-square text-danger w-icon"></i>
		                                </div>
		                                <h5 class="text-muted mt-0" title="Number of Customers">Rejected</h5>
		                                <h3 class="mt-3" id="rejected">0</h3>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="col-6">
		                        <div class="card">
		                            <div class="card-body">
		                                <div class="float-end">
		                                    <i class="fal fa-check-square text-success w-icon"></i>
		                                </div>
		                                <h5 class="text-muted mt-0" title="Number of Customers">Complete</h5>
		                                <h3 class="mt-3" id="complete">0</h3>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <!-- APEX CHART-->
		            <div class="col-xl-7 col-lg-6">
		            	<div class="card h-100" style="min-height: 200px;">
		            		<div id="withdrawalChart"></div>
		            	</div>
		            </div>
		        </article>

		        <article>
        			<div class="table-responsive no-shadow rounded-3">
			    		<table class="table table-light table-hover table-striped table-borderless" id="sortable">
        					<thead>
        						<tr><th>Id</th><th>Amount</th><th>Wallet</th><th>Status</th><th>Date</th><th>Actions</th></tr>
        					</thead>
        					<tbody id="tr"></tbody>
        				</table>
        			</div>
		        </article>
			</main>
		</section>
	</aside>
</section>
<script type="text/javascript">
	let pending = complete = rejected = 0;
	if(WITHDRAWAL.length){
		WITHDRAWAL.map(DATA => {
			let DETAIL = JSON.parse(DATA.detail)
			let status = DETAIL.status, color = 'warning'
			
			// Increment pending, complete and rejected
			eval(`${status}++`);

			if(status == 'rejected'){
				color = 'danger'
			} else if(status == 'complete'){
				color = 'success'
			}

			let auto_id = `h${APP.unique(4)}`

			let inner = `
			<tr>
				<td>${DATA.id}</td>
				<td>${DETAIL.amount} ${APP_CURRENCY}</td>
				<td>${DETAIL.wallet} <i class="fal fa-copy copy" onclick="copy('${DETAIL.wallet}')"></i></td>
				<td><i class="fa fa-circle text-${color} me-1"></i> ${status}</td>
				<td>${DATA.stamp}</td>
				<td>
					<form class="d-flex align-items-center justify-content-between" style="gap: 2rem;">
        				<input type="hidden" name="userid" value="${DATA.userid}">
        				<input type="hidden" name="id" value="${DATA.id}">
        				<input type="hidden" name="withdrawal_helper">
            			<div class="form-check form-switch">
            				<input onclick="APP.authentication(this)" type="checkbox" class="form-check-input" id="${auto_id}" ${status == 'complete'?'checked': ''}>
            				<label for="${auto_id}">Complete</label>
            			</div>
            		</form>
				</td>
			</tr>
			`
			$("#tr").append(inner)
		})
		$("#total").html(WITHDRAWAL.length)
		$("#pending").html(pending)
		$("#complete").html(complete)
		$("#rejected").html(rejected)
	}
		

	// Show analysis

	let table1 = document.querySelector('#sortable');
    let dataTable = new simpleDatatables.DataTable(table1);

    options = {
    	chart: {
    		type: 'donut',
    		width: '100%',
    		height: '200px',
    	},
    	series: [0, complete, pending, rejected],
    	labels: ['Total', 'Complete', 'Pending', 'Rejected'],
	    dataLabels: {
			enabled: true,
			formatter: function (val) {
				return val.toFixed(1) + "%"
			},
			dropShadow: {

			}
		},
		plotOptions: {
			pie:{
				customScale: 0.8
			}
		},
	}

	var withdrawalChart = new ApexCharts(document.querySelector("#withdrawalChart"), options);
	withdrawalChart.render();
</script>