<?php
/*
.---------------------------------------------------------------------------.
|  Software: 	SMS - HTTP API BULK SMS Messaging class     	            |
|  Version: 	3.07														|															
|  Email: 		sales@solutions4mobiles.com									|									
|  Info: 			http://www.solutions4mobiles.com						|									
|  Phone:			+44 203 318 3618										|
| ------------------------------------------------------------------------- |
| Copyright (c) 1999-2013, Mobiweb Ltd. All Rights Reserved.                |
| ------------------------------------------------------------------------- |
| LICENSE:																	|																
| Distributed under the General Public License v3 (GPLv3)					|					
| http://www.gnu.org/licenses/gpl-3.0.html									|								
| This program is distributed AS IS and in the hope that it will be useful	|
| WITHOUT ANY WARRANTY; without even the implied warranty of				|				
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      |
| ------------------------------------------------------------------------- |
| SERVICES:																	|													
| We offer a number of paid services at http//www.solutions4mobiles.com:    |
| - Bulk SMS / MMS / Premium SMS Services	/ HLR Lookup Service			|			
| ------------------------------------------------------------------------- |
| HELP:																		|														
| - This class requires a valid HTTP API Account. Please email to			|			
| sales@solutions4mobiles.com to get one									|
'---------------------------------------------------------------------------'

/**
 * SMS - HTTP API BULK SMS Messaging class
 * @copyright 1999 - 2013 Mobiweb Ltd.
 */

class sms{
	
	//---------------------------------
	// PROPERTIES
	//---------------------------------
	
	/**
   	* The HTTP API username that is supplied to your account 
   	* (most of the times it is your email)
   	* String
   	*/
	public $username=	'username';
	
	/**
   	* The HTTP API password of your account
   	* String
   	*/
	public $password=	'password';
	
	/**
   	* The HTTP API provider of your account
   	* String
   	*/
	public $provider=	'solutions4mobiles.com';
	
	/**
   	* The HTTP request URL used for account balance information.
   	* (Use it to get the remaining credits to your account)
   	* String
   	*/
	public $balance_url=	'http://my.talkwithtext.com/bulksms/getBALANCE.go';	

	/**
   	* The HTTP request URL used for messaging
   	* (Use it to send SMS)
   	* String
   	*/
	public $send_url=	'http://my.talkwithtext.com/bulksms/bulksend.go'; 			
	
	/**
   	* The SMS charset
   	* Int [OPTIONAL] -> Default is 0.
   	*/
	public $charset=	0;
	
	/**
   	* The SMS message text
   	* String
   	*/
	public $msgtext=	"Hello World";
	
	/**
   	* The originator of your message (11 alphanumeric or 14 numeric values).
   	* Valid Chars are: A-z,0-9 (no Spaces or other chars like $-+!).
   	* String
   	*/
	public $originator=	'TestAccount';
	
	/**
   	* The full International mobile number of the recipient excluding
   	* the leeding + (e.g. 33xxxxxxxxxx for France, 4478xxxxxx for UK etc)
   	* To send to multiple recipients, separate each number with a comma 
   	* (e.g. 44xxxxx,33xxxxxx,20xxxxxx).
   	* Please note that no blanks can be used.
   	* String
   	*/
	public $phone=	'recipient';
	
	/**
   	* Request Delivery Report.
   	* If set to 1 a unique id for requesting delivery report of this sms 
   	* is returned with the OK return value upon sending.
   	* Int [OPTIONAL] -> Default is 0 (No DLR).
   	*/
	public $showDLR=	0;
	
	/**
   	* The SMS message type.
   	* If set to F the sms is sent as Flash.
   	* String [OPTIONAL] -> Default is empty (GSM text message).
   	*/
	public $msgtype=	'';
	
	/**
   	* Adjust Delivery date to UTC time. (acceptable values: …-11…+11).
   	* Int [OPTIONAL] -> Default is 0 (UTC 0).
   	*/
	public $utc=	0;
	
	//---------------------------------
	// METHODS
	//---------------------------------
	
	/**
	* This method sends out one SMS message or multiple (for multiple recipients).
	* Parameters:
	* 	none. (NOTE: sms object must be correctly initialized in order for this method to work.)
	* Returns:
	* 	OK				Successfully Sent
	* 	ERROR100	Temporary Internal Server Error. Try again later
	*		ERROR101	Authentication Error (Not valid login Information)
	*		ERROR102	No credits available
	*		ERROR103	MSIDSN (phone parameter) is invalid or prefix is not supported
	*		ERROR104	Tariff Error
	*		ERROR105	You are not allowed to send to that destination/country
	*		ERROR106	Not Valid Route number or you are not allowed to use this route
	*		ERROR107	No proper Authentication (IP restriction is activated)
	*		ERROR108	You have no permission to send messages through HTTP API
	*		ERROR109	Not Valid Originator
	*		ERROR999	Invalid HTTP Request
	* 	if showDLR is set to 1 a unique id for the delivery report of this sms is returned with the OK return value.
	*/
	
	function send(){
		$fieldcnt=8;
		$fieldstring = "username=$this->username&password=$this->password&charset=$this->charset&msgtext=$this->msgtext&originator=$this->originator&phone=$this->phone&provider=$this->provider&showDLR=$this->showDLR&msgtype=$this->msgtype";
		
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$this->send_url);  
		curl_setopt($ch,CURLOPT_POST,$fieldcnt);  
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fieldstring);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		$res = curl_exec($ch);   
		curl_close($ch);  
		return $res;  
	}
	
	/**
	* This method gets the balance of a HTTP API account.
	* Parameters:
	* 	none. (NOTE: sms object must be correctly initialized in order for this method to work.)
	* Returns:
	* 	Account balance in EUROS
	*/
	
	function getBalance(){
		$fieldcnt=2;
		$fieldstring = "username=$this->username&password=$this->password&provider=$this->provider";
		
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$this->balance_url);  
		curl_setopt($ch,CURLOPT_POST,$fieldcnt);  
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fieldstring);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		$res = curl_exec($ch);   
		curl_close($ch);  
		return $res;  
	}
}?>