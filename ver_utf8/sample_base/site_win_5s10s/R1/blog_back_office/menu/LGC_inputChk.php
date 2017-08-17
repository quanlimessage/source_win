<?php
/*******************************************************************************
ALL-INTERNET BLOG

    カテゴリー管理：入力データチェック
        ※エラー発生時はエラー通知文字列を$error_mesに格納

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post", [8,7,1,4], true));

#===============================================================================
# エラーチェック
#===============================================================================

#エラー通知文字列格納用変数定義
$error_message = "";

# 新規登録時/既存データ更新時で処理分岐
switch ($_POST["regist_type"]):

    # 更新
    case "update":

        # 更新データのIDチェック
        if (empty($category_code)) {
            $error_message.="カテゴリーが指定されていません。<br>\n";
        }

        # 更新後のカテゴリー名チェック
        $error_message .= utilLib::strCheck($category_name, 0, "カテゴリー名を入力してください。\n");

    break;

    # 新規登録
    case "new":

        # 新規登録カテゴリー名チェック
        $error_message .= utilLib::strCheck($category_name, 0, "カテゴリー名を入力してください。\n");

    break;

endswitch;
