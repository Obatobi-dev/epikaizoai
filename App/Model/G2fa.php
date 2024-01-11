<?php
declare(strict_types=1);

namespace Model;


use \Sonata\GoogleAuthenticator\GoogleAuthenticator;
require_once APP_ROOT."/App/3rd/G2fa/FixedBitNotation.php";
require_once APP_ROOT."/App/3rd/G2fa/GoogleAuthenticatorInterface.php";
require_once APP_ROOT."/App/3rd/G2fa/GoogleAuthenticator.php";
require_once APP_ROOT."/App/3rd/G2fa/GoogleQrUrl.php";

class G2fa extends GoogleAuthenticator
{
	
}