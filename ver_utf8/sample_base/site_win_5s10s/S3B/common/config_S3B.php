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
define('S3B_TITLE','商品紹介の更新');	// Sx系（商品紹介）のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
define('S3B_DBMAX_CNT',50);	// Sx系（商品紹介）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('S3B_DISP_MAXROW',50);	// Sx系（商品紹介）の 1ページの最大表示件数

define('DISP_MAXROW_BACK',10);	// 管理画面での1ページの最大表示件数

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL',1);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('S3B_PRODUCT_LST','S3B_PRODUCT_LST');	//登録データ

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('S3B_IMG_PATH','../../S3B/up_img/');

// 画像ファイルサイズ
define('S3B_IMGSIZE_SX',40);	// 管理画面サムネイル用
define('S3B_IMGSIZE_SY',30);	// 管理画面サムネイル用
define('S3B_IMGSIZE_MX',200);	// アップロード画像幅（高自動算出）
define('S3B_IMGSIZE_MY',200);	// アップロード画像高（幅自動算出）
?>