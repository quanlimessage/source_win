<?php
/*******************************************************************************
ShopWinLite コンテンツ：ショッピングカートプログラム内

パスワード変更プログラム：
Logic：入力チェック＆データ更新＆メール送信プログラム

	※$_POST['status']及びGETパラメーターの有無により処理内容を分岐

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#=============================================================================
# $_POST['status']の内容により処理を分岐
#	※GETパラメーター（“cid”と“did”）があった場合の処理を最優先する事！
#=============================================================================
if ( $_GET['cid'] && $_GET['did'] ):
////////////////////////////////////////////////////////////////////
// ユーザーから指定したパラメーター付URL経由でアクセスしてきた場合
// ※チェックに引っかかった場合はショッピングトップへ強制転送して終了
// ※ＯＫだったら、謫ｾした個人ID（$cid）とパスワードをセッションに格納

	#========================================================================
	# GETデータの受け取りと共通な文字列処理
	#========================================================================
	extract(utilLib::getRequestParams("get",array(8,7,1,4)));

	// GETパラメーターの文字列変換
	$cid = mb_convert_kana($cid, "a");
	$did = mb_convert_kana($did, "a");

	// データ形式チェック（不正があればショッピングトップページへ飛ばして終了）
	if ( !preg_match("/^([0-9]{10,})-([0-9]{6})$/", $cid) ){
		header("Location: ../../");	exit();
	}
	elseif ( preg_match("/[^a-zA-Z0-9]/", $did) ){
		header("Location: ../../");	exit();
	}

	#--------------------------------------------------------------------
	# DB（CUSTOMER_LST）より該当する顧客IDとパスワードをチェックする
	#--------------------------------------------------------------------
	$sql = "
	SELECT
		CUSTOMER_ID,
		ALPWD,
		EMAIL
	FROM
		".CUSTOMER_LST."
	WHERE
		( CUSTOMER_ID = '$cid' )
	AND
		( DEL_FLG = '0' )
	AND
		(EXISTING_CUSTOMER_FLG = '1')
	";
	$fetchCust = dbOpe::fetch($sql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

	// チェック
	if ( $fetchCust[0]['CUSTOMER_ID'] == "" || $fetchCust[0]['CUSTOMER_ID'] == NULL ){
		header("Location: ../../");	exit();
	}
	elseif ( $did != $cryptPass($fetchCust[0]['ALPWD']) ){
		header("Location: ../../");	exit();
	}
	elseif ( count($fetchCust) > 1 ){
		// ないとは思うが万が一、登録数が複数存在しているようなら強制終了する
		die("不正な登録情報により処理を中断しました<br>恐れ入りますがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");
	}
	else{
		// 識別した個人IDとパスワードとメールアドレスをセッションに格納
		$_SESSION['authOK']['CUSTOMER_ID'] = $fetchCust[0]['CUSTOMER_ID'];
		$_SESSION['authOK']['ALPWD'] = $fetchCust[0]['ALPWD'];
		$_SESSION['authOK']['EMAIL'] = $fetchCust[0]['EMAIL'];
	}

else:
////////////////////////////////////////////////////////////////////
// 通常のメイン処理

	#-------------------------------------------------------------------
	# $_POST['status']の内容により処理内容を分岐	※hiddenデータ
	#-------------------------------------------------------------------
	switch($_POST['status']):
	case "pck":
		///////////////////////////////////////////////////////////
		// 新パスワードチェックとデータ更新。完了メール送信
		//  ※パスワードデータなのでチェックはするが文字列処理は行わない
		// ※ブラウザの戻るボタンによる２重実行防止及び認証データがある場合のみ処理を行う
		
		#========================================================================
		# POSTデータの受け取りと共通な文字列処理
		#========================================================================
		if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}
		

		if ($npwd != $npwd2):
			$error_message .= "パスワードが一致しません<br>\n";
		elseif (preg_match("/^(\s|　)+$/", $npwd)):
			$error_message .= 'パスワードを入力してください。'."<br>\n";
		elseif (!$npwd):
			$error_message .= 'パスワードを入力してください。'."<br>\n";
		elseif (preg_match("/[\\\]/", $npwd) || preg_match("/[\\\]/", $npwd2)):
			$error_message .= '大変申し訳ございませんが“\”という文字をパスワードに使用するのはご遠慮いただけますよう何卒お願い申し上げます'."<br>\n";
		else:

			if($_SESSION['authOK'] && !$error_message):

				// パスワードの更新（CUSTOMER_LST）
				$sql = "
				UPDATE
					".CUSTOMER_LST."
				SET
					ALPWD = '".utilLib::strRep($npwd,5)."',
					UPD_DATE = NOW()
				WHERE
					( CUSTOMER_ID = '".$_SESSION['authOK']['CUSTOMER_ID']."' )
				AND
					( ALPWD = '".utilLib::strRep($_SESSION['authOK']['ALPWD'],5)."' )
				AND
					( DEL_FLG = '0' )
				AND
					(EXISTING_CUSTOMER_FLG = '1')
				";

				// ＳＱＬを実行し、パスワード更新ＯＫならメール送信する
				$update_result_mes = dbOpe::regist($sql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);
				if ( $update_result_mes ){
					$error_message .= "更新に失敗しました。<br>大変申し訳ございませんがこちらまでお問い合わせください<br>".WEBMST_SHOP_MAIL."<hr>{$error_message}<hr>{$update_result_mes}";
				}
				else{

					// 本文雛形を読み込み（文字列差し替え無し）
					$mailbody = file_get_contents(dirname(__FILE__)."/../mail_tmpl/INI_mailbody_ChangedPass.dat");
					if ( strstr($mailbody, "<WEBMST_NAME>") )	$mailbody = str_replace("<WEBMST_NAME>", WEBMST_NAME, $mailbody);
					if ( strstr($mailbody, "<COMPANY_INFO>") )	$mailbody = str_replace("<COMPANY_INFO>", COMPANY_INFO, $mailbody);

					// 件名とフッター
					$subject  = "パスワードの変更完了";
					$headers  = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
					$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
					$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".WEBMST_SHOP_MAIL.">\n";

					$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

					// メール送信（失敗時強制終了）
					$send_mail_result = mb_send_mail($_SESSION['authOK']['EMAIL'], $subject, $mailbody, $headers);
					if ( !$send_mail_result )die("お客様へのメール送信に失敗しました。。。<br>\n誠に申し訳ございませんがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");

				}

			else:
				// 認証のセッションデータないならトップへすっ飛ばす
				header("Location: ../../");	exit();
			endif;

		endif;

		break;
	case "mck":
	///////////////////////////////////////////////////////////
	// メールチェックと認証。パスワード変更ページ案内メール送信

		// リクエストパラメーター受取と文字列処理（POST）
		extract(utilLib::getRequestParams("post",array(8,7,1,4)));

		// メアドの未入力チェック＆半角英数字の変換＆整合性チェックを行う
		$chkmail = mb_convert_kana($chkmail, "a");
		$error_message .= utilLib::strCheck($chkmail, 0, "Eメールアドレスを入力してください。<br>\n");

		if ( empty($error_message) ){
			$chkmail = mb_convert_kana($chkmail, "a");
			$e_chk  = "";
			$e_chk .= utilLib::strCheck($chkmail, 1, true);
			$e_chk .= utilLib::strCheck($chkmail, 4, true);
			$e_chk .= utilLib::strCheck($chkmail, 5, true);
			$e_chk .= utilLib::strCheck($chkmail, 6, true);

			if ( $e_chk )	$error_message .= "Eメールアドレスに誤りがあります。<br>\n";
		}

		#--------------------------------------------------------------------
		# DB（CUSTOMER_LST）より該当するメアドがあるかチェックすると同時に
		# CUSTOMER_IDとパスワードも取得しておく（GET付URLのパラメーターに使用）
		#--------------------------------------------------------------------
		if ( empty($error_message) ){

			$sql = "
			SELECT
				CUSTOMER_ID,
				ALPWD,
				EMAIL
			FROM
				".CUSTOMER_LST."
			WHERE
				( EMAIL = '$chkmail' )
			AND
				( ALPWD != '' )
			AND
				( DEL_FLG = '0' )
			AND
				(EXISTING_CUSTOMER_FLG = '1')
			";
			$fetchCust = dbOpe::fetch($sql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

			if ( $fetchCust[0]['EMAIL'] == "" || $fetchCust[0]['EMAIL'] == NULL ){
				$error_message .= "お客様の情報はご登録されていません。<br>\n";
			}
			else{

				// ないとは思うか万が一、登録数が複数存在しているようなら強制終了する
				if ( count($fetchCust) > 1 )
					die("不正な登録情報により処理を中断しました<br>恐れ入りますがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");

				// GET付URLのパラメーター用データとして取得（パスワードはcrypt化しておく）
				$cid = $fetchCust[0]['CUSTOMER_ID'];
				$did = $cryptPass($fetchCust[0]['ALPWD']);
			}

			#---------------------------------------------------------------------
			# パスワード変更ページ案内メールを送信（ここまでエラーがない場合）
			#---------------------------------------------------------------------
			if ( !$error_message ){

				// 本文雛形を読み込み
				$mailbody = file_get_contents(dirname(__FILE__)."/../mail_tmpl/INI_mailbody_ForgetPass.dat");

				// 該当文字列の差し替え：メールアドレス
				if ( strstr($mailbody, "<MAIL>") )	$mailbody = str_replace("<MAIL>", $chkmail, $mailbody);

				// 該当文字列の差し替え：URL（アクセス先（自分自身）。パラメーター付）
				$self_url = "http://".$_SERVER["HTTP_HOST"].str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"])."?cid={$cid}&did={$did}";
				if ( strstr($mailbody, "<WEBMST_NAME>") )	$mailbody = str_replace("<WEBMST_NAME>", WEBMST_NAME, $mailbody);
				if ( strstr($mailbody, "<URL>") )	$mailbody = str_replace("<URL>", $self_url, $mailbody);
				if ( strstr($mailbody, "<COMPANY_INFO>") )	$mailbody = str_replace("<COMPANY_INFO>", COMPANY_INFO, $mailbody);

				// 件名とフッター
				$subject = "パスワードの再設定";
				$headers  = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
				$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
				$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".WEBMST_SHOP_MAIL.">\n";

				$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

				// メール送信（エラーなら強制終了）
				$send_mail_result = mb_send_mail($chkmail, $subject, $mailbody, $headers);
				if ( !$send_mail_result )
					die("お客様へのメール送信に失敗しました。。。<br>\n誠に申し訳ございませんがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");

			}

		}

		break;
	endswitch;

endif;
?>
