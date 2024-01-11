<?php
define("ISLOADED", __DIR__); // Page approver agent

/**
 * @return
 * @ APP DEVELOPED BY OBATOBI.DEV
*/

$minPHPVersion = '7.4';
if (phpversion() < $minPHPVersion)
{
  die("Your PHP version must be ${minPHPVersion} or higher to run this app. Your current version is " . phpversion());
}

ob_start();
clearstatcache();

/**
 * @param
 * @return
 * ENVIRONMENT and SITE SETTING is in app/core/config-manual.php
 * DATABASE SETTING is in app/core/config.php
*/

// Initializer
require "App/Core/Init.php";
$APP = new App();
$APP->loadController();