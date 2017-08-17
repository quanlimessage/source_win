<?php
/***********************************************************
RSS配信

	コンテンツ内容をXML形式で配信する

***********************************************************/

$injustice_access_chk = 1;

// ライブラリ読み込み
if(file_exists('./common/config.php')){
	$com_path = './common/';
}
else{
	$com_path = '../common/';
}
require_once($com_path."config_rss.php"); 	// 共通設定情報
require_once($com_path."config_N3_2.php"); 	// 共通設定情報
require_once($com_path.'util_lib.php');
require_once($com_path.'dbOpe.php');

$sql = "
SELECT
	RES_ID,TITLE,CONTENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D,
	DISPLAY_FLG
FROM
	".RSS_TABLE."
WHERE
	(DISPLAY_FLG = '1' )
ORDER BY
	DISP_DATE DESC
LIMIT
	0 , ".RSS_DBMAX_CNT."
";

// MySQL
$fetchItem = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#------------------------------------------------------------------------
#	ページネーション情報の取得
#------------------------------------------------------------------------
$sql_page = "
SELECT
	RES_ID,
	PAGE_FLG
FROM
	".N3_2WHATSNEW_PAGE."
	";

$fetch_page = dbOpe::fetch($sql_page,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// 1ページ辺りの表示件数
$page = $fetch_page[0]['PAGE_FLG'];
if($page == 0)$page = N3_2DBMAX_CNT;

#------------------------------------------------------------------------

// RSS用のcontent-typeを出力する
header('content-type: text/xml; charset=utf-8');

$xml="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rdf:RDF
	xmlns=\"http://purl.org/rss/1.0/\"
	xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
	xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
	xml:lang=\"ja-JP\">
	<channel rdf:about=\"".SITE_LINK."/\">
	<title>".RSS_TITLE."</title>
	<link>".SITE_LINK."</link>
	<description>".RSS_DESCRIPTION."</description>
	<dc:creator>".RSS_CREATOR."</dc:creator>
<items>
<rdf:Seq>\n";
$items = array();
foreach ($fetchItem as $item_url) :
	for($i=0;$i<count($fetchItem);$i++):
	$target = ($i + 1); // ○番目
	// 表示されるページを算出
	$p[$i] = ceil($target/$page);
	$p_id[$i] = $fetchItem[$i]['RES_ID'];
	if($item_url['RES_ID'] == $p_id[$i])$page_rss=$p[$i];
	endfor;

	$link = SITE_LINK."/".DIR."/?p=".$page_rss."#".urlencode($item_url['RES_ID']);
	$xml .= "<rdf:li rdf:resource=\"".toURL($link)."\" />\n";
endforeach ;
$xml.="
 </rdf:Seq>
 </items>
 </channel>\n
";
$items = array();
foreach ($fetchItem as $item) :
	for($i=0;$i<count($fetchItem);$i++):
	$target = ($i + 1); // ○番目
	// 表示されるページを算出
	$p[$i] = ceil($target/$page);
	$p_id[$i] = $fetchItem[$i]['RES_ID'];
	if($item['RES_ID'] == $p_id[$i])$page_rss=$p[$i];
	endfor;

 $link = SITE_LINK."/".DIR."/?p=".$page_rss."#".urlencode($item['RES_ID']);

 $xml .= "<item rdf:about=\"".toURL($link)."\">
	<link>".toURL($link)."</link>
	<dc:date>".toTEXT($item['Y'])."-".toTEXT($item['M'])."-".toTEXT($item['D'])."</dc:date>
	<title>".toTEXT($item['TITLE'])."</title>
	<description>
	<![CDATA[".toHTML($item['CONTENT'])."]]>
 </description>
 </item>\n";

endforeach;

$xml .= "</rdf:RDF>\n";

echo $xml;

#================================================================
# 文字処理用関数群
#================================================================

	// タグを無効化と文字を５０文字以下にする
function toTEXT($str){

	$str = strip_tags($str);
	$str = mb_substr($str, 0 , 50);
	$str = mb_convert_encoding($str,"UTF-8","UTF-8");

	return $str;

}

// 改行コードを<br />に変換する(タグはそのまま)
function toHTML($str){

	$str = str_replace("\n","<br />\n", $str);
	$str = mb_convert_encoding($str,"UTF-8","UTF-8");

	return $str;

}

// URL中の'&','<','>'をエスケープする
function toURL($str){

	$str = htmlspecialchars($str);

	return $str;

}

?>