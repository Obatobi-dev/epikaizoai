<?php

namespace Model;

class User extends User_Auth {
	use Model;
	protected $table = "user", $type = 'user';

	// Update KYC detail from the ADMIN
	public function completeKyc($data, $USER){
		// Check if the user have uploaded KYC
		$this->table = "kyc";
		if(!$KYC = $this->read('userid', $data->userid)){
			return false;
		}

		// Change the user verification status
		$KYC_DETAIL = json_decode($KYC->detail);
		$verify_user = null;
		$verify_text = "";
		if($KYC_DETAIL->status === 'under_review'){
			$KYC_DETAIL->status = 'complete';
			$verify_user = true;
			$verify_text = "verified";
		} else if($KYC_DETAIL->status === 'complete'){
			$KYC_DETAIL->status = 'under_review';
			$verify_user = false;
			$verify_text = "nolified";
		}

		// Update KYC detail
		$KYC_DETAIL = json($KYC_DETAIL);
		$this->update(['detail' => $KYC_DETAIL], ['id' => $KYC->id]);

		// Change user's account verification status
		$this->table = "user";
		$this->update(['verified' => $verify_user], ['id' => $KYC->userid]);
		return form_message(ucfirst($USER->fullname)." account has been ${verify_text}", true);
	}




	/**
	 * /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
	 * TRADE
	 * 
	*/
	// Trade bot plans outlist, real array in the configManual file
	private function tradeBotPlan($version){
		$BOT = json_decode(BOT_PLANS);

		// Check is not bot is active
		if(empty($BOT)){
			return APP_NAME." ${version} is inactive at the moment.\n\nPlease try again.";
		}

		foreach((object)$BOT as $bot => $val){
			$Bot = $BOT[$bot];
			if($Bot->version == $version){
				// Check status
				if($Bot->active){
					return json_decode($Bot->detail); // Return the detail
				}
			}
		}

		return APP_NAME." V${version} is inactive at the moment.\n\nPlease try again.";
	}


	// Trading bot subscription
	public function robotSubscription($detail, $USER){
		$BOT = $this->tradeBotPlan($detail->version); // Get details of the bot version
		$data = new \stdClass();

		// If bot result is not successful
		if($BOT = $this->tradeBotPlan($detail->version)){
			if(!is_object($BOT)){
				return form_message($BOT);
			}
		}

		// If not a valid number
		if(!is_numeric($detail->amount)){
			return form_message("Enter a valid amount");
		}

		// Min and max invextment
		if($detail->amount < $BOT->min){
			return form_message("Min investment for bot ".$detail->version." is ".APP_CURRENCY.number_format($BOT->min, 2));
		}

		if($detail->amount > $BOT->max){
			return form_message("Max investment for bot ".$detail->version." is ".APP_CURRENCY.number_format($BOT->max, 2));
		}

		// Balance warning
		if($detail->amount > $USER->balance){
			$balance = number_format($USER->balance, 2);
			return form_message("Insufficient fund you have ".APP_CURRENCY.$balance." left.\n\nPlease top up your balance.");
		}

		// Ready for upload
		$data->id = Api::random(8);
		$data->userid = $USER->id;
		$data->stamp = STAMP;
		$data->tzstamp = TZ_STAMP;
		$detail->profit = 0.00; // Add today's percentage of earning
		$detail->daily_return = $BOT->return;
		$detail->upline = $USER->upline ?? null; // Get upline for rebate purpose
		$detail->lock_duration = $BOT->lock_duration;
		$detail->sub_date = TZ_STAMP; // Subscription date same as TZ_STAMP
		$detail->timezone = $USER->timezone; // Get user timezone for default timezone check in auto bot // Instaead of checking the user table for a particular user, this will help do the job well 100;
		$detail->withdrawn_date = null;
		$detail->isWithdrawn = false; // Weither profit and investment is returned
		$detail->isMatured = false; // Maturity date is come
		$detail->log = []; // Empty log for earning / profit purpose and rebates
		$data->status = 'running'; // Running, closed
		$data->detail = json($detail);


		$this->table = "botsub";
		$this->create($data); // Create a subscription

		// Deduct from total amount balance
		$this->balanceManager($USER->id, $detail->amount, "deduct");

		// Update meta data robot count and active
		$this->metaData($USER->id, ['robot_count' => 1, 'robot_active' => 1]);
		return form_message("Successfully subscribed to ".APP_NAME." V".$detail->version." with ".$detail->amount." ".APP_CURRENCY, true, ['reload' => true]);
	}


