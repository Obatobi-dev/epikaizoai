<?php $this->view("inc/head");?>
<style type="text/css">
	#openOrder_ {
		position: absolute;
		bottom: 0;
		left: 0;
	}
</style>
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<section id="wrapper" class="bg-light">
	<?php $this->view(USER_ROOT."/nav");?>
	<main class="">
		<!-- Analysis for deposit and withdrawal START -->
		<section class="row">
			<div class="col-lg-8 col-xl-9">
				<div class="card h-100" style="/*min-height: 300px;*/">
					<div class="row gy-1">
						<div class="col-6 col-sm-4 col-md-2">
							<li class="drp-menu m-0 p-0">
								<p class="dropdown-toggle nav-link drp-btn f-sm m-0 p-0" data-bs-toggle="dropdown" id="pair_both">-----</p>
								<ul class="dropdown-menu drp-menu" style="min-width: 250px;" id="securityList_"></ul>
							</li>
							<p class="f-sm text-muted text-cap" id="pair_name">--</p>
						</div>

						<div class="col-6 col-sm-4 col-md-2">
							<p class="f-sm">Last price</p>
							<p class="f-sm text-muted" id="lastPrice">0</p>
						</div>

						<div class="col-6 col-sm-4 col-md-2">
							<p class="f-sm">24 hr change</p>
							<p class="f-sm text-muted" id="priceChangePercent">0</p>
						</div>

						<div class="col-6 col-sm-4 col-md-2">
							<p class="f-sm">24hr high</p>
							<p class="f-sm text-muted" id="highPrice">0</p>
						</div>

						<div class="col-6 col-sm-4 col-md-2">
							<p class="f-sm">24hr low</p>
							<p class="f-sm text-muted" id="lowPrice">0</p>
						</div>

						<div class="col-6 col-sm-4 col-md-2">
							<p class="f-sm">24hr volume</p>
							<p class="f-sm text-muted" id="volume">0</p>
						</div>
					</div>
					<hr>
					<div id="tradingview_1c055_3" style="min-height: 300px;"></div>
				</div>
			</div>

			<!-- Order imp -->
			<div class="col-lg-4 col-xl-3">
				<div class="card h-100 p-3">
					<form class="" id="currencyOrder_" autocomplete="off">
						<div class="d-flex align-items-center justify-content-between f-sm">
							<button type="button" class="btn btn-sm btn-light btn-primary w-100 no-shadow" data-mode="btn-primary" data-order>Buy</button>
							<button type="button" class="btn btn-sm btn-light btn-danger w-100 no-shadow" data-mode="btn-danger" data-order>Sell</button>
						</div>
						<hr>

						<!-- Display balance in <?=APP_CURRENCY?> -->
						<p class="text-muted text-end"><span class="badge bg-primary mb-1"><?=number_format($balance)?> <?=APP_CURRENCY?></span></p>

						<div class="input-group form-group">
							<span class="text-muted input-group-text f-sm bg-light">Price</span>
							<input type="number" step="any" name="volume" class="form-control bg-light text-end text-dark" id="from" value="1" onkeyup="TRADE.changeVolume()">
							<span class="text-muted input-group-text bg-light" id="getFrom_"><span class="badge bg-primary">--</span></span>
						</div>

						<div class="input-group form-group bg-light">
							<span class="text-muted input-group-text f-sm bg-light">Amount</span>
							<input type="number" step="any" name="open_price" class="form-control bg-light text-end text-dark" id="to" onkeyup="TRADE.changeVolume( this.value)" readonly>
							<span class="text-muted input-group-text bg-light" id="getTo_"><span class="badge bg-danger">--</span></span>
						</div>

						<div class="input-group form-group bg-light">
							<span class="text-muted input-group-text f-sm bg-light">Total</span>
							<input type="number" step="any" name="total_price" class="form-control bg-light text-end text-dark" placeholder="0" readonly>
							<span class="text-muted input-group-text bg-light"><span class="badge bg-primary">USDT</span></span>
						</div>

						<button class="btn btn-light no-shadow w-100 f-sm" id="placeOrder_" onclick="APP.authentication(this);">
							<span>--</span>
							<span>--</span>
							<input type="hidden" name="open_order">
							<input type="hidden" name="security_name" value="">
							<input type="hidden" name="order_type" value="">
							<input type="hidden" name="currency_type" value="">
						</button>
					</form>
				</div>
			</div>
			<!-- !st end -->
		</section>
		<!-- Analysis for deposit and withdrawal END -->



		<!-- TRADES HISTORY -->
		<section class="card">
			<ul class="nav nav-pills pb-4" role="tablist">
				<li class="nav-item">
					<a class="btn btn-primary active f-sm" data-bs-toggle="pill" href="#opentrade">Open trade</a>
					<a class="btn btn-danger f-sm" data-bs-toggle="pill" href="#tradeHistory">Closed trade</a>
				</li>
			</ul>

			<div class="tab-content">
				<!-- Open / Active trade -->
				<div id="opentrade" class="tab-pane active">
					<div class="table-responsive no-shadow rounded-3">
						<table class="table table-light table-hover table-striped table-borderless" id="stockTable_">
							<thead>
								<tr><th>Pair</th><th>Volume</th><th>Open price</th><th>Market price</th><th>Profit / Loss</th><th>Order type</th><th>Date</th><th>Actions</th></tr>
							</thead>
							<tbody id="openOrderHistory_"></tbody>
						</table>
					</div>
				</div>

				<!-- Closed trade history -->
				<div id="tradeHistory" class="tab-pane">
					<div class="table-responsive no-shadow rounded-3">
						<table class="table table-light table-hover table-striped table-borderless" id="stockTable_">
							<thead>
								<tr><th>Pair</th><th>Volume</th><th>Open price</th><th>Close price</th><th>Profit / Loss</th><th>Order type</th><th>Close time</th></tr>
							</thead>
							<tbody id="closeOrderHistory_"></tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</main>
