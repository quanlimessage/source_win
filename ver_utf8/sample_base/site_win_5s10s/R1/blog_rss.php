<?php
/***********************************************************
RSS配信

    コンテンツ内容をXML形式で配信する

***********************************************************/

$injustice_access_chk = 1;

// ライブラリ読み込み
if (file_exists('./common/config_r1_rss.php')) {
    $com_path = './common/';
} else {
    $com_path = '../common/';
}
require_once($com_path."config_r1_rss.php");    // 共通設定情報

$sql = "
SELECT
    RES_ID,TITLE,CONTENT,
    YEAR(DISP_DATE) AS Y,
    MONTH(DISP_DATE) AS M,
    DAYOFMONTH(DISP_DATE) AS D,
    DISPLAY_FLG
FROM
    ".BLOG_RSS_TABLE."
WHERE
    (DISPLAY_FLG = '1' )
    AND
    (DEL_FLG = '0')
ORDER BY
    DISP_DATE DESC
LIMIT
    0 , ".BLOG_RSS_DBMAX_CNT."
";

// MySQL
$fetchItem = $PDO->fetch($sql);

// RSS用のcontent-typeを出力する
header('content-type: text/xml; charset=utf-8');

$xml="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<rdf:RDF
    xmlns=\"http://purl.org/rss/1.0/\"
    xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
    xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
    xml:lang=\"ja-JP\">
    <channel rdf:about=\"".BLOG_SITE_LINK."/\">
    <title>".BLOG_RSS_TITLE."</title>
    <link>".BLOG_SITE_LINK."</link>
    <description>".BLOG_RSS_DESCRIPTION."</description>
    <dc:creator>".BLOG_RSS_CREATOR."</dc:creator>
<items>
<rdf:Seq>\n";
$items = [];
foreach ($fetchItem as $item_url) :
    $link = BLOG_SITE_LINK."/".BLOG_DIR."/#".urlencode($item_url['RES_ID']);
    $xml .= "<rdf:li rdf:resource=\"".toURL($link)."\" />\n";
endforeach ;
$xml.="
 </rdf:Seq>
 </items>
 </channel>\n
";
$items = [];
foreach ($fetchItem as $item) :

 $link = BLOG_SITE_LINK."/".BLOG_DIR."/#".urlencode($item['RES_ID']);

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
function toTEXT($str)
{
    $str = strip_tags($str);
    $str = mb_substr($str, 0, 50);
    $str = mb_convert_encoding($str, "UTF-8", "UTF-8");

    return $str;
}

// 改行コードを<br />に変換する(タグはそのまま)
function toHTML($str)
{
    $str = str_replace("\n", "<br />\n", $str);
    $str = mb_convert_encoding($str, "UTF-8", "UTF-8");

    return $str;
}

// URL中の'&','<','>'をエスケープする
function toURL($str)
{
    $str = htmlspecialchars($str);

    return $str;
}