	// Withdraw robot earning
	public function robotWithdrawal($data, $USER){
		$this->table = 'botsub';
		$id = $data->bot_withdrawal;
		if(!$DATA = $this->read('id', $id)){
			return form_message(SYSTEM_ERROR);
		}

		// Manipulations from user
		if($DATA->userid !== $USER->id){
			return form_message(SYSTEM_ERROR);
		}

		$DETAIL = json_decode($DATA->detail); // Robot detail

		// Check maturity
		// If not matured maybe from manipulations from the user then. Send a warning or maybe ban their account
		if(!$DETAIL->isMatured){
			return form_message("Maturity date not meet");
		}

		// Check if the user have withdrawan it already
		if($DETAIL->isWithdrawn){
			return form_message("You have withdrawn your profit on ".$DETAIL->withdrawn_date);
		}

		// Good to go here
		$total_amount = $DETAIL->amount + $DETAIL->profit; // Investment plus profit
		$DETAIL->withdrawn_date = TZ_STAMP;
		$DETAIL->isWithdrawn = true;

		// Change status

		// Update bot detail
		if($this->update(['detail' => json($DETAIL), 'status' => 'closed'], ['id' => $DATA->id])){
			// Send total amount to user
			if($this->balanceManager($DATA->userid, $total_amount)){
				// Decrement active bot subscription
				$this->metaData($USER->id, ['robot_active' => -1]);
				return form_message("Withdrawal of subscription is successful.\n\nCheck your main balance, it is transferred.", true, ['reload' => true]);
			}
		}

		// Update bot detail
		return form_message(SYSTEM_ERROR);
	}


	// Robot
	public static function autoRobot(){
		// Get all user subscription
		$MODEL = new User;

		// Get only subs that are running / active
		$ROBOT_SUB = json_decode($MODEL->findSingle('botsub', ['status' => 'running']));

		// Loop through individual subscription
		foreach($ROBOT_SUB as $ROBOT){
			$ROBOT_DETAIL = json_decode($ROBOT->detail);
			$ROBOT_DETAIL->log = (array) $ROBOT_DETAIL->log;

			// Set default timezone for this user
			date_default_timezone_set($ROBOT_DETAIL->timezone);
			// $current_date = date("Y-m-d H:i:s"); // Subscriber's current date in their LOCALE_TIME_ZONE
			$current_date = date("Y-m-d"); // Subscriber's current date in their LOCALE_TIME_ZONE

			// Days left / Checking if the bot withdrawal day hasn't been meet
			// If subscription is still active
			// Check difference in the sub dates
			$diff_arr = timeDiff($ROBOT_DETAIL->sub_date, null, 'reverse');
			$days_running = $diff_arr->day; // Days the subscription have been running
			$expiry_date = date("Y-m-d", strtotime($ROBOT_DETAIL->sub_date.$ROBOT_DETAIL->lock_duration." day"));

			// if eventually the date is tally
			$log_count = count((array)$ROBOT_DETAIL->log); // Log count to see how many time have rebate
			// if($current_date < $expiry_date){
			
			if(($current_date <= $expiry_date) && ($log_count <= $ROBOT_DETAIL->lock_duration)){
				if($log_count == $days_running){
					// We can stop loop here, becuase it is up to date
					continue;
				} else if($days_running > $log_count){
					// Create log and rebates for upline here
					$remain_log_count = ($days_running - $log_count);
					/*
					// Day is always -1 if day is 1 that means i = 0; We needed to add +1 in order to get accurate day
					*/
					for($day = 1; $day <= $remain_log_count; $day++){
						$log_count++;

						$profit_return = ($ROBOT_DETAIL->amount / 100) * $ROBOT_DETAIL->daily_return; // The first day return as initial_return

						$daily_compound = ($profit_return / 100) * $ROBOT_DETAIL->daily_return * ($log_count - 1); // Daily compound // 1 was minused from the day in order to get accurate day. The first day will serve $profit_return value then as 1 - 1 = 0; second = 2 - 1 = 1 and so on, in order not go give day one profut as day 2

						$log_stamp = date("Y-m-d H:i:s", strtotime($ROBOT_DETAIL->sub_date."+${log_count} day"));
						// show($ROBOT_DETAIL->log);

						// Initial set of the rebate
						$rebate = 0;
						$haveUpline = false;

						if($ROBOT_DETAIL->upline != null){
							$rebate = (($profit_return + $daily_compound) / 100) * REBATE_AMOUNT; // The last number is the rebate percentage set by the admin
							$haveUpline = true;
						}

						$ROBOT_DETAIL->log[] = array(
							'day' => $log_count,
							'date' => $log_stamp,
							'compound_return' => $daily_compound,
							'compound_profit' => $profit_return + $daily_compound, // initial daily return + profit
							'upline_rebate' => $rebate, // 2 is in percentage
						);

						// Save to database
						if($haveUpline){
							$MODEL->giveRebate($ROBOT_DETAIL->upline, $rebate);
						}
					}

					// Resave
					$MODEL->update(['detail' => json($ROBOT_DETAIL)], ['id' => $ROBOT->id]);
				}
			} else {
				// Expired
				if(!$ROBOT_DETAIL->isMatured){
					// Change the maturity value. That is, user can now withraw earning and rebates
					// $ROBOT_DETAIL
					$ROBOT_DETAIL->isMatured = true; // Robot subscription is now matured for withdrawal.

					// Calculate all profit earned so far
					foreach($ROBOT_DETAIL->log as $log){
						$ROBOT_DETAIL->profit += $log->compound_profit;
					}

					$MODEL->update(['detail' => json($ROBOT_DETAIL)], ['id' => $ROBOT->id]);
				}
			}
		}
	}

