<?php
// 設定＆ライブラリファイル読み込み
require_once("../common/blog_config.php");

#============================================================================
# GETデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#============================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
if ($_GET) {
    extract(utilLib::getRequestParams("get", [8,7,1,4], true));
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
<link href="for_main.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" onLoad="MM_preloadImages('img/support_on.jpg')">
<table width="98%" height="" align="center" cellpadding="5" cellspacing="5">
  <tr>

    <td align="center"><img src="img/pg_title01.jpg" width="650" height="84" alt="更新プログラム管理画面"></td>
  </tr>
  <tr>

    <td align="center">
    <ul class="attention_box">
      <li> <span>※</span> 登録する画像は必ずJPEG形式にしてください。 </li>
      <li> <span>※</span> ブラウザの『戻る』ボタンは押さないようにしてください。 </li>
      <li> <span>※</span> 長時間操作をしないとタイムアウトとなります。再度ログインの上、ご利用ください。 </li>
      <li> <span>※</span> 半角カタカナ、及び半角記号は入力しても正しく表示されない場合があります。 </li>
    </ul></td>
  </tr>

</table>

  </tr>

</table>
</body>
</html>
