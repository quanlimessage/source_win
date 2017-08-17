<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if(!$accessChk){
	header("Location: ../../index.php");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>

	</tr>
</table>

<p class="page_title">売上管理：メール送信編集画面</p>
<p class="explanation">

</p>

<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td class="other-td" align="center">
		<?php
			//エラーが発生していない場合
			if(!$err_mes){
			?>
			<p align=center style="line-height:160%;text-align:center;">
			正常に送信されました。
			</p>
						<?php
			}else{
			//エラーが発生している場合エラー内容を表示する
			echo "<br><p style=\"color:#FF0000;\">".$err_mes."</p>";
			}?>
		</td>
	</tr>
</table>

<form action="../" method="post">
<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

</body>
</html>