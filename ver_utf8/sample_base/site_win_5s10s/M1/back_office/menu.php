<?php
/*******************************************************************************
アパレル対応(カテゴリ二つ)
ショッピングカートプログラム バックオフィス

    メニュー画面

*******************************************************************************/
require_once("../common/config.php");

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <link href="for_bkmanu.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div align="center">
        <form action="./" method="post" target="_parent">
            <input type="submit" value="管理画面トップへ" style="width:150px;">
        </form>
    </div>
    <p><strong>処理を選択してください</strong></p>
    <!--メニューテーブル-->
    <table border="0" cellpadding="0" cellspacing="0" width="90%">
        <tr>
            <td class="menutitle">
                ▼ 管理情報管理
            </td>
        </tr>
        <tr>
            <td class="space">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitle">
                ・<a href="m1_member/" target="main">会員ID/PWの更新</a>
            </td>
        </tr>
        <tr>
            <td class="explanation">
                会員ページのID/PASSWORDの更新などを行います。
            </td>
        </tr>
        <tr>
            <td class="space">&nbsp;</td>
        </tr>
    </table>
    <div class="largespace"></div>
    <!--メニューテーブルここまで-->
    <div align="center">
        <form action="./" method="post" target="_parent">
            <input type="submit" value="管理画面トップへ" style="width:150px;">
        </form>
    </div>
</body>
</html>
