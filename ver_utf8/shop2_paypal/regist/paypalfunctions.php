<?php
require_once ("../common/INI_config.php");
require_once ("../common/INI_ShopConfig.php");
require_once('../common/INI_pref_list.php');	// 都道府県＆送料情報（配列）
require_once("dbOpe.php");										// ＤＢ操作クラスライブラリ
require_once("util_lib.php");									// 汎用処理クラスライブラリ

	/********************************************
	PayPal API Module
	 
	Defines all the global variables and the wrapper functions 
	********************************************/
	$PROXY_HOST = '127.0.0.1';
	$PROXY_PORT = '808';

	$SandboxFlag = SANDBOX_FLG;

	//'------------------------------------
	//' PayPal API Credentials
	//' Replace <API_USERNAME> with your API Username
	//' Replace <API_PASSWORD> with your API Password
	//' Replace <API_SIGNATURE> with your Signature
	//'------------------------------------
	$API_UserName=PAYPAL_USER;
	$API_Password=PAYPAL_PWD;
	$API_Signature=PAYPAL_SIGNATURE;

	// BN Code 	is only applicable for partners
	$sBNCode = BUTTONSOURCE;
	
	
	/*	
	' Define the PayPal Redirect URLs.  
	' 	This is the URL that the buyer is first sent to do authorize payment with their paypal account
	' 	change the URL depending if you are testing on the sandbox or the live PayPal site
	'
	' For the sandbox, the URL is       https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
	' For the live site, the URL is        https://www.paypal.com/webscr&cmd=_express-checkout&token=
	*/
	
	if ($SandboxFlag == true) 
	{
		$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
		$PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=";
	}
	else
	{
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		$PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=";
	}

	$USE_PROXY = false;
	$version=PAYPAL_VERSION;

	if (session_id() == "") 
		session_start();

	/* An express checkout transaction starts with a token, that
	   identifies to PayPal your transaction
	   In this example, when the script sees a token, the script
	   knows that the buyer has already authorized payment through
	   paypal.  If no token was found, the action is to send the buyer
	   to PayPal to first authorize payment
	   */

	/*   
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:  
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function CallExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $order_id) 
	{
		//------------------------------------------------------------------------------------------------------------------------------------
		
		$nvpstr="&PAYMENTREQUEST_0_AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;	   //支払総額
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;	   //完了画面のURL
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;				   //キャンセル時のURL
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType; //通貨コード
		
		$nvpstr = $nvpstr . GetDetail($order_id);				   //購入詳細
		
		
		//このサイトのＴＯＰ階層を抽出する（裏側のファイルのパスなどに使用）
		$base_path = str_replace("/regist","",dirname(__FILE__));

		//表示不可能な【httpdocs】まで分割して削除（リンク、画像の表示パスの表側に関して使用）
		$temporary_path = explode("/httpdocs",$base_path);//一時的に分割したパスを保存
		$dest_path = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $temporary_path[1]."/";//リンクを入れる。
		
		$nvpstr = $nvpstr . "&LOGOIMG=" . $dest_path . LOGOIMG; //ショップ画像
		$nvpstr = $nvpstr . "&CARTBORDERCOLOR=" . CARTBORDERCOLOR;
		$nvpstr = $nvpstr . "&BUTTONSOURCE=" . BUTTONSOURCE;
		
		$_SESSION["paypal"]["currencyCodeType"] = $currencyCodeType;	  
		$_SESSION["paypal"]["PaymentType"] = $paymentType;

		//'--------------------------------------------------------------------------------------------------------------- 
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	    $resArray=hash_call("SetExpressCheckout", $nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION["paypal"]['TOKEN']=$token;
		}
		   
	    return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		None
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'-------------------------------------------------------------------------------------------
	*/
	function GetShippingDetails( $token )
	{
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------
	   
	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
	    $nvpstr="&TOKEN=" . $token;

		//'---------------------------------------------------------------------------
		//' Make the API call and store the results in an array.  
		//'	If the call was a success, show the authorization details, and provide
		//' 	an action to complete the payment.  
		//'	If failed, show the error
		//'---------------------------------------------------------------------------
	    $resArray=hash_call("GetExpressCheckoutDetails",$nvpstr);
	    $ack = strtoupper($resArray["ACK"]);
		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{	
			$_SESSION["paypal"]['payer_id'] =	$resArray['PAYERID'];
		} 
		return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	function ConfirmPayment( $FinalPaymentAmt, $order_id )
	{
		/* Gather the information to make the final call to
		   finalize the PayPal payment.  The variable nvpstr
		   holds the name value pairs
		   */
		

		//Format the other parameters that were stored in the session from the previous calls	
		$token 				= urlencode($_SESSION["paypal"]['TOKEN']);
		$paymentType 		= urlencode($_SESSION["paypal"]['PaymentType']);
		$currencyCodeType 	= urlencode($_SESSION["paypal"]['currencyCodeType']);
		$payerID 			= urlencode($_SESSION["paypal"]['payer_id']);

		$serverName 		= urlencode($_SERVER['SERVER_NAME']);

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 

		$nvpstr = $nvpstr . GetDetail($order_id);				   //購入詳細
		 /* Make the call to PayPal to finalize payment
		    If an error occured, show the resulting errors
		    */
		$resArray=hash_call("DoExpressCheckoutPayment",$nvpstr);

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	This function makes a DoDirectPayment API call
	'
	' Inputs:  
	'		paymentType:		paymentType has to be one of the following values: Sale or Order or Authorization
	'		paymentAmount:  	total value of the shopping cart
	'		currencyCode:	 	currency code value the PayPal API
	'		firstName:			first name as it appears on credit card
	'		lastName:			last name as it appears on credit card
	'		street:				buyer's street address line as it appears on credit card
	'		city:				buyer's city
	'		state:				buyer's state
	'		countryCode:		buyer's country code
	'		zip:				buyer's zip
	'		creditCardType:		buyer's credit card type (i.e. Visa, MasterCard ... )
	'		creditCardNumber:	buyers credit card number without any spaces, dashes or any other characters
	'		expDate:			credit card expiration date
	'		cvv2:				Card Verification Value 
	'		
	'-------------------------------------------------------------------------------------------
	'		
	' Returns: 
	'		The NVP Collection object of the DoDirectPayment Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/


	function DirectPayment( $paymentType, $paymentAmount, $creditCardType, $creditCardNumber,
							$expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, 
							$countryCode, $currencyCode )
	{
		//Construct the parameter string that describes DoDirectPayment
		$nvpstr = "&AMT=" . $paymentAmount;
		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCode;
		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
		$nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
		$nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
		$nvpstr = $nvpstr . "&CVV2=" . $cvv2;
		$nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;
		$nvpstr = $nvpstr . "&LASTNAME=" . $lastName;
		$nvpstr = $nvpstr . "&STREET=" . $street;
		$nvpstr = $nvpstr . "&CITY=" . $city;
		$nvpstr = $nvpstr . "&STATE=" . $state;
		$nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;
		$nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];

		$resArray=hash_call("DoDirectPayment", $nvpstr);

		return $resArray;
	}


	/**
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	function hash_call($methodName,$nvpStr)
	{
		//declaring of global variables
		global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;
		global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;
		global $gv_ApiErrorURL;
		global $sBNCode;

		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, $PROXY_HOST. ":" . $PROXY_PORT); 

		//NVPRequest for submitting to server
		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($version) . "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($sBNCode);

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray=deformatNVP($response);
		$nvpReqArray=deformatNVP($nvpreq);
		$_SESSION["paypal"]['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch)) 
		{
			// moving to display page to display curl errors
			  $_SESSION["paypal"]['curl_error_no']=curl_errno($ch) ;
			  $_SESSION["paypal"]['curl_error_msg']=curl_error($ch);

			  //Execute the Error handling module to display errors. 
		} 
		else 
		{
			 //closing the curl
		  	curl_close($ch);
		}

		return $nvpResArray;
	}

	/*'----------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	 Inputs:  NVP string.
	 Returns: 
	----------------------------------------------------------------------------------
	*/
	function RedirectToPayPal ( $token )
	{
		global $PAYPAL_URL;
		
		// Redirect to paypal.com here
		$payPalURL = $PAYPAL_URL . $token;
		header("Location: ".$payPalURL);
		exit;
	}

	
	/*'----------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	   ----------------------------------------------------------------------------------
	  */
	function deformatNVP($nvpstr)
	{
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}

	function GetDetail($order_id){
	    
	    //都道府県
	    global $shipping_list;
		$get_detail = "";
		// 注文情報と個人情報
		$sql = "
		SELECT
			".PURCHASE_LST.".ORDER_ID,
			".PURCHASE_LST.".CUSTOMER_ID,
			".PURCHASE_LST.".TOTAL_PRICE,
			".PURCHASE_LST.".SUM_PRICE,
			".PURCHASE_LST.".SHIPPING_AMOUNT,
			".PURCHASE_LST.".DAIBIKI_AMOUNT,
			".PURCHASE_LST.".DELI_LAST_NAME,
			".PURCHASE_LST.".DELI_FIRST_NAME,
			".PURCHASE_LST.".DELI_ZIP_CD1,
			".PURCHASE_LST.".DELI_ZIP_CD2,
			".PURCHASE_LST.".DELI_STATE,
			".PURCHASE_LST.".DELI_ADDRESS1,
			".PURCHASE_LST.".DELI_ADDRESS2,
			".PURCHASE_LST.".DELI_TEL1,
			".PURCHASE_LST.".DELI_TEL2,
			".PURCHASE_LST.".DELI_TEL3,
			".PURCHASE_LST.".PAYMENT_TYPE,
			".PURCHASE_LST.".ORDER_DATE,
			".PURCHASE_LST.".PAYMENT_FLG,
			".PURCHASE_LST.".PAYMENT_DATE,
			".PURCHASE_LST.".SHIPPED_FLG,
			".PURCHASE_LST.".SHIPPED_DAY,
			".PURCHASE_LST.".CONFIG_MEMO,
			".PURCHASE_LST.".REMARKS
		FROM
			".PURCHASE_LST."
		WHERE
			(".PURCHASE_LST.".ORDER_ID = '".$order_id."')
		AND
			(".PURCHASE_LST.".DEL_FLG = '0')
		";

		$fetchOrderCust = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		
		$get_detail = $get_detail . "&ADDROVERRIDE=1";
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTONAME=" . $fetchOrderCust[0]['DELI_LAST_NAME'] . " " . $fetchOrderCust[0]['DELI_FIRST_NAME'];    //配送先名
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $fetchOrderCust[0]['DELI_ADDRESS1'];						     //住所１
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOSTREET=";						     //住所２
		$get_detail .= ($fetchOrderCust[0]['DELI_ADDRESS2'])?$fetchOrderCust[0]['DELI_ADDRESS2']:"未入力";
		$sid = $fetchOrderCust[0]["DELI_STATE"];
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipping_list[$sid]['pref'];							     //都道府県
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . "JP";									     //国コード
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $fetchOrderCust[0]['DELI_ZIP_CD1'] . "-"  . $fetchOrderCust[0]['DELI_ZIP_CD2'];	     //郵便番号
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $fetchOrderCust[0]['DELI_TEL1'] . "-" . $fetchOrderCust[0]['DELI_TEL2'] . "-" . $fetchOrderCust[0]['DELI_TEL3']; //電話番号
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_ITEMAMT=" . $fetchOrderCust[0]['SUM_PRICE'];							     //合計金額
		$get_detail = $get_detail . "&PAYMENTREQUEST_0_SHIPPINGAMT=" . $fetchOrderCust[0]['SHIPPING_AMOUNT'];						     //配送料

		// 注文情報詳細
		$sql = "
		SELECT
			".PURCHASE_ITEM_DATA.".PART_NO,
			".PURCHASE_ITEM_DATA.".PRODUCT_NAME,
			".PURCHASE_ITEM_DATA.".SELLING_PRICE,
			".PURCHASE_ITEM_DATA.".QUANTITY
		FROM
			".PURCHASE_ITEM_DATA."
		WHERE
			(".PURCHASE_ITEM_DATA.".ORDER_ID = '".$order_id."')
		AND
			(".PURCHASE_ITEM_DATA.".DEL_FLG = '0')
		ORDER BY
			".PURCHASE_ITEM_DATA.".PID ASC
		";
		$fetchPerItem = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		
		for ($i = 0; $i < count($fetchPerItem); $i++) {
		    $get_detail = $get_detail . "&L_PAYMENTREQUEST_0_NUMBER{$i}=". $fetchPerItem[$i]['PART_NO'];    //商品番号
		    $get_detail = $get_detail . "&L_PAYMENTREQUEST_0_NAME{$i}=". $fetchPerItem[$i]['PRODUCT_NAME']; //商品名
		    $get_detail = $get_detail . "&L_PAYMENTREQUEST_0_AMT{$i}=". $fetchPerItem[$i]['SELLING_PRICE']; //単価
		    $get_detail = $get_detail . "&L_PAYMENTREQUEST_0_QTY{$i}=". $fetchPerItem[$i]['QUANTITY'];	    //数量
		}
		
	    return $get_detail;
	}
?>