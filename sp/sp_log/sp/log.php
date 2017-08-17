<?php
/*******************************************************************************

	ログ取得用ファイル DB保存用
	アクセス解析をしたいページに貼ってある
	インクルード用タグが読み込まれたらこのファイルが実行される

	SQLite版

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("./common/INI_logconfig.php");		// 設定ファイル
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once("dbOpe.php");				// SQLite操作クラスライブラリ
require_once("envOpe.php");					// 環境変数取得ライブラリ

#---------------------------------------------------------------------------------
# ユーザー環境の取得
# 	１．IPアドレス取得
# 	２．環境変数取得ライブラリで総合判定したUA情報を取得 ※戻り値の$result[1]を使用する
#		３．OS情報の取得       ※戻り値の$result[2]と$result[3]を使用する
#   ４．ブラウザ情報の取得 ※戻り値の$result[4]と$result[5]を使用する
#
#---------------------------------------------------------------------------------
$ip = $_SERVER["REMOTE_ADDR"];

/* OS.ブラウザ情報取得生ログ用 */
$agent = $_SERVER["HTTP_USER_AGENT"];

$e = new UA_Info();
$result = $e->getNavInfo();

$os      = $result[2] . $result[3];
$browser = $result[4] . $result[5];

#-------------------------------------------------------------------------------------------------------
# リンク元の各情報を取得(parse_url)
#		※ 設置するHTMLファイルからimgタグでの読込み時に
#		GETパラメータに付加してあるJavaScriptで取得したリファラー情報
#		$_GET["referrer"]からリンク元を取得
#				(通常通りリファラーを参照しても、設置するHTMLファイルの情報が入ってしまうため)
#--------------------------------------------------------------------------------------------------------

// 現サイトのホストを取得
$host_addr = $_SERVER["HTTP_HOST"];
//$host_addr = "all-internet.jp";

// リファラーのURL情報をparse_urlで配列にセット
$ref_info	= parse_url($_GET["referrer"]);

// リンク元URL取得 ※リンク元が取得できてる場合のみ、URL文字列整形
if(!empty($_GET["referrer"])){
	$ref_url	= $ref_info["scheme"]."://".$ref_info["host"].$ref_info["path"];

	// リンク元に現サイトのホスト名が入っていればリファラーをとらない
	$str = strstr($ref_url , $host_addr);

	// $strに値があればリファラーを空にして格納しない
	if($str)$ref_url = "";
}

#----------------------------------------------------------------------------------------------------------------------
# 県名情報
#----------------------------------------------------------------------------------------------------------------------
$state = ($_GET["st_id_obj"])?mb_convert_encoding(urldecode($_GET["st_id_obj"]),"EUC-JP","auto"):"";
$state = ($state == "Hokkaido")?"北海道":$state;
$state = ($state == "Kanagawa")?"神奈川県":$state;

#----------------------------------------------------------------------------------------------------------------------
# 現ファイル情報を取得(parse_url)
#		※ 設置したHTMLファイル情報を取得するには
#		この取得ファイルlog.phpのリファラー情報を通常通り参照すればよい
#		リンク元の情報(インクルード元の情報)つまり、JavaScriptを使用して設置した現HTMLファイル情報が取得できる
#----------------------------------------------------------------------------------------------------------------------
$file_info	= parse_url($_SERVER['HTTP_REFERER']);

// index.html,index.php,index.cfmは外す
$file_path = str_replace("/index.html","/",$file_info["path"]);
$file_path = str_replace("/index.php","/",$file_path);
$file_path = str_replace("/index.cfm","/",$file_path);

$url_q = (empty($file_info["query"]))?"":"?".$file_info["query"];

$fname		= $file_info["scheme"]."://".$file_info["host"].$file_path.$url_q;		// クエリ文字列を含むURLを取得

$filename = str_replace(".","_",$fname);

// リファラーの文字列取得
$refe = $_GET["referrer"];

// クエリ文字列取得（必ずurlデコードし、文字コードを統一）
$query = mb_convert_encoding(urldecode($ref_info["query"]),"EUC-JP","auto");

