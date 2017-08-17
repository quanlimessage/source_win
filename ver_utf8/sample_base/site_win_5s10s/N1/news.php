<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）N1
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
require_once($com_path."config_N1.php");		// 共通設定情報
require_once("util_lib.php");	// 汎用処理クラスライブラリ

if($_POST['regist_type']=="new" || $_POST['regist_type']=="update"):
//////////////////////////////////////////////
// 新規登録・更新画面からのプレビュー

	//小文字の変数名を大文字に変換して入れる、データベースに入れるカラム名が変数名と違う場合は個々対応させる
		foreach($_POST as $key => $val){
			if(!is_array($val)){//配列以外は入れる。（データベースに入れる性質上　文字列の為、配列はありえない）
				$fetch[0][strtoupper($key)] = utilLib::strRep($val,4);//データベースに入れないのでエスケープ処理をはずす
			}
		}

elseif($_POST['act']=="prev"):
///////////////////////////////
//一覧画面からのプレビュー

$sql = "
SELECT
	RES_ID,TITLE,CONTENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D,
	LINK,
	LINK_FLG,
	IMG_FLG,
	DISPLAY_FLG
FROM
	".N1_WHATSNEW."
WHERE
	(DEL_FLG = '0')
AND
	(RES_ID = '".addslashes($_POST[res_id])."')
ORDER BY
	DISP_DATE DESC
LIMIT
	0 , ".N1_DBMAX_CNT."
";

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

else:
///////////////////////////////
//通常

#-------------------------------------------------------------------------
# DBより新着情報のデータを取り出す
#-------------------------------------------------------------------------
$sql = "
SELECT
	RES_ID,TITLE,CONTENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D,
	LINK,
	LINK_FLG,
	IMG_FLG,
	DISPLAY_FLG
FROM
	".N1_WHATSNEW."
WHERE
	(DISPLAY_FLG = '1' )
AND
	(DEL_FLG = '0')
ORDER BY
	DISP_DATE DESC
LIMIT
	0 , ".N1_DBMAX_CNT."
";

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

endif;

		for($i=0;$i<count($fetch);$i++):

		//ＨＴＭＬでの表示処理
			//ID
				$id[$i] = $fetch[$i]['RES_ID'];

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
				$target = ($i + 1); // ○番目

		endfor;

?>
