<?php
/*******************************************************************************
アパレル対応(カテゴリ二つ)
ショッピングカートプログラム バックオフィス

    メニュー画面

*******************************************************************************/
session_start();
require_once '../common/config.php';

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ./err.php");
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
                ▼ 更新プログラム管理
            </td>
        </tr>
        <tr>
            <td class="space">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitle">
                ・<a href="column_product/" target="main">コラムの更新</a><br>
            </td>
        </tr>
        <tr>
            <td class="explanation"> コラムの新規登録や既存データの更新などを行います。
            </td>
        </tr>
        <tr>
            <td class="space">&nbsp;</td>
        </tr>
        <!-- <tr>
            <td class="subtitle">
                ・<a href="./column_category" target="main">コラムカテゴリーの更新</a>
            </td>
        </tr>
        <tr>
            <td class="explanation"> コラムカテゴリーの新規登録や既存データの更新などを行います。<br>
                また、表示順番の変更等の管理もできます。
            </td>
        </tr>
        <tr>
            <td class="space">&nbsp;</td>
        </tr> -->
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