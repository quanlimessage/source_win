<?php
/*******************************************************************************
Site Win 10 20 30用のNx系プログラム（MySQLバージョン）
コメントの内容をテロップ形式でFlashに出力するプログラム

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
if(file_exists('./common/config.php')){
	$com_path = './common/';
}
else{
	$com_path = '../common/';
}
require_once($com_path."config_N5.php");		// 共通設定情報
require_once("dbOpe.php");				// DB操作クラスライブラリ
require_once("util_lib.php");	// 汎用処理クラスライブラリ

#-------------------------------------------------------------------------
# DBよりコメントのデータを取り出す
#-------------------------------------------------------------------------
$sql = "
SELECT
	RES_ID,COMMENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D
FROM
	N5_TICKER
WHERE
	(DISPLAY_FLG = '1')
AND
	(DEL_FLG = '0')
ORDER BY
	DISP_DATE ASC
LIMIT
	0 , ".N5_DBMAX_CNT."
";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#-----------------------------------------------------------------------------
# 登録されている件数分をFlashがLoadVersメソッドで読み込めるクエリ文字列で出力
#

# 	※変数“$ticker0&ticker1&ticker2”にそれぞれクエリ文字列を設定し、以下のような文字列を出力する
#		“ticker0=メッセージ&ticker2=メッセージ&ticker3=メッセージ”
#-------------------------------------------------------------------------

for($i=0; $i<N5_DBMAX_CNT; $i++):

	//出力するFlashの変数を生成
	if($i==0){
		$ticline[$i] = 'ticker'."$i".'=';
	}else{
		$ticline[$i] = '&ticker'."$i".'=';
	}

	// コメント（CONFIGの表示タイプで分岐）
	if(!empty($fetch[$i]['COMMENT'])):
		$ticline[$i] .= "<font color=\"".N5_COMMENT_COLOR."\" size=\"12\">".h14s_han2zen($fetch[$i]['COMMENT'])."</font>";

	else:
		$ticline[$i] .= "";

	endif;

	//文字列を連結
	if($i==0){
		$tickerall = $ticline[$i];
	}else{
		$tickerall .= $ticline[$i].'&co='.N5_DBMAX_CNT;
	}

endfor;

// 文字コードをutf8jp-winからUTF-8に変換
$ticker = mb_convert_encoding($tickerall, "UTF-8", "utf8jp-win");

// 文字列の出力
echo $ticker;
?>