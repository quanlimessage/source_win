<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
メールエラー画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>
<div class="header"></div>
<p class="page_title"><?php echo MEMBER_TITLE?>：一括メール送信エラー画面</p>
<strong><?php echo $msg;?></strong>
<br><br>
<p class="err">メール送信に失敗しました。<br>
お手数をおかけして誠に申し訳ございませんが、<br>
送信内容を確認の上、もう一度送信しなおしてください。</p>
<form action="./" method="post" style="margin:0px;">
	<input type="submit" value="ユーザー検索画面へ" style="width:150px;">
</form>
</body>
</html>
