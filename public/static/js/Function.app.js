// Create readable time interval (time ago)
function timeAgo(date, tz) {
	if(date == null || date == ''){
		return ''
	}

	if(!Number.isInteger(date)){
		date = new Date(date).toLocaleString({timeZone: tz});
		date = new Date(date).getTime() / 1000
	}

	let now = new Date().toLocaleString('en-US', {timeZone: tz});
	now = new Date(now).getTime() / 1000

	let seconds = now - date;
	var interval = seconds / 31536000;

	if (interval > 1) {
		return Math.floor(interval) + "yr";
	}

	interval = seconds / 2592000;
	if (interval > 1) {
		return Math.floor(interval) + "mth";
	}
	interval = seconds / 86400;
	if (interval > 1) {
		return Math.floor(interval) + "d";
	}
	interval = seconds / 3600;
	if (interval > 1) {
		return Math.floor(interval) + "hr";
	}

	interval = seconds / 60;
	if (interval > 1) {
		return Math.floor(interval) + "min";
	} else {
		return "just now";
	}
}

function timeDiff(date, tz, mode = null){
	if(!date){
		return '';
	}

	if(!tz){
		return '';
	}

	let now, static, interval;

	now = new Date().toLocaleString('en-US', {timeZone: tz});
	now = new Date(now).getTime() / 1000


	static = new Date(date).toLocaleString({timeZone: tz});
	static = new Date(static).getTime() / 1000

	// Interval difference in seconds
	interval = static - now
	// Reverse mode
	if(mode === "ago"){
		interval = now - static
	}
	

	year = parseInt(interval / 60 / 60 / 24 / 7 / 4 / 12);
	month = parseInt(interval / 60 / 60 / 24 / 7 / 4);
	week = parseInt(interval / 60 / 60 / 24 / 7);
	day = parseInt(interval / 60 / 60 / 24);
	// day = Math.floor(interval / 60 / 60 / 24);
	hour = parseInt(interval / 60 / 60 % 24, 10);
    minute = parseInt(interval / 60 % 60, 10);
    second = parseInt(interval % 60, 10);

    return {
    	year: year,
    	month: month,
    	week: week,
    	hour: hour,
    	day: day,
    	minute: minute,
    	second: second,
    }
}

// Make first letter a upper case
function ucfirst(str) {
	// converting first letter to uppercase
	return capitalized = str.charAt(0).toUpperCase() + str.slice(1);
}

// Copy text to clipboard
function copy(text){
	navigator.clipboard.writeText(text)
	.then(() => {
		APP.message(`${text}\n\nCopied to clipboard`)
	}).catch(() => {
		APP.message("something went wrong");
	});
}


// Show helper
function show(data = null){
	console.log(data)
}

// Convert to reable number
function number(num, fixed = 2){
	// show((Number(num).toLocaleString(fixed)))
    // return (num)
    return Number(num).toFixed(fixed)
}