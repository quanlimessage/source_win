<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	入力情報チェック

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4,5),true));

// 不正パラメーターチェック(修正する顧客ID)
if( !isset($_POST['customer_id']) || !ereg("^([0-9]{10,})-([0-9]{6})$", $_POST['customer_id']) ){
	header("Location: ./");	exit();
}

#------------------------
# 入力文字列変換
#------------------------

// 半角英字に統一
$alpwd = mb_convert_kana($alpwd,"a");
$email = mb_convert_kana($email,"a");

// 半角数字に統一
$tel1	= mb_convert_kana($tel1,"n");
$tel2		= mb_convert_kana($tel2,"n");
$tel3	= mb_convert_kana($tel3,"n");
$zip1	= mb_convert_kana($zip1,"n");
$zip2	= mb_convert_kana($zip2,"n");
$state = mb_convert_kana($state,"n");

// 半角カナ→全角カナ
$last_name	= mb_convert_kana($last_name,"KV");
$first_name		= mb_convert_kana($first_name,"KV");
//$last_kana		= mb_convert_kana($last_kana,"KV");
//$first_kana		= mb_convert_kana($first_kana,"KV");
$address1		= mb_convert_kana($address1,"KV");
$address2		= mb_convert_kana($address2,"KV");

#------------------------
# 文字データエラーチェック
#------------------------
$error_message = "";

/* 文字列チェック */

// パスワード
$error_message .= utilLib::strCheck($alpwd,0,"パスワードを入力してください。\n");
if(strlen($alpwd)>PW_LIMIT_STR){$error_message.="パスワードが長過ぎます。<br>\n";}

// 名字(漢字)
$error_message .= utilLib::strCheck($last_name,0,"名字(漢字)を入力してください。\n");
//if(strlen($last_name)>30){$error_message.="名字(漢字)が長過ぎます。<br>\n";}

// 名前(漢字)
$error_message .= utilLib::strCheck($first_name,0,"名前(漢字)を入力してください。\n");
//if(strlen($first_name)>30){$error_message.="名前(漢字)が長過ぎます。<br>\n";}

// 名字(かな)
$error_message .= utilLib::strCheck($last_kana,0,"名字(かな)を入力してください。\n");
//if(strlen($last_kana)>30){$error_message.="名字(かな)が長過ぎます。<br>\n";}

// 名前(かな)
$error_message .= utilLib::strCheck($first_kana,0,"名前(かな)を入力してください。\n");
//if(strlen($first_kana)>30){$error_message.="名前(かな)が長過ぎます。<br>\n";}

// メールアドレス
$error_message .= utilLib::strCheck($email,0,"メールアドレスを入力してください。\n");
if(strlen($email)>100){$error_message.="メールアドレスが長過ぎます。<br>\n";}
if(!empty($email)){
	// 未入力以外のエラーチェック
	$e_chk = "";
	$e_chk .= utilLib::strCheck($email,1,true);
	$e_chk .= utilLib::strCheck($email,4,true);
	$e_chk .= utilLib::strCheck($email,5,true);
	$e_chk .= utilLib::strCheck($email,6,true);

	if($e_chk)$error_message .= "Eメールアドレスに誤りがあります。<br>\n";
}

//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	mb_regex_encoding("UTF-8");//mb_ereg用にエンコードを指定
if((mb_strlen($email, 'UTF-8') != strlen($email)) || mb_ereg("[ｱ-ﾝ]", $email)){
	$error_message .= "メールアドレスに不正な文字が含まれております。<br>";
}

// 電話番号チェック
	if(ereg("[^0-9]",$tel1) or ereg("[^0-9]",$tel2) or ereg("[^0-9]",$tel3)){
	$error_message.="電話番号を正しく入力してください。<br>\n";
	}
/*
if(!eregi("^[0-9]{1,3}[0-9]$",$tel1) or !eregi("^[0-9]{1,3}[0-9]$",$tel2) or !eregi("^[0-9]{1,3}[0-9]$",$tel3)){
	$error_message.="電話番号を正しく入力してください。<br>\n";
}
*/
// 郵便番号チェック
	if(ereg("[^0-9]",$zip1) or ereg("[^0-9]",$zip2)){
	$error_message.="郵便番号を正しく入力してください。<br>\n";
	}
/*
if(!eregi("^[0-9]{2,2}[0-9]$",$zip1) or !eregi("^[0-9]{3,3}[0-9]$",$zip2)){
	$error_message.="郵便番号を正しく入力してください。<br>\n";
}
*/
// 都道府県番号
$error_message .= utilLib::strCheck($state,0,"都道府県指定が不正です。\n");

// 住所1
$error_message .= utilLib::strCheck($address1,0,"住所1を入力してください。\n");
//if(strlen($address1)>30){$error_message.="住所1が長過ぎます。<br>\n";}

// 住所2
/*
$error_message .= utilLib::strCheck($address2,0,"住所2を入力してください。\n");
if(strlen($address2)>30){$error_message.="住所2が長過ぎます。<br>\n";}
*/
?>
