<?php

namespace Model;

class Otp {
	use Model;

	protected static function create($len = 6){
		Api::random($len);


		// Create a session
	}
}