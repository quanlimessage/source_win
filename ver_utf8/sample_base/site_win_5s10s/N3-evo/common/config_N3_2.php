<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）N3-2
設定ファイルの定義

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");	// 共通設定情報

#=================================================================================
# 表示の色設定
#=================================================================================
define('DATE_COLOR','#333333');		// 日付
define('TITLE_COLOR','#333333');	// タイトル
define('COMMENT_COLOR','#333333');	// コメント
define('KUGIRI_COLOR','#666666');	// 区切り線

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('N3_2TITLE','新着情報の更新');	// Nx系（WhatsNew）のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
define('N3_2DBMAX_CNT',50);	// Nx系（WhatsNew）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('N3_2DISP_MAXROW',50);	// Nx系（WhatsNew）の 1ページの最大表示件数

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('N3_2WHATSNEW','N3_2WHATSNEW');	//登録データ
define('N3_2WHATSNEW_PAGE','N3_2WHATSNEW_PAGE');	//ページネーション表示件数

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH','../../news_N3_2/');

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('N3_2IMG_PATH','../../news_N3_2/up_img/');

// 画像枚数
define('IMG_CNT',1);

// アップロードサイズ上限(MB)
define('MAX_MB',2);

// 画像表示サイズ
define('N3_2IMGSIZE_SX',40);	// 管理画面サムネイル用
define('N3_2IMGSIZE_SY',30);	// 管理画面サムネイル用

define('N3_2IMGSIZE_MX1',600);	// アップロード画像幅（WhatsNew）
define('N3_2IMGSIZE_MY2',150);	// アップロード画像高（WhatsNew）
//define('N3_2IMGSIZE_MX2',200);	// アップロード画像幅（WhatsNew）基本的には使用しない
//define('N3_2IMGSIZE_MY2',150);	// アップロード画像高（WhatsNew）基本的には使用しない

//定数を配列に格納しておく（back_officeでの画像登録処理（LGC_regist.php）で使用）
$ox = array(N3_2IMGSIZE_MX1);
$oy = array(N3_2IMGSIZE_MY1);

?>
