<?php
/*******************************************************************************

リスト出力

*******************************************************************************/
// 検索情報管理用にセッション管理開始
session_start();

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/blog_config.php");    // 共通設定情報

#=============================================================================================
# CSV形式のファイルに保存する
#
# 現在の時間を取得し、list-日時.csvというファイル名にして出力する
#=============================================================================================

    // 全データの取得
    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		BLOG_CATEGORY_MST.CATEGORY_NAME,
		BLOG_ENTRY_LST.DISP_DATE,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_ENTRY_LST.EXTENTION1
	FROM
		BLOG_ENTRY_LST,BLOG_CATEGORY_MST
	WHERE
		(BLOG_ENTRY_LST.CATEGORY_CODE = BLOG_CATEGORY_MST.CATEGORY_CODE)
	AND
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	ORDER BY
		DISP_DATE DESC
	";
    // ＳＱＬを実行
    $fetch = $PDO->fetch($sql);

// ヘッダー情報
header("Content-Type: text/plain; charset=Shift_JIS");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list-".date("YmdHis").".txt");

//入力された危険文字が実態参照で表示されるため処理をする
function csv_conversion($str)
{
    $str = str_replace("&amp;", "＆", $str);
    $str = str_replace("&quot;", "”", $str);
    $str = str_replace("&lt;", "＜", $str);
    $str = str_replace("&gt;", "＞", $str);
    $str = str_replace("&#39", "’", $str);
    $str = str_replace("'", "’", $str);
    $str = str_replace("\"", "'", $str);
    $str = str_replace("&", "＆", $str);

    return $str;
}

// データの数だけループする。
for ($i=0;$i<count($fetch);$i++):

    $id            = csv_conversion($fetch[$i]['RES_ID']);
    $date        = csv_conversion($fetch[$i]['DISP_DATE']);
    $category =csv_conversion($fetch[$i]['CATEGORY_NAME']);
    $title        = csv_conversion($fetch[$i]['TITLE']);
    $content    = csv_conversion($fetch[$i]['CONTENT']);

// 表示用の文字列を作成
$data .= "
-----------------------------------------------------------------------
ID：{$id}
投稿日付：{$date}
所属カテゴリー：{$category}
タイトル：{$title}
本文：
{$content}
-----------------------------------------------------------------------
";

endfor;

//if($data)$data = mb_convert_encoding($data,"Shift-JIS","UTF-8");

echo $data;
// 以下確認用（本番事は消すこと）
//echo nl2br($data);