#----------------------------------------------------------------------------------------------------------------------
# ユニークアクセスの取得
#	・アクセスしてきたユーザーに対してURL名でクッキー情報を格納($_COOKIE["URL名"])
#   ・$_COOKIE["URL"]が存在するかどうかでユニークアクセスユーザーを取得する
#   ・始めてのアクセスなら$uniqueを１、２回目以降なら$uniqueを２にセットする
#   ・COOKIEの有効期限は3時間とする。
#----------------------------------------------------------------------------------------------------------------------
if($_COOKIE[$filename]!=$filename){
	$value = $filename;
	$expire = time() + 3600*3;
	setcookie($filename, $value, $expire);
	$unique = 1;
}else{
	$unique = 2;
}

#----------------------------------------------------------------------------------------------------------------------
# 訪問者数の取得
#	・アクセスしてきたユーザーに対してクッキー情報を格納
#   ・$_COOKIE['UNIQUE_USER']が存在するかどうかでユニークアクセスユーザーを取得する
#   ・始めてのアクセスなら$unique_userを１、２回目以降なら$unique_userを２にセットする
#   ・COOKIEの有効期限は3時間とする。
#----------------------------------------------------------------------------------------------------------------------
if(!isset($_COOKIE["UNIQUE_USER"])){
	$cookie = "UNIQUE_USER";
	$value = "visited";
	$expire = time() + 3600*3;
	setcookie($cookie, $value, $expire);
	$unique_user = 1;
}else{
	$unique_user = 2;
}

#============================================
# 検索エンジン取得&検索キーワード取得
#============================================

//リスト読み込み
$list_fn = "../back_office/list/engine.txt";
if(file_exists($list_fn)) $engine_list = setting_read($list_fn);
unset($list_fn);

#====================================================================================
# get_keyword関数で検索キーワードを取得
# listフォルダにあるengine.txtファイルより$fnameとマッチする検索エンジンのURLを取得
# $eng["name"]  // 検索エンジン名
# $eng["q"]     // 検索キーワードのkey名 googleだったら「p=%E8%A7%A3%E6%」のｐの部分
# $eng["uri"]   // 検索エンジンのURL msnだったらsearch.msn.co.jp
#====================================================================================

foreach($engine_list as $list){

	unset($eng);

	list($eng["name"],$eng["q"],$eng["uri"]) = explode("||",$list);
		if(eregi("($eng[uri])",$refe)){

				//キーワード取得
				$keyword = get_keyword($query ,$eng["q"]);

				// 検索エンジン名
        $engine = $eng["name"];
		}
}

// 検索キーワード
$query = $keyword;

#=================================================================================
# 各データのDB登録
#=================================================================================

	$date_now = date("Y-m-d");
	$time_now = date("H:i:s");

	$sql_ins = "
	INSERT INTO ACCESS_LOG(
		REMOTE_ADDR,
		USER_AGENT,
		REFERER,
		QUERY_STRING,
		ENGINE,
		OS,
		BROWSER,
		PAGE_URL,
		STATE,
		UNIQUE_FLG,
		USER_FLG,
		INS_DATE,
		TIME
	)
	VALUES(
		'".utilLib::strRep($ip,5)."',
		'".utilLib::strRep($agent,5)."',
		'".utilLib::strRep($ref_url,5)."',
		'".utilLib::strRep($query,5)."',
		'".utilLib::strRep($engine,5)."',
		'".utilLib::strRep($os,5)."',
		'".utilLib::strRep($browser,5)."',
		'".utilLib::strRep($fname,5)."',
		'".utilLib::strRep($state,5)."',
		'".utilLib::strRep($unique,5)."',
		'".utilLib::strRep($unique_user,5)."',
		'$date_now',
		'$time_now'
	)
	";
// SQL実行
if( (!empty($sql_ins)) && ($fname != "://") ){
	$SQLITE = access_log_start();
	$SQLITE -> regist($sql_ins);
}

/*
echo nl2br(print_r($_COOKIE , true));
echo "UNIQU : ".$unique."<br>\n";
echo "USER : ".$unique_user."<br>\n";
*/
?>
