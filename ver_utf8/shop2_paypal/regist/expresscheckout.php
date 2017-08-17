<?php
require_once ("paypalfunctions.php");
// ==================================
// PayPal Express Checkout Module
// ==================================

//'------------------------------------
//' The paymentAmount is the total value of 
//' the shopping cart, that was set 
//' earlier in a session variable 
//' by the shopping cart page
//'------------------------------------
// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

//購入データの取得
$sql_ordercust = "
SELECT
	".PURCHASE_LST.".ORDER_ID,
	".PURCHASE_LST.".TOTAL_PRICE,
	".PURCHASE_LST.".PAYMENT_TYPE
FROM
	".PURCHASE_LST."
WHERE
	(".PURCHASE_LST.".ORDER_ID = '".$order_id."')
AND
	(".PURCHASE_LST.".DEL_FLG = '0')
";

$fetchOrderCust = dbOpe::fetch($sql_ordercust,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//購入データがない or PayPal決済ではない場合はエラー
if(!count($fetchOrderCust) || $fetchOrderCust[0]['PAYMENT_TYPE'] != 6){
    die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
}else{
    $_SESSION["paypal"]["Order_Id"] = $fetchOrderCust[0]['ORDER_ID'];
    $_SESSION["paypal"]["Payment_Amount"] = $fetchOrderCust[0]['TOTAL_PRICE'];
    $paymentAmount = $_SESSION["paypal"]["Payment_Amount"];
}
//'------------------------------------
//' The currencyCodeType and paymentType 
//' are set to the selections made on the Integration Assistant 
//'------------------------------------
$currencyCodeType = "JPY";
$paymentType = "Sale";

//'------------------------------------
//' The returnURL is the location where buyers return to when a
//' payment has been succesfully authorized.
//'
//' This is set to the value entered on the Integration Assistant 
//'------------------------------------
$returnURL = str_replace("expresscheckout","order_complete",(empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
//'------------------------------------
//' The cancelURL is the location buyers are sent to when they hit the
//' cancel button during authorization of payment during the PayPal flow
//'
//' This is set to the value entered on the Integration Assistant 
//'------------------------------------
$cancelURL = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];

//'------------------------------------
//' Calls the SetExpressCheckout API call
//'
//' The CallExpressCheckout function is defined in the file PayPalFunctions.php,
//' it is included at the top of this file.
//'-------------------------------------------------
$resArray = CallExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $order_id);
$ack = strtoupper($resArray["ACK"]);
if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
{
	RedirectToPayPal ( $resArray["TOKEN"] );
} 
else  
{
	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	
	echo "SetExpressCheckout API call failed. ";
	echo "Detailed Error Message: " . $ErrorLongMsg;
	echo "Short Error Message: " . $ErrorShortMsg;
	echo "Error Code: " . $ErrorCode;
	echo "Error Severity Code: " . $ErrorSeverityCode;
}
?>