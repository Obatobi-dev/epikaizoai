<article class="row" id="bots_">
</article>
<script type="text/javascript">
$(() => {
	let BOT = APP.CONFIG.BOT
	BOT = BOT.reverse()
	BOT_COUNTER = BOT.length; // Used for BOT display on big screen
	BOT.map(DATA => {
		let bot = JSON.parse(DATA.detail)
		let inner = `<div class="col-12 col-lg-${12 / BOT_COUNTER}">
			<div class="card h-100 d-block">
				<h3 class="text-dark"><i class="fal fa-robot"></i> ${APP.CONFIG.APP_NAME} V${DATA.version}</h3>
				<div class="my-5">
					<p><i class="fal fa-badge-check"></i> ${bot.return}%  return daily & compounding</p>
					<p><i class="fal fa-badge-check"></i> Release of capital every <b>${bot.lock_duration} days</b></p>
					<p class="text-dark">Investment capital (Per subscription)</p>
					<p class="text-bold"><i class="fal fa-badge-check"></i> <span></span><span>Min: ${APP.CONFIG.APP_CURRENCY+bot.min.toLocaleString()}</span> <span>Max:  ${APP.CONFIG.APP_CURRENCY+bot.max.toLocaleString()}</span></p>
					<p><i class="fal fa-badge-check"></i> Terminate after every ${bot.lock_duration} days</p>
				</div>
				<button class="btn btn-primary bot-sub-btn w-100" type="button" data-bs-toggle="modal" data-bs-target="#myModal" data-version="${DATA.version}" data-bot='${JSON.stringify(bot)}'>Subscribe now</button>
			</div>
		</div>`
		$("#bots_").append(inner)
	})

	$(".bot-sub-btn").click(function(){
		let version = this.dataset.version
		// Bot = BOT[version];
		let Bot = JSON.parse(this.dataset.bot)
		Bot.version = version
		$("#versionEye_").val(version); // Place version

		// Get id of an html element then insert the value according to the set name of the html element
		for(let x in Bot){
			$(`#bot${ucfirst(x)}_`).html(`${Bot[x].toLocaleString()}`)
		}
	})
})
</script>
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Subscribe to <?=APP_NAME?> V<span id="botVersion_"></span></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form autocomplete="off">
					<div class="form-group">
						<label>Enter amount</label>
						<div class="input-group">
							<input type="text" step="any" class="form-control" placeholder="Bal: <?=APP_CURRENCY?> <?=number_format($balance ?? 0, 2)?>" name="amount">
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