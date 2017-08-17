<?php
/*******************************************************************************
更新プログラム

	View：更新画面表示

2005/04/15 Author K.C
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理画面</title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title">禁則ワード編集・追加画面</p>
<p class="explanation">
▼入力した単語と単語の間に必ず半角<font color="#FF0000" style=" font-weight:bold;">「,」カンマ</font>を入れてください。<br>　ただし、単語の最後に<font color="#FF0000" style=" font-weight:bold;">「,」カンマなどの記号を記入しないでください。</font><br>
　正しい例：馬鹿<font color="#FF0000">,</font>バカ<font color="#FF0000">,</font>アホ<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>
<form action="./" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■禁則ワード編集・追加<?php echo ($e_msg)?$e_msg:"";?></th>
	</tr>
	<tr>
		<th nowrap class="tdcolored" width="20%">前回更新日：</th>
		<td class="other-td"><?php echo $fetch[0]["UPD_DATE"];?></td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">現在の禁則ワード：</th>
		<td class="other-td"><?php echo $fetch[0]["WORDS"];?></td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">ワード：</th>
		<td class="other-td">
		<input name="words" type="text" id="words" value="<?php echo ($words)?$words:$fetch[0]["WORDS"];?>" size="88" style="ime-mode:inactive;">
		</td>
	</tr>
</table>
<input type="submit" value="更新する" style="width:150px;">
<input type="hidden" name="regist_type" value="update">
</form>
<form action="../main.php" method="post" enctype="multipart/form-data">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</body>
</html>
