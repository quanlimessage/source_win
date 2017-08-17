<?php
/*******************************************************************************
ALL-INTERNETBLOG

    メインコントローラー

*******************************************************************************/

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/blog_config.php");    // 共通設定情報
require_once("tmpl2.class.php");  // テンプレートクラスライブラリ
require_once('../common/imgOpe2.php');                    // 画像アップロードクラスライブラリ

// 基本的な設定情報を取得
include("LGC_getDB-base.php");

// 管理画面からのパラメーターの有無で処理を分岐
if ($_POST['act'] == "prev") {
    // プレビューを表示
    include("LGC_preview.php");
} else {
    // DBより記事情報を取得し、一覧表示する
    include("LGC_getDB-data.php");
}

include("DISP_List.php");
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/
