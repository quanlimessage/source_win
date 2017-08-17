<?php
/**********************************************************************************************************
アクセス解析

	アクセス解析データをXML形式に変換用スクリプト
	表示条件によってデータベース表示を変更

	直接アクセス可能
	パラメータ種別(mode)
	----------------------------------------------------------------------------------------------------------
	  基本URL http://www.クライアントドメイン/back_office/log/access.php?mode=date&ym=取得したい年月(例200912)
		mode + ym(年月)で分岐
	・日別データ 		date
	・月別データ 		month
	・時間別データ 		time
	・曜日別データ 		week
	・ページ別データ 	page
	・検索エンジン別データ 	engin
	・キーワード別データ 	keywords
	・ブラウザ別データ 		brow
	・OS別データ 			os
	・リファラー別データ 	ref
	・都道府県別データ 		state

 2010.01.14 fujiyama
**********************************************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../sp/common/INI_logconfig.php");		// 設定情報
require_once("util_lib.php");						// 汎用処理クラスライブラリ
require_once("sqliteOpe.php");						// SQLite操作クラスライブラリ

// GETデータの受け取りと共通な文字列処理
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

$term = substr($ym,0,4)."_".substr($ym,4,2);

if(!file_exists(ACCESS_PATH.$term."_access_log_db")){
	// データなしオールインターネットサイトへリダイレクト
	header("Location: http://www.all-internet.jp");
	exit;
}

$db_filepath = ACCESS_PATH.$term."_access_log_db";
$dbh = new sqliteOpe($db_filepath,CREATE_SQL);

// コンバート関数
function strConvert($str){
	$str = mb_convert_encoding($str,"UTF-8","EUC-JP");
	return $str;
}

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
		ACCESS_LOG
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
		ACCESS_LOG
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
		ACCESS_LOG
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1～12)をインデックスキーに置き換え(表示用に12個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1～12)をインデックスキーに置き換え(表示用に12個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%m', INS_DATE)
	ORDER BY
		strftime('%m', INS_DATE) ASC
	";

	$cnt_month = $dbins->fetch($mon_sql);

	// 月(1～12)をインデックスキーに置き換え(表示用に12個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1～24)をインデックスキーに置き換え(表示用に24個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1～24)をインデックスキーに置き換え(表示用に24個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%H', TIME)
	ORDER BY
		TIME ASC
	";

	$cnt_time = $dbins->fetch($time_sql);

	// 時間(1～24)をインデックスキーに置き換え(表示用に24個要素配列に)
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
		ACCESS_LOG
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
		ACCESS_LOG
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
		ACCESS_LOG
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
function access_query($where_term,$dbins,$num){
	$q_sql = "
	SELECT
		ENGINE,QUERY_STRING,
		COUNT(*) AS CNT
	FROM
		ACCESS_LOG
	".$where_term."
	GROUP BY
		QUERY_STRING, ENGINE
	HAVING
   (QUERY_STRING != \"\")
	ORDER BY
		CNT DESC
	";

	if( ($num!="all") && (is_numeric($num)) ){$q_sql .= " LIMIT 0 , ".$num;}

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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0～6)をインデックスキーに置き換え(表示用に7個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0～6)をインデックスキーに置き換え(表示用に7個要素配列に)
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
		ACCESS_LOG
	".$where_term."
	GROUP BY
		strftime('%w', INS_DATE)
	ORDER BY
		strftime('%w', INS_DATE) ASC
	";

	$cnt_dayofweek = $dbins->fetch($dayofweek_sql);

	// 曜日(0～6)をインデックスキーに置き換え(表示用に7個要素配列に)
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
		ACCESS_LOG
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
		ACCESS_LOG
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
		ACCESS_LOG
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
		ACCESS_LOG
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
$db_filepath = ACCESS_PATH.$term."_access_log_db";
$dbh3 = new sqliteOpe($db_filepath,CREATE_SQL);
}

if(empty($num))$num = 100;

switch ($_GET["mode"]):
	case "date":
			$fetch_day = day_access($where_term,$dbh3);
			$fetch_day_uu = day_uu_access($where_term,$dbh3);

			// データ抽出
			for($i=0;$i<count($fetch_day);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Date>".strConvert($fetch_day[$i]["M"])."月".strConvert($fetch_day[$i]["D"])."日</Date>\n";
				$xmlData .= "<PV>".strConvert($fetch_day[$i]['CNT'])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($fetch_day_uu[$i]['CNT'])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "month":
			$MonCnt = mon_access($where_term,$dbh3);
			$MonCnt_uu = mon_uu_access($where_term,$dbh3);

			$ym_date = explode("_",$term);
			$mon = (int)$ym_date[1];

			// データ抽出
			for($i=0;$i<count($MonCnt);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Date>".strConvert($mon)."月"."</Date>\n";
				$xmlData .= "<PV>".strConvert($MonCnt[$mon])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($MonCnt_uu[$mon])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "time":
			$fetch_time = hour_access($where_term,$dbh3);
			$fetch_time_uu = hour_uu_access($where_term,$dbh3);

			// データ抽出
			for($i=0;$i<=23;$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Time>".$i."時"."</Time>\n";
				$i = sprintf("%02d",$i);
				$xmlData .= "<PV>".strConvert($fetch_time[$i])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($fetch_time_uu[$i])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "week":
			$fetch_dayofweek = dayofweek_access($where_term,$dbh3);
			$fetch_dayofweek_uu = dayofweek_uu_access($where_term,$dbh3);

			// データ抽出
			for($i=0;$i<=6;$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Week>";
				switch ($i):
					case 0:$xmlData.="日曜日";break;
					case 1:$xmlData.="月曜日";break;
					case 2:$xmlData.="火曜日";break;
					case 3:$xmlData.="水曜日";break;
					case 4:$xmlData.="木曜日";break;
					case 5:$xmlData.="金曜日";break;
					case 6:$xmlData.="土曜日";break;
				endswitch;
				$xmlData .= "</Week>\n";
				$xmlData .= "<PV>".strConvert($fetch_dayofweek[$i])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($fetch_dayofweek_uu[$i])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "page":
			$fetchURL = page_access($where_term,$dbh3);
			$fetch_sum = 0;
			for($i=0;$i<=count($fetchURL);$i++){
				$fetch_sum += $fetchURL[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetchURL);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Page>".str_replace("&","&#38;",strConvert($fetchURL[$i]['PAGE_URL']))."</Page>\n";
				$xmlData .= "<Per>".round(strConvert($fetchURL[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetchURL[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "engin":
			$fetchENGINE = engine_access($where_term,$dbh3);
			$fetch_sum = 0;
			for($i=0;$i<=count($fetchENGINE);$i++){
				$fetch_sum += $fetchENGINE[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetchENGINE);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Page>".str_replace("&","&#38;",strConvert($fetchENGINE[$i]['ENGINE']))."</Page>\n";
				$xmlData .= "<Per>".round(strConvert($fetchENGINE[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetchENGINE[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "keywords":
			$fetchQuery = access_query($where_term,$dbh3,$num);
			$fetch_sum = 0;
			for($i=0;$i<=count($fetchQuery);$i++){
				$fetch_sum += $fetchQuery[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetchQuery);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Page>".strConvert($fetchQuery[$i]['ENGINE'])." ".str_replace(array("&nbsp;","］","［"),array("","]","["),strConvert($fetchQuery[$i]['QUERY_STRING']))."</Page>\n";
				$xmlData .= "<Per>".round(strConvert($fetchQuery[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetchQuery[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "brow":
			$fetch_bro = bro_access($where_term,$dbh3);

			$fetch_sum = 0;
			for($i=0;$i<=count($fetch_bro);$i++){
				$fetch_sum += $fetch_bro[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetch_bro);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Web>".strConvert($fetch_bro[$i]['BROWSER'])."</Web>\n";
				$xmlData .= "<Per>".round(strConvert($fetch_bro[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetch_bro[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "os":
			$fetch_os = os_access($where_term,$dbh3);

			$fetch_sum = 0;
			for($i=0;$i<=count($fetch_os);$i++){
				$fetch_sum += $fetch_os[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetch_os);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<OS>".strConvert($fetch_os[$i]['OS'])."</OS>\n";
				$xmlData .= "<Per>".round(strConvert($fetch_os[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetch_os[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;
		break;
	case "ref":
			$fetch_ref = ref_access($where_term,$dbh3);

			$fetch_sum = 0;
			for($i=0;$i<=count($fetch_ref);$i++){
				$fetch_sum += $fetch_ref[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetch_ref);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Ref>".strConvert($fetch_ref[$i]['REFERER'])."</Ref>\n";
				$xmlData .= "<Per>".round(strConvert($fetch_ref[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetch_ref[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;
		break;
	case "state":
			$fetch_state_u = state_access($where_term,$dbh3);

			$fetch_sum = 0;
			for($i=0;$i<=count($fetch_state_u);$i++){
				$fetch_sum += $fetch_state_u[$i]['CNT'];
			}
			// データ抽出
			for($i=0;$i<count($fetch_state_u);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Ref>".strConvert($fetch_state_u[$i]['STATE'])."</Ref>\n";
				$xmlData .= "<Per>".round(strConvert($fetch_state_u[$i]['CNT'])/$fetch_sum * 100)."</Per>\n";
				$xmlData .= "<PV>".strConvert($fetch_state_u[$i]['CNT'])."</PV>\n";
				$xmlData .= "</item>\n";
			endfor;
		break;
	/*
	case "all":
	default:
			$fetch_day = day_access($where_term,$dbh3);
			$fetch_day_uu = day_uu_access($where_term,$dbh3);
		break;
	*/
endswitch;

// RSS用のcontent-typeを出力する
header('content-type: text/xml; charset=utf-8');

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$xml .= "<data>\n";
$xml .= $xmlData;
$xml .= "</data>";

echo $xml;

?>
