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
	$email 			= mb_convert_kana($email,"a");

	// 半角カナ→全角カナ
	$name			= mb_convert_kana($name,"KV");
	$conpamy_info	= mb_convert_kana($company_info,"KV");
	$bank_info		= mb_convert_kana($bank_info,"KV");
	$post_info		= mb_convert_kana($post_info,"KV");
	$shopping_title	= mb_convert_kana($shopping_title,"KV");
	$bo_title		= mb_convert_kana($bo_title,"KV");

	// 会社名確認
	$error_mes .= utilLib::strCheck($name,0,"御社名を入力してください。\n");
	if(strlen($name)>100){$error_mes="御社名の文字列が長過ぎます。<br>\n";}

	// メールアドレスの入力チェック
	$error_mes .= utilLib::strCheck($email,0,"ショップ用Eメールアドレスを入力してください。\n");
	if(!empty($email)){
		// 未入力以外のエラーチェック
		$e_chk = "";
		$e_chk .= utilLib::strCheck($email,1,true);
		$e_chk .= utilLib::strCheck($email,4,true);
		$e_chk .= utilLib::strCheck($email,5,true);
		$e_chk .= utilLib::strCheck($email,6,true);

		if($e_chk)$error_mes .= "ショップ用Eメールアドレスに誤りがあります。<br>\n";

	}

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	mb_regex_encoding("UTF-8");//mb_ereg用にエンコードを指定
	if((mb_strlen($email, 'UTF-8') != strlen($email)) || mb_ereg("[ｱ-ﾝ]", $email)){
		$error_message .= "ショップ用Eメールアドレスに不正な文字が含まれております。";
	}

	// 会社情報のエラーチェック
	$error_mes .= utilLib::strCheck($conpamy_info,0,"メール表示用会社情報を入力してください。\n");
	if(mb_strlen($conpamy_info) > 2000){$error_mes .= "メール表示用会社情報の文字数をお減らしください。<br>\n";}

	// 銀行情報のエラーチェック(必須ではない)
	if(mb_strlen($bank_info) > 2000){$error_mes .= "メール表示用銀行口座情報の文字数をお減らしください。<br>\n";}

	/*
	// ショッピングページタイトル
	$error_mes .= utilLib::strCheck($shopping_title,0,"ショッピングページタイトルを入力してください。\n");
	if(strlen($shopping_title)>100){$error_mes .= "ショッピングページタイトルの文字列が長過ぎます。<br>\n";}

	// 管理画面タイトル
	$error_mes .= utilLib::strCheck($bo_title,0,"管理画面タイトルを入力してください。\n");
	if(strlen($bo_title)>100){$error_mes .= "管理画面タイトル文字列が長過ぎます。<br>\n";}
	*/
?>
