<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
// 不正アクセスチェック（直接このファイルにアクセスした場合）
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
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo MEMBER_TITLE;?>：新規登録</p>
<p class="explanation">
▼新規会員データの登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。<br>
▼最大登録件数は<strong><?php echo MEMBER_DBMAX_CNT;?>件</strong>です。
</p>
<?php
#-----------------------------------------------------
# 書込許可（最大登録件数に達していない）の場合に表示
#-----------------------------------------------------
if(count($fetch) < MEMBER_DBMAX_CNT):?>
<form action="./" method="post">
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="action" value="new_entry">
</form>
<?php else:?>
<p class="err">最大登録件数<?php echo MEMBER_DBMAX_CNT;?>件に達しています。<br>
新規登録を行う場合は、いずれかの既存データを削除してください。</p>
<?php endif;?>
<p class="page_title"><?php echo MEMBER_TITLE;?>：登録一覧</p>
<p class="explanation">
▼既存データの修正は<strong>「編集」</strong>をクリックしてください<br>
▼<strong>「削除」</strong>をクリックすると登録されている登録データが削除されます。<br>
▼<strong>削除したデータは復帰できません。</strong>十分に注意して処理を行ってください。
</p>

	<form action="./" method="post">
		<input type="submit" value="検索画面へ戻る" style="width:150px;">
	</form>
<?php if(!$fetch):?>
<p><b>登録されている会員データはありません。</b></p>
<?php else:?>
<div>※登録件数：<strong><?php echo count($fetch);?></strong>&nbsp;件</div>
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<th width="15%" nowrap>登録日</th>
		<th width="25%" nowrap>お名前</th>
		<th nowrap>メールアドレス</th>
		<th width="15%" nowrap>配信希望</th>
		<th width="5%" nowrap>編集</th>
	    <th width="5%" nowrap>削除</th>
	</tr>
	<?php for($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center"><?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?></td>
		<td align="center"><?php echo (!empty($fetch[$i]['NAME']))?$fetch[$i]['NAME']:"&nbsp;";?></td>
		<td align="center"><?php echo (!empty($fetch[$i]['EMAIL']))?$fetch[$i]['EMAIL']:"&nbsp;";?></td>
		<td align="center"><?php echo ($fetch[$i]['SENDMAIL'] == "1")?"希望する":"希望しない";?></td>
		<td align="center">
			<form method="post" action="./" style="margin:0;">
				<input type="submit" name="reg" value="編集">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['MEMBER_ID'];?>">
			</form>
		</td>
		<td align="center">
			<form method="post" action="./" style="margin:0;" onSubmit="return confirm('この登録データを完全に削除します。\n登録データの復帰は出来ません。\nよろしいですか？');">
				<input type="submit" value="削除">
				<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['MEMBER_ID'];?>">
				<input type="hidden" name="action" value="del_data">
			</form>
		</td>
	</tr>
	<?php endfor;?>
</table>
	<br>

		<?php
		//ＣＳＶ出力が許可されている場合表示する
		if($csv_output == "1"):?>
			<form method="post" action="csv.php">
			<input type="submit" value="会員情報のCSV出力"><br>
			（現在一覧に表示されています会員様の情報をＣＳＶ形式で出力致します。）<br>
			</form>
		<?php endif;?>

		<input type="button" value="この画面を印刷" style="width:150px;" onClick="javascript:window.print();"><br>
		<br>

	<form action="./" method="post">
		<input type="submit" value="検索画面へ戻る" style="width:150px;">
	</form>
<?php endif;?>

</body>
</html>
