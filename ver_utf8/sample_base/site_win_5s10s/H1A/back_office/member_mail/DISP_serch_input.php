<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
View：会員検索画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("Location: ../"); exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="common.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<?php
#-----------------------------------------------------
# 最大送信件数を超えた場合
#-----------------------------------------------------
if($error_msg):?>
<p class="err"><?php echo $error_msg;?></p>
<?php endif;?>
<p class="page_title"><?php echo MEMBER_TITLE;?>：メルマガ送信先の検索</p>
<p class="explanation">
▼検索条件入力し終えたら<strong>「検索開始」</strong>をクリックしてください。<br>
▼最大送信件数は<strong>1000件</strong>です。
</p>
<form action="./" method="post" style="margin:0px;">
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<tr align="center">
		<td colspan="2" class="tdcolored"><b>■ユーザー情報検索</b></td>
	</tr>
	<tr>
		<th class="tdcolored">お名前（一部でも可）：</th>
		<td class="other-td">
			<input type="text" name="name" size="40" style="ime-mode:active;">
		</td>
	</tr>

	<tr>
		<th class="tdcolored">E-MAIL：</th>
		<td class="other-td">
			<input type="text" name="email" size="40" style="ime-mode:disabled;">
		</td>
	</tr>

	<tr>
		<th class="tdcolored">年代：</th>
		<td class="other-td">
			<input type="radio" name="generation" value="" id="Generation" checked><label for="Generation">指定なし</label>　<br>
			<?php for($i=1;$i<count($generation_list);$i++):?>
				<input type="radio" name="generation" value="<?php echo $i;?>" id="Generation<?php echo $i;?>"><label for="Generation<?php echo $i;?>"><?php echo $generation_list[$i];?></label>　<br>
			<?php endfor;?>
		</td>
	</tr>
	<tr>
		<th class="tdcolored">メルマガ配信：</th>
		<td class="other-td">
			<input type="radio" name="mailmag" value="" id="mailmag0" checked><label for="mailmag0">指定なし</label>　<br>
			<input type="radio" name="mailmag" value="1" id="mailmag1"><label for="mailmag1">希望する</label>　<br>
			<input type="radio" name="mailmag" value="0" id="mailmag2"><label for="mailmag2">希望しない</label>
		</td>
	</tr>
</table>
		<br>
		 <input name="submit" type="submit" style="width:150px;" value="検索開始">
		 <input type="hidden" name="status" value="search_result">
		</form>
<div class="footer"></div>
</body>
</html>
