<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）

View：検索結果の表示画面

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
utilLib::httpHeadersPrint("UTF-8",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>

<link href="../for_bk.css" rel="stylesheet" type="text/css" media="all">
<link href="../for_bk_print.css" rel="stylesheet" type="text/css" media="print">

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
<p class="page_title"><?php echo MEMBER_TITLE;?>：配信済みメール一覧</p>
<p class="explanation">
  ▼各メールの内容を確認する際は<strong>「詳細」</strong>ボタンをクリックしてください。<br>
</p>
<?php if(!$fetch):?>
	<p>配信済みメールはありません。</p>
<?php else:?>
  <div>※配信済みメール：<strong><?php echo count($fetch);?></strong>&nbsp;件</div>

  <table width="800" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td>
		<table width="650" border="0" cellpadding="5" cellspacing="2" style="float:left;margin:0px;">
			<tr class="tdcolored">
				<th width="5%" nowrap>配信日時</th>
				<th width="10%" nowrap>タイトル</th>
				<th width="10%" nowrap>配信件数</th>
				<th width="10%" nowrap>詳細</th>
			</tr>

			<?php
			for($i=0;$i<count($fetch);$i++):
			?>
			<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" height="30px">
			<td align="center" class="other-td">
				<?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?>
			</td>
			<td align="center" nowrap class="other-td"><?php echo ($fetch[$i]["TITLE"])?$fetch[$i]["TITLE"]:"&nbsp;";?></td>
			<td align="center" nowrap class="other-td"><?php echo ($fetch[$i]["SEND_NUMBER"])?$fetch[$i]["SEND_NUMBER"]:"&nbsp;";?></td>
			<td align="center" class="other-td">
				<form method="post" action="./" style="margin:0;">
				<input type="submit" name="reg" value="詳細">
				<input type="hidden" name="status" value="history_detail">
				<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
				</form>
			</td>

			</tr>
			<?php endfor;?>
		</table>
	</td>
	</tr>
  </table>

	<?php endif;?><br>

<div class="footer"></div>
</body>
</html>
