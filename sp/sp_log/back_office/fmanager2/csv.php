<?php
/*******************************************************************************
アクセス解析ファイルマネージャー

リスト出力

*******************************************************************************/

session_start();
// 設定ファイル＆共通ライブラリの読み込み
require_once("../../sp/common/INI_logconfig.php");		// 設定ファイル
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once("dbOpe.php");					// SQLite操作クラスライブラリ
/*
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}
*/
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

#=============================================================================================
# CSV形式のファイルに保存する
#
# 現在の時間を取得し、list-日時.csvというファイル名にして出力する
#=============================================================================================

header("Content-Type: text/plain; charset=Shift_JIS");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list-".date("YmdHis").".csv");

$SQLITE = access_log_start($filename);

// 各項目のタイトルをつける
$data = "リモートホスト,リファラ,検索キーワード,検索エンジン,地域,OS,ブラウザ,URL,日付,時間\n";
$data = mb_convert_encoding($data,"SJIS","EUC-JP");

// 出力に必要なデータの取得
$sql = "
	SELECT
		REMOTE_ADDR,
		USER_AGENT,
		REFERER,
		QUERY_STRING,
		ENGINE,
		OS,
		BROWSER,
		PAGE_URL,
		UNIQUE_FLG,
		STATE,
		INS_DATE,
		TIME
	FROM
		ACCESS_LOG
	ORDER BY
		INS_DATE ASC,TIME ASC
";

$fetchLogList = $SQLITE->fetch($sql);

	// データの数だけループする。
	for($i=0;$i<count($fetchLogList);$i++):

		$data .= str_replace(",",".",$fetchLogList[$i]['REMOTE_ADDR']).",";
	//	$data .= str_replace(",",".",$fetchLogList[$i]['USER_AGENT']).",";
		$data .= str_replace(",",".",$fetchLogList[$i]['REFERER']).",";
		$data .= mb_convert_encoding(str_replace(",",".",$fetchLogList[$i]['QUERY_STRING']),"SJIS","EUC-JP").",";
		$data .= str_replace(",",".",$fetchLogList[$i]['ENGINE']).",";
		$data .= mb_convert_encoding(str_replace(",",".",$fetchLogList[$i]['STATE']),"SJIS","EUC-JP").",";
		$data .= str_replace(",",".",$fetchLogList[$i]['OS']).",";
		$data .= $fetchLogList[$i]['BROWSER'].",";
		$data .= str_replace(",",".",$fetchLogList[$i]['PAGE_URL']).",";
	//	$data .= str_replace(",",".",$fetchLogList[$i]['UNIQUE_FLG']).",";
		$data .= $fetchLogList[$i]['INS_DATE'].",";
		$data .= $fetchLogList[$i]['TIME']."\n";

	endfor;

echo $data;

?>
