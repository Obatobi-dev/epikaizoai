<?php
// User this to get all active bots. This is used to run it as a ROBOT
\Model\User::autoRobot();

$vendor_scripts = ['jquery', 'apexchart', 'bootstrap', 'myalert', 'datatable'];
$vendor_css = ['aos', 'bootstrap'];
$scripts = ['Function', 'App', 'Main'];
if(HOST_IS_LIVE){
	define('MR_VERSION', '0.0000001000002');
} else {
	define('MR_VERSION', TIME);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=APP_NAME?></title>
<meta name="description" value="<?=APP_NAME?>">
<?php foreach($vendor_css as $script):?>
	<link href="<?=ROOT;?>/public/vendor/css/<?=$script?>.min.css" rel="stylesheet">
<?php endforeach;?>
<link href="<?=ROOT;?>/public/static/css/mandatory.min.css?version=<?=MR_VERSION?>" rel="stylesheet">
<link href="<?=BASE.LOGO_IMAGE?>" rel="image_src">
<link href="<?=BASE.FAV_ICON?>" rel="icon" type="image/x-icon">
<?php foreach($vendor_scripts as $script):?>
	<script src="<?=ROOT;?>/public/vendor/js/<?=$script?>.min.js"></script>
<?php endforeach;?>
<?php foreach($scripts as $script):?>
	<script src="<?=ROOT;?>/public/static/js/<?=$script?>.app.js?version=<?=MR_VERSION?>"></script>
<?php endforeach;?>
<script type="text/javascript" src="https://chatterpal.me/build/js/chatpal.js?8.3" integrity="sha384-+YIWcPZjPZYuhrEm13vJJg76TIO/g7y5B14VE35zhQdrojfD9dPemo7q6vnH44FR" crossorigin="anonymous" data-cfasync="false"></script>
<script type="text/javascript">
APP.CONFIG = {
	HOSTNAME: "<?=HOST;?>",
	HOST_IS_LIVE: <?=HOST_IS_LIVE?>,
	BASE: "<?=BASE?>",
	VERSION: "<?=VERSION?>",
	APP_NAME: "<?=APP_NAME?>",
	GETTER: "<?=$id ?? ''?>",
	ORIGIN: "<?=ROOT;?>",
	PROFILE_IMAGE: '<?=PROFILE_IMAGE?>',
	CURRENCY: "<?=CURRENCY?>",
	APP_CURRENCY: "<?=APP_CURRENCY?>",
	COIN_ICON_API: "<?=COIN_ICON_API?>",
	USER_TZ: "<?=$timezone ?? SYSTEM_TZ;?>",
	SYSTEM_TZ: "<?=SYSTEM_TZ ?? json_decode(\Model\Cookie::read('HUGON'))->timeZone ?? null?>",
	BOT: <?=BOT_PLANS?>,
	COLOR: {
		pending: "info",
		running: "info",
		reject: "danger",
		complete: "success",
		closed: "danger",
	}
}

// Show only on live server !!!!!!
if(APP.CONFIG.HOST_IS_LIVE){
	// Live chat
	/*var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/64e720f1cc26a871b03114c7/1h8jdhccl';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();*/
	var chatPal = new ChatPal({embedId: '572bqQuJAGBN', remoteBaseUrl: 'https://chatterpal.me/', version: '8.3'});
}
</script>

<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="09a0f454-fee6-4ea1-ad2b-7252d102c8d0" data-blockingmode="auto" type="text/javascript"></script>
<script id="CookieDeclaration" src="https://consent.cookiebot.com/09a0f454-fee6-4ea1-ad2b-7252d102c8d0/cd.js" type="text/javascript" async></script>
<!--End of Tawk.to Script-->
</head>
<body>