	private function giveRebate($userid, $amount){
		$this->balanceManager($ROBOT_DETAIL->upline, $rebate);

		$this->metaData($userid, ['rebate_count' => 1, 'rebate_amount' => $amount]);
		return true;
	}






	// Open a trade order
	public function openOrder($data, $USER){
		$currency_type = $data->currency_type;

		// Validate trade
		if(empty($data->security_name)){
			return form_message("Choose a ${currency_type} pair to open order.");
		}

		if(empty($data->volume) || empty($data->open_price) || empty($data->total_price)){
			return form_message("Enter a valid trade amount to pair ".$data->security_name);
		}

		// Trade type validity
		if(empty($data->order_type)){
			return form_message("Choose an order type\n\n(Buy or Sell)");
		}

		// Order type validation
		if(!in_array($data->order_type, ['buy', 'sell'])){
			return form_message(SYSTEM_ERROR);
		}


		// Balance check
		$total_price = roundNumber($data->volume * $data->open_price, 4);
		$data->open_price = roundNumber($data->open_price, 4);
		$data->volume = roundNumber($data->volume, 4);
		$USER->balance = json_decode($USER->balance);

		// Balance warning
		if($total_price > $USER->balance){
			return form_message("Insufficient fund. Please top up your  balance. Cheers.");
		}

		$data->status = 'open'; // Open, close
		$data->close_price = 0;
		$data->close_date = null;
		$data->profit = 0;
		$data->total_price = $data->open_price * $data->volume;

		$order = new \StdClass;
		$order->userid = $USER->id;
		$order->detail = json($data);
		$order->id = Api::random(6);
		$order->stamp = STAMP;
		$order->tzstamp = TZ_STAMP;

		// Create order
		$this->table = "trade";
		$this->create($order);

		// Deduct amount of funds from the user account
		$this->balanceManager($USER->id, $total_price, 'deduct');

		//
		$this->metaData($USER->id, ['trade_count' => 1, 'trade_active' => 1]);

		// Success message
		return form_message(ucfirst($data->order_type)." order for ".$data->security_name." is successful", true, ['reload' => true]);
	}


	// CLOSE AN ACTIVE ORDER
	public function closeOrder($data, $USER){

		// Read a Trade if the a valid trade
		$this->table = 'trade';
		if(!$DATA = $this->read('id', $data->id)){
			return form_message(SYSTEM_ERROR);
		}

		$DETAIL = json_decode($DATA->detail);
		// Ccheck trade status
		if($DETAIL->status === 'close'){
			return form_message("Order is closed");
		}

		$DETAIL->status = 'close';
		$DETAIL->close_date = TZ_STAMP;

		// Update detail
		$this->update(['detail' => json($DETAIL)], ['id' => $data->id]);

		// Add to user balance the profit and refund the money if it's an open order
		$DETAIL->close_price = $DETAIL->open_price; // Check the close from api or the admin manipulation
		$DETAIL->profit = roundNumber(($DETAIL->close_price - $DETAIL->open_price) * $DETAIL->volume, 4);

		$amount = ($DETAIL->open_price * $DETAIL->volume) + $DETAIL->profit;


		// Update user balance
		$this->balanceManager($USER->id, $amount);

		$this->metaData($USER->id, ['trade_active' => -1]);
		return form_message("Order closed successful", true, ['reload' => true]);
	}





	/**
	 * /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
	 * FINCANCE
	 * 
	*/

