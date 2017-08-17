<?php
/******************************************************************************
ntf

ピックアップ商品の内容をFlashに出力するプログラム

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config_S14.php");	// 設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
#-------------------------------------------------------------------------
# DBより新着情報のデータを取り出す
#-------------------------------------------------------------------------
$sql = "
	SELECT
		PICKUP_ID,
		TITLE,
		PICKUP_COMMENT
	FROM
		S14_PICKUP
";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#-----------------------------------------------------------------------------
# 登録されている件数分をFlashがLoadVersメソッドで読み込めるクエリ文字列で出力
#
# 	※変数“$news”にクエリ文字列を設定し、以下のような文字列を出力する
# 	news=<br>○年×月△日<br>新着情報の本文<br>...
#-------------------------------------------------------------------------

$news = 'news=';

for ( $i = 0; $i < count($fetch); $i++ ){

	// タイトル
	if ( !empty($fetch[$i]['TITLE']) ){
		$news .= "<b><font color=\"" . TITLE_COLOR . "\">" . $fetch[$i]['TITLE'] . "</font></b><br>";
	}
	else{
		$news .= "<br>\n";

	}

	// コメント（文中の改行コードを統一して、<br>に変換しておく）
	if ( !empty($fetch[$i]['PICKUP_COMMENT']) ){
		$comment = str_replace("\r\n", "\r", $fetch[$i]['PICKUP_COMMENT']);
		$comment = str_replace("\r", "\n", $comment);
		$comment = str_replace("\n", "<br>", $comment);
		$comment = "<font color=\"" . COMMENT_COLOR . "\">" . $comment . "</font>";

		$news .= $comment . "<br><br>\n\n";

	}

}

// 文字コードをutf8jp-winからUTF-8に変換
$news = mb_convert_encoding($news, "UTF-8", "utf8jp-win");

// 文字列の出力
echo $news;

?>
