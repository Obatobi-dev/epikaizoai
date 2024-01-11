<?php

namespace Model;

class Api
{
	######################### Ip and location info
	public static function ipInfo($ip = null, $key = null){
		if($ip == null){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$link_1 = "http://ip-api.io/json/${ip}";
		$link_2 = "http://www.geoplugin.net/php.gp?ip=${ip}"; // serialized

		$info_1 = json_decode(file_get_contents_curl($link_1), true) ?? [];
		$info_2 = unserialize(file_get_contents_curl($link_2)) ?? [];

		$data_mg = array(
			"dollar_rate" => @$info_2['geoplugin_currencyConverter'],
			"currency" => @$info_2['geoplugin_currencyCode'],
			"currencySymbol" => @$info_2['geoplugin_currencySymbol'],
			"continent" => @$info_2['geoplugin_continentName'],
		);

		$final_data = array_merge($info_1, $data_mg);
		$info = [];

		// Remove '_' sign from array keys
		foreach($final_data as $key => $val){
			$keys = explode("_", $key); // Extracts
			$count = count($keys); // Count the keys
			// if the keys is more than one then ....
			//  That is, if a key has no _ in it name, it wouldn't modify it
			if($count > 0){
				for($i = 0; $i < $count; $i++){
					if($i != 0){
						$keys[$i] = ucfirst($keys[$i]);
					}
				}
				$keys = implode("", $keys);
			}

			$info[$keys] = $val;
		}

		return (object) $info;
	}


	public static function UserAgent(){
		$referrer = $browser = $device = "";

		if(isset($_SERVER['HTTP_REFERER'])){
			$refDetail = strtolower(PAGE_REF);
			$platform = "";
			if(empty($refDetail)){
				$referrer = "direct";
				$platform = "others";
			} else if(!empty($refDetail)){
				$listOfReferrer = array(
					'facebook' => 'facebook',
					'instagram' => 'instagram',
					'google' => 'google',
					'twitter' => 'twitter',
					'whatsapp' => 'whatsapp',
					'quora' => 'quora',
					'pinterest' => 'pinterest',
					'reddit' => 'reddit',
					'mail' => 'email',
					HOST => 'self'
				);

				foreach($listOfReferrer as $ref => $val){
					if(stristr($refDetail, $ref)){
						$referrer = $val;
						$platform = "social";
					} else {
						$referrer = "others";
						$platform = "social";
					}
				}
			}
		} else {
			$referrer = "direct";
			$platform = "others";
		}

		// User browser information
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

		// Get user browser name
		$browsers = array(
			'edge' => 'edge',
			'chrome' => 'chrome',
			'msie' => 'ie', // Internet explorer
			'trident' => 'internet explorer',
			'netscape' => 'netscape',
			'maxthon' => 'maxthon',
			'konqueror' => 'konqueror',
			'opera' => 'opera',
			'firefox' => 'firefox',
			'ucbrowser' => 'ucbrowser',
			'brave' => 'brave',
			'safari' => 'safari'
		);

		foreach($browsers as $ref => $val){
			if(stristr($userAgent, $ref)){
				$browser = $val;
				break; // A break was added because CHROME also carry SAFARI.. This will prevent from taking safari as chrome and vice
			} else {
				$browser = 'others';
			}
		}

		// Get user device
		// The reason why IPAD appeared before MOBILE is because, in the $userAgent the IPAD came first. No conflict will be caused..
		if(stristr($userAgent, 'ipad')){
			$device = 'tablet';
		} else if(stristr($userAgent, 'mobile')){
			$device = 'mobile';
		} else {
			$device = 'computer';
		}

		// Get user OS -- device name

		return (object) array('referrer' => $referrer, 'platform' => $platform, 'browser' => $browser, 'device' => $device);
	}



	######################### Image upload
	public static function imageUpload($image, $max_size = 4, /*$compress = true,*/ $compression = 60){

		// Compress the image
		$accept = ["jpg", "png", "jpeg", "gif", "webp",];

		// 
		// Get extention of the uploaded image
		$ext = pathinfo($image['name'], PATHINFO_EXTENSION);

		$image['max_size'] = 4;

		$result = self::upload($image, "image");

		// Failure test
		if(!is_object($result)){
			return $result;
		}


		// On success, then compress
		$imagename = $result->filename;

		// If svg type
		if($ext === 'svg'){
			return (object) array('filename' => $imagename);
		}

		$image_file_name = APP_ROOT.$imagename;
		$imageInfo = getimagesize($image_file_name);

		switch ($imageInfo['mime']){
			case 'image/jpeg':
				$img_resource = imagecreatefromjpeg($image_file_name);
				break;
			case 'image/png':
				$img_resource = imagecreatefrompng($image_file_name);
				break;
			case 'image/webp':
				$img_resource = imagecreatefromwebp($image_file_name);
				break;
			case 'image/gif':
				$img_resource = imagecreatefromgif($image_file_name);
				break;
			default:
				$img_resource = imagecreatefromjpeg($image_file_name);
				break;
		}

		imagejpeg($img_resource, $image_file_name, $compression);
		return (object) array('filename' => $imagename);
	}


	public static function qrCode($link, $imagename = null){
		if($qr = file_get_contents_curl("https://chart.apis.google.com/chart?cht=qr&chs=500x500&chl=".$link)){
            $qr = imagecreatefromstring($qr);
            $path = "/public/static/upload/image/";
            $code = $imagename ?? self::random();
            $qrimage = "${path}${code}.png";

            // Create a file path if not exist
			if(!file_exists(APP_ROOT.$path)){
				mkdir(APP_ROOT.$path, 0777, true);
			}

            $qr = imagejpeg($qr, APP_ROOT.$qrimage); // Upload qr to server
            return $qrimage;
        }

        form_message("Can't create QRCODE at the moment. Make sure you have an internet connection", $this->type);
	}



	######################### File upload
	public static function upload($file, string $type = null){
		// Check if the type is null.
		if(is_null($type)){
			return "File type is required";
		}

		$file = (object) $file; // Convert to an object array
		
		// File extract
		$type = strtolower($type);
		$fileTemp = $file->tmp_name;
		$filename = $file->filename ?? self::random(12);
		$ext = pathinfo($file->name, PATHINFO_EXTENSION);

		// Create a file path if not exist
		$path = "/public/static/upload/$type/";
		if(!file_exists(APP_ROOT.$path)){
			mkdir(APP_ROOT.$path, 0777, true);
		}

		// Add root path to make upload possible
		$filename = $path.$filename.".".$ext;

		// Upload file
		if(!move_uploaded_file($fileTemp, APP_ROOT.$filename)){
			return "Some error occured while uploading the $type. Please try again ...";
		}

		return (object) array('filename' => $filename);
	}

	######################### Random strings
	public static function random($length = 12, $useLowerCase = false, $useUpperCase = false, $useNumber = false, $useSpecialChar = false){
		// Default characters if no values are being passed from the argument
		// $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		if($useLowerCase || $useUpperCase || $useNumber || $useSpecialChar){
			$chars = "";
		}

		if($useLowerCase){
			$chars .= "abcdefghijklmnopqrstuvwxyz";
		}

		if($useUpperCase){
			$chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		}

		if($useNumber){
			$chars .= "0123456789";
		}

		if($useSpecialChar){
			$chars .= "!@#$%^&*()?></'\|{}[]`~,._-";
		}

		$chars = str_split($chars);
		$result = "";

		for($i = 0; $i < $length; $i++){
			$result .= $chars[rand(0, count($chars) - 1)];
		}

		return $result;
	}

	// OTP code
	public static function otpCode($isText = false, $len = 6){
		// Text only
		if($isText){
			return self::random($len);
		}

		// Number only
		return self::random($len, 0, 0, true);
	}
}