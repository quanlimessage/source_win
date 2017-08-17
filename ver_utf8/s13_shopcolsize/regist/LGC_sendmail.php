<?php
/*******************************************************************************

Logic : 最終処理２：メール送信（支払い方法が郵便振込と代引きの場合に実行）

		注：クレジット支払の場合は決済サイトで決済処理完了後に、結果受信用のファイル
			regist.php(旧：credit_payment_result.php)に送信されるので、結果を含んだ
		　　パラメーター（$_GET['result']／$_GET['sendid']）を元に独立プログラム
		　　として受信ファイル内でDB更新・メール送信・表示までの処理を一貫して行う。

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

// 都道府県のIDを取得（INI_pref_list.phpより都道府県名を取り出すのに使用）
$sid = $_SESSION['cust']['STATE'];			// お客様用
$dsid = $_SESSION['cust']['DELI_STATE'];	// 配送先用

#=================================================================================
# 購入者（お客様）へサンキューメールを送信
#=================================================================================

# テンプレートメールファイルの決定
####################################
if ( $_SESSION['cust']['PAYMENT_METHOD'] == 2 ){

	$mailbody_file ="INI_mailbody_payBANK.dat";

}elseif ( $_SESSION['cust']['PAYMENT_METHOD'] == 3 ){

	$mailbody_file ="INI_mailbody_payDAIBIKI.dat";

}elseif ( $_SESSION['cust']['PAYMENT_METHOD'] == 4 ){

	$mailbody_file ="INI_mailbody_payCONV.dat";

}elseif ( $_SESSION['cust']['PAYMENT_METHOD'] == 5 ){

	$mailbody_file ="INI_mailbody_payPOST.dat";

}else{

	die("予想外のエラーが発生しました。<br>※メール送信ができません。");
}

# メール内容組み立て(設定情報と雛形の置換)
###########################################

// 本文雛形を読み込み
$mailbody = file_get_contents(dirname(__FILE__)."/mail_tmpl/{$mailbody_file}");

//口座情報
if(strstr($mailbody,"<BANK_INFO>"))$mailbody = str_replace("<BANK_INFO>",BANK_INFO,$mailbody);

if(strstr($mailbody,"<POST_INFO>"))$mailbody = str_replace("<POST_INFO>",POST_INFO,$mailbody);

// 基本情報
if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$_SESSION['cust']['LAST_NAME']." ".$_SESSION['cust']['FIRST_NAME'],$mailbody);
if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$order_id,$mailbody);
if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

// 購入商品（$itemsは管理者用にも使用）
for($i = 0, $items = ""; $i < count($purchaseData); $i++){
	$items .= "商品番号：".$purchaseData[$i]['part_no']."\n";
	$items .= "商品名：".$purchaseData[$i]['product_name']."\n";
	$color_name = ($purchaseData[$i]['color_name'])?$purchaseData[$i]['color_name']:"-";
	$items .= "カラー：".$color_name."\n";
	$size_name = ($purchaseData[$i]['size_name'])?$purchaseData[$i]['size_name']:"-";
	$items .= "サイズ：".$size_name."\n";
	$items .= "\\".number_format($purchaseData[$i]['selling_price'])."\t";
	$items .= "数量 ".$purchaseData[$i]['quantity']."\n\n";
}
if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);

// 小計
if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($sum_price),$mailbody);

// 配送料
if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($_SESSION['cust']['shipping_amount']),$mailbody);

// 代引きの場合は設定
if($_SESSION['cust']['daibiki_amount']){
	if(strstr($mailbody,"<DAIBIKI_AMOUNT>"))$mailbody = str_replace("<DAIBIKI_AMOUNT>",number_format($_SESSION['cust']['daibiki_amount']),$mailbody);
}
else{
	if(strstr($mailbody,"<DAIBIKI_AMOUNT>"))$mailbody = str_replace("<DAIBIKI_AMOUNT>","",$mailbody);
}

// コンビニ決済手数料
if(strstr($mailbody,"<CONV_AMOUNT>"))$mailbody = str_replace("<CONV_AMOUNT>",$_SESSION['cust']['conv_amount'],$mailbody);

// 総合計金額
if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($total_price),$mailbody);

// 個人情報
if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$_SESSION['cust']['LAST_KANA']." ".$_SESSION['cust']['FIRST_KANA'],$mailbody);
if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$_SESSION['cust']['EMAIL'],$mailbody);
if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$_SESSION['cust']['ZIP_CD1']."-".$_SESSION['cust']['ZIP_CD2'],$mailbody);
if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$sid]['pref']."{$_SESSION['cust']['ADDRESS1']} {$_SESSION['cust']['ADDRESS2']}",$mailbody);
if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$_SESSION['cust']['TEL1']."-".$_SESSION['cust']['TEL2']."-".$_SESSION['cust']['TEL3'],$mailbody);

// ※パスワードの表示は初回の場合と初回がクレジット決済未完了だった場合のみ
if($alpwd){
	if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","PASSWORD：{$alpwd} → 次回のお買い物の際にご利用ください",$mailbody);
}
else{
	if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","",$mailbody);
}

// 配送先
if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$_SESSION['cust']['DELI_LAST_NAME']." ".$_SESSION['cust']['DELI_FIRST_NAME'],$mailbody);
if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$_SESSION['cust']['DELI_ZIP_CD1']."-".$_SESSION['cust']['DELI_ZIP_CD2'],$mailbody);
if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$dsid]['pref']."{$_SESSION['cust']['DELI_ADDRESS1']} {$_SESSION['cust']['DELI_ADDRESS2']}",$mailbody);
if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$_SESSION['cust']['DELI_TEL1']."-".$_SESSION['cust']['DELI_TEL2']."-".$_SESSION['cust']['DELI_TEL3'],$mailbody);

// 備考欄
if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$_SESSION['cust']['REMARKS'],$mailbody);

//パスワード変更のお知らせ（forgetpass）のURL
//現在の位置を求めてforgetpassへのURLを出す

$url = getcwd();
$sname = $_SERVER["SERVER_NAME"];
$dr = $_SERVER["DOCUMENT_ROOT"];

$url = str_replace($dr,"",$url);
$url = "http://".$sname.$url."/forgetpass/";

if(strstr($mailbody,"<FOR_GET_PASS>"))$mailbody = str_replace("<FOR_GET_PASS>",$url,$mailbody);

// 会社情報
if(strstr($mailbody,"<COMPANY_INFO>"))$mailbody = str_replace("<COMPANY_INFO>",COMPANY_INFO,$mailbody);

	////////////////////////////////////////////////////////////////
	//gmailでReply-Toをカンマ区切りでメールを送信すると弾かれる仕様なのでカンマがあった場合は一番最初のメールアドレスを
	//Reply-Toに設定する（この設定はエンドユーザーに送信するメールに対して行う）
	//mb_send_mailのReturn-Pathの設定
		$rt_email = substr(WEBMST_SHOP_MAIL,0,strpos(WEBMST_SHOP_MAIL,","));//メールアドレスがカンマ区切りになっていた場合用にメールアドレスを一つだけ抽出処理
		$rt_email = ($rt_email)?$rt_email:WEBMST_SHOP_MAIL;//空っぽだった場合はカンマ区切りは無し

// 件名とフッター
$subject = SUBJECT_CLIENT_NAME."より自動返信メール";
$headers = "Reply-To: ".$rt_email."\n";
$headers .= "Return-Path: ".$rt_email."\n";
$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".$rt_email.">\n";
	$rpath = "-f ".$rt_email;//mb_send_mailのReturn-Path を設定

//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
//$mailbody = str_replace("\n","\r\n",$mailbody);
$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）
	$mailbody = mbody_auto_lf($mailbody,400);//改行無しで長文を入力された場合の対応処理(区切る数字が短い場合URLなどが途中で区切られてしまう)

// メール送信（失敗時：強制終了）
$usrmail_result = mb_send_mail($_SESSION['cust']['EMAIL'],$subject,$mailbody,$headers,$rpath);
if(!$usrmail_result)die("お客様へのメール送信に失敗しました。。。<br>\n誠に申し訳ございませんがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");

#=================================================================================
# 管理者（Webマスター宛）へ報告メール送信
#=================================================================================
// 本文雛形読み込み
$mailbody = file_get_contents(dirname(__FILE__)."/mail_tmpl/INI_mailbody_forADMIN.dat");

// 基本情報
if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$_SESSION['cust']['LAST_NAME']." ".$_SESSION['cust']['FIRST_NAME'],$mailbody);
if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$order_id,$mailbody);
if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

// 購入商品
if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);
if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($sum_price),$mailbody);
if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($_SESSION['cust']['shipping_amount']),$mailbody);
if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($total_price),$mailbody);

// 代引きの場合は表示
if($_SESSION['cust']['daibiki_amount']){
	if(strstr($mailbody,"<DAIBIKI_DISP>"))$mailbody = str_replace("<DAIBIKI_DISP>","　※代引手数料（\\".number_format($_SESSION['cust']['daibiki_amount'])."）を含みます",$mailbody);
}
else{
	if(strstr($mailbody,"<DAIBIKI_DISP>"))$mailbody = str_replace("<DAIBIKI_DISP>","",$mailbody);
}

// コンビニ決済の場合は表示CONV_DISP
if($_SESSION['cust']['conv_amount']){
	if(strstr($mailbody,"<CONV_DISP>"))$mailbody = str_replace("<CONV_DISP>","　※コンビニ決済手数料（\\".number_format($_SESSION['cust']['conv_amount'])."）を含みます",$mailbody);
}else{
	if(strstr($mailbody,"<CONV_DISP>"))$mailbody = str_replace("<CONV_DISP>","",$mailbody);
}
// コンビニ決済の場合は管理者宛てにカード番号通知
if($_SESSION['cust']['CARD_NO']){
	if(strstr($mailbody,"<CONV_NO>"))$mailbody = str_replace("<CONV_NO>","「smart pit」番号：".$_SESSION['cust']['CARD_NO'],$mailbody);
}else{
	if(strstr($mailbody,"<CONV_NO>"))$mailbody = str_replace("<CONV_NO>","",$mailbody);
}

// 個人情報
if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$_SESSION['cust']['LAST_KANA']." ".$_SESSION['cust']['FIRST_KANA'],$mailbody);
if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$_SESSION['cust']['EMAIL'],$mailbody);
if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$_SESSION['cust']['ZIP_CD1']."-".$_SESSION['cust']['ZIP_CD2'],$mailbody);
if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$sid]['pref']."{$_SESSION['cust']['ADDRESS1']} {$_SESSION['cust']['ADDRESS2']}",$mailbody);
if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$_SESSION['cust']['TEL1']."-".$_SESSION['cust']['TEL2']."-".$_SESSION['cust']['TEL3'],$mailbody);

// 配送先
if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$_SESSION['cust']['DELI_LAST_NAME']." ".$_SESSION['cust']['DELI_FIRST_NAME'],$mailbody);
if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$_SESSION['cust']['DELI_ZIP_CD1']."-".$_SESSION['cust']['DELI_ZIP_CD2'],$mailbody);
if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$dsid]['pref']."{$_SESSION['cust']['DELI_ADDRESS1']} {$_SESSION['cust']['DELI_ADDRESS2']}",$mailbody);
if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$_SESSION['cust']['DELI_TEL1']."-".$_SESSION['cust']['DELI_TEL2']."-".$_SESSION['cust']['DELI_TEL3'],$mailbody);

// 備考欄
if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$_SESSION['cust']['REMARKS'],$mailbody);

// 支払方法（クレジットカード or 銀行振込 or 代引き or コンビニ決済）
switch($_SESSION["cust"]["PAYMENT_METHOD"]):
case 1:
	if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","クレジットカード",$mailbody);
	$payment_method = "クレジットカード";
	break;
case 2:
	if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","銀行振込",$mailbody);
	$payment_method = "銀行振込";
	break;
case 3:
	if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","代引き",$mailbody);
	$payment_method = "代引き";
	break;
case 4:
	if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","コンビニ決済",$mailbody);
	$payment_method = "コンビニ決済";
	break;
case 5:
	if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","郵便振替",$mailbody);
	$payment_method = "郵便振替";
	break;
endswitch;

// 件名とフッター
$subject = "webより";
$subject .= $payment_method;
$subject .= "でお申し込みがありました";

$headers = "Reply-To: ".$_SESSION['cust']['EMAIL']."\n";
$headers .= "Return-Path: ".$_SESSION['cust']['EMAIL']."\n";
$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".WEBMST_SHOP_MAIL.">\n";
	$rpath = "-f ".$_SESSION['cust']['EMAIL'];//mb_send_mailのReturn-Path を設定

	//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
	//$mailbody = str_replace("\n","\r\n",$mailbody);
	$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）
	$mailbody = mbody_auto_lf($mailbody,400);//改行無しで長文を入力された場合の対応処理(区切る数字が短い場合URLなどが途中で区切られてしまう)

// メール送信（失敗時：強制終了）
$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers,$rpath);
if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

//処理が終わる前に購入経験フラグをありに変える
	$sqlbuy_flg = "
		UPDATE
			".CUSTOMER_LST."
		SET
			EXISTING_CUSTOMER_FLG  = '1'
		WHERE
			( CUSTOMER_ID = '".$customer_id."' )
		AND
			(DEL_FLG = '0')
	";
	$PDO -> regist($sqlbuy_flg);

?>
