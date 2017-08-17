<?php
/*******************************************************************************
管理情報
入力データチェック
※エラー発生時はエラー通知文字列を$error_mesに格納

#チェック手順
1.半全角の変換
2.入力データ有無チェック
3.文字列長チェック

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

#===============================================================================
# エラーチェック
#===============================================================================

#エラー通知文字列格納用変数定義
$error_mes = "";

	// 半角英数字に統一
	$email 			= mb_convert_kana($email1,"a");
	$email2 		= mb_convert_kana($email2,"a");

	// メールアドレス1の入力チェック
	$error_mes .= utilLib::strCheck($email1,0,"メールアドレスを入力してください。\n");
	if(!empty($email1)){
		// 未入力以外のエラーチェック
		$e_chk = "";

		$e_chk .= utilLib::strCheck($email1,1,true);
		$e_chk .= utilLib::strCheck($email1,4,true);
		$e_chk .= utilLib::strCheck($email1,5,true);
		$e_chk .= utilLib::strCheck($email1,6,true);

		if($e_chk)$error_mes .= "メールアドレスに誤りがあります。<br>\n";

	}

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	mb_regex_encoding("UTF-8");//mb_ereg用にエンコードを指定
	if((mb_strlen($email1, 'UTF-8') != strlen($email1)) || mb_ereg("[ｱ-ﾝ]", $email1)){
		$error_message .= "メールアドレスに不正な文字が含まれております。";
	}

	// メールアドレス2の入力チェック
/*	$error_mes .= utilLib::strCheck($email2,0,"メールアドレス２を入力してください。\n");
	if(!empty($email2)){
		// 未入力以外のエラーチェック
		$e_chk = "";

		$e_chk .= utilLib::strCheck($email2,1,true);
		$e_chk .= utilLib::strCheck($email2,4,true);
		$e_chk .= utilLib::strCheck($email2,5,true);
		$e_chk .= utilLib::strCheck($email2,6,true);

		if($e_chk)$error_mes .= "メールアドレス２に誤りがあります。<br>\n";

	}

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	if((mb_strlen($email2, 'UTF-8') != strlen($email2)) || mb_ereg("[ｱ-ﾝ]", $email2)){
		$error_message .= "メールアドレス２に不正な文字が含まれております。";
	}
*/

	// 会社情報のエラーチェック
	$error_mes .= utilLib::strCheck($content,0,"自動返信メール表示用内容を入力してください。\n");

?>
