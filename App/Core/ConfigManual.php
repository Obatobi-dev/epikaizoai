<?php
// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

/**
 * @param
 * Site setting
 * Basic settings by APP owner
 * 
*/

// Connection to the databse will be made from here to fill in the blanks
// All DEFINE value will be from the database except exceptional
// Manual setting -- Other confige to be manipulated manually from database setting

// Site setting
define("APP_NAME", "Epikaizo AI");
define("APP_NAME_ABBR", "EAI");
define("SITENAME", "Your site name");

// Ext key
define("USER_COOKIE", "csf_enk__");
define("USER_SESSION", "JAT");
define("VERIFICATION_DATA", "verification_data"); // Signup key holder
define("AJAX_HANDLER_KEY", "AJAXResponse");
define("G2FA", 'g2fa');


define("PAGE_TITLE", APP_NAME ." - ". ucwords(str_replace("/", " | ", trim($_GET['name'] ?? "home"))));
define("NOTIFICATION_EMAIL", 'obatobi.dev@gmail.com'); // Notification email
define("CONTACT_EMAIL", 'support@'.HOST); // Notification email


// Mailer setting
// Mailer setting
// DEPLOYED ON LIVE

if(HOST != 'localhost'){
    // define("MAILER_HOST", strtolower(HOST)); // Mailer server host
    define("MAILER_HOST", 'vip.bodpay.xyz'); // Mailer server host
    define("MAILER_USERNAME", 'info@bodpay.xyz'); // mailer username
    define("MAILER_SETFROM", 'info@bodpay.xyz'); // Mailer sent from who ?
    define("MAILER_PASSWORD", 'aqxlB5*JX!eU'); // Mailer password
    // Mailer security // tls: 587, ssl: 465
    define("MAILER_SECURE_TYPE", "ssl");
    define("MAILER_PORT", "465");
} else {
    define("MAILER_HOST", 'vip.bodpay.xyz'); // Mailer server host
    define("MAILER_USERNAME", 'info@bodpay.xyz'); // mailer username
    define("MAILER_SETFROM", 'info@bodpay.xyz'); // Mailer sent from who ?
    define("MAILER_PASSWORD", 'aqxlB5*JX!eU'); // Mailer password
    // Mailer security // tls: 587, ssl: 465
    define("MAILER_SECURE_TYPE", "ssl");
    define("MAILER_PORT", "465");
}

// Currency
define("CURRENCY", "$");
define("APP_ABBR_CURRECNY", "#");
define("APP_CURRENCY", "USDT");


// Relative image
define('BANNER1_IMAGE', '/public/static/img/respiratory/banner-bg.svg');
define('BANNER2_IMAGE', '/public/static/img/respiratory/some.jpg');

define("LOGO_IMAGE", "/public/static/img/respiratory/logo.jpeg");
define("LOGO_IMAGE_HTML", "<img src='".ROOT.LOGO_IMAGE."' class='img-fluid rounded-3' style='height: 30px; width: 120px;object-fit: contain;background: none;'>");

define("PROFILE_IMAGE", "/public/static/img/respiratory/avatar.svg");
define("PLACEHOLDER_IMAGE", "/public/static/img/respiratory/placeholder.png");

define("DOT_IMAGE", "/public/static/img/respiratory/dot.svg");
define("RIGHT_DOT_IMAGE", "/public/static/img/respiratory/right-dots.svg");
define("BG_IMAGE", "/public/static/img/respiratory/bg.jpg");
define("BG1_IMAGE", "/public/static/img/respiratory/bg-1.png");
define("BG2_IMAGE", "/public/static/img/respiratory/bg-2.jpg");
define("BG3_IMAGE", "/public/static/img/respiratory/bg-3.jpg");
define("BG4_IMAGE", "/public/static/img/respiratory/bg-4.jpg");
define("MALE_PROFILE_IMAGE", "/public/static/img/respiratory/male.svg");
define("FEMALE_PROFILE_IMAGE", "/public/static/img/respiratory/female.svg");
define("COIN_ICON_API", "https://cdn.jsdelivr.net/gh/atomiclabs/cryptocurrency-icons@1a63530be6e374711a8554f31b17e4cb92c25fa5/svg/color/");

define("AI_PHOTO_1", "/public/static/img/respiratory/ai-photo-1.jpg");
define("AI_PHOTO_2", "/public/static/img/respiratory/ai-photo-2.jpg");
define("FAV_ICON", "/public/static/img/respiratory/fav-light.jpg");


// Errors
define("SYSTEM_ERROR", "Sorry, an error occured from our end.\n\nPlease try again.");
define("VIOLATION_ERROR", "You are violating our policy. Your account will be ban if you try again.");


// Models
// Bot
$ADMIN_MODEL = new \Model\Admin;
$BOT_MODEL = new \Model\Bot;
define("BOT_PLANS",
    $BOT_MODEL->findSingle('bot', ['active' => true])
);

// var_dump($BOT_MODEL->findSingle('bot', ['active' => true]));

// contact, social, site
if(!$ADMIN_DATA = $ADMIN_MODEL->read('username', 'admin')){
    // Create a data and insert into the database
    $data = [
        'username' => 'admin',
        'password' => password_hash('12345678', PASSWORD_DEFAULT),
        'timezone' => ''
    ];
    $ADMIN_MODEL->create($data);
}

// Admin password
define('ADMIN_USERNAME', $ADMIN_DATA->username ?? null);
define('ADMIN_PASSWORD', $ADMIN_DATA->password ?? null);
$contact = json_decode($ADMIN_DATA->contact ?? '');
$sitesetting = json_decode($ADMIN_DATA->sitesetting ?? '');

// Contact setting
define('ADMIN_CONTACT_EMAIL', $contact->email ?? null);
define('ADMIN_CONTACT_WHATSAPP', $contact->whatsapp ?? null);
define('ADMIN_CONTACT_TELEGRAM', $contact->telegram ?? null);
define('ADMIN_CONTACT_PHONE', $contact->phone ?? null);


// Site settings
define("DEPOSIT_WALLET", $sitesetting->deposit_wallet ?? null);
define("REBATE_AMOUNT", $sitesetting->rebate ?? 0);

// Social links
define("FACEBOOK_LINK", $sitesetting->facebook_link ?? null);
define("YOUTUBE_LINK", $sitesetting->youtube_link ?? null);
define("TWITTER_LINK", $sitesetting->twitter_link ?? null);
define("TELEGRAM_LINK", $sitesetting->telegram_link ?? null);

// Set cookie of Geo Location
if(!isset($_COOKIE['HUGON'])){
    $geo = Model\Api::IpInfo();
    if($geo->continent != null){
        setcookie("HUGON", json_encode($geo), strtotime("+3 day"), "/", null, true, true);
    }
}