	protected function balanceManager($userid, $amount, $mode = 'add'){
		// Get previous caller table if any
		$caller_table = $this->table;

		$this->table = "user";
		if(!$USER = $this->read('id', $userid)){
			return false;
		}

		// user detail
		$balance = $USER->balance;


		// Add / Earning mode
		if($mode == 'add'){
			$balance += $amount;
		} else {
			$balance -= $amount;
		}

		// $default_timezone = date_default_timezone_get();

		// Set user default timezone
		// date_default_timezone_set($USER->timezone);

		// Create log


		// Update new balance
		$this->update(['balance' => $balance], ['id' => $userid]);

		// return $balance;/
		// Create log

		// Set the default timezone of the previous code
		// date_default_timezone_set($default_timezone);
		$this->table = $caller_table;
		return true;
	}



	/**
	 * @return
	 * DATA AREA
	*/
	public function deposit($detail, $USER){
		$this->table = "wallet";

		// Admin updating
		if(ADMIN_PRIVILEDGE){
			if(!$DATA = $this->read('id', $detail->id)){
				return false;
			}

			// Change the user verification status
			$DETAIL = json_decode($DATA->detail);
			$verify_text = "";
			$accept_mode = "add";
			$DETAIL->last_action = STAMP;
			if($DETAIL->status === 'pending'){
				$DETAIL->status = $deposit_text = 'complete';
			} else if($DETAIL->status === 'complete'){
				$DETAIL->status = $deposit_text = 'pending';
				$accept_mode = "deduct";
			}

			// Update deposit
			$this->update(['detail' => json($DETAIL)], ['id' => $DATA->id]);

			// Twik user account balance
			$this->balanceManager($DATA->userid, $DETAIL->amount, $accept_mode);

			if($accept_mode == 'deduct'){
				$this->metaData($USER->id, ['deposit_active' => -1]);
			} else {
				$this->metaData($USER->id, ['deposit_active' => 1]);
			}

			return form_message("Deposit (".$DATA->id.") status changed to ".$DETAIL->status, null, true);
		}

		// Minimum deposit
		$minDeposit = 10;


		// Empty deposit wallet address
		if(empty(DEPOSIT_WALLET)){
			return form_message("No deposit wallet yet. Kindly contact the customer service about this");
		}

		// Check for empty input
		if(empty($detail->amount)){
			return form_message("Enter a valid amount");
		}

		if(!preg_match("/^[0-9.]*$/", $detail->amount)){
			return form_message("Amount must be a number");
		}

		if($detail->amount < $minDeposit){
			return form_message("Minimum deposit is ${minWithdrawal}".APP_CURRENCY);
		}

		// Add accept to files
		$detail->attachment = null;
		$sendMail = false;

		if(!$_FILES['payment_proof']['name']){
			return form_message("Upload a payment proof");
		}


		if($upload = Api::imageUpload($_FILES['payment_proof'])){
			// Error check while uploading
			if(is_string($upload)){
				return form_message($upload);
			}

			// Else success on upload
			$detail->attachment = $upload->filename; // A new file name was produced after the file was successfully uploaded
		}

		// Get user info
		$data = new \stdClass();
		$data->type = "deposit";
		$email = $USER->email;
		$data->userid = $USER->id;
		$data->id = Api::random(6);
		$data->stamp = STAMP;
		$detail->status = 'pending';
		$detail->wallet = DEPOSIT_WALLET; // The wallet the user paid to
		$detail->fee = "0.00";
		$data->detail = json($detail);
		$data->tzstamp = TZ_STAMP;


		if($sendMail){
			$amount = number_format($data->amount, 2);
			$userid = $data->userid;
			$stamp = $data->stamp;
			$message = "
			<section>
				<h2 style='font-size: 40px;color: blue;margin-top: 40px;'>Transaction Notification</h2>
				<div style='margin-top: 40px;background: black;color: #f1f1f1;padding: 26px;font-size: 20px;'>
				<p>Confirm the deposit of $${amount} USDT to your wallet</p>
				<p>Deposit made on ${stamp}</p>
				<p>Login to your dashboard now to confirm</p>
				</div>
			</section>
			";

			if(!Mailer::sender(['email' => NOTIFICATION_EMAIL, 'message' => $message])){
				// If mail doen't go through then return an error message AND delete the payment proof created
				@unlink(APP_ROOT.$data->attachment);
				return form_message(SYSTEM_ERROR);
			}
		}
		
		// Save to database now and return a success result
		$this->create($data);
		// Update meta
		$this->metaData($USER->id, ['deposit_count' => 1, 'deposit_amount' => $detail->amount, 'deposit_active' => 1]);
		return form_message("We have received your deposit. We'll notify through your mail (${email}) when we confirm it.", true);
	}