</section>
<script type="text/javascript">
FIXED = 2
INTERVAL = null
$(() => {
	APP.getInfo("trade", APP.CONFIG.GETTER)
	.then(Json => {
		if(Json.length){
			Json.map(DATA => {
				let DETAIL = JSON.parse(DATA.detail)
				tz = `${DATA.tzstamp} (${timeAgo(DATA.tzstamp, APP.CONFIG.USER_TZ)})`;
				if(DETAIL.status == 'close'){
					show(DETAIL)
					tz = `${DETAIL.close_date} (${timeAgo(DETAIL.close_date, APP.CONFIG.USER_TZ)})`;
				}
				let inner = `
				<tr>
					<td>${DETAIL.security_name}</td>
					<td>${DETAIL.volume} <small class="badge bg-primary">${Number(DETAIL.total_price).toLocaleString()} ${APP.CONFIG.APP_CURRENCY}</small></td>
					<td>${DETAIL.open_price}</td>
					<td>${DETAIL.open_price}</td>
					<td class="text-danger">${DETAIL.profit}</td>
					<td class="first-cap text-${DETAIL.order_type == 'buy'? 'primary': 'danger'}">${DETAIL.order_type.toUpperCase()}</td>
					<td>${tz}</td>
					${DETAIL.status == 'open'? `<td>
					<form onsubmit="return false">
					<button class="btn btn-danger btn-sm activeTradeActionBtn_" onclick="APP.authentication(this)">
					<input type="hidden" name="id" value="${DATA.id}">
					<input type="hidden" name="close_order">
					<span>Close</span>
					</button>
					</form>
					</td>`: ``}
				</tr>`
				$(`#${DETAIL.status}OrderHistory_`).append(inner)
			})
		}
	})
})

// WBTC ETH

// Order table
$("[data-order]").click(function(){
	$("[data-order]").each((index, elem) => {
		$(elem).addClass("btn-light")
	})

	let order_type = $(this).html()
	$(this).removeClass("btn-light")
	$("#placeOrder_").attr({class: this.classList.value})
	$($("#placeOrder_").find("span")[0]).html(order_type)
	$("[name=order_type]").val(order_type.toLowerCase())
})

var TRADE = {
	fixed: 2,
	SECURITY_LIST: {
		crypto: {
			"BTC-USDT": "bitcoin / tether",
			"ETH-USDT": "ethereum / tether",
			"BTC-USD": "bitcoin / usd",
			"ETH-BTC": "ethereum / bitcoin",
			"SOL-USDT": "solana / tether",
			"BTC-EUR": "bitcoin / euro",
			"WBTC-ETH": "bitcoin / ethereum"
		},
		forex: {
			// "USD-EUR": "us dollar / euro",
		}
	},
	orderType: function(){},
	startOrder: function(data, isInit = false){
		// Clear interval / timer in case of conflict
		clearInterval(INTERVAL)
		// Get security pair and split them into 2
		security = data.pair.toUpperCase(); // Make upper case in case there is a lower case parse
		let get = security.split("-")
		$("#getFrom_").find('.badge').html(get[0])
		$("#getTo_").find('.badge').html(get[1])

		security = security.replace("-", ""); // Final spliting for chart purpose

		// Open chart
		new TradingView.widget({"width": "100%","height": "100%","symbol": security,"interval": "1","timezone": "Etc/UTC","theme": "light","style": "3","locale": "en","toolbar_bg": "#f1f3f6","enable_publishing": false, "save_image": false, "container_id": "tradingview_1c055_3"});

		INTERVAL = setInterval(function(){
			// Update price of security
			fetch('https://api.binance.com/api/v3/ticker/24hr').then(res => {return res.json()}).then(DATA => {
				DATA.map(COIN => {
					if(COIN.symbol === security){
						let key = Object.keys(COIN)
						let value = Object.values(COIN)
						// Get id of an html element then insert the value according to the set name of the html element
						for(let x in key){
							let val = Number(value[x]).toFixed(TRADE.fixed)
							$(`#${key[x]}`).html(val)
						}

						$("#to").val(Number(COIN.lastPrice).toFixed(TRADE.fixed)); // Security amount in USDT price
						$("[name=total_price]").val(COIN.lastPrice * $("[name=volume]").val()); // Total price multiplied by the volume set
						$("#pair_name").html(ucfirst(data.pair_name)); // Set up the pair_name type near the choose security list
						$($("#placeOrder_").find("span")[1]).html(data.pair)
						$("[name=currency_type]").val(data.type); // Set up the pair_name type near the choose security list
						let pair_both = data.pair.split("-")
						base = pair_both[0]
						quote = pair_both[1]

						$("#pair_both").html(data.pair); // Set the pair
						$("[name=security_name]").val(data.pair); // Set the pair
					}
				})
			})
		}, 3000)
		// END
	},
	init: function(){
		let LIST = this.SECURITY_LIST; // List of security pairs
		
		for(let security in LIST){
			// Loop through and add to list of security listing in the pick security
			for(let pair in LIST[security]){
				// Get pair_name of the pair currenct full name eg. btc as pair_name is bitcoin / 
				let pair_name = LIST[security][pair];

				// Base is the first currency, while the second pair is named quote
				// Get the base fo that i can insert the picture
				let pair_n = pair.split("-")
				base = pair_n[0]
				quote = pair_n[1]
				$("#securityList_").append(
					`
					<li class="my-1">
					<a class="dropdown-item f-sm" href="javascript:void(0)" data-security-type="${security}" data-security-pair="${pair}" data-security-pair-name="${pair_name}" onclick="TRADE.changePair(this)">
						<!--<i class="fal fa-star"></i>-->
						<img src="${APP.CONFIG.COIN_ICON_API}${base.toLowerCase()}.svg" alt="${base}" style="width: 22px; height: 22px;object-fit: contain;z-index: 1;">
						<img src="https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1a63530be6e374711a8554f31b17e4cb92c25fa5/svg/color/${quote.toLowerCase()}.svg" alt="${quote}" style="width: 18px; height: 18px;object-fit: contain;margin-left: -10px;">
						<span>${pair}</span>
					</a>
					</li>`
				);
			}
		}

		let securityType = "crypto"; // Security type crypto, stock, commodities
		let securityPair = "BTC-USDT"; // Initial pair
		let securityBase = LIST[securityType][securityPair]; // Initial pair fpair_name full name

		let data = {
			pair_name: securityBase,
			pair: securityPair,
			type: securityType,
		}

		this.startOrder(data, true)
	},
	changePair: function(x){
		let source = (x.dataset)
		let data = {
			pair_name: source.securityPairName, // The pair combined name
			pair: source.securityPair, // Pair e.g BTC-USDT
			type: source.securityType, // Type, crypto, forex, comodity and others
		}

		this.startOrder(data)
	},
	changeVolume: function(){
		let volume = $("[name=volume]").val()
		let open_price = $("[name=open_price]").val()
		$("[name=total_price]").val((volume * open_price))
	},
}

window.onload = () => {
	TRADE.init()
}
</script>