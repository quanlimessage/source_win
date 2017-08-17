<?php
/*******************************************************************************
ShopWinLite コンテンツ：ショッピングカートプログラム内

メールアドレス変更プログラム
Logic：メールアドレス更新とメール送信

	※ＤＢ（CUSTOMER_LST）より対象のメアドを更新する
	※書き換えたユーザー宛にメールを送信する（新しいメアド宛）

2005/10/13 KC
s*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk) {
	header("HTTP/1.0 404 Not Found");	exit();
}

if ( $_SESSION['setParam']['emailChg_Auth_flg'] ):

	#---------------------------------------------------------------------------
	#　最後に念のため、もう一度認証チェックを行う（ＤＢ：CUSTOMER_LST）
	#	※戻るボタン等の不正利用防~の為
	#---------------------------------------------------------------------------
	$sql = "
	SELECT
		ALPWD,
		EMAIL
	FROM
		".CUSTOMER_LST."
	WHERE
		( ALPWD = '".utilLib::strRep($_SESSION['getParam']['pwd'],5)."' )
	AND
		( EMAIL = '".$_SESSION['getParam']['oldmail']."' )
	AND
		( DEL_FLG = '0' )
	";
	$fetchAgainAuth = $PDO -> fetch($sql);

	// データがあればＯＫ（なければ書き換わったので不正→トップページへ転送）
	if ( !$fetchAgainAuth ){
		$_SESSION = array();
		header("Location: ../../");	exit();
	}

	#---------------------------------------------------------------------------
	# 新しいメールアドレスに更新する（CUSTOMER_LST）
	#---------------------------------------------------------------------------
	$sql = "
	UPDATE
		".CUSTOMER_LST."
	SET
		EMAIL = '".utilLib::strRep($_SESSION['getParam']['newmail'],5)."'
	WHERE
		( ALPWD = '".utilLib::strRep($_SESSION['getParam']['pwd'],5)."' )
	AND
		( EMAIL = '".$_SESSION['getParam']['oldmail']."' )
	AND
		( DEL_FLG = '0' )
	";

	// ＳＱＬを実行（実行結果を格納）
	$update_result = $PDO -> regist($sql);

	#---------------------------------------------------------------------------
	# ユーザーへメール送信（変更お知らせ）※ＳＱＬ実行結果がＯＫな場合のみ
	#---------------------------------------------------------------------------
	if ( $update_result ){

		// 本文雛形を読み込み
		$mailbody = file_get_contents(dirname(__FILE__)."/../mail_tmpl/INI_mailbody_ChangedEmail.dat");

		// 件名とフッター
		$subject = SUBJECT_CLIENT_NAME."より自動返信メール";
		$headers  = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
		$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
		$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".WEBMST_SHOP_MAIL.">\n";

		// 本文組み立て
		if ( strstr($mailbody, "<WEBMST_NAME>") )	$mailbody = str_replace("<WEBMST_NAME>", WEBMST_NAME, $mailbody);
		if ( strstr($mailbody, "<COMPANY_INFO>") )	$mailbody = str_replace("<COMPANY_INFO>", COMPANY_INFO, $mailbody);

		$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

		// メール送信（失敗時：強制終了）
		$usrmail_result = mb_send_mail($_SESSION['getParam']['newmail'], $subject, $mailbody, $headers);
		if ( !$usrmail_result )
			die("お客様へのメール送信に失敗しました。。。<br>\n誠に申し訳ございませんがこちらまでご連絡ください“".WEBMST_SHOP_MAIL."”");

	}

endif;

?>
