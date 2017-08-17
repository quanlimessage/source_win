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
define('N6_1TITLE','日記の更新');	// Nx系（Ticker）のタイトル
define('N6_1MAIL','メール設定の更新【日記】');	// メール設定のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
//define('N6_1PAGE_MAX',10);		// DIARY MAX PAGE用
define('N6_1BOF_MAX',10);		// DIARY BACK OFFICE PAGE用 DEFULT SET 20
define('FILE_DBMAX_CNT',250);	// File最多登録件数的設定
define('N6_1DBMAX_CNT',50);		// Nx系（日記）の最大登録件数

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH','../../N6/');

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（写メールからの画像）
//zeeksdgが含まれていたら、デモ用
if(strpos($_SERVER['PHP_SELF'],"zeeksdg")==""){
	//本番用
	define('N6_1IMG_TMPPATH','/new_sample_base_update/site_win_5s10s/N6_update/N6/tmg_img/');	//（写メール）
	define('N6_1IMG_UPPATH','/new_sample_base_update/site_win_5s10s/N6_update/N6/up_img/');	//（写メール）
}else{
	//デモ用
	define('N6_1IMG_TMPPATH','/new_sample_base_update/site_win_5s10s/N6_update/N6/tmg_img/');	//（写メール）
	define('N6_1IMG_UPPATH','/new_sample_base_update/site_win_5s10s/N6_update/N6/up_img/');	//（写メール）
}

// 画像ファイルパス（管理画面のみで使用）
define('N6_1IMG_PATH','../../N6/up_img/');	//（写メール）

// 画像枚数
//define('IMG_CNT',1);

// 画像ファイルサイズ
define('N6_1IMGSIZE_SX',40);	// 管理画面サムネイル用
define('N6_1IMGSIZE_SY',30);	// 管理画面サムネイル用
define('N6_1IMGSIZE_MX',200);	// アップロード画像幅（高自動算出）
define('N6_1IMGSIZE_MY',200);	// アップロード画像高（幅自動算出）
?>