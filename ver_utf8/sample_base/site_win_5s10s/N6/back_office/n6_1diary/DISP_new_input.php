<?php
/*******************************************************************************
更新プログラム

	View：新規登録画面表示

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
<title></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../actchange.js" type="text/javascript"></script>
</head>
<body>
<div class="header"></div>
<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo N6_1TITLE;?>：新規登録</p>
<p class="explanation">
▼新規登録画面です。<br>
▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてください。
</p>
<form action="./" method="post" enctype="multipart/form-data" style="margin:0px;" name="new_regist">
<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■ 新規登録</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored" width="20%">日付：</th>
		<td class="other-td">表示する日付です。<br>
            <select name="y">
            	<?php for($yn=2010;$yn<=(date("Y")+10);$yn++):?>
            	<option value="<?php echo $yn;?>"<?php echo (date("Y") == $yn)?" selected":"";?>>
            	<?php echo $yn;?>
            	</option>
            	<?php endfor;?>
       	  </select>
			年
			<select name="m">
				<?php for($mn=1;$mn<=12;$mn++):?>
				<option value="<?php echo $mn;?>"<?php echo (date("n") == $mn)?" selected":"";?>>
				<?php echo $mn;?>
				</option>
				<?php endfor;?>
			</select>
			月
			<select name="d">
				<?php for($dn=1;$dn<=31;$dn++):?>
				<option value="<?php echo $dn;?>"<?php echo (date("j") == $dn)?" selected":"";?>>
				<?php echo $dn;?>
				</option>
				<?php endfor;?>
			</select>
			日</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">サブジェクト：</th>
		<td class="other-td">
		<input name="subject" type="text" id="subject" style="ime-mode:active;" value="" size="38" maxlength="127" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">メールアドレス：</th>
		<td class="other-td">
		<input name="email" type="text" id="email" style="ime-mode:disabled;" value="" size="38" maxlength="200">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">内容：</th>
		<td class="other-td">
		<select name="fontsize" onChange="CheckObj();addFontsSize(Temp.name,this); this.options.selectedIndex=0; return false;">
			<option value="">サイズ</option>
			<option value="x-small">極小</option>
			<option value="small">小</option>
			<option value="medium">小さめ</option>
			<option value="large">中</option>
			<option value="x-large">大きめ</option>
			<option value="xx-large">大</option>
			</select>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:left;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_left.png" width="16" height="16" alt="テキストを左寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:center;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_center.png" width="16" height="16" alt="テキストを真中寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:right;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_right.png" width="16" height="16" alt="テキストを右寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a><br>
		    <textarea name="comment" cols="60" rows="5" wrap="VIRTUAL" id="comment" style="ime-mode:active;" onFocus="SaveOBJ(this)"></textarea>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">画像：</th>
		<td class="other-td">アップロード後画像サイズ：<strong>横<?php echo N6_1IMGSIZE_MX;?>px×縦<?php //echo N6_1IMGSIZE_MY."px";?> 自動算出</strong><br>
		<input name="img_file" type="file" size="38">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">リンク先：</th>
		<td class="other-td">
		<input name="link" type="text" value="<?php echo $link;?>" size="80" style="ime-mode:inactive">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">リンク先表示タイプ：</th>
		<td class="other-td">
		<input name="link_flg" id="link_flg1" type="radio" value="1" checked>リンクさせない
		<input name="link_flg" id="link_flg2" type="radio" value="2">別のウィンドウ
		<input name="link_flg" id="link_flg3" type="radio" value="3">現在のウィンドウ</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">画像の拡大有/無：</th>
		<td class="other-td">
		<input name="img_flg" id="img_flg1" type="radio" value="1" checked>有
		<input name="img_flg" id="img_flg2" type="radio" value="0">無
		</td>
	</tr>
</table>
<input type="submit" value="上記の内容で登録する" style="width:150px;" onClick="chgsubmit();return confirm_message(this.form);">
<input type="hidden" name="status" value="completion">
<input type="hidden" name="regist_type" value="new">
<input type="submit" value="プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?php echo PREV_PATH;?>')">
</form>
<form action="./" method="post" enctype="multipart/form-data">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
</form>
<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");
?>
</body>
</html>
