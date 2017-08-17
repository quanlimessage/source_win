<?php
/*******************************************************************************
更新プログラム

	View：登録内容一覧表示（最初に表示する）

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
$TOTLE_PAGES = ceil($TCNT/N6_1BOF_MAX);

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
<title></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="header"></div>
<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo N6_1TITLE;?>：新規登録</p>
<p class="explanation">
▼新規登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。<br>
▼最大登録件数は<strong><?php echo N6_1DBMAX_CNT;?>件</strong>です。<br>
▼最大登録件数以上のメールデータを受信した場合は古い内容から自動で削除されます。
</p>
<form action="./" method="post">
<select name="page_flg">
	 <?php for($i=0;$i<10;$i++):?>
	 <?php if($i==0){?>
	 <option value="<?php echo $i;?>" <?php if($fetch_page[0]['PAGE_FLG'] == 0)echo "selected";?>>すべて</option>
	 <?php }?>

	 <option value="<?php echo ($i+1);?>" <?php if(($i+1) == $fetch_page[0]['PAGE_FLG'])echo "selected";?>><?php echo ($i+1)?></option>
	 <?php endfor;?>
	</select><br>
<input type="submit" value="ページネーションの件数の更新" style="width:200px;">
<input type="hidden" name="res_id" value="<?php echo $fetch_page[0]['RES_ID'];?>">
<input type="hidden" name="status" value="page_flg">
<input type="hidden" name="regist_type" value="page_flg">
</form>

<?php
#-----------------------------------------------------
# 書込許可（最大登録件数に達していない）の場合に表示
#-----------------------------------------------------
if($fetchCNT[0]['CNT'] < N6_1DBMAX_CNT){?>
<form action="./" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="status" value="new_entry">
</form>
<?php }else{?>
<p class="err">最大登録件数<?php echo N6_1DBMAX_CNT;?>件に達しています。<br>
新規登録を行う場合は、いずれかの既存データを削除してください。</p>
<?php }?>

<p class="page_title"><?php echo N6_1TITLE;?>：登録一覧</p>
<p class="explanation">
▼既存データの修正は<strong>「編集」</strong>をクリックしてください。<br>
▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
▼サブジェクトがない場合は「No Subject」になります。</p>
<?php if(!$fetch):?>
<p><b>登録されているデータはありません。</b></p>
<?php else:?>
<div>※登録データ件数：<strong><?php echo $TCNT;?></strong>&nbsp;件</div>
<table width="700" border="0" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th width="20%" nowrap>日付</th>
		<th width="10%" nowrap>画像</th>
		<th nowrap><div align="left">サブジェクト</div></th>
		<th width="10%" nowrap>編集</th>
		<th width="10%" nowrap>表示</th>
		<th width="10%" nowrap>削除</th>
		<th width="10%">プレビュー</th>
	</tr>
	<?php for($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td><div align="center"><?php echo $fetch[$i]["Y"];?>年<?php echo $fetch[$i]["M"];?>月<?php echo $fetch[$i]["D"];?>日</div></td>
		<td>
		<!--<div align="left" <?php //if(file_exists("../../05_photodiary/img/".$fetch[$i]['DIARY_ID'].".jpg")){?>onMouseOver="OnLink('L<?php //echo $i;?>',event.x,event.y,event.pageX,event.pageY)" onMouseOut="OffLink('L<?php //echo $i;?>')" style="color:#0000FF;"<?php //}?>>-->
		<?php

		$IMAGE_FILE = N6_1IMG_PATH.$fetch[$i]['DIARY_ID'].".jpg";
		if(file_exists($IMAGE_FILE)){?>
			<img src="<?php echo $IMAGE_FILE?>?r=<?php echo rand();?>" width="<?php echo N6_1IMGSIZE_SX;?>">

		<?php }
		else{
			echo "no photo";
			}
		?>
		<!--</div>-->
		</td>
		<td>
		<?php

			if(!$fetch[$i]['SUBJECT']){
				echo "No Subject";
			}elseif($fetch[$i]['SUBJECT'] == " "){
				echo "No Subject";
			}else{
				echo $fetch[$i]['SUBJECT'];
			}
		?>
		<!--</div>-->
		</td>

		<td><div align="center">
		<form action="./" method="post" enctype="multipart/form-data" style="margin:0">
		<input type="submit" name="reg" value="編集" style="width:60px;">
		<input type="hidden" name="status" value="update">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		<input type="hidden" name="diary_id" value="<?php echo $fetch[$i]['DIARY_ID'];?>">
		</form></div>
		</td>
		<td><div align="center">
		<form action="./" method="post" style="margin:0">
		<input type="submit" name="reg" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"現在非表示";?>" style="width:80px;">
		<input type="hidden" name="diary_id" value="<?php echo $fetch[$i]['DIARY_ID'];?>">
		<input type="hidden" name="status" value="display_change">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		<input type="hidden" name="display_change" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		</form></div>
		</td>
		<td>
		<div align="center">
		<form method="post" action="./" onSubmit="return del_chk(this);" style="margin:0">
		<input type="submit" value="削除" style="width:60px;">
		<input type="hidden" name="diary_id" value="<?php echo $fetch[$i]['DIARY_ID'];?>">
		<input type="hidden" name="status" value="del_data">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		</form>
		</div>
		</td>
		<td align="center">
		<form method="post" action="<?php echo PREV_PATH;?>" target="_blank" style="margin:0;">
		<input type="submit" value="プレビュー">
		<input type="hidden" name="diary_id" value="<?php echo $fetch[$i]['DIARY_ID'];?>">
		<input type="hidden" name="page" value="<?php echo $page;?>">
		<input type="hidden" name="status" value="prev">
		</form>
		</td>
	</tr>
	<?php endfor;?>
</table>
<table width="700" height="38" border="0" cellpadding="0" cellspacing="0">
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
</body>
</html>
