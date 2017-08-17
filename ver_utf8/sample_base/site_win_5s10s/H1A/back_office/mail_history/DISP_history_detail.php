<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
View：メルマガの送信する内容を入力する

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
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>
<p class="page_title"><?php echo MEMBER_TITLE;?>：配信内容詳細</p>

	<form action="./" method="post" style="margin:0px;" name="sendform" onSubmit="return inputChk(this,true)">
		<table width="600" border="0" cellpadding="5" cellspacing="2">
			<tr align="left">
				<th width="10%" class="tdcolored">宛先：</th>
			    <td class="other-td">
					<?php
					for($i=0;$i<count($fetchCustList);$i++){
						echo $fetchCustList[$i]['NAME']."(".$fetchCustList[$i]['EMAIL'].")<br>";
					}
					?>
			    </td>
			</tr>
			<tr align="left">
				<th width="10%" class="tdcolored">件名：</th>
			    <td class="other-td">
					<?php echo $fetch[0]['TITLE'];?>
			    </td>
			</tr>
			<tr align="left">
				<th class="tdcolored">内容：</th>
					<td class="other-td">
					<?php echo nl2br($fetch[0]['CONTENT']);?>
					</td>
			</tr>
		</table>
		<br>
		<input type="hidden" name="status" value="search_input">
		<input type="hidden" name="res_id" value="<?php echo $fetch[0]['RES_ID'];?>">
	</form>

	<br>
	<form action="./" method="post" style="margin:0px;">
        <input type="submit" value="一覧画面へ" style="width:150px;">
    </form>
</body>
</html>
