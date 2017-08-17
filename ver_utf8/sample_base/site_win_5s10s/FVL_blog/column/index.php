<?php
/*******************************************************************************
SiteWiN20 20 30（MySQL版）N3_2
新着情報の内容をFlashに出力するプログラム

コントローラー

*******************************************************************************/

    // 不正アクセスチェックのフラグ
    $injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once '../common/config.php';        // 共通設定情報
require_once '../common/config_column.php';   // 共通設定情報
require_once '../common/imgOpe2.php';       // 画像アップロードクラスライブラリ

// 商品情報取得
if ($_POST['act']) {
    require_once("LGC_preview.php");//プレビュー表示
} else {
    require_once("LGC_getDB-data.php");
}

// 商品IDが送信されパラメーターが不正でなければ商品詳細を表示
if ((isset($_GET['id']) && preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_GET['id'])) || $_POST['act']=="prev_d") {
    include("DISP_detail.php");
} else {
    include("DISP_List.php");
}
