<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if(!$accessChk){
	header("Location: ../");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title">CSV出力</p>
<p class="explanation">
▼【CSV出力】のボタンを押しますとCSVデータを出力いたします。
</p>

<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th width="150px" nowrap class="tdcolored">会員情報（仮）</th>
		<form method="post" action="./csv1.php">
			<td align="left" class="other-td">
			<input type="submit" name="reg" value="会員情報のCSV出力" style="margin:20px;">
			</td>
		</form>
	</tr>

	<tr>
		<th width="150px" nowrap class="tdcolored">物件情報（仮）</th>
		<form method="post" action="./csv2.php">
			<td align="left" class="other-td">
			<input type="submit" name="reg" value="物件情報のCSV出力" style="margin:20px;">
			</td>
		</form>
	</tr>

</table>

</body>
</html>