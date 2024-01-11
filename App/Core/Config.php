<?php
// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

// Host name
define("HOST", $_SERVER['SERVER_NAME']);

/// Configuration
if(HOST != "localhost"){
    define("RELATIVE_PATH", ""); // Leave empty if on root directory public_html
    define("DB_HOSTNAME", "localhost");
    define("DB_USERNAME", "iamcrset_admin");
    define("DB_PASSWORD", 'jQ]6b8yFFBsl');
    define("DB_NAME", "iamcrset_index");// Enter your database name

    // Timezone
    define("SYSTEM_TZ", "Africa/Lagos"); // Change to your timezone
    // Project mode
    define("HOST_IS_LIVE", 1);
} else {
    
    // test on local server
    define("RELATIVE_PATH", "/epikaizo");
    define("DB_HOSTNAME", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DB_NAME", trim(RELATIVE_PATH, '/')); // Enter your database name
    $_SERVER['REMOTE_ADDR'] = rand(0, 200).".".rand(0, 200).".".rand(0, 200).".".rand(0, 200); // Forge a fake IP address on local server

    // Timezone
    define("SYSTEM_TZ", "Africa/Lagos");
    // Project mode
    define("HOST_IS_LIVE", 0);
}


// Set official system timezone
date_default_timezone_set(SYSTEM_TZ);


/// App setting by DEV
define("ROOT", RELATIVE_PATH);
define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'].ROOT);
define("VERSION", "1.0");
define("SKEME", $_SERVER['REQUEST_SCHEME']);
define("BASE", SKEME."://".HOST.RELATIVE_PATH);
define("SKIN", "original");
define("DEBUG", true);
define("STAMP_DATE", date("Y-m-d"));
define("STAMP", date("Y-m-d H:i:s"));
define("TIME", time());
define("PAGE_REF", $_SERVER['HTTP_REFERER'] ?? BASE); // Page referrer

define("DEFAULT_PAGE", "home"); // Default display page when website is first loaded
define("USER_ROOT", "user"); // Current folder in the viewing file
define("USER_ROOT_PATH", "/".USER_ROOT); // Default path to be seen by user (This can be changed)
// define("USER_ROOT_PATH", "/shego"); // Default part to be seen by user (This can be changed)


// Tables for database conn and api fetch from db
define("DB_TABLES", json_encode(['admin', 'user', 'userlogin', 'trade', 'wallet', 'kyc', 'bot', 'botsub', 'contact']));