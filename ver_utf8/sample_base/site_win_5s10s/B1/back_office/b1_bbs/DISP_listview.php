<?php
/*******************************************************************************
更新プログラム

	登録内容一覧表示（最初に表示する）

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

$NEXT = $page + 1;

$PREVIOUS = $page - 1;
// CHECK ALL DATA
$TCNT = $fetchCNT[0]["CNT"];
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/BBS_BO_MAX);

// SET DISPLAY
if($page > 1){
	$PREVIOUS_PAGE1 = "<a href='./?page={$PREVIOUS}'>";
	$PREVIOUS_PAGE2 = "</a>";
}else{
	$PREVIOUS_PAGE1 = "";
	$PREVIOUS_PAGE2 = "";
}

if($TOTLE_PAGES > $page){
	$NEXT_PAGE1 = "<a href='./?page={$NEXT}'>";
	$NEXT_PAGE2 = "</a>";
}else{
	$NEXT_PAGE1 = "";
	$NEXT_PAGE2 = "";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<link href="./disp_s.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="for_bk.js"></script>
</head>
<body>

<?php

	//コメント内容の表示用（スレッド）
	for($i=0;$i<count($MAIN_fetch);$i++):?>
	<span id="L<?php echo $i?>" class="disp_s">
	<table width="300" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<div style="float:left; height:200px; width:300px; overflow:auto;">
			<?php

			if(BBS_IMAGE == 1){
				$IMAGE_FILE = BBS_IMG_FILEPATH.$MAIN_fetch[$i]['MASTER_ID'].".jpg";
				if(file_exists($IMAGE_FILE)){
			?>
			<div align="center"><img src="<?php echo $IMAGE_FILE;?>?r=<?php echo rand();?>"></div>
			<?php

				}
			}
			?>
			<div align="left">内容：<br><?php echo nl2br($MAIN_fetch[$i]['COMMENT']);?></div>
			</div>
			</td>
		</tr>
		<tr>
			<td height="18"><div align="center"><a href="javascript:;" onClick="OffLink('L<?php echo $i;?>')" style="text-decoration:underline;">[ 閉じる ]</a></div></td>
		</tr>
	</table>
	</span>
<?php

	//コメント内容の表示用（レス）
	if($SUB_fetch[$i]){
		for($j=0;$j<count($SUB_fetch[$i]);$j++){
	?>
	<span id="L<?php echo $i.$j?>" class="disp_s">
	<table width="300" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<div style="float:left; height:200px; width:300px; overflow:auto;">
			<?php

			if(BBS_IMAGE == 1){
				$IMAGE_FILE = BBS_IMG_FILEPATH.$SUB_fetch[$i][$j]['SUB_ID'].".jpg";
				if(file_exists($IMAGE_FILE)){
			?>
			<div align="center"><img src="<?php echo $IMAGE_FILE;?>?r=<?php echo rand();?>"></div>
			<?php

				}
			}
			?>
			<div align="left">内容：<br><?php echo nl2br($SUB_fetch[$i][$j]['COMMENT']);?></div>
			</div>
			</td>
		</tr>
		<tr>
			<td height="18"><div align="center"><a href="javascript:;" onClick="OffLink('L<?php echo $i.$j;?>')" style="text-decoration:underline;">[ 閉じる ]</a></div></td>
		</tr>
	</table>
	</span>
<?php

	}
}
 endfor;?>

<div class="header"></div>
<p class="page_title">BBS管理：投稿内容管理</p>
<p class="explanation">
▼新規投稿したい場合は<strong>「新規投稿」</strong>をクリックしてください<br>
▼レス投稿したい場合は<strong>「レス」</strong>をクリックしてください<br>
▼<strong>「表示中」「非表示」</strong>をクリックで切替えてと表示ページでの表示を制御します。<br>

</p>
<form action="./" method="post">
		<input type="submit" name="reg" value="新規投稿" style="width:150px;">
		<input type="hidden" name="status" value="new_entry">
</form>
<?php if(!$MAIN_fetch):?>
<p><b>データはありません。</b></p>
<?php else:?>
<div>※親記事登録件数：<strong><?php echo count($MAIN_fetch);?></strong>&nbsp;件</div>
<table width="780" border="1" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th width="110" nowrap>投稿日</th>
		<th nowrap><div align="left">投稿タイトル</div></th>
		<th width="110" nowrap><div align="left">投稿者</div></th>
		<th width="80" nowrap>IPアドレス</th>
		<th width="60" nowrap>レス</th>
		<th width="60" nowrap>登録状態</th>
		<th width="60" nowrap>削除</th>
	</tr>
	<?php for($i=0;$i<count($MAIN_fetch);$i++):?>
	<tr class="otherColTd">
		<td><div align="center"><?php echo $MAIN_fetch[$i]["REG_DATE"];?></div></td>
		<td><div align="left"><a href="javascript:;" onClick="OnLink('L<?php echo $i;?>',event.x,event.y,event.pageX,event.pageY)" style="color:#0000FF; text-decoration:underline;"><?php echo $MAIN_fetch[$i]['TITLE'];?></a></div></td>
		<td><div align="left"><?php echo ($MAIN_fetch[$i]['NAME'] == "admin")?"<span style='color:#FF0000;'>管理人</span>":$MAIN_fetch[$i]['NAME'];?></div></td>
		<td><div align="center"><?php echo $MAIN_fetch[$i]["IP"];?></div></td>
		<td><div align="center">
		<?php if($MAIN_fetch[$i]['NAME'] != "admin"){?>
		<form action="./" method="post" style="margin:0">
		<input type="submit" name="reg" value="レス" style="width:60px;">
		<input type="hidden" name="status" value="new_entry">
		<input type="hidden" name="res" value="1">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		<input type="hidden" name="master_id" value="<?php echo $MAIN_fetch[$i]["MASTER_ID"];?>">
		</form>
		<?php }?>
		</div>
		</td>
		<td><div align="center">
		<form action="./" method="post" style="margin:0">
		<input type="submit" name="reg" value="<?php echo ($MAIN_fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"非表示";?>" style="width:60px;">
		<input type="hidden" name="status" value="display_change">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		<input type="hidden" name="display_change" value="<?php echo ($MAIN_fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		<input type="hidden" name="master_id" value="<?php echo $MAIN_fetch[$i]["MASTER_ID"];?>">
		</form></div>
		</td>
		<td><form method="post" action="./" style="margin:0" onSubmit="return confirm('この記事データを完全に削除します。\n記事データの復帰は出来ません。\nよろしいですか？');">
        	<input type="submit" value="削除" style="width:60px;">
		<input type="hidden" name="master_id" value="<?php echo $MAIN_fetch[$i]["MASTER_ID"];?>">
        	<input type="hidden" name="status" value="del_data">
        	<input type="hidden" name="page" value="<?php echo $page;?>">
        	</form></td>
	</tr>
	<?php

	if($SUB_fetch[$i]){
		for($j=0;$j<count($SUB_fetch[$i]);$j++){
	?>
	<tr>
		<td><div align="center"><?php echo $SUB_fetch[$i][$j]["REG_DATE"];?></div></td>
		<td><div align="left">レス</div></td>
		<td><div align="left"><?php echo ($SUB_fetch[$i][$j]['NAME'] == "admin")?"<span style='color:#FF0000;'>管理人</span>":$SUB_fetch[$i][$j]['NAME'];?></div></td>
		<td><div align="center"><?php echo $SUB_fetch[$i][$j]["IP"];?></div></td>
		<td><div align="center">&nbsp;</div></td>
		<td><div align="center">
		<form action="./" method="post" style="margin:0">
		<input type="submit" name="reg" value="<?php echo ($SUB_fetch[$i][$j]["DISPLAY_FLG"] == 1)?"表示中":"非表示";?>" style="width:60px;">
		<input type="hidden" name="status" value="display_change">
		<input type="hidden" name="display_change" value="<?php echo ($SUB_fetch[$i][$j]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		<input type="hidden" name="sub_id" value="<?php echo $SUB_fetch[$i][$j]["SUB_ID"];?>">
		<input type="hidden" name="master_display_change" value="<?php echo $MAIN_fetch[$i]["DISPLAY_FLG"];?>">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		</form></div>
		</td>
		<td><form method="post" action="./" style="margin:0" onSubmit="return confirm('この記事データを完全に削除します。\n記事データの復帰は出来ません。\nよろしいですか？');">
        	<input type="submit" value="削除" style="width:60px;">
		<input type="hidden" name="sub_id" value="<?php echo $SUB_fetch[$i][$j]["SUB_ID"];?>">
        	<input type="hidden" name="status" value="del_data">
        	<input type="hidden" name="page" value="<?php echo $page;?>">
        	</form></td>
	</tr>
	<?php

		}}
	endfor;
	?>
</table>
<table width="780" height="38" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><div align="center">
				<table width="400" height="20" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><div align="center"> <?php echo $PREVIOUS_PAGE1;?>&lt;&lt; 前のページへ<?php echo $PREVIOUS_PAGE2;?>&nbsp;&nbsp;
        								<?php for($i=1;$i<=$TOTLE_PAGES;$i++){if($page == $i){echo "[{$i}]&nbsp";}else{echo "<a href='./?page={$i}'>[{$i}]</a>&nbsp";}}?>
&nbsp; <?php echo $NEXT_PAGE1;?>次のページへ &gt;&gt;<?php echo $NEXT_PAGE2;?> </div></td>
					</tr>
				</table>
		</div></td>
	</tr>
</table>
<?php endif;?>
<br><br><br>
<form action="../main.php" method="post" enctype="multipart/form-data">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</body>
</html>
