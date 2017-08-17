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
define('S6_1TITLE','商品紹介の更新');	// Sx系（商品紹介）のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
define('S6_1DBMAX_CNT',100);	// Sx系（商品紹介）の最大登録件数
define('W3_DBMAX_CNT',15);	// w系（商品紹介）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('S6_1DISP_MAXROW',10);	// Sx系（商品紹介）の 1ページの最大表示件数

define('DISP_MAXROW_BACK',10);	// 管理画面での1ページの最大表示件数

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL',1);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('S6_1PRODUCT_LST','W3_S6_1PRODUCT_LST');	//登録データ

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('S6_1IMG_PATH','../../S6_1/up_img/');
// 画像枚数
define('S6_1IMG_CNT',5);

// 画像ファイルサイズ
define('S6_1IMGSIZE_SX',40);	// 管理画面サムネイル用
define('S6_1IMGSIZE_SY',30);	// 管理画面サムネイル用
define('S6_1IMGSIZE_MX1',150);	// アップロード画像幅（商品紹介／高自動算出）
define('S6_1IMGSIZE_MY1',150);	// アップロード画像高（商品紹介／幅自動算出）
define('S6_1IMGSIZE_MX2',200);	// アップロード画像幅詳細用（商品紹介／高自動算出）
define('S6_1IMGSIZE_MY2',200);	// アップロード画像高詳細用（商品紹介／幅自動算出）
define('S6_1IMGSIZE_LX',300);	// アップロード画像幅拡大用（商品紹介／高自動算出）
define('S6_1IMGSIZE_LY',300);	// アップロード画像高拡大用（商品紹介／幅自動算出）

	//定数を配列に格納しておく（back_office/6_1product/LGC_regist.phpで使用）
	$ox = array(S6_1IMGSIZE_MX1,S6_1IMGSIZE_MX2,S6_1IMGSIZE_LX,S6_1IMGSIZE_LX,S6_1IMGSIZE_LX);
	$oy = array(S6_1IMGSIZE_MY1,S6_1IMGSIZE_MY2,S6_1IMGSIZE_LY,S6_1IMGSIZE_LY,S6_1IMGSIZE_LY);

#=================================================================================
# ファイルのアップロード制限
# 大きいファイルのアップロードを行うとサーバー設定されている制限でアップロードが
# 行えない、または、ファイルの置ける容量限界を超えてしまいファイルのアップロードを
# 行えない場合がございます。
#=================================================================================
define('LIMIT_SIZE',3);	// MB単位で記入をしてください。

?>