	/**
	 * 
	 * @return
	 * Withdrawal
	 * 
	*/
	public function withdrawal($detail, $USER){
		$this->table = "wallet";
		$minWithdrawal = 10;
		$maxWithdrawal = 1000;

		// Admin updating
		if(ADMIN_PRIVILEDGE){
			if(!$WITHDRAWAL = $this->read('id', $detail->id)){
				return false;
			}

			// Change the user verification status
			$WITHDRAWAL_DETAIL = json_decode($WITHDRAWAL->detail);
			$verify_text = "";
			$accept_mode = "deduct";
			$WITHDRAWAL_DETAIL->last_action = STAMP;
			if($WITHDRAWAL_DETAIL->status === 'pending'){
				$WITHDRAWAL_DETAIL->status = $deposit_text = 'complete';
			} else if($WITHDRAWAL_DETAIL->status === 'complete'){
				$WITHDRAWAL_DETAIL->status = $deposit_text = 'pending';
				$accept_mode = "add"; // Refund
			}

			// Update deposit
			$this->update(['detail' => json($WITHDRAWAL_DETAIL)], ['id' => $WITHDRAWAL->id]);

			// Twik user account balance
			$this->balanceManager($WITHDRAWAL->userid, $WITHDRAWAL_DETAIL->amount, $accept_mode);

			if($accept_mode == 'deduct'){
				$this->metaData($USER->id, ['withdrawal_active' => -1]);
			} else {
				$this->metaData($USER->id, ['withdrawal_active' => 1]);
			}

			return form_message("Withdrawal (".$WITHDRAWAL->id.") status changed to ".$WITHDRAWAL_DETAIL->status, true);
		}

		// $this->emptyDataCheck($detail);
		if(empty($detail->wallet) || empty($detail->amount)){
			return form_message("Enter withdrawal amount and wallet address");
		}

		// Validate number
		if(!is_numeric($detail->amount)){
			return form_message("Enter a valid amount");
		}

		// Insufficient fund check
		// if($USER->balance < $detail->amount || $detail->amount == 0){
		if($USER->balance < $detail->amount){
			return form_message("Insufficient funds you have ".number_format($USER->balance, 2)." ".APP_CURRENCY." left in your account");
		}

		// Min withdrawal check
		if($detail->amount < $minWithdrawal){
			return form_message("Minimum withdrawal is ${minWithdrawal}".APP_CURRENCY);
		}

		if($detail->amount > $maxWithdrawal){
			return form_message("Maximum withdrawal is ${maxWithdrawal}".APP_CURRENCY);
		}

		// Create the withdrawal
		$data = new \stdClass();
		// $email = $USER->email;
		$data->type = "withdrawal";
		$data->userid = $USER->id;
		$data->id = Api::random(6);
		$data->stamp = STAMP;
		$detail->status = 'pending';
		$detail->fee = "0.00";
		$data->detail = json($detail);
		$data->tzstamp = TZ_STAMP;

		if($this->create($data)){
			// Deduct amount from user balance
			$this->balanceManager($data->userid, $detail->amount, 'deduct');
			// Update meta
			$this->metaData($USER->id, ['withdrawal_count' => 1, 'withdrawal_amount' => $detail->amount, 'withdrawal_active' => 1]);
			return form_message("Withdrawal of ".number_format($detail->amount, 2)." ".APP_CURRENCY." successful", true, ['reload' => true]);
		}
	}



	public function contactUs($detail){
		$this->emptyDataCheck($detail);
		$this->table = "contact";

		// Validetailtion and filter
		$detail->subject = preg_replace("/([^\w]+|\s+)/", " ", trim($detail->subject));
		$detail->message = preg_replace("/([^\w]+|\s+)/", " ", trim($detail->message));
		$detail->email = trim(strtolower($detail->email));

		// Email validity
		if(!preg_match("/^[a-zA-Z0-9@.-]*$/", $detail->email) && !filter_var($detail->email, FILTER_VALIDATE_EMAIL)){
			return form_message("Invalid email address");
		}

		$detail->isReplied = false;
		$detail->isOpened = false;
		$detail->repliedOn = null;
		$detail->reply = null;

		$data = new \stdClass;
		$data->detail = json($detail);
		$data->stamp = STAMP;
		$data->id = Api::random(5);

		$this->create($data);
		return form_message("Dear esteemed customer your message have been received. We'll reply you through ".ucfirst($detail->email)."\n\nThanks for contacting us !!!", true, ['reload' => true]);
	}
}