<?php $this->view("inc/head");?>
<section id="wrapper">
	<aside id="dash__wrapper">
		<!-- Aside nav -->
		<?php $this->view("admin/aside");?>

		<section class="w-100">
			<!-- Top nav -->
			<?php $this->view("admin/nav");?>
			<!-- MAIN -->
			<main id="main" class="w-100">
				<article class="" id="userHistory_">
			    	<div class="card">
		            	<h3><i class="fal fa-user-alien"></i> Kyc</h3>
				    	<hr>
		            	<div class="mb-4" style="min-height: 200px;">
		            		<div id="chart"></div>
		            	</div>

		            	<div class="table-responsive no-shadow rounded-3">
			    		<table class="table table-light table-hover table-striped table-borderless" id="sortable">
	        					<thead>
	        						<tr><th>Id</th><th>Identity type</th><th>Status</th><th>Date</th><th>Actions</th></tr>
	        					</thead>
	        					<tbody id="tr"></tbody>
	        				</table>
	        			</div>
		            </div>
			    </article>
			    <script>
			    	let total = verified = under_review = revenue = 0;
			    	if(KYC.length){
			    		KYC.map(DATA => {
			    			let DETAIL = JSON.parse(DATA.detail)
			    			let color = 'danger'
			    			if(DETAIL.status === "complete"){
			    				verified++;
			    				color = `success`
			    			} else if(DETAIL.status === "under_review"){
	                        	under_review++;
	                        }

	                        let inner = `
	                        <tr><td>${DATA.id}</td><td>${ucfirst(DETAIL.identity_type)}</td><td class="text-${color}">${ucfirst(DETAIL.status.replace("_", " "))}</td><td>${DATA.stamp} <small class="text-muted f-sm">(${timeAgo(DATA.stamp)} ago)</small></td><td><a href="../client/${DATA.userid}/" class="btn btn-sm btn-primary"><i class="fal fa-user"></i> View user</a></td>
	                        </tr>
	                        `
	                        $("#tr").append(inner)

	                        // Detail for chart
	                        total++
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
					        categories: ['All', 'Verified', 'Under review'],
					        axisBorder: {
					            show: !1
					        }
					    },
					    colors: ["#0d6efd", "#e3eaef"],
					    series: [{
					    	name: "All time",
					        data: [total, verified, under_review]
					    }],
					    fill: {
					        opacity: 0.2,
					    },
					}

					var chart = new ApexCharts(document.querySelector("#chart"), options);
					chart.render();
                </script>
			</main>
		</section>
	</aside>
</section>