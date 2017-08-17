<?php
/*******************************************************************************
アクセス解析

	アクセス解析表示データ取得用ＤＢアクセス
	表示条件によってデータベース表示を変更

	SQLite対応版

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("Location: ../err.php");exit();
}

// POSTデータの受け取りと共通な文字列処理
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

if(empty($term)){
$dbh = new sqliteOpe(DB_FILEPATH,CREATE_SQL);
}else{
$db_filepath = ACCESS_PATH.$term.FILE_SUFFIX;
$dbh = new sqliteOpe($db_filepath,CREATE_SQL);
}

#---------------------------------------------------------------
# 旧データの削除(4ヶ月以上経過したもの)
#---------------------------------------------------------------
/*$DEL_limit = date('Ym', mktime(0,0,0,(date("n")-4),date("j"),date("Y")));

$del_sql = "
DELETE FROM
	" . ACCESS_LOG . "
WHERE
	( strftime('%Y%m', INS_DATE) <= '$DEL_limit' )
";
$delResult = $dbh->regist($del_sql);
if($delResult)die("データ削除に失敗しました<br>\n{$delResult}<br>\n");
*/
$i = 6;
while(1){
	//$DEL_limit = date('Y_m', mktime(0,0,0,(date("n")-$i),date("j"),date("Y")));
	$DEL_limit = date('Y_m', mktime(0,0,0,(date("n")-$i),'1',date("Y")));

	if(file_exists(ACCESS_PATH.$DEL_limit.FILE_SUFFIX)){
		unlink(ACCESS_PATH.$DEL_limit.FILE_SUFFIX) or die("旧データの削除に失敗しました。");
	}
	else{
		break;
	}
	$i++;
}

#---------------------------------------------------------------
# データ取得
#---------------------------------------------------------------

// 全データ取得
$total_sql = "
SELECT
	ID
FROM
 " . ACCESS_LOG . "
".$where_term."
";
$fetch = $dbh->fetch($total_sql);

//if($where_term){$total_u_sql =  $total_sql."AND (UNIQUE_FLG == '1')";}else{$total_u_sql = $total_sql."WHERE (UNIQUE_FLG == '1')";}

//$fetch_u = $dbh->fetch($total_u_sql);

if($where_term){$total_uu_sql =  $total_sql."AND (USER_FLG == '1')";}else{$total_uu_sql = $total_sql."WHERE (USER_FLG == '1')";}

$fetch_uu = $dbh->fetch($total_uu_sql);

// 今日のアクセス件数
$today_day_time = date('Ymd', mktime(0,0,0,date("n"),date("j"),date("Y")));

$today_sql = "
SELECT
	ID
FROM
 " . ACCESS_LOG . "
WHERE
	( strftime('%Y%m%d', INS_DATE) = '".$today_day_time."')
";
$TodayCnt = $dbh->fetch($today_sql);

#---------------------------------------------------------------
# 各データ取得関数の定義
#---------------------------------------------------------------
// 日別アクセス数取得
function day_access($where_term,$dbins){
	$day_sql = "
	SELECT
		INS_DATE,
		strftime('%Y', INS_DATE) AS Y,
		strftime('%m', INS_DATE) AS M,
		strftime('%d', INS_DATE) AS D,
		count(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%Y%m%d', INS_DATE)
	ORDER BY
		strftime('%Y%m%d', INS_DATE) DESC
	";

	$fetch_day = $dbins->fetch($day_sql);
	return $fetch_day;
}

// 日別ユニークアクセス数取得
function day_u_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$day_u_sql = "
	SELECT
		INS_DATE,
		strftime('%Y', INS_DATE) AS Y,
		strftime('%m', INS_DATE) AS M,
		strftime('%d', INS_DATE) AS D,
		count(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%Y%m%d', INS_DATE)
	ORDER BY
		strftime('%Y%m%d', INS_DATE) DESC
	";

	$fetch_day_u = $dbins->fetch($day_u_sql);
	return $fetch_day_u;
}

// 日別訪問者数取得
function day_uu_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (USER_FLG == '1')";}else{$where_term .= "WHERE (USER_FLG == '1')";}

	$day_uu_sql = "
	SELECT
		INS_DATE,
		strftime('%Y', INS_DATE) AS Y,
		strftime('%m', INS_DATE) AS M,
		strftime('%d', INS_DATE) AS D,
		count(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%Y%m%d', INS_DATE)
	ORDER BY
		strftime('%Y%m%d', INS_DATE) DESC
	";

	$fetch_day_uu = $dbins->fetch($day_uu_sql);
	return $fetch_day_uu;
}

// 月別アクセス取得関数
function mon_access($where_term,$dbins){
	$mon_sql = "
	SELECT
		INS_DATE,
		strftime('%m', INS_DATE) AS M,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1〜12)をインデックスキーに置き換え(表示用に12個要素配列に)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt[$key] = $v["CNT"];
	}
	return $MonCnt;
}

// 月別ユニークアクセス取得関数
function mon_u_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$mon_sql = "
	SELECT
		INS_DATE,
		strftime('%m', INS_DATE) AS M,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1〜12)をインデックスキーに置き換え(表示用に12個要素配列に)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt_u[$key] = $v["CNT"];
	}
	return $MonCnt_u;
}

// 月別訪問者数取得関数
function mon_uu_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (USER_FLG == '1')";}else{$where_term .= "WHERE (USER_FLG == '1')";}

	$mon_sql = "
	SELECT
		INS_DATE,
		strftime('%m', INS_DATE) AS M,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1〜12)をインデックスキーに置き換え(表示用に12個要素配列に)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt_uu[$key] = $v["CNT"];
	}
	return $MonCnt_uu;
}

// 時間別アクセス数取得
function hour_access($where_term,$dbins){
	$time_sql = "
	SELECT
		strftime('%H', TIME) AS TIME,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time[$key] = $v["CNT"];
	}

	return $fetch_time;
}

// 時間別ユニークアクセス数取得
function hour_u_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$time_sql = "
	SELECT
		strftime('%H', TIME) AS TIME,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time_u[$key] = $v["CNT"];
	}

	return $fetch_time_u;
}

// 時間別訪問者数取得
function hour_uu_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (USER_FLG == '1')";}else{$where_term .= "WHERE (USER_FLG == '1')";}

	$time_sql = "
	SELECT
		strftime('%H', TIME) AS TIME,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1〜24)をインデックスキーに置き換え(表示用に24個要素配列に)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time_uu[$key] = $v["CNT"];
	}

	return $fetch_time_uu;
}

// ページ別アクセス数取得
function page_access($where_term,$dbins){
	$url_sql = "
	SELECT
		PAGE_URL,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		PAGE_URL
	ORDER BY
		CNT DESC
	";

	$fetchURL = $dbins->fetch($url_sql);

	return $fetchURL;
}

// ページ別ユニークアクセス数取得
function page_u_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$url_sql = "
	SELECT
		PAGE_URL,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		PAGE_URL
	ORDER BY
		CNT DESC
	";

	$fetchURL_u = $dbins->fetch($url_sql);

	return $fetchURL_u;
}

// 検索エンジン数取得
function engine_access($where_term,$dbins){
	$engine_sql = "
	SELECT
		ENGINE,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		ENGINE
	HAVING
   (ENGINE != \"\")
	ORDER BY
		CNT DESC
	";

	$fetchENGINE = $dbins->fetch($engine_sql);

	return $fetchENGINE;
}

// 検索文字列取得
function access_query($where_term,$dbins,$kensu){
	$q_sql = "
	SELECT
		ENGINE,QUERY_STRING,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		QUERY_STRING, ENGINE
	HAVING
   (QUERY_STRING != \"\")
	ORDER BY
		CNT DESC
	";

	if($kensu == 1)$q_sql .= " LIMIT 0,100";
	elseif($kensu == 2)$q_sql .= " LIMIT 0,300";

	$fetchQuery = $dbins->fetch($q_sql);

	return $fetchQuery;
}

// 曜日別アクセス数取得
function dayofweek_access($where_term,$dbins){
	$dayofweek_sql = "
	SELECT
		strftime('%w', INS_DATE) AS DAYOFWEEK,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek[$key] = $v["CNT"];
	}

	return $fetch_dayofweek;
}

// 曜日別ユニークアクセス数取得
function dayofweek_u_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$dayofweek_sql = "
	SELECT
		strftime('%w', INS_DATE) AS DAYOFWEEK,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek_u[$key] = $v["CNT"];
	}

	return $fetch_dayofweek_u;
}

// 曜日別訪問者数取得
function dayofweek_uu_access($where_term,$dbins){

	if($where_term){$where_term .= "AND (USER_FLG == '1')";}else{$where_term .= "WHERE (USER_FLG == '1')";}

	$dayofweek_sql = "
	SELECT
		strftime('%w', INS_DATE) AS DAYOFWEEK,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0〜6)をインデックスキーに置き換え(表示用に7個要素配列に)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek_uu[$key] = $v["CNT"];
	}

	return $fetch_dayofweek_uu;
}

// ブラウザ取得用
function bro_access($where_term,$dbins){
	$bro_sql = "
	SELECT
		BROWSER,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		BROWSER
	ORDER BY
		CNT DESC
	";

	$fetch_bro = $dbins->fetch($bro_sql);
	return $fetch_bro;
}

// OS取得用
function os_access($where_term,$dbins){
	$os_sql = "
	SELECT
		OS,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		OS
	ORDER BY
		CNT DESC
	";

	$fetch_os = $dbins->fetch($os_sql);
	return $fetch_os;
}

// リファラー取得用
function ref_access($where_term,$dbins){

if($where_term){$where_term .= " AND ( REFERER != \"\" )";}else{$where_term = "WHERE ( REFERER != \"\" )";}

	$ref_sql = "
	SELECT
		REFERER,
		COUNT(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		REFERER
	ORDER BY
		CNT DESC
	LIMIT 0 , 10
	";

	$fetch_ref = $dbins->fetch($ref_sql);

	return $fetch_ref;
}

// 県別ユニークアクセス数取得
function state_access($where_term,$dbins){

	// if($where_term){$where_term .= "AND (UNIQUE_FLG == '1')";}else{$where_term .= "WHERE (UNIQUE_FLG == '1')";}

	$state_u_sql = "
	SELECT
		STATE,
		count(*) AS CNT
	FROM
		" . ACCESS_LOG . "
	".$where_term."
	GROUP BY
		STATE
	HAVING
		(STATE != \"\")
	ORDER BY
		CNT DESC
	";

	$fetch_state_u = $dbins->fetch($state_u_sql);
	return $fetch_state_u;
}

// SQL実行
if(empty($term)){
$dbh3 = new sqliteOpe(DB_FILEPATH,CREATE_SQL);
}else{
$db_filepath = ACCESS_PATH.$term.FILE_SUFFIX;
$dbh3 = new sqliteOpe($db_filepath,CREATE_SQL);
}

if(empty($kensu))$kensu = 1;

switch ($_POST["mode"]):
	case "day":
			$fetch_day = day_access($where_term,$dbh3);
			//$fetch_day_u = day_u_access($where_term,$dbh3);
			$fetch_day_uu = day_uu_access($where_term,$dbh3);
		break;
	case "month":
			$MonCnt = mon_access($where_term,$dbh3);
			//$MonCnt_u = mon_u_access($where_term,$dbh3);
			$MonCnt_uu = mon_uu_access($where_term,$dbh3);
		break;
	case "hour":
			$fetch_time = hour_access($where_term,$dbh3);
			//$fetch_time_u = hour_u_access($where_term,$dbh3);
			$fetch_time_uu = hour_uu_access($where_term,$dbh3);
		break;
	case "youbi":
			$fetch_dayofweek = dayofweek_access($where_term,$dbh3);
			//$fetch_dayofweek_u = dayofweek_u_access($where_term,$dbh3);
			$fetch_dayofweek_uu = dayofweek_uu_access($where_term,$dbh3);
		break;
	case "page":
			$fetchURL = page_access($where_term,$dbh3);
			//$fetchURL_u = page_u_access($where_term,$dbh3);
		break;
	case "engine":
			$fetchENGINE = engine_access($where_term,$dbh3);
		break;
	case "query":
			$fetchQuery = access_query($where_term,$dbh3,$kensu);
		break;
	case "bro":
			$fetch_bro = bro_access($where_term,$dbh3);
		break;
	case "os":
			$fetch_os = os_access($where_term,$dbh3);
		break;
	case "ref":
			$fetch_ref = ref_access($where_term,$dbh3);
		break;
	case "state":
			$fetch_state_u = state_access($where_term,$dbh3);
		break;
	case "all":
			$fetch_day = day_access($where_term,$dbh3);
			//$fetch_day_u = day_u_access($where_term,$dbh3);
			$fetch_day_uu = day_uu_access($where_term,$dbh3);
			$MonCnt = mon_access($where_term,$dbh3);
			//$MonCnt_u = mon_u_access($where_term,$dbh3);
			$MonCnt_uu = mon_uu_access($where_term,$dbh3);
			$fetch_time = hour_access($where_term,$dbh3);
			//$fetch_time_u = hour_u_access($where_term,$dbh3);
			$fetch_time_uu = hour_uu_access($where_term,$dbh3);
			$fetch_dayofweek = dayofweek_access($where_term,$dbh3);
			//$fetch_dayofweek_u = dayofweek_u_access($where_term,$dbh3);
			$fetch_dayofweek_uu = dayofweek_uu_access($where_term,$dbh3);
			$fetchURL = page_access($where_term,$dbh3);
			//$fetchURL_u = page_u_access($where_term,$dbh3);
			$fetchENGINE = engine_access($where_term,$dbh3);
			$fetchQuery = access_query($where_term,$dbh3,$kensu);
			$fetch_bro = bro_access($where_term,$dbh3);
			$fetch_os = os_access($where_term,$dbh3);
			$fetch_ref = ref_access($where_term,$dbh3);
			$fetch_state_u = state_access($where_term,$dbh3);
	default:
			$fetch_day = day_access($where_term,$dbh3);
			//$fetch_day_u = day_u_access($where_term,$dbh3);
			$fetch_day_uu = day_uu_access($where_term,$dbh3);
		break;
endswitch;

?>
