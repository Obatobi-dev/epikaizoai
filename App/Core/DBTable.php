<?php

namespace Model;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");
// echo "sdfsd";
class Table
{
	use Model;
	
	public function __construct(){
		$DB_TABLES = json_decode(DB_TABLES);
		foreach($DB_TABLES as $table){
			if($this->query($this->$table())){
				result(SYSTEM_ERROR, true);
			}
		}
	}

	// Users
	private function user(){
		// Wanting to create a meta column or bio column (This will contain: Email, fullname, username, phone, ip, timezone and others ...)
		// N4BbFhY
		// super@user.com
		// Create a bio_data column, we'll have (email, fullname, phone, bio) in it
		return $query = "
		CREATE TABLE IF NOT EXISTS user(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE, -- id is userid in all the table
			email VARCHAR(60) NOT NULL,
			fullname VARCHAR(60) NOT NULL,
			phone VARCHAR(60) NOT NULL,
			password TEXT NOT NULL,
			balance DECIMAL(11, 2) NOT NULL,
			acct_type VARCHAR(100) NULL,
			verification TEXT NULL, -- VERIFIED (date, key), BAN, KYC in an array
			auth TEXT NULL, -- current login id and 2fa secret key
			bio TEXT NULL,
			wallet VARCHAR(100) NULL,
			image VARCHAR(100) NULL,
			timezone VARCHAR(60) NOT NULL,
			ip VARCHAR(20) NOT NULL,
			upline VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NULL, -- Upline userid is userid in all the table
			meta_data TEXT NULL, -- Extra information like, upline full name, rebate generated for upline, referral count, deposit count, withdrawal count
			tzstamp DATETIME NOT NULL,
			stamp DATETIME NOT NULL
		);";
	}

	// Login history table
	private function userlogin(){
		return $query = "
		CREATE TABLE IF NOT EXISTS userlogin(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			info TEXT NOT NULL,
			tzstamp DATETIME NOT NULL,
			stamp DATETIME NOT NULL
		);";
	}

	// KYC
	private function kyc(){
		return $query = "
		CREATE TABLE IF NOT EXISTS kyc(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL, -- ALL ID be changed to INDEX the, userid changed to id (And for all tables)
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			detail TEXT NOT NULL, -- start price, end price, trade type (High or low), status, amount, starttime, endtime
			tzstamp DATETIME NOT NULL, -- User timezone
			stamp DATETIME NOT NULL -- System timezone
		);";
	}

	// Trades
	private function trade(){
		return $query = "
		CREATE TABLE IF NOT EXISTS trade(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL, -- ALL ID be changed to INDEX the, userid changed to id (And for all tables)
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			upline VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL, -- Upline id
			detail TEXT NOT NULL, -- start price, end price, trade type (High or low), status, amount, starttime, endtime
			tzstamp DATETIME NOT NULL, -- User timezone
			stamp DATETIME NOT NULL -- System timezone
		);";
	}

	// Bot
	private function botsub(){
		return $query = "
		CREATE TABLE IF NOT EXISTS botsub(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			detail TEXT NOT NULL, -- start price, end price, trade type (High or low), status, amount, starttime, endtime
			status VARCHAR(40) NOT NULL, -- Status
			tzstamp DATETIME NOT NULL, -- User timezone
			stamp DATETIME NOT NULL -- System timezone
		);";
	}

	// Bot
	private function bot(){
		return $query = "
		CREATE TABLE IF NOT EXISTS bot(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			version VARCHAR(10) NOT NULL,
			active BOOL NOT NULL DEFAULT 1, -- active by default
			detail TEXT NOT NULL, -- start price, end price, trade type (High or low), status, amount, starttime, endtime
			stamp DATETIME NOT NULL -- System timezone
		);";
	}

	// Wallet for deposit and withdrawal
	private function wallet(){
		return $query = "
		CREATE TABLE IF NOT EXISTS wallet(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL, -- 
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			type VARCHAR(40) NOT NULL,
			detail TEXT NOT NULL, -- 
			tzstamp DATETIME NOT NULL, -- User timezone
			stamp DATETIME NOT NULL -- System timezone
		);";
	}

	// Admin
	private function admin(){
		return $query = "
		CREATE TABLE IF NOT EXISTS admin(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			username VARCHAR(100) NOT NULL,
			password VARCHAR(100) NOT NULL,
			timezone VARCHAR(100) NOT NULL,
			sitesetting TEXT NULL,
			contact TEXT NULL -- email, phone, whatsapp, telegram, facebook
		);";
	}

	private function contact(){
		return $query = "
		CREATE TABLE IF NOT EXISTS contact(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			detail TEXT NOT NULL, -- Subject, message, attachment, isReplied, repliedon
			stamp DATETIME NOT NULL
		);";
	}

	// Admin
	private function support(){
		return $query = "
		CREATE TABLE IF NOT EXISTS support(
			sn BIGINT AUTO_INCREMENT PRIMARY KEY NOT NULL,
			id VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL UNIQUE,
			userid VARCHAR(40) CHARACTER SET latin7 COLLATE latin7_general_cs NOT NULL,
			detail TEXT NOT NULL, -- Subject, message, attachment, isReplied, repliedon
			tzstamp DATETIME NOT NULL,
			stamp DATETIME NOT NULL
		);";
	}
}

// use Model\Tables;
$table = new Table();