<?php
session_start();
// 設定＆ライブラリファイル読み込み
require_once("../common/config.php");
require_once("util_lib.php");				// 汎用処理クラスライブラリ

if($_GET) extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="98%" height="" align="center" cellpadding="5" cellspacing="5">
  <tr>

    <td align="center"><img src="img/title.gif" width="615"></td>
  </tr>
  <tr>

    <td align="center"><h2>ログイン画面</h2></td>
  </tr>
  <tr>

    <td align="center">
	<?php if(!$_SESSION['LOGIN']){ ?>
	<span style="color:#ff0000"><?php echo ($err) ?$err : ""; ?></span>
	<form action="./" method="post" style="margin:0;" target="_parent">
	<table width="" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<td align="left" class="tdcolored">
			<strong>ログインID</strong>：</td>
			<td align="left"><input name="login_id" type="text" size="20" value="" style="ime-mode:disabled;"></td>
		</tr>
		<tr>
			<td align="left" class="tdcolored">
			<strong>パスワード</strong>：</td>
			<td align="left"><input name="login_pass" type="password" size="20" value="" style="ime-mode:disabled;"></td>
		</tr>
	</table>
	<input name="login" type="hidden" value="1">
	<div align="center"><input name="" type="submit" value="ログイン"></div>
	</form>
	<?php }else{?>
	<form action="./" method="post" style="margin:0;" target="_parent">
	<input name="logout" type="hidden" value="1">
	<div align="center"><input name="" type="submit" value="ログアウト"></div>
	</form>
	<?php } ?>

	</td>
  </tr>
</table>

  </tr>
</table>
</body>
</html>
