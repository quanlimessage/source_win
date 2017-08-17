<?php
/**********************************************************************************************************
������������

	�����������ϥǡ�����XML�������Ѵ��ѥ�����ץ�
	ɽ�����ˤ�äƥǡ����١���ɽ�����ѹ�

	ľ�ܥ���������ǽ
	�ѥ�᡼������(mode)
	----------------------------------------------------------------------------------------------------------
	  ����URL http://www.���饤����ȥɥᥤ��/back_office/log/access.php?mode=date&ym=����������ǯ��(��200912)
		mode + ym(ǯ��)��ʬ��
	�����̥ǡ��� 		date
	�����̥ǡ��� 		month
	�������̥ǡ��� 		time
	�������̥ǡ��� 		week
	���ڡ����̥ǡ��� 	page
	���������󥸥��̥ǡ��� 	engin
	����������̥ǡ��� 	keywords
	���֥饦���̥ǡ��� 		brow
	��OS�̥ǡ��� 			os
	����ե��顼�̥ǡ��� 	ref
	����ƻ�ܸ��̥ǡ��� 		state

 2010.01.14 fujiyama
**********************************************************************************************************/

// ����ե���������̥饤�֥����ɤ߹���
require_once("../../common/INI_logconfig.php");		// �������
require_once("util_lib.php");						// ���ѽ������饹�饤�֥��
require_once("sqliteOpe.php");						// SQLite���饹�饤�֥��

// GET�ǡ����μ������ȶ��̤�ʸ�������
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

$term = substr($ym,0,4)."_".substr($ym,4,2);

if(!file_exists(ACCESS_PATH.$term."_access_log_db")){
	// �ǡ����ʤ������륤�󥿡��ͥåȥ����Ȥإ�����쥯��
	header("Location: http://www.all-internet.jp");
	exit;
}

$db_filepath = ACCESS_PATH.$term."_access_log_db";
$dbh = new sqliteOpe($db_filepath,CREATE_SQL);

// ����С��ȴؿ�
function strConvert($str){
	$str = mb_convert_encoding($str,"UTF-8","EUC-JP");
	return $str;
}

#---------------------------------------------------------------
# �ƥǡ��������ؿ������
#---------------------------------------------------------------
// ���̥�������������
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

// ���̥�ˡ�����������������
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

// ����ˬ��Կ�����
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

// ���̥������������ؿ�
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

	// ��(1��12)�򥤥�ǥå����������֤�����(ɽ���Ѥ�12�����������)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt[$key] = $v["CNT"];
	}
	return $MonCnt;
}

// ���̥�ˡ����������������ؿ�
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

	// ��(1��12)�򥤥�ǥå����������֤�����(ɽ���Ѥ�12�����������)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt_u[$key] = $v["CNT"];
	}
	return $MonCnt_u;
}

// ����ˬ��Կ������ؿ�
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

	// ��(1��12)�򥤥�ǥå����������֤�����(ɽ���Ѥ�12�����������)
	foreach($cnt_month as $k => $v){
		$key = (int)$v["M"];
		$MonCnt_uu[$key] = $v["CNT"];
	}
	return $MonCnt_uu;
}

// �����̥�������������
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

	// ����(1��24)�򥤥�ǥå����������֤�����(ɽ���Ѥ�24�����������)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time[$key] = $v["CNT"];
	}

	return $fetch_time;
}

// �����̥�ˡ�����������������
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

	// ����(1��24)�򥤥�ǥå����������֤�����(ɽ���Ѥ�24�����������)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time_u[$key] = $v["CNT"];
	}

	return $fetch_time_u;
}

// ������ˬ��Կ�����
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

	// ����(1��24)�򥤥�ǥå����������֤�����(ɽ���Ѥ�24�����������)
	foreach($cnt_time as $k => $v){
		$key = $v["TIME"];

		$key = sprintf("%02d",$key);

		$fetch_time_uu[$key] = $v["CNT"];
	}

	return $fetch_time_uu;
}

// �ڡ����̥�������������
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

// �ڡ����̥�ˡ�����������������
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

// �������󥸥������
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

// ����ʸ�������
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

// �����̥�������������
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

	// ����(0��6)�򥤥�ǥå����������֤�����(ɽ���Ѥ�7�����������)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek[$key] = $v["CNT"];
	}

	return $fetch_dayofweek;
}

// �����̥�ˡ�����������������
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

	// ����(0��6)�򥤥�ǥå����������֤�����(ɽ���Ѥ�7�����������)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek_u[$key] = $v["CNT"];
	}

	return $fetch_dayofweek_u;
}

// ������ˬ��Կ�����
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

	// ����(0��6)�򥤥�ǥå����������֤�����(ɽ���Ѥ�7�����������)
	foreach($cnt_dayofweek as $k => $v){
		$key = $v["DAYOFWEEK"];
		$fetch_dayofweek_uu[$key] = $v["CNT"];
	}

	return $fetch_dayofweek_uu;
}

// �֥饦��������
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

// OS������
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

// ��ե��顼������
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

// ���̥�ˡ�����������������
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

// SQL�¹�
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

			// �ǡ������
			for($i=0;$i<count($fetch_day);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Date>".strConvert($fetch_day[$i]["M"])."��".strConvert($fetch_day[$i]["D"])."��</Date>\n";
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

			// �ǡ������
			for($i=0;$i<count($MonCnt);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Date>".strConvert($mon)."��"."</Date>\n";
				$xmlData .= "<PV>".strConvert($MonCnt[$mon])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($MonCnt_uu[$mon])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "time":
			$fetch_time = hour_access($where_term,$dbh3);
			$fetch_time_uu = hour_uu_access($where_term,$dbh3);

			// �ǡ������
			for($i=0;$i<=23;$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Time>".$i."��"."</Time>\n";
				$i = sprintf("%02d",$i);
				$xmlData .= "<PV>".strConvert($fetch_time[$i])."</PV>\n";
				$xmlData .= "<VISIT>".strConvert($fetch_time_uu[$i])."</VISIT>\n";
				$xmlData .= "</item>\n";
			endfor;

		break;
	case "week":
			$fetch_dayofweek = dayofweek_access($where_term,$dbh3);
			$fetch_dayofweek_uu = dayofweek_uu_access($where_term,$dbh3);

			// �ǡ������
			for($i=0;$i<=6;$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Week>";
				switch ($i):
					case 0:$xmlData.="������";break;
					case 1:$xmlData.="������";break;
					case 2:$xmlData.="������";break;
					case 3:$xmlData.="������";break;
					case 4:$xmlData.="������";break;
					case 5:$xmlData.="������";break;
					case 6:$xmlData.="������";break;
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
			// �ǡ������
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
			// �ǡ������
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
			// �ǡ������
			for($i=0;$i<count($fetchQuery);$i++) :
				$xmlData .= "<item>\n";
				$xmlData .= "<Page>".strConvert($fetchQuery[$i]['ENGINE'])." ".str_replace(array("&nbsp;","��","��"),array("","]","["),strConvert($fetchQuery[$i]['QUERY_STRING']))."</Page>\n";
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
			// �ǡ������
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
			// �ǡ������
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
			// �ǡ������
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
			// �ǡ������
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

// RSS�Ѥ�content-type����Ϥ���
header('content-type: text/xml; charset=utf-8');

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$xml .= "<data>\n";
$xml .= $xmlData;
$xml .= "</data>";

echo $xml;

?>
