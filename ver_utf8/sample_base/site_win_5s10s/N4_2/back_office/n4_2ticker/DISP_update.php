<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理画面</title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title"><?php echo N4_2TITLE;?>：既存データ編集画面</p>
<p class="explanation">
▼現在のデータ内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。<br>
▼最大登録表示文字数は日本語で<strong><?php echo N4_2TXTMAX_CNT;?>文字まで</strong>です。
</p>
<form action="./" method="post" enctype="multipart/form-data" onSubmit="return confirm_message(this);" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■データの更新</th>
	</tr>
	<?php /*<tr>
		<th width="15%" nowrap class="tdcolored">表示日付：</th>
		<td class="other-td">
		現在表示されている日付です。<br>
		<select name="y">
		<?php for($i=2010;$i<=(date("Y")+10);$i++):?>
		<option value="<?php printf("%04d",$i);?>"<?php echo ($fetch[0]["Y"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m">
		<?php for($i=1;$i<=12;$i++):?>
		<option value="<?php printf("%02d",$i);?>"<?php echo ($fetch[0]["M"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d">
		<?php for($i=1;$i<=31;$i++):?>
		<option value="<?php printf("%02d",$i);?>"<?php echo ($fetch[0]["D"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		日
		</td>
	</tr>*/?>
	<tr>
		<th nowrap class="tdcolored">コメント：</th>
		<td class="other-td">
		<input name="comment" type="text" value="<?php echo $fetch[0]["COMMENT"];?>" size="<?php echo N4_2TXTMAX_CNT*2;?>" maxlength="<?php echo N4_2TXTMAX_CNT;?>" style="ime-mode:active;">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">リンクURL：</th>
		<td class="other-td">
		<input name="linkurl" type="text" value="<?php echo $fetch[0]["LINKURL"];?>" size="<?php echo N4_2TXTMAX_CNT*2;?>" style="ime-mode:disabled;">
		</td>
	</tr>
	<?php /*<tr>
		<th nowrap class="tdcolored">本文：</th>
		<td class="other-td"><textarea name="content" cols="55" rows="5" style="ime-mode:active"><?php echo $fetch[0]["CONTENT"];?></textarea></td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">画像：</th>
		<td class="other-td">
		<?php if(file_exists(N4_2IMG_PATH.$fetch[0]['RES_ID'].".jpg")):?>
		現在表示中の画像<br>
		<img src="<?php echo N4_2IMG_PATH.$fetch[0]['RES_ID'].".jpg";?>?r=<?php echo rand();?>" width="<?php echo N4_2IMGSIZE_MX;?>" height="<?php echo N4_2IMGSIZE_MY;?>">
		<input type="checkbox" name="del_img" value="1" id="del_img"><label for="del_img">この画像を削除</label>
		<br>
		<?php endif;?>
		アップロード後画像サイズ：<strong>横<?php echo N4_2IMGSIZE_MX;?>px×縦<?php echo N4_2IMGSIZE_MY;?>px</strong><br>
		<input type="file" name="up_img" value="">
	  </td>
	</tr>*/?>
	<tr>
		<th nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1"<?php echo ($fetch[0]["DISPLAY_FLG"]==1)?" checked":"";?>>
		<label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"<?php echo ($fetch[0]["DISPLAY_FLG"]==0)?" checked":"";?>>
		<label for="dispoff">非表示</label>
		</td>
	</tr>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
</form>

<form action="./" method="post">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
</form>
</body>
</html>
