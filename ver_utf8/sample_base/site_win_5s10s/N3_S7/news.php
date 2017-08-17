<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）N3-2
新着情報の内容をFlashに出力するプログラム

※使用する際はファイル名を“news.php”にする事！

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
if(file_exists('./common/config.php')){
	$com_path = './common/';
}
else{
	$com_path = '../common/';
}
require_once($com_path."config_N3_S7.php");		// 共通設定情報
require_once("util_lib.php");	// 汎用処理クラスライブラリ

#------------------------------------------------------------------------
#	ページネーション情報の取得
#------------------------------------------------------------------------
//ページネーション
$sql_page = "
SELECT
	RES_ID,
	PAGE_FLG
FROM
	".N3_S7_WHATSNEW_PAGE."
	";

$fetch_page = $PDO -> fetch($sql_page);

// 1ページ辺りの表示件数
$page = $fetch_page[0]['PAGE_FLG'];
if($page == 0)$page = DBMAX_CNT;

#-------------------------------------------------------------------------
# DBより新着情報のデータを取り出す
#-------------------------------------------------------------------------
$sql = "
SELECT
	*,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D
FROM
	".N3_S7_WHATSNEW."
WHERE
	(DISPLAY_FLG = '1' )
AND
	(DEL_FLG = '0')
ORDER BY
	DISP_DATE DESC
LIMIT
	0 , ".DBMAX_CNT."
";

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

		for($i=0;$i<count($fetch);$i++):

		//ＨＴＭＬでの表示処理
			//ID
				$id[$i] = $fetch[$i]['RES_ID'];

			//カテゴリーコード
				$ca[$i] = $fetch[$i]['CATEGORY_CODE'];

			// 日付
				$time[$i] = sprintf("%04d/%02d/%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);

			// タイトル
				$title[$i] = ($fetch[$i]['TITLE'])?$fetch[$i]['TITLE']:"&nbsp;";

			// コメント
				$content[$i] = ($fetch[$i]['CONTENT'])?nl2br($fetch[$i]['CONTENT']):"&nbsp";
			// リンク先
				$link[$i] = $fetch[$i]['LINK'];
			// リンク先表示タイプ
				$link_flg[$i] = $fetch[$i]['LINK_FLG'];

			// 記事の並び順の取得
				${"target".$fetch[$i]['CATEGORY_CODE']} = (${"target".$fetch[$i]['CATEGORY_CODE']} + 1);

			// 表示されるページを算出
				$p[$i] = ceil(${"target".$fetch[$i]['CATEGORY_CODE']}/$page);

		endfor;

?>
