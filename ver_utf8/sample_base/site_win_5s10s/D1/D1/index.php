<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
D系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_D1.php');
require_once('util_lib.php');
require_once('dbOpe.php');

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

// ページ遷移開始位置データの初期設定（$_GET["ns"]に格納）
$start = (isset($_GET["ns"]) && is_numeric($_GET["ns"]))?$_GET["ns"]:0;

// DBよりデータを取得（開始位置と終了位置を指定）
$sql = "
SELECT
	RES_ID,TITLE,CONTENT
FROM
	D1_OUTPUT
WHERE
	(DEL_FLG = '0')
AND
	(DISPLAY_FLG = '1')
ORDER BY
	VIEW_ORDER ASC
LIMIT
	".$start." , ".D1_DISP_MAXROW."
";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// DBより現在の登録件数を取得（カウント）
$sql = "SELECT COUNT(*) AS CNT FROM D1_OUTPUT WHERE(DISPLAY_FLG = '1')AND(DEL_FLG = '0')";
$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// 取得件数分のデータをHTML出力
include("DISP_listview.php");
?>