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

date_default_timezone_set('Asia/Tokyo');//php 5.3.0から時間設定をしないと警告が出てしまうためここで設定をする。

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

#=================================================================================
# 表示件数が０件の場合文言表を示するファンクション
#=================================================================================

	//管理画面でフリーワード入力欄数の設定
	define('INPUT_WORDS','3');

	//データベースのテーブル名
	define('FREE_WORDS_DB','FREE_WORDS_DB');

	//表示する文言を配列で格納（テンプレート）
	$word_str = array(
	'ただいま準備中のため、もうしばらくお待ちください。',
	'このカテゴリの商品は完売いたしました。',
	'大好評につき、現在品切れとなっております。',
	'在庫切れです。再入荷までもうしばらくお待ちください。',
	'現在、商品を創作しています。もうしばらくお待ちください。',
	'現在、ご依頼は受け付けておりません。',
	'現在、このサービスは提供しておりません。',
	'このカテゴリの商品は完売いたしました。<br>申し訳ございませんが、再入荷までもうしばらくお待ちください。',
	'大好評につき、現在品切れとなっております。<br>次回の入荷に関しては新着情報にて随時お知らせしていきます。',
	'このカテゴリの商品は完売いたしました。<br>次回の入荷に関しては新着情報にて随時お知らせしていきます。',
	'大好評につき、現在品切れとなっております。<br>申し訳ございませんが、再入荷までもうしばらくお待ちください。'
	);

//表示処理
function display_words($num){

	require_once("dbOpe.php");				// ＤＢ操作クラスライブラリ
	require_once("util_lib.php");				// 汎用処理クラスライブラリ

	//文言配列のグローバル宣言を行う
	global $word_str;

	//入力の制限をここで行う
		$num = utilLib::strRep($num ,8);

		$num = utilLib::strRep($num ,7);

		$num = utilLib::strRep($num ,1);

		$num = utilLib::strRep($num ,4);

		$num = utilLib::strRep($num ,5);

	//選択されている文言の情報を取得する
		$sql = "
		SELECT
			 *

		FROM

			".WORDS_NUMBER_DB."
		WHERE
			(PG_ID = '".$num."')
		";

		$fetch_fw = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//テンプレートの表示かフリーワードの表示か判定をする
		if(strpos("_".$fetch_fw[0]['RES_ID'],"fw") == ""){
			//テンプレート用
			$tn = str_replace("tw","",$fetch_fw[0]['RES_ID']);
			$word = $word_str[$tn];//選択された文言を入れる

		}else{
			//フリーワード用
			$sql = "
			SELECT
				 *

			FROM

				".FREE_WORDS_DB."
			WHERE
				(PG_ID = '".$num."')
				AND
				(RES_ID = '".$fetch_fw[0]['RES_ID']."')
			";

			$fetch_ifw = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//もし何も入力されて無い場合標準の文言を表示させる
			if($fetch_ifw[0]['FREE_WORD']){
				$word = $fetch_ifw[0]['FREE_WORD'];
			}else{
				$word = $word_str[0];
			}

		}

	//ここでもう一度チェック、何も入ってない場合は標準の文言を表示させる
	if($word == ""){
		$word = $word_str[0];

		//配列の側で何も文言が無い場合はこちらの文言を表示
		if($word == ""){
			$word = "ただいま準備中のため、もうしばらくお待ちください。";
		}
	}

	//表示する文言を返す
	return nl2br($word);

}
?>