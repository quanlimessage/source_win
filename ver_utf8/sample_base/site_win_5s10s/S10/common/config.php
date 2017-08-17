<?php
/*******************************************************************************
SiteWin10 20 30（MySQL版）
共通設定情報ファイルの定義

*******************************************************************************/

#=================================================================================
# マルチバイト関数用に言語と文字コードを指定する（文字列置換、メール送信等で必須）
#=================================================================================
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//エラーメッセージの表示の時エンコードがsjisで文字化けをするのを回避
	header("Content-Type: text/html; charset=UTF-8");
	header("Content-Language: ja");

#=================================================================================
#  本番アップ後の処理 ※本番アップする際に以下の記述をする事！
#=================================================================================
// demoURLへのアクセス 0：アクセス許可（制作時は“0”） ／ 1：許可しない（本番アップ後は“1”にする）
$siteopne_flg = 0;

// 本番のURL
$domain = "http://samplepg.zeeksdg.net/site10/back_office/";

#=================================================================================
# ＤＢ接続の情報（定数化）
#=================================================================================
define('DB_SERVER','localhost');
define('DB_NAME','site10_db');
define('DB_USER','site10_db');
define('DB_PASS','371VtfE7');
define('DSN',"mysql://site10_db:371VtfE7@localhost/site10_db");	// PEAR用

#=================================================================================
# 管理画面のIDとパスワード
#=================================================================================
define('BO_ID','test');		# ID
define('BO_PW','pass');		# PW

#=================================================================================
# 頻繁に行う簡易処理のファンクション化（匿名関数）
#=================================================================================
// UNIX時間＋マイクロ秒によるID生成
$makeID = create_function('','return date("U")."-".sprintf("%06d",(microtime() * 1000000));');

// パスワード作成
$makePass = create_function('','$pass = crypt(mt_rand(0,99999999),"CP");return ereg_replace("[^a-zA-Z0-9]","",$pass);');

#=================================================================================
# “htmlspecialchars()”でエンティティ化したHTML特殊文字を
#	Flashで正常表示できるように全角に変換するファンクション
#=================================================================================
function h14s_han2zen(&$str){

	$str = str_replace("&amp;","＆",$str);
	$str = str_replace("&quot;","”",$str);
	$str = str_replace("&lt;","＜",$str);
	$str = str_replace("&gt;","＞",$str);
	$str = str_replace("&#39","’",$str);
	$str = str_replace("'","’",$str);
	$str = str_replace("&","＆",$str);
	$str = str_replace("%","%25",$str);
	$str = str_replace("+","%2b",$str);

	return $str;
}

#=================================================================================
# 管理情報テーブルよりメールアドレスを取得するファンクション
#	メソッド名：getInitData("カラム名")
#=================================================================================
function getInitData($colum = ""){

	if(!$colum)$colum = "EMAIL1";

	$sql = "SELECT {$colum} FROM APP_INIT_DATA WHERE(RES_ID = '1')";
	$con = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME,$con);

	if($query = mysql_query($sql)):

		$fetch = mysql_result($query,0,$colum);
		mysql_free_result($query);
		mysql_close($con);

		return $fetch;

	endif;

}

#=================================================================================
# 転送処理を行うファンクション（引数：対象URL、戻り値：なし）
#=================================================================================
function location($url){

echo '<html><head><title></title></head>',
'<body onLoad="Javascript:document.location.submit();">',
'<form name="location" action="'.$url.'" method="post">',
'</form></body></html>',
'<noscript>header("Location: '.$url.'");</noscript>';
exit();
}
?>