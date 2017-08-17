<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
メインコントローラー

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once '../../common/config_column.php';  // 共通設定情報
require_once '../../common/imgOpe2.php';        // 画像アップロードクラスライブラリ

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch ($_POST["action"]) {
    case "completion":

        // データ登録処理を行い一覧へ戻る
        require_once("LGC_regist.php");
        header("Location: ./");
        exit();

    case "update":
    //////////////////////////////////////////////////
    //	更新画面出力

        require_once("LGC_getDB-data.php");
        include("DISP_update.php");
        break;

    case "new_entry":
    //////////////////////////////////////////////////
    //	新規登録画面出力

        include("DISP_newInput.php");
        break;

    case "display_change":
    case "del_data":
    /////////////////////////////////////////////////
    //	対象データの表示・非表示の切替 OR 削除

        require_once("LGC_del_and_dispchng.php");
        header("Location: ./");
        exit();

    default:
    /////////////////////////////////////////////////
    // DBより情報を取得し、一覧表示する

        require_once("LGC_getDB-data.php");
        include("DISP_listview.php");
        break;

}
