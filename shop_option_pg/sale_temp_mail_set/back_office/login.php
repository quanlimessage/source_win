<?php
session_start();
// ������饤�֥��ե������ɤ߹���
require_once("../common/INI_config.php");
require_once("util_lib.php");				// ���ѽ������饹�饤�֥��

if($_GET) extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0">
<table width="98%" height="" align="center" cellpadding="5" cellspacing="5">
  <tr>

    <td align="center"><img src="img/title.gif" width="615"></td>
  </tr>
  <tr>

    <td align="center"><h2>���������</h2></td>
  </tr>
  <tr>

    <td align="center">
	<?php if(!$_SESSION['LOGIN']){ ?>
	<span style="color:#ff0000"><?=($err) ?$err : ""; ?></span>
	<form action="./index.php" method="post" style="margin:0;" target="_parent">
	<table width="" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<td align="left" class="tdcolored">
			<strong>������ID</strong>��</td>
			<td align="left"><input name="login_id" type="text" size="20" value="" style="ime-mode:disabled;"></td>
		</tr>
		<tr>
			<td align="left" class="tdcolored">
			<strong>�ѥ����</strong>��</td>
			<td align="left"><input name="login_pass" type="password" size="20" value="" style="ime-mode:disabled;"></td>
		</tr>
	</table>
	<input name="login" type="hidden" value="1">
	<div align="center"><input name="" type="submit" value="������"></div>
	</form>
	<?php }else{?>
	<form action="./index.php" method="post" style="margin:0;" target="_parent">
	<input name="logout" type="hidden" value="1">
	<div align="center"><input name="" type="submit" value="��������"></div>
	</form>
	<?php } ?>

	</td>
  </tr>
</table>

  </tr>
</table>
</body>
</html>