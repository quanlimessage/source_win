<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）N3_2
新着情報の内容を表示するプログラム

DSP：DBより取得情報をHTML出力

2005/10/13 : yossee
2006/6/9 arai
*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>新着情報</title>
<link href="../css/base.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
window.onload = function(){
window.moveTo(0,0);
window.focus();
}
//-->
</script>
</head>

<body bgcolor="#e6e6e6">
<div align="center">
<br>
<img src="../image/new_title.jpg" alt="新着情報">
<table width="460" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF">
  <tr>
    <td colspan="2" bgcolor="#EAF2F6" align="left">
		<?php
		// 日付
		echo sprintf("%04d/%02d/%02d", $fetch[0]['Y'], $fetch[0]['M'], $fetch[0]['D']);?>
		<b>
		<?php
		// タイトル
		echo (!empty($fetch[0]['COMMENT']))?$fetch[0]['COMMENT']:"";?>
		</b>
	</td>
  </tr>
  <tr>
	<?php // 画像
	if(file_exists('up_img/'.$fetch[0]['RES_ID'].'.jpg')){?>
	<td width="220" bgcolor="#EAF2F6">
		<?php echo "<img src=\"up_img/".$fetch[0]['RES_ID'].'.jpg?r='.rand()."\" width=\"".N4_1IMGSIZE_MX."\" height=\"".N4_1IMGSIZE_MY."\" border=\"0\">\n";?>
	</td>
	<td align="left" valign="top" bgcolor="#EAF2F6">
	<?php }
		else{?>

    <td colspan="2" width="100%" align="left" valign="top" bgcolor="#EAF2F6">
	<?php }?>
	<?php // コメント
	if(!empty($fetch[0]['CONTENT']))echo nl2br($fetch[0]['CONTENT']);?>
	</td>

  </tr>
</table>
<p align="center"><input type="button" value="Close Button" onClick="javascript:window.close();"></p>
</div>
</body>
