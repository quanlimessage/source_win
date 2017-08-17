<?php
/*******************************************************************************
rss用設定ファイル
 RSSファイルのエンコードはUTF-8なので他のファイルとの併用はなるべく避ける
*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");	// 共通設定情報

#=================================================================================
# 定数設定
#=================================================================================
define('DIR','news_N3_2');	// RSSを出力するコンテンツのディレクトリ （一覧ページのフォルダを指定する）
define('RSS_TITLE','株式会社○○ 新着情報'); // RSSタイトル
define('RSS_DESCRIPTION','株式会社○○は、本当に効果のあるプロモーション映像をお求めの方は、お気軽にご連絡ください。');//RSS記述 metaタグのname="description"でcontentの内容を記載
define('RSS_CREATOR','株式会社○○');//RSS提供者

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('RSS_DISP_MAXROW',50);	// 1ページの最大表示件数
define('RSS_DBMAX_CNT',50);	// 最大登録件数

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('RSS_TABLE','N3_2WHATSNEW'); // DBテーブル

?>