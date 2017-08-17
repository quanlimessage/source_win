<?php
/*******************************************************************************
ID PASSWORDでの会員用ページの表示（Ｍ１）

*******************************************************************************/

//ログインチェック回避のためにセッションにIDとパスを格納
session_start();

//ログアウト処理　セッションを破棄して戻る
if($_POST['state'] == 'log_out'){
	session_destroy();
	header("Location: ./");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config.php");	// 設定ファイル
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");			// 汎用処理クラスライブラリ

// POSTデータの受け取りと共通な文字列処理
if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4),true));}

#---------------------------------------------------------------
# 会員のログインチェック、ログオンの処理をする
#
#---------------------------------------------------------------

// エラーメッセージの初期化
$erro_mes = "";

$id = $_POST["id"];
$pass = $_POST["pass"];

//SESSIONの中身が無くてID PASSWORDを入力している場合
if(!($_SESSION["ID"] && $_SESSION["PASS"]) && (!empty($id) && !empty($pass))){

	//不正な入力を防ぐ
	$id = addslashes($id);
	$pass = addslashes($pass);

	//使用するID PASSWORDの設定はこちらのＳＱＬ文を変更
	$sql = "
		SELECT
			RES_ID
		FROM
			M1_PRODUCT_LST
		WHERE
			(ID = '".$id."')
		AND
			(PW = '".$pass."')
		AND
			(DEL_FLG = '0')
			";

	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if(!($fetch[0]['RES_ID'])){//取得できてない場合はエラー判定
		$erro_mes  = "IDかPASSWORDが間違っています。再度入力してください。\n<br><br>";
	}else{//ID PASSWORDの入力が合っている場合
		$_SESSION["ID"] = $id;
		$_SESSION["PASS"] = $pass;
	}

}elseif($state == 'log_in'){//最初に訪れた場合は警告文を表示しない

	$erro_mes = "IDとPASSWORDを入力してください。\n<br><br>";

}

?>