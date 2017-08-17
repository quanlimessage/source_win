<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
詳細の内容を表示するプログラム

DSP：DBより取得情報をHTML出力

*******************************************************************************/
// 不正アクセスチェック
if(!$injustice_access_chk){
	header("Location: ../");exit();
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
<meta http-equiv="Content-language" content="ja">
<meta name="description" content="<?php echo $fetch[0]["TITLE"];?>">
<meta name="keywords" content="<?php echo $fetch[0]["TITLE"];?>">
<meta name="robots" content="index,follow">
<title><?php echo $fetch[0]["TITLE"];?></title>
<link href="../popup.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript"></script>
<script type="text/javascript">
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
<br><br>
<table width="460" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF" class="pop_text">
   <tr>
    <td height="300" colspan="2" bgcolor="#EAF2F6" align="center">
	<?php // 画像
		$img_path = "./up_img/".$res_id.'_'.($pid+2).'.jpg';
		if(file_exists($img_path)){
			echo "<img src=\"".$img_path."?r='".rand()."\" border=\"0\">\n";
		}
		?>
	</td>
  </tr>
</table>
<br><br>
<p align="center"><input type="button" value="Cloese Button" onClick="javascript:window.close();"></p>
</div>
</body>