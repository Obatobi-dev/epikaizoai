var APP = {
	message: function(msg, success = false, extra = null){
		let ico = "info";
		isErrorMode = true;
		if(success){
			ico = "success"
			isErrorMode = false;
		}

		swal({text: msg, icon: ico, button: false, dangerMode: isErrorMode})

		if(extra){
			var ext = ['redirect', 'reload'];
			if(extra.redirect){
				let loc = `${APP.CONFIG.ORIGIN}/${extra.redirect}`
				loc = loc.replace("//", "/")
				setTimeout(e => {location = loc}, 3000)
			} else if(extra.reload){
				setTimeout(e => {location.reload();}, 3000)
			}
		}
	},
	sendToServer: function(data){
		// Check if a data isn't a form data
		// Creat to a form data
		if(data instanceof FormData === false){
			let p = data
			data = new FormData();
			for(let key in p){
				data.append(key, p[key])
			}
		}

		let RES;
		$.ajax({
			type: "POST",
			url: `${APP.CONFIG.ORIGIN}/public/App/Authenticate.php`,
			data: data,
			processData: false,
			async: false,
			contentType: false,
			success: function(e){
				RES = e;
			},
			error: function(e){
				APP.message(`Network connection issue. Please check your network and try again. Or please try reload the page`);
				return;
			}
		})

		return RES
	},
	getInfo: async function(apiCall, sortType = '', isLen = false){
		// isLen is used to count the returned result from api.
		const res = await fetch(`${this.CONFIG.ORIGIN}/json/${apiCall}/${sortType}`)
		const result = await res.json();
		
		if(isLen) return result.length;
		return result;
	},
	unique: function(len = 12){
		let str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_"
		str = str.split("")
		let ran = "";
		isF = 0
		for(let i = 0; i < len; i++){
			ran += str[Math.ceil(Math.random() * str.length - 1)]
		}

		return ran;
	},
	// Configuration file (In head)
	// this.CONFIG
	authentication: function(x){
		let data = new FormData(x.form)
		if(!this.CONFIG.HOST_IS_LIVE){
			show(this.sendToServer(data))
			return
		}

		x.disabled = true
		if(result = this.sendToServer(data)){
			result = JSON.parse(result)
			this.message(result.message, result.success, result.extra)
		}
		x.disabled = false
	},
}