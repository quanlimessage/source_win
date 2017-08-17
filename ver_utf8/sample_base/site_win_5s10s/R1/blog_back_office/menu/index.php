<?php
/*******************************************************************************
ALL-INTERNET BLOG

カテゴリーコントローラー

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
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/blog_config.php");    // 共通設定情報
require_once('imgOpe.php');                    // 画像アップロードクラスライブラリ

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch ($_POST["status"]):
case "completion":

    // PHPによるエラーチェック(JavaScriptチェックをスルー後のチェック)
    require_once("LGC_inputChk.php");
    // エラー発生時
    if (!empty($error_message)):
        # 初期画面再表示
        require_once("LGC_getDB-data.php");
        require_once("DISP_serch_input.php");
        break;
    endif;

    require_once("LGC_registDB.php");

    require_once("LGC_getDB-data.php");

    // 削除の場合は元の画面に戻る
    if ($regist_type == 'delete') {
        require_once("DISP_serch_input.php");
        break;
    }

    // 登録・更新処理を行い、DBの処理が問題なければ完了画面を表示
    require_once("DISP_completion.php");

    break;
case "edit":
///////////////////////////////////////
//	更新
//
    // 更新入力画面
    require_once("LGC_getDB-data.php");
    require_once("DISP_update.php");

    break;
default:
///////////////////////////////////////
//	新規登録 & 更新ジャンル名指定
//
        #-------------------------------------------------------------------
        # 初めてのアクセス。
        # 新規登録画面&更新業種選択画面表示
        #-----------------------------------------------------------------
        require_once("LGC_getDB-data.php");
        require_once("DISP_serch_input.php");

endswitch;

// デバッグ用
/*
print_r($_POST);
echo "<hr>";
print_r($fetchBU);
*/
