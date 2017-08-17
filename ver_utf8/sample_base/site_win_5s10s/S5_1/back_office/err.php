<?php
/*******************************************************************************
ログインの有効期限が切れた場合表示するページ
*******************************************************************************/
require_once("../common/config.php");		// 共通設定ファイル
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：UTF-8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0">
<table width="98%" height="98%" align="center" cellpadding="00" cellspacing="0">
  <tr>

    <td align="center" valign="middle" class="black12px">
      <br>
      <br>
      <br>
      <br>
      <br>
      <table width="500" cellspacing="0" cellpadding="5">
        <tr>
          <td align="center">
	  	エラー！！ログインの有効期限が切れました。<br>
		大変申し訳ございませんが、再度ログインをしてください。<br>
		<br>
		<a href="./" target="_top">ログイン画面へ</p>
		<br>

	  </td>
        </tr>
      </table> </td>
  </tr>
</table>
</body>
</html>