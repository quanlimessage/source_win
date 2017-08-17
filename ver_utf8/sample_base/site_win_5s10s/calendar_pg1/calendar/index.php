<?php
/***********************************************************
カレンダープログラム
***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_calendar.php');
require_once('util_lib.php');
require_once('dbOpe.php');

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

#--------------------------------------------------------------------------------
# GET受信データの処理
#--------------------------------------------------------------------------------
// GETデータの受け取りと共通な文字列処理
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));

// 年月のデータがなければ今年の今月
if(empty($y)){
	$y = date('Y');
}

if(empty($m)){
	$m = date('n');
}

if($m >= 13){
	$y = $y + 1;
	$m = $m - 12;
}

// カレンダー表示
require_once 'Calendar/Month/Weekdays.php';

// インスタンスを生成（第3引数に“0”を指定すると月曜始まりにできる。省略時：日曜始まり）
$Month = new Calendar_Month_Weekdays($y,$m,0);
$Month->build();

// データベース情報を取得して表示する
$sql = "
SELECT
	SCHEDULE_ID,
	DAY_1,
	DAY_2,
	DAY_3,
	DAY_4,
	DAY_5,
	DAY_6,
	DAY_7,
	DAY_8,
	DAY_9,
	DAY_10,
	DAY_11,
	DAY_12,
	DAY_13,
	DAY_14,
	DAY_15,
	DAY_16,
	DAY_17,
	DAY_18,
	DAY_19,
	DAY_20,
	DAY_21,
	DAY_22,
	DAY_23,
	DAY_24,
	DAY_25,
	DAY_26,
	DAY_27,
	DAY_28,
	DAY_29,
	DAY_30,
	DAY_31,
	COMM_1,
	COMM_2,
	COMM_3,
	COMM_4,
	COMM_5,
	COMM_6,
	COMM_7,
	COMM_8,
	COMM_9,
	COMM_10,
	COMM_11,
	COMM_12,
	COMM_13,
	COMM_14,
	COMM_15,
	COMM_16,
	COMM_17,
	COMM_18,
	COMM_19,
	COMM_20,
	COMM_21,
	COMM_22,
	COMM_23,
	COMM_24,
	COMM_25,
	COMM_26,
	COMM_27,
	COMM_28,
	COMM_29,
	COMM_30,
	COMM_31
FROM
	".SCHEDULE."
WHERE
	(YEAR = '$y') AND (MONTH = '$m') AND (DEL_FLG = '0')
";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//次に紐づくであろうEVENTテーブルを取得する。

$sql = "
	SELECT
		ID,
		TITLE,
		DAY
	FROM
		".EVENT."
	WHERE
		(SCHEDULE_ID = '" . $fetch[0]["SCHEDULE_ID"]  . "')
		AND
		(DEL_FLG = '0')

";
$Eventfetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//配列の宣言 データを扱いやすいように配列に入れなおす。
$Event = array();

for($i = 0 ; $i < count($Eventfetch); $i++){
	$idx = $Eventfetch[$i]["DAY"];

	$Event[$idx]["ID"] = $Eventfetch[$i]["ID"];
	$Event[$idx]["TITLE"] = $Eventfetch[$i]["TITLE"];
}

// 取得件数分のデータをHTML出力
include("DISP_data.php");

?>
