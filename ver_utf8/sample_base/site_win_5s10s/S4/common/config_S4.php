<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
設定ファイルの定義

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");	// 共通設定情報

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('S4_TITLE','before/afterの更新');	// Sx系（before／after）のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
define('S4_DBMAX_CNT',25);	// Sx系（before／after）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('S4_DISP_MAXROW',25);	// Sx系（before／after）の 1ページの最大表示件数

define('DISP_MAXROW_BACK',10);	// 管理画面での1ページの最大表示件数

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL',1);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('S4_PRODUCT_LST','S4_PRODUCT_LST');	//登録データ

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH','../../S4/');

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('S4_IMG_PATH','../../S4/up_img/');

// 画像枚数
define('S4_IMG_CNT',2);

// アップロードサイズ上限(MB)
define('MAX_MB',2);

// 画像ファイルサイズ
define('S4_IMGSIZE_SX',40);	// 管理画面サムネイル用
define('S4_IMGSIZE_SY',30);	// 管理画面サムネイル用

// 画像ファイルサイズ
define('S4_IMGSIZE_SX',40);	// 管理画面サムネイル用
define('S4_IMGSIZE_SY',30);	// 管理画面サムネイル用
define('S4_IMGSIZE_MX1',200);	// アップロード画像幅（before画像／高自動算出）
define('S4_IMGSIZE_MY1',200);	// アップロード画像高（before画像／幅自動算出）
define('S4_IMGSIZE_MX2',200);	// アップロード画像幅（after画像／高自動算出）
define('S4_IMGSIZE_MY2',200);	// アップロード画像高（after画像／幅自動算出）

//定数を配列に格納しておく（back_office/s4_product/LGC_regist.php）
$mx = array(S4_IMGSIZE_MX1,S4_IMGSIZE_MX2);
$my = array(S4_IMGSIZE_MY1,S4_IMGSIZE_MY2);

#=================================================================================
# 管理画面のデータ並び替えのタイプ
# 1 新しいタイプの並び替えPG
# 0 古いタイプの並び替えPG
#=================================================================================
define('S4_SORT_TYPE','1');	// Sx系（商品紹介）のタイトル

?>
