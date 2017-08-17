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
define('CALENDAR_TITLE','スケジュールの更新');	// Sx系（商品紹介）のタイトル

#=================================================================================
# ＤＢのテーブル名の指定
#=================================================================================
define('SCHEDULE','SCHEDULE');	// カレンダー登録データのテーブル
define('EVENT','EVENT');	// カレンダー登録された日付の詳細データのテーブル
define('CA_SCHEDULE','CA_SCHEDULE');	// カテゴリーのテーブル

?>