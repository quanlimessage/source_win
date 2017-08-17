<?php
/*******************************************************************************
通常ショップベース
	ショッピングカートプログラム

Logic : 最終処理１：入力データをＤＢへ格納
 		※実行結果の失敗時：$registDB_resultにエラーメッセージを格納

【在庫管理】
	現在庫数-購入個数=残在庫数
		を求めDBに上書き

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

// 前準備：ＳＱＬ文を格納する配列を初期化しておく（念のため。。。）
$sql = "";

#=================================================================
# ＤＢ格納する前にセッションデータにメタ文字エスケープをやっとく
#=================================================================
$_SESSION['cust'] = array_map("addslashes",$_SESSION['cust']);

#===============================================================================
# 個人情報を格納するＳＱＬを設定	※テーブル：CUSTOMER_LST（顧客情報リスト）
#
# 	更新：リピーター。認証時に$_SESSION['cust']['CUSTOMER_ID']を取得している筈
#			なのでそれをキーにしてUPDATE文を発行する
#
#	新規：初来店：$_SESSION['cust']['CUSTOMER_ID']が空の筈なので
#			新しいCUSTOMER_IDとPASSWORDを発行してINSERT文を発行する
#===============================================================================
if($_SESSION['cust']['CUSTOMER_ID']):	// 利用経験者（UPDATE）

	$sql = "
	UPDATE
		".CUSTOMER_LST."
	SET
		LAST_NAME = '".$_SESSION['cust']['LAST_NAME']."',
		FIRST_NAME = '".$_SESSION['cust']['FIRST_NAME']."',
		LAST_KANA = '".$_SESSION['cust']['LAST_KANA']."',
		FIRST_KANA = '".$_SESSION['cust']['FIRST_KANA']."',
		ZIP_CD1 = '".$_SESSION['cust']['ZIP_CD1']."',
		ZIP_CD2 = '".$_SESSION['cust']['ZIP_CD2']."',
		STATE = '".$_SESSION['cust']['STATE']."',
		ADDRESS1 = '".$_SESSION['cust']['ADDRESS1']."',
		ADDRESS2 = '".$_SESSION['cust']['ADDRESS2']."',
		EMAIL = '".$_SESSION['cust']['EMAIL']."',
		TEL1 = '".$_SESSION['cust']['TEL1']."',
		TEL2 = '".$_SESSION['cust']['TEL2']."',
		TEL3 = '".$_SESSION['cust']['TEL3']."',
		EXISTING_CUSTOMER_FLG = '1'
	WHERE
		( CUSTOMER_ID = '".$_SESSION['cust']['CUSTOMER_ID']."' )
	AND
		(DEL_FLG = '0')
	";

else:	// 利用未経験者（INSERT文）

	// 念のため、最後にEMAILが既に登録されているものかどうかチェック
//	$lastEmailChk = dbOpe::fetch("SELECT EMAIL FROM ".CUSTOMER_LST." WHERE(EMAIL = '".$_SESSION['cust']['EMAIL']."')AND(DEL_FLG = '0') AND ((ALPWD IS NOT NULL) OR (ALPWD != ''))",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	//$lastEmailChk = dbOpe::fetch("SELECT EMAIL FROM ".CUSTOMER_LST." WHERE(EMAIL = '".$_SESSION['cust']['EMAIL']."')AND(DEL_FLG = '0') AND (ALPWD != '')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	$lastEmailChk = $PDO -> fetch("SELECT EMAIL FROM ".CUSTOMER_LST." WHERE(EMAIL = '".$_SESSION['cust']['EMAIL']."')AND(DEL_FLG = '0') AND (ALPWD != '') AND (EXISTING_CUSTOMER_FLG = '1')");
	if ( $lastEmailChk ){
		$_SESSION['cust'] = array();
		deleteItems();
		header("Location: ./");
		exit();
	}

	//お客様のＩＤを生成
	$customer_id = $makeID();	// ID（商品情報関連テーブルにも格納）
	$alpwd = $_SESSION['cust']['PASSWORD'];

	// クレカか？
	if($_SESSION['cust']['PAYMENT_METHOD'] == 1){

		// 注文ＩＤを生成（PURCHASE_ITEM_LSTにも格納）
		$order_id = $makeID();

		$sql = "
		INSERT INTO
			".PRE_CUSTOMER_LST."
		SET
			CUSTOMER_ID = '$customer_id',
			ORDER_ID = '$order_id',
			LAST_NAME = '".$_SESSION['cust']['LAST_NAME']."',
			FIRST_NAME = '".$_SESSION['cust']['FIRST_NAME']."',
			LAST_KANA = '".$_SESSION['cust']['LAST_KANA']."',
			FIRST_KANA = '".$_SESSION['cust']['FIRST_KANA']."',
			ALPWD = '".$_SESSION['cust']['PASSWORD']."',
			ZIP_CD1 = '".$_SESSION['cust']['ZIP_CD1']."',
			ZIP_CD2 = '".$_SESSION['cust']['ZIP_CD2']."',
			STATE = '".$_SESSION['cust']['STATE']."',
			ADDRESS1 = '".$_SESSION['cust']['ADDRESS1']."',
			ADDRESS2 = '".$_SESSION['cust']['ADDRESS2']."',
			EMAIL = '".$_SESSION['cust']['EMAIL']."',
			TEL1 = '".$_SESSION['cust']['TEL1']."',
			TEL2 = '".$_SESSION['cust']['TEL2']."',
			TEL3 = '".$_SESSION['cust']['TEL3']."',
			INS_DATE = NOW()
		";
	}else{
		$sql = "
		INSERT INTO
			".CUSTOMER_LST."
		SET
			CUSTOMER_ID = '$customer_id',
			LAST_NAME = '".$_SESSION['cust']['LAST_NAME']."',
			FIRST_NAME = '".$_SESSION['cust']['FIRST_NAME']."',
			LAST_KANA = '".$_SESSION['cust']['LAST_KANA']."',
			FIRST_KANA = '".$_SESSION['cust']['FIRST_KANA']."',
			ALPWD = '".$_SESSION['cust']['PASSWORD']."',
			ZIP_CD1 = '".$_SESSION['cust']['ZIP_CD1']."',
			ZIP_CD2 = '".$_SESSION['cust']['ZIP_CD2']."',
			STATE = '".$_SESSION['cust']['STATE']."',
			ADDRESS1 = '".$_SESSION['cust']['ADDRESS1']."',
			ADDRESS2 = '".$_SESSION['cust']['ADDRESS2']."',
			EMAIL = '".$_SESSION['cust']['EMAIL']."',
			TEL1 = '".$_SESSION['cust']['TEL1']."',
			TEL2 = '".$_SESSION['cust']['TEL2']."',
			TEL3 = '".$_SESSION['cust']['TEL3']."',
			INS_DATE = NOW(),
			EXISTING_CUSTOMER_FLG = '0'
		";
	}

endif;

$PDO -> regist($sql);

#===============================================================================
# 注文情報と注文商品一覧を格納するＳＱＬを設定（新規／リピーター共通）
#	※テーブル：
#	・PURCHASE_LST（注文情報リスト）
#	・PURCHASE_ITEM_LST（注文商品一覧 ※購入内容の詳細）
#===============================================================================
// カスタマーIDを設定（更新：セッション ／ 新規：$makeID()で発行した$customer_id）
$cust_id = ($_SESSION['cust']['CUSTOMER_ID'])?$_SESSION['cust']['CUSTOMER_ID']:$customer_id;

// 注文ＩＤを生成（PURCHASE_ITEM_LSTにも格納）
if(!$order_id){
	$order_id = $makeID();
}

// デジタルチェック決済の場合
//$order_id = $makeID2();

// 支払い総額を算出
$total_price = ($_SESSION["cust"]["sum_price"] + $_SESSION['cust']['shipping_amount'] + $_SESSION['cust']['daibiki_amount']);

$sql = "
	INSERT INTO
		".PURCHASE_LST."
	SET
		ORDER_ID = '$order_id',
		CUSTOMER_ID = '$cust_id',
		TOTAL_PRICE = '$total_price',
		SUM_PRICE = '".$_SESSION["cust"]["sum_price"]."',
		SHIPPING_AMOUNT = '".$_SESSION['cust']['shipping_amount']."',
		DAIBIKI_AMOUNT = '".$_SESSION['cust']['daibiki_amount']."',
		CONV_AMOUNT = '".$_SESSION['cust']['conv_amount']."',
		DELI_LAST_NAME = '".$_SESSION['cust']['DELI_LAST_NAME']."',
		DELI_FIRST_NAME = '".$_SESSION['cust']['DELI_FIRST_NAME']."',
		DELI_ZIP_CD1 = '".$_SESSION['cust']['DELI_ZIP_CD1']."',
		DELI_ZIP_CD2 = '".$_SESSION['cust']['DELI_ZIP_CD2']."',
		DELI_STATE = '".$_SESSION['cust']['DELI_STATE']."',
		DELI_ADDRESS1 = '".$_SESSION['cust']['DELI_ADDRESS1']."',
		DELI_ADDRESS2 = '".$_SESSION['cust']['DELI_ADDRESS2']."',
		DELI_TEL1 = '".$_SESSION['cust']['DELI_TEL1']."',
		DELI_TEL2 = '".$_SESSION['cust']['DELI_TEL2']."',
		DELI_TEL3 = '".$_SESSION['cust']['DELI_TEL3']."',
		PAYMENT_TYPE = '".$_SESSION['cust']['PAYMENT_METHOD']."',
		ORDER_DATE = NOW(),
		PAYMENT_FLG = '0',
		SHIPPED_FLG = '0',
		REMARKS = '".$_SESSION['cust']['REMARKS']."'
	";

	$PDO -> regist($sql);

// PURCHASE_ITEM_LST（注文商品一覧 ※購入内容の詳細）
// 	※購入アイテム数（カゴから取り出した件数）の分だけSQLを設定
//	※同時に該当商品の購入個数分、現在の在庫から差し引くSQL文を設定

#カート内商品データ取得 商品配列へ
$cart_list = getItems();

	if ( !count($cart_list) ){//カートに商品が無い場合は中断させる。
		$error_type = "Completion : Session Time Out.";
		$error_message = "エラー！！: エラーが発生した為、商品購入を中断いたしました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		include("DISP_error_disp.php");exit();
	}

for ( $i = 0; $i < count($cart_list); $i++ ){

	// 各データを取り出し、多次元配列に格納
	list($product_id,$part_no,$product_name,$selling_price,$quantity,$stock_quantity) = explode("\t", $cart_list[$i]);

	if (( $quantity < 1 ) || !is_numeric($quantity)){//購入数が1未満の場合はエラー
		$error_type = "Completion : Session Time Out.";
		$error_message = "エラー！！: エラーが発生した為、商品購入を中断いたしました。<br>大変申し訳ございませんが、もう一度やり直してください。";
		include("DISP_error_disp.php");exit();
	}

	$purchaseData[$i]['product_id']			= $product_id;						// 商品ID
	$purchaseData[$i]['part_no']			= $part_no;							// 商品番号
	$purchaseData[$i]['product_name']		= $product_name;					// 商品名
	$purchaseData[$i]['selling_price']		= $selling_price;					// 販売価格
	$purchaseData[$i]['quantity']			= $quantity;						// 数量
	$purchaseData[$i]['stock_quantity']		= $stock_quantity;					// 在庫数
	$purchaseData[$i]['sum_price']			= ($selling_price * $quantity);		// 商品小計

	// 合計金額を算出
	$sum_price += $purchaseData[$i]['sum_price'];

}

#===============================================================================
# 通常ショッピングの場合のみ処理をする（ショップライトには処理をさせない）
#===============================================================================
if(!SHOP_LITE_FLG){//ショップライトで無い場合、在庫チェック処理をする

	#-------------------------------------------------------------------------
	#	在庫管理処理
	#-------------------------------------------------------------------------
	// エラーメッセージ格納用変数
	$error_message = "";
	//////////////////////////////////////////////////
	// 在庫数チェック
	for ( $i = 0; $i < count($purchaseData); $i++ ){

		# 現在個数取得
		$cnt_sql ="
		SELECT
			STOCK_QUANTITY
		FROM
			".PRODUCT_LST."
		WHERE
			PRODUCT_ID = '".$purchaseData[$i]['product_id']."'
		";

		$CntRst = $PDO -> fetch($cnt_sql);

		# 現在個数を購入個数が超えていたらエラー
		if($CntRst[0]["STOCK_QUANTITY"] < $purchaseData[$i]['quantity']){
			//$error_message .= "下記の商品につきまして在庫切れによりご購入手続きができませんでした。<br>\n大変申し訳ございませんが、ご購入一覧のページへお戻りいただけますよう何卒お願い申し上げます。<hr>\n";
			$error_message .= "商品番号：".$purchaseData[$i]['part_no']."<br>\n";
			$error_message .= "商品名：".$purchaseData[$i]['product_name']."<br>\n";
			$error_message .="ご購入個数：".$purchaseData[$i]['quantity']."／現在在庫数：".number_format($CntRst[0]["STOCK_QUANTITY"])."<br>\n";
		}

		# 配列の要素に現在在庫数をセット
		$purchaseData[$i]['stock_quantity'] = $CntRst[0]["STOCK_QUANTITY"];
	}
}

///////////////////////////////////////////////////
// エラー発生時はエラーの出力
if(!empty($error_message)){
	$error_title = "下記の商品につきまして在庫切れによりご購入手続きができませんでした。<br>\n大変申し訳ございませんが、ご購入一覧のページへお戻りいただけますよう何卒お願い申し上げます。<hr>\n";
	$error_message = $error_title . $error_message;
	include("DISP_error_disp.php");
	exit();
}

////////////////////////////////////////////////////
// エラーが無ければ購入商品登録処理+在庫数更新処理

$zaikogire_lst = "";// 在庫切れ商品リストの初期化

for ( $i = 0; $i < count($purchaseData); $i++ ){

	if(!SHOP_LITE_FLG){//ショップライトで無い場合、在庫を減らす処理をする
		#-----------------------------------------------
		# 在庫数更新（クレジットの場合は行わない）
		#-----------------------------------------------

		if($_SESSION['cust']['PAYMENT_METHOD'] != 1){

			// 現在庫数 - 購入個数 で購入後の在庫数を求める
			$zaiko = $purchaseData[$i]["stock_quantity"] - $purchaseData[$i]['quantity'];

			// 在庫が0になった商品があれば、メール通知するためにリスト化する
			if($zaiko < 1)$zaikogire_lst .= "商品番号：".$purchaseData[$i]["part_no"]." ".$purchaseData[$i]["product_name"]."\n";

			# 購入後の在庫数をDBに上書き
			$zaiko_sql ="
			UPDATE
				".PRODUCT_LST."
			SET
				STOCK_QUANTITY = ".$zaiko."
			WHERE
				PRODUCT_ID = '".$purchaseData[$i]['product_id']."'
			";

			$PDO -> regist($zaiko_sql);

		}
	}

	#-----------------------------------------------
	# 購入商品情報登録
	#-----------------------------------------------
	// 格納前にメタ文字エスケープをやっとく
	$purchaseData[$i] = array_map("addslashes", $purchaseData[$i]);

	// PURCHASE_ITEM_LSTへ購入詳細情報を登録
	$sql = "
	INSERT INTO
		".PURCHASE_ITEM_DATA."
	SET
		ORDER_ID = '$order_id',
		PRODUCT_ID = '".$purchaseData[$i]['product_id']."',
		PART_NO = '".$purchaseData[$i]['part_no']."',
		PRODUCT_NAME = '".$purchaseData[$i]['product_name']."',
		SELLING_PRICE = '".$purchaseData[$i]['selling_price']."',
		QUANTITY = '".$purchaseData[$i]['quantity']."',
		INS_DATE = NOW()
	";

	#================================================================================
	# 設定したＳＱＬを実行（登録失敗時：ＤＢエラー出力して強制終了）
	#================================================================================
	$PDO -> regist($sql);

}

/////////////////////////////////////
//	コンビニ決済カード番号保管
//		※保管する設定の場合のみ
if(CONV_NO_SQL == 1):
	$no_sql = "
	UPDATE
		".PURCHASE_LST."
	SET
		CONV_NO = '".$_SESSION['cust']['CARD_NO']."'
	WHERE
		( ORDER_ID = '".$order_id."' )
	";

	$PDO -> regist($no_sql);

endif;

// 在庫切れメール通知
if($zaikogire_lst):

// 件名とフッター
$subject = "【自動通知メール】商品の在庫数が0になりました";

$headers = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".WEBMST_SHOP_MAIL.">\n";

$mailbody = "
お客様のご購入により、下記商品の在庫数が0になりました。

{$zaikogire_lst}
";

//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
//$mailbody = str_replace("\n","\r\n",$mailbody);
$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

// メール送信（失敗時：強制終了）
$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers);
if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

endif

?>
