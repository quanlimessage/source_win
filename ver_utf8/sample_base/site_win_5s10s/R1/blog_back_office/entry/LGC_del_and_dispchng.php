<?php
/*******************************************************************************
ALL-INTERNET BLOG

Logic:以下の処理を行う
    ・表示/非表示の切替(DISPLAY_FLGの切替)
    ・削除処理	※完全にデータを削除します。(DELETE文)

※$_POST["action"]の内容で分岐

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
if (!$accessChk) {
    header("Location: ../");
    exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
extract(utilLib::getRequestParams("post", [8,7,1,4]));

// 対象記事IDデータのチェック
if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res_id)||empty($res_id)) {
    die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
}

#---------------------------------------------------------------
# $_POST["action"]の内容で処理を分岐
#---------------------------------------------------------------
switch ($_POST["act"]):
case "del_data":
////////////////////////////////////////////////////////////////
// 該当データの完全削除

    // SQL実行
    $PDO->regist("UPDATE BLOG_ENTRY_LST SET DEL_FLG = '1' WHERE(RES_ID = '$res_id')");

    // 記事画像の削除
    if (file_exists(IMG_FILE_PATH.$res_id."_1.jpg")) {
        unlink(IMG_FILE_PATH.$res_id."_1.jpg") or die("画像の削除に失敗しました。");
    }

    if (file_exists(IMG_FILE_PATH.$res_id."_L1.jpg")) {
        unlink(IMG_FILE_PATH.$res_id."_L1.jpg") or die("画像の削除に失敗しました。");
    }

    break;
case "display_change":
////////////////////////////////////////////////////////////////
// 表示/非表示の切替（フラグを更新）

    // 表示／非表示のデータ調整
    $up_display = ($display_change == "t")?1:0;

    // SQLを実行
    $PDO->regist("UPDATE BLOG_ENTRY_LST SET DISPLAY_FLG = '$up_display' WHERE(RES_ID = '$res_id')");

endswitch;
