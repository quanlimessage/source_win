<?php
/*******************************************************************************
通常ショップベース

 LGC: クレジット決済の最終処理スクリプト
		（独立プログラムとして実行）

		※支払方法がクレジット決済の場合のみにこのスクリプトが実行される
			決済サイト：https://secure.zeroweb.ne.jp/cgi-bin/order.cgi?orders

		※クレジット支払の場合は決済サイトで決済処理完了後にこのファイル宛に
			GETを含んだ結果が送信されるので下記処理を行う。

			※GETのパラメーター（重要：このパラメーターがない場合は何も処理しない）
				・$_GET['sendid']（購入ID。PHACASE_LSTのORDER_IDが格納。決済サイトの誘導時のhiddenで仕込んだsendidの値）
				・$_GET['result']（決済結果。OK:成功 :失敗）

			１．上記のGETデータ($_GET["sendid"])を元に一時保存のデータを取得

			２・読み込んだデータをつかってDBを更新（パスワード、決済フラグ、在庫）

			３．ユーザー／管理者共に完了のメールを送信（ＤＢより情報を取得）

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("./common/INI_config.php");		// 設定ファイル
require_once("./common/INI_ShopConfig.php");		// ショップ用設定ファイル
require_once("./common/INI_pref_list.php");		// 送料＆都道府県情報（配列）
require_once("dbOpe.php");				// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ

#=============================================================================
# 決済サイトより決済処理完了後に送信されるGETの受取とエラーチェック
# （エラー時：強制終了）
#
#	※汎用処理クラスライブラリを使用して処理
#	※処理項目：タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
#=============================================================================
extract(utilLib::getRequestParams("get",array(8,7,1,4,5),true));

// 念のためURLデコードしておく（ハイフンがエンコードされる時とされない時があるようだ。by Yos）
$cod = urldecode($sendid);
$result  = urldecode($result);

// 仮顧客情報を本顧客情報に登録（2013/07/09 仕様変更 野添）
// 決済の成功、失敗による分岐
if($result == 'ok' || $result == 'OK'){
	do{
		// 仮顧客情報からデータ取得
		$sqlPre = "
		SELECT
			*
		FROM
			".PRE_CUSTOMER_LST."
		WHERE
			( ORDER_ID = '".$cod."' )
		AND
			( DEL_FLG = '0' )
		";
		$fetchPreCust = dbOpe::fetch($sqlPre, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

		// 仮情報が存在しなければ中断
		if(0 == count($fetchPreCust)){
			//$error_mes .= "予想外のエラー：仮顧客情報が存在しませんでした。\n";
			break;
		}

		$fetchPreCust[0] = array_map("addslashes",$fetchPreCust[0]);

		$sqlPre = "
		SELECT
			CUSTOMER_ID
		FROM
			".CUSTOMER_LST."
		WHERE
			( CUSTOMER_ID = '".$fetchPreCust[0]["CUSTOMER_ID"]."' )
		AND
			( DEL_FLG = '0' )
		AND
			(EXISTING_CUSTOMER_FLG = '1')
		";
		$fetchCust = dbOpe::fetch($sqlPre, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

		// 本登録済みなら中断
		if(0 != count($fetchCust)){
			//$error_mes .= "予想外のエラー：顧客情報が存在しました。\n";
			break;
		}

		// 登録
		$sqlPre = "
		INSERT INTO
			".CUSTOMER_LST."
		SET
			CUSTOMER_ID = '".$fetchPreCust[0]['CUSTOMER_ID']."',
			LAST_NAME = '".$fetchPreCust[0]['LAST_NAME']."',
			FIRST_NAME = '".$fetchPreCust[0]['FIRST_NAME']."',
			LAST_KANA = '".$fetchPreCust[0]['LAST_KANA']."',
			FIRST_KANA = '".$fetchPreCust[0]['FIRST_KANA']."',
			ALPWD = '".$fetchPreCust[0]['ALPWD']."',
			ZIP_CD1 = '".$fetchPreCust[0]['ZIP_CD1']."',
			ZIP_CD2 = '".$fetchPreCust[0]['ZIP_CD2']."',
			STATE = '".$fetchPreCust[0]['STATE']."',
			ADDRESS1 = '".$fetchPreCust[0]['ADDRESS1']."',
			ADDRESS2 = '".$fetchPreCust[0]['ADDRESS2']."',
			EMAIL = '".$fetchPreCust[0]['EMAIL']."',
			TEL1 = '".$fetchPreCust[0]['TEL1']."',
			TEL2 = '".$fetchPreCust[0]['TEL2']."',
			TEL3 = '".$fetchPreCust[0]['TEL3']."',
			INS_DATE = NOW(),
			EXISTING_CUSTOMER_FLG = '0'
		";
		$registDB_buy_flg = dbOpe::regist($sqlPre,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		if ( $registDB_buy_flg ){
			$error_mes .= "予想外のエラー：顧客情報のデータベースへの登録ができませんでした。{$registDB_result}\n";
			break;
		}

		// 仮顧客情報の削除フラグを立てる
		$sqlPre = "
		UPDATE
			".PRE_CUSTOMER_LST."
		SET
			DEL_FLG = '1'
		WHERE
			( CUSTOMER_ID = '".$fetchPreCust[0]["CUSTOMER_ID"]."' )
		AND
			( ORDER_ID = '".$fetchPreCust[0]["ORDER_ID"]."' )
		AND
			(DEL_FLG = '0')
		";
		$registDB_buy_flg = dbOpe::regist($sqlPre,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		if ( $registDB_buy_flg ){
			$error_mes .= "予想外のエラー：仮顧客情報のデータベース更新ができませんでした。{$registDB_result}\n";
			break;
		}
	}while(false);
}

///////////////////////////////////////
// 注文情報と個人情報の取り出し
$sql_ordercust = "
SELECT
	".PURCHASE_LST.".ORDER_ID,
	".PURCHASE_LST.".CUSTOMER_ID,
	".CUSTOMER_LST.".LAST_NAME,
	".CUSTOMER_LST.".FIRST_NAME,
	".CUSTOMER_LST.".LAST_KANA,
	".CUSTOMER_LST.".FIRST_KANA,
	".CUSTOMER_LST.".ZIP_CD1,
	".CUSTOMER_LST.".ZIP_CD2,
	".CUSTOMER_LST.".STATE,
	".CUSTOMER_LST.".ADDRESS1,
	".CUSTOMER_LST.".ADDRESS2,
	".CUSTOMER_LST.".EMAIL,
	".CUSTOMER_LST.".TEL1,
	".CUSTOMER_LST.".TEL2,
	".CUSTOMER_LST.".TEL3,
	".CUSTOMER_LST.".EXISTING_CUSTOMER_FLG,
	".CUSTOMER_LST.".ALPWD,
	".PURCHASE_LST.".TOTAL_PRICE,
	".PURCHASE_LST.".SUM_PRICE,
	".PURCHASE_LST.".SHIPPING_AMOUNT,
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
	".PURCHASE_LST.".REMARKS,
	".PURCHASE_LST.".CREDIT_CLOSE_FLG,
	".PURCHASE_LST.".PAYMENT_TYPE
FROM
	".PURCHASE_LST.",".CUSTOMER_LST."
WHERE
	".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
AND
	(".PURCHASE_LST.".ORDER_ID = '".$cod."')
AND
	(".PURCHASE_LST.".DEL_FLG = '0')
AND
	(".CUSTOMER_LST.".DEL_FLG = '0')
";

$fetchOrderCust = dbOpe::fetch($sql_ordercust,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//データが取得できなかった場合
if(!count($fetchOrderCust)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
}

// クレジット処理完了しているか
if(!$fetchOrderCust[0]["CREDIT_CLOSE_FLG"]){

// 決済の成功、失敗による分岐
if($result == 'ok' || $result == 'OK'):

	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$cod)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
	}

	#=============================================================================
	# 各DBへのデータ格納 ※各SQLを作成したあとまとめてクエリ発行
	#=============================================================================

	// 新規顧客か再度利用客か判断
	if($fetchOrderCust[0]['EXISTING_CUSTOMER_FLG'] == "1")://既存顧客の場合

		// メールにパスを添付するためのフラグ(添付しないのでfalseに)
		$pw_flg = false;

	else://新規顧客の場合

		// メールにパスを添付するためのフラグ(添付するのでtrueに)
		$pw_flg = true;

	endif;

	///////////////////////////////////////
	// 注文商品情報(PURCHASE_LST)

	$sql[] = "
	UPDATE
		".PURCHASE_LST."
	SET
		PAYMENT_FLG = '1',
		PAYMENT_DATE = NOW(),
		CREDIT_CLOSE_FLG = '1'
	WHERE
		(ORDER_ID = '".$fetchOrderCust[0]["ORDER_ID"]."')
	AND
		(CUSTOMER_ID = '".$fetchOrderCust[0]["CUSTOMER_ID"]."')
	AND
		(DEL_FLG = '0')
	";

	///////////////////////////////////////
	// 注文商品情報(PURCHASE_ITEM_DATA)

	$sql_peritem = "
	SELECT
		PRODUCT_ID,
		PART_NO,
		PRODUCT_NAME,
		SELLING_PRICE,
		QUANTITY
	FROM
		".PURCHASE_ITEM_DATA."
	WHERE
		( ORDER_ID = '".$fetchOrderCust[0]["ORDER_ID"]."' )
	AND
		( DEL_FLG = '0' )
	ORDER BY PID
	";
	$fetchPerItem = dbOpe::fetch($sql_peritem,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if(!SHOP_LITE_FLG){//ショップライトで無い場合、在庫を減らす処理をする
		for ( $i = 0; $i < count($fetchPerItem); $i++ ){

			#-------------------------------------------------------------------------
			#	在庫管理処理
			#-------------------------------------------------------------------------

			# 現在個数取得
			$cnt_sql ="
			SELECT
				STOCK_QUANTITY
			FROM
				".PRODUCT_LST."
			WHERE
				PRODUCT_ID = '".$fetchPerItem[$i]['PRODUCT_ID']."'
			";

			$CntRst = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			########################################################################################################
			# 在庫数オーバーのチェックは決済前に行ってるのでここでは在庫オーバーは管理者メールに添付させる
			#	決済処理中に在庫数が変動し在庫切れになった場合・・・
			########################################################################################################

			// 在庫数が購入個数を下回ってる(決済処理中に在庫切れ・・)
			if($CntRst[0]["STOCK_QUANTITY"] < $fetchPerItem[$i]['QUANTITY']):
				// とりあえず現在庫数を「0」に
				$zaiko = 0;

				// 管理者通知用
				$zaiko_err_str .= "【！】決済処理中に在庫切れが発生してしまいました。\n";
				$zaiko_err_str .= "該当商品番号：".$fetchPerItem[$i]["PART_NO"]."\n";
				$zaiko_err_str .= "該当商品名：".$fetchPerItem[$i]["PRODUCT_NAME"]."\n";
				$zaiko_err_str .= "現在商品数：".$CntRst[0]["STOCK_QUANTITY"]."\n";
				$zaiko_err_str .= "購入数：".$fetchPerItem[$i]['QUANTITY']."\n";
				$zaiko_err_str .= "決済は完了してしまっています。至急対処をお願い致します。\n\n";
			// それ以外(通常計算)
			else:
				# 現在庫数 - 購入個数 で購入後の在庫数を求める
				$zaiko = $CntRst[0]["STOCK_QUANTITY"] - $fetchPerItem[$i]['QUANTITY'];
			endif;

			// 在庫が0になった商品があれば、メール通知するためにリスト化する
			if($zaiko < 1)$zaikogire_lst .= "商品番号：".$fetchPerItem[$i]["PART_NO"]." ".$fetchPerItem[$i]["PRODUCT_NAME"]."\n";

			/////////////////////////////////
			// 購入後の在庫数をDBに上書き
			$zaiko_sql ="
			UPDATE
				".PRODUCT_LST."
			SET
				STOCK_QUANTITY = ".$zaiko."
			WHERE
				PRODUCT_ID = '".$fetchPerItem[$i]['PRODUCT_ID']."'
			";

			$ZaikoRst = dbOpe::regist($zaiko_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			// 在庫データ書換え失敗時はエラー
			if ( $ZaikoRst ){
				$error_mes .= "予想外のエラー：在庫数上書きに失敗しました。<hr>{$ZaikoRst}\n";
			}

		}
	}

	#================================================================================
	# 設定したＳＱＬを実行（登録失敗時：ＤＢエラー出力して強制終了）
	#================================================================================
	if(!empty($sql)){
		$registDB_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if ( $registDB_result ){
			$error_mes .= "予想外のエラー：データベースへの購入情報更新ができませんでした。<hr>{$registDB_result}\n";
		}
	}
	#=============================================================================
	# ユーザーと管理者へ メール送信の設定と送信を行う
	#=============================================================================

	#-------------------------------------------------------------------------
	# 購入者（お客様）へサンキューメールを送信
	#-------------------------------------------------------------------------
	// 本文雛形を読み込み
	
	if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	    $mailbody = file_get_contents("./regist/mail_tmpl/INI_mailbody_payCREDIT.dat");
	}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	    $mailbody = file_get_contents("./regist/mail_tmpl/INI_mailbody_payPAYPAL.dat");
	}
	// 基本情報
	if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$fetchOrderCust[0]["LAST_NAME"]." ".$fetchOrderCust[0]["FIRST_NAME"],$mailbody);
	if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$fetchOrderCust[0]["ORDER_ID"],$mailbody);
	if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

	// 購入商品（$itemsは管理者用にも使用）
	for($i = 0; $i < count($fetchPerItem); $i++){
		$items .= "商品番号：".$fetchPerItem[$i]['PART_NO']."\n";
		$items .= "商品名：".$fetchPerItem[$i]['PRODUCT_NAME']."\n";
		$items .= "\\".number_format($fetchPerItem[$i]['SELLING_PRICE'])."\t";
		$items .= "数量：".$fetchPerItem[$i]['QUANTITY']."\n\n";
	}
	if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);
	if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($fetchOrderCust[0]["SUM_PRICE"]),$mailbody);
	if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($fetchOrderCust[0]["SHIPPING_AMOUNT"]),$mailbody);

	if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($fetchOrderCust[0]["TOTAL_PRICE"]),$mailbody);

	// 個人情報
	if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$fetchOrderCust[0]["LAST_KANA"]." ".$fetchOrderCust[0]["FIRST_KANA"],$mailbody);
	if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$fetchOrderCust[0]["EMAIL"],$mailbody);
	if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$fetchOrderCust[0]["ZIP_CD1"]."-".$fetchOrderCust[0]["ZIP_CD2"],$mailbody);
	if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$fetchOrderCust[0]["STATE"]]['pref'].$fetchOrderCust[0]["ADDRESS1"].$fetchOrderCust[0]["ADDRESS2"],$mailbody);
	if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$fetchOrderCust[0]["TEL1"]."-".$fetchOrderCust[0]["TEL2"]."-".$fetchOrderCust[0]["TEL3"],$mailbody);

	// ※パスワードの表示は初回のみ(フラグ$pw_flgで判定)
	if($pw_flg){
		if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","パスワード：".$fetchOrderCust[0]['ALPWD']." → 次回のお買い物の際にご利用ください",$mailbody);
	}
	else{
		if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","",$mailbody);
	}

	// 配送先
	if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$fetchOrderCust[0]["DELI_LAST_NAME"]." ".$fetchOrderCust[0]["DELI_FIRST_NAME"],$mailbody);
	if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$fetchOrderCust[0]["DELI_ZIP_CD1"]."-".$fetchOrderCust[0]["DELI_ZIP_CD2"],$mailbody);
	if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$fetchOrderCust[0]["DELI_STATE"]]['pref'].$fetchOrderCust[0]["DELI_ADDRESS1"].$fetchOrderCust[0]["DELI_ADDRESS2"],$mailbody);
	if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$fetchOrderCust[0]["DELI_TEL1"]."-".$fetchOrderCust[0]["DELI_TEL2"]."-".$fetchOrderCust[0]["DELI_TEL3"],$mailbody);

	// 備考欄
	if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$fetchOrderCust[0]["REMARKS"],$mailbody);

	//パスワード変更のお知らせ（forgetpass）のURL
	//現在の位置を求めてforgetpassへのURLを出す

	$url = getcwd();
	$sname = $_SERVER["SERVER_NAME"];
	$dr = $_SERVER["DOCUMENT_ROOT"];

	$url = str_replace($dr,"",$url);
	$url = "http://".$sname.$url."/regist/forgetpass/";

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
	$usrmail_result = mb_send_mail($fetchOrderCust[0]["EMAIL"],$subject,$mailbody,$headers,$rpath);
	if(!$usrmail_result)$error_mes .= "お客様へのメール送信に失敗しました。\n";

	#-------------------------------------------------------------------------
	# 管理者（Webマスター宛）へ報告メール送信
	#-------------------------------------------------------------------------
	// 本文雛形読み込み
	$mailbody = file_get_contents("./regist/mail_tmpl/INI_mailbody_forADMIN.dat");

	// 基本情報
	if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$fetchOrderCust[0]["LAST_NAME"]." ".$fetchOrderCust[0]["FIRST_NAME"],$mailbody);
	if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$fetchOrderCust[0]["ORDER_ID"],$mailbody);
	if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

	// 購入商品
	if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);
	if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($fetchOrderCust[0]["SUM_PRICE"]),$mailbody);
	if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($fetchOrderCust[0]["SHIPPING_AMOUNT"]),$mailbody);
	if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($fetchOrderCust[0]["TOTAL_PRICE"]),$mailbody);
	if(strstr($mailbody,"<DAIBIKI_DISP>"))$mailbody = str_replace("<DAIBIKI_DISP>","",$mailbody);
	if(strstr($mailbody,"<CONV_NO>"))$mailbody = str_replace("<CONV_NO>","",$mailbody);

	// 個人情報
	if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$fetchOrderCust[0]["LAST_KANA"]." ".$fetchOrderCust[0]["FIRST_KANA"],$mailbody);
	if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$fetchOrderCust[0]["EMAIL"],$mailbody);
	if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$fetchOrderCust[0]["ZIP_CD1"]."-".$fetchOrderCust[0]["ZIP_CD2"],$mailbody);
	if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$fetchOrderCust[0]["STATE"]]['pref'].$fetchOrderCust[0]["ADDRESS1"].$fetchOrderCust[0]["ADDRESS2"],$mailbody);
	if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$fetchOrderCust[0]["TEL1"]."-".$fetchOrderCust[0]["TEL2"]."-".$fetchOrderCust[0]["TEL3"],$mailbody);
	if(strstr($mailbody,"<ALPWD>"))$mailbody = str_replace("<ALPWD>",$fetchOrderCust[0]['ALPWD'],$mailbody);

	// 配送先
	if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$fetchOrderCust[0]["DELI_LAST_NAME"]." ".$fetchOrderCust[0]["DELI_FIRST_NAME"],$mailbody);
	if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$fetchOrderCust[0]["DELI_ZIP_CD1"]."-".$fetchOrderCust[0]["DELI_ZIP_CD2"],$mailbody);
	if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$fetchOrderCust[0]["DELI_STATE"]]['pref'].$fetchOrderCust[0]["DELI_ADDRESS1"].$fetchOrderCust[0]["DELI_ADDRESS2"],$mailbody);
	if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$fetchOrderCust[0]["DELI_TEL1"]."-".$fetchOrderCust[0]["DELI_TEL2"]."-".$fetchOrderCust[0]["DELI_TEL3"],$mailbody);

	// もし決済処理中在庫エラーが発生していたら備考欄で通知
	if(!empty($zaiko_err_str)){
		$remarks = $fetchOrderCust[0]["REMARKS"]."\n".$zaiko_err_str;
	}else{
		$remarks = $fetchOrderCust[0]["REMARKS"];
	}

	if(strlen($error_mes) > 0)$remarks .= "\n".$error_mes;

	// 備考欄
	if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$remarks,$mailbody);

	// 会社情報
	if(strstr($mailbody,"<COMPANY_INFO>"))$mailbody = str_replace("<COMPANY_INFO>",COMPANY_INFO,$mailbody);

	// 支払方法（ここではクレジットを固定）
	if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	    if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","クレジット",$mailbody);
	}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	    if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","PayPal",$mailbody);
	}
	

	// コンビニ決済置換用文字を空白に置換
	if(strstr($mailbody,"<CONV_DISP>"))$mailbody = str_replace("<CONV_DISP>","",$mailbody);

	// 件名とフッター
	if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	    $subject = "webよりクレジットでお申し込みがありました";
	}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	    $subject = "webよりPayPalでお申し込みがありました";
	}
	
	$headers = "Reply-To: ".$fetchOrderCust[0]["EMAIL"]."\n";
	$headers .= "Return-Path: ".$fetchOrderCust[0]["EMAIL"]."\n";
	$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".$fetchOrderCust[0]["EMAIL"].">\n";
	$rpath = "-f ".$fetchOrderCust[0]["EMAIL"];//mb_send_mailのReturn-Path を設定

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
		 		( CUSTOMER_ID = '".$fetchOrderCust[0]["CUSTOMER_ID"]."' )
		 	AND
		 		(DEL_FLG = '0')
		 ";
		 $registDB_buy_flg = dbOpe::regist($sqlbuy_flg,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		 $base_path = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		 $temporary_path = explode("/regist.php",$base_path);//一時的に分割したパスを保存
// 最後にクレジット用完了画面へ転送
?>
<html>
<head><meta http-equiv="refresh" content="0;URL=<?php echo $temporary_path[0];?>/regist/credit_completion.html">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
<body>
</body>
</html>
<?php
elseif($result == 'ng' || $result == 'NG'):

	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$cod)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
	}

	$sql = "
		UPDATE
			PURCHASE_LST
		SET
			PAYMENT_FLG = '2',
			CREDIT_CLOSE_FLG = '0'
		WHERE
			ORDER_ID = '".$cod."'
	";

#================================================================================
# 設定したＳＱＬを実行（登録失敗時：ＤＢエラー出力して強制終了）
#================================================================================
$registDB_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
if ( $registDB_result ){
	if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	    $error_mes .= "予想外のエラー：クレジット決済失敗通知のデータベースへの登録ができませんでした。{$registDB_result}\n";
	}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	    $error_mes .= "予想外のエラー：PayPal決済失敗通知のデータベースへの登録ができませんでした。{$registDB_result}\n";
	}
}

if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
    $error_mes .= "注文番号：".$cod."のお客様がクレジット決済に失敗をいたしました。\n";
}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
    $error_mes .= "注文番号：".$cod."のお客様がPayPal決済に失敗をいたしました。\n";
}

$error_mes .= "決済代行会社より決済エラーの通知が届いているためお客様に確認メールは届いていません。\n";
$error_mes .= "内容を確認の上対応をお願いします。\n決済内容は管理画面等で確認出来ます。";
$mailbody .= $error_mes;

// 件名とフッター
	if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	    $subject = "クレジットでお申し込みがありましたがエラーが発生しています。";
	}elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	    $subject = "PayPalでお申し込みがありましたがエラーが発生しています。";
	}
	
	$headers = "Reply-To: ".$fetchOrderCust[0]["EMAIL"]."\n";
	$headers .= "Return-Path: ".$fetchOrderCust[0]["EMAIL"]."\n";
	$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".WEBMST_SHOP_MAIL.">\n";

// メール送信（失敗時：強制終了）
	$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers);
	if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

endif;

}else{
    if($fetchOrderCust[0]['PAYMENT_TYPE']==1){
	echo "このクレジット決済処理は完了しています。";
    }elseif($fetchOrderCust[0]['PAYMENT_TYPE']==6){
	echo "このPayPal決済処理は完了しています。";
    }
}

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
