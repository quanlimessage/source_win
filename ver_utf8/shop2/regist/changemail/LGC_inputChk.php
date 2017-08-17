<?php
/*******************************************************************************
ShopWinLite コンテンツ：ショッピングカートプログラム内

メールアドレス変更プログラム
Logic：入力内容をチェック

	※認証に成功した場合は認証フラグを立てる
	※認証に成功した場合のエラーチェック（修正で再度チェックする場合）は
	　メアドの整合性のみチェックする

2005/10/13 KC
*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#---------------------------------------------------------------------------
#	POSTデータの受け取りと共通な文字列処理
# 	タグ、空白の除求^危険文字無効化／“\”を取る／半角カナを全角に変換
#---------------------------------------------------------------------------
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

#================================================================================
# 未認証：すべてチェック
# 認証済み：新しいメールアドレスのみ処理
#================================================================================
if ( !$_SESSION['setParam']['emailChg_Auth_flg'] ):

	#---------------------------------------------------------------------------
	# 半角英数字の統一
	#---------------------------------------------------------------------------
	$oldmail = mb_convert_kana($oldmail, "a");
	$pwd = mb_convert_kana($pwd, "a");
	$newmail = mb_convert_kana($newmail, "a");

	#---------------------------------------------------------------------------
	# エラーチェック
	#---------------------------------------------------------------------------
	// 古いメールアドレス
	$error_message .= utilLib::strCheck($oldmail, 0, "古いメールアドレスを入力してください。<br>\n");
	if ( $oldmail ){

		// 未入力以外のエラーチェック
		$e_chk = "";
		$e_chk .= utilLib::strCheck($oldmail, 1, true);
		$e_chk .= utilLib::strCheck($oldmail, 4, true);
		$e_chk .= utilLib::strCheck($oldmail, 5, true);
		$e_chk .= utilLib::strCheck($oldmail, 6, true);

		if ($e_chk )	$error_message .= "古いメールアドレスに誤りがあります。<br>\n";

	}

	// パスワード
	$error_message .= utilLib::strCheck($pwd, 0, "パスワードを入力してください。<br>\n");

	// 新しいメールアドレス
	$error_message .= utilLib::strCheck($newmail, 0, "新しいメールアドレスを入力してください。<br>\n");
	if ( $newmail ){

		// 未入力以外のエラーチェック
		$e_chk = "";
		$e_chk .= utilLib::strCheck($newmail, 1, true);
		$e_chk .= utilLib::strCheck($newmail, 4, true);
		$e_chk .= utilLib::strCheck($newmail, 5, true);
		$e_chk .= utilLib::strCheck($newmail, 6, true);

		if ( $e_chk )	$error_message .= "新しいメールアドレスに誤りがあります。<br>\n";

	}

	#-----------------------------------------------------------------------
	# 基本チェックのここまで、エラーがなければ新しいメールアドレスが既に
	# 登録されているかチェック（不正登録チェック）
	#-----------------------------------------------------------------------
	if ( !$error_message ){

		$sql = "SELECT EMAIL FROM CUSTOMER_LST WHERE ( EMAIL = '$newmail' ) AND (ALPWD != '') AND ( DEL_FLG = '0' )AND(EXISTING_CUSTOMER_FLG = '1')";
		$fetchNewEmailChk = $PDO -> fetch($sql);
		if ( $fetchNewEmailChk )	$error_message .= "既に登録済みのメールアドレスです。<br>（新しいメールアドレス）<br>\n";
	}

	#---------------------------------------------------------------------------
	#　基本チェックのここまで、エラーがなければ認証チェック（ＤＢ：CUSTOMER_LST）
	#---------------------------------------------------------------------------
	if ( !$error_message ){

		$sql = "
		SELECT
			ALPWD,
			EMAIL
		FROM
			".CUSTOMER_LST."
		WHERE
			( ALPWD = '".utilLib::strRep($pwd,5)."' )
		AND
			( EMAIL = '$oldmail' )
		AND
			( DEL_FLG = '0' )
		AND
			(EXISTING_CUSTOMER_FLG = '1')
		";
		$fetchAuth = $PDO -> fetch($sql);

		// 認証チェック（ＯＫなら認証済みフラグをつける）
		if( ($fetchAuth[0]['ALPWD'] == $pwd) && ($fetchAuth[0]['EMAIL'] == $oldmail) ){
			$_SESSION['setParam']['emailChg_Auth_flg'] = 1;
		}
		else{
			$error_message .= "登録されていない情報です。<br>\n恐れ入りますが、もう一度入力してください<br>\n";
		}

	}

	#---------------------------------------------------------------------------
	# 取得データをセッションに格納
	#---------------------------------------------------------------------------
	$_SESSION['getParam']['oldmail'] = $oldmail;
	$_SESSION['getParam']['pwd']	 = $pwd;
	$_SESSION['getParam']['newmail'] = $newmail;

else:

	// 半角英数字の統一
	$newmail = mb_convert_kana($newmail, "a");

	// 新しいメールアドレス
	$error_message .= utilLib::strCheck($newmail, 0, "新しいメールアドレスを入力してください。<br>\n");
	if ( $newmail ){

		// 未入力以外のエラーチェック
		$e_chk = "";
		$e_chk .= utilLib::strCheck($newmail, 1, true);
		$e_chk .= utilLib::strCheck($newmail, 4, true);
		$e_chk .= utilLib::strCheck($newmail, 5, true);
		$e_chk .= utilLib::strCheck($newmail, 6, true);

		if( $e_chk )	$error_message .= "新しいメールアドレスに誤りがあります。<br>\n";

	}

	// セッションに格納
	$_SESSION['getParam']['newmail'] = $newmail;

endif;

?>
