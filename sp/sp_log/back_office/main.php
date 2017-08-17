<?php
// 設定＆ライブラリファイル読み込み
require_once("../common/config.php");

#============================================================================
# GETデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#============================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

//config.phpに顧客コードが登録されていれば、以下の処理を行う
if(UW_CUSTOMER_CODE != ""):

		$params = "c_code=".UW_CUSTOMER_CODE;
		$params .= "&domain=".$_SERVER["SERVER_NAME"];

		//curlを用いて通信を実施
		$ch = curl_init();

		$url = UW_INFO_URL;

	//curlのオプションを設定
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//TRUE を設定すると、curl_exec() の返り値を 文字列で返します。通常はデータを直接出力します。
		//curl_setopt($ch, CURLOPT_PROXY, '*********');		//リクエストを経由させる HTTP プロキシ。（https リクエストを送信する場合などに使用）
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	//FALSE を設定すると、cURL はサーバ証明書の検証を行いません。
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);	//1 は SSL ピア証明書に一般名が存在するかどうかを調べます。 2 はそれに加え、その名前がホスト名と一致することを検証します。

		curl_setopt($ch, CURLOPT_POST, TRUE);			//TRUE を設定すると、HTTP POST を行います。
		//curl_setopt($ch, CURLOPT_HTTPHEADER,Array('Content-Type: text/xml;charset=shift_jis'));	//XML用ヘッダー情報
		curl_setopt($ch, CURLOPT_URL, $url);//接続先を設定

		//HTTP "POST" で送信するすべてのデータ。 ファイルを送信するには、ファイル名の先頭に @ をつけてフルパスを指定します。

		//これは、 'para1=val1&para2=val2&...' のように url エンコードされた文字列形式で渡すこともできますし、 フィールド名をキー、データを値とする配列で渡すこともできます。
		//value が配列の場合、 Content-Type ヘッダには multipart/form-data を設定します。
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);

	//リクエストを送信し、レスポンスを取得
		$result_data = curl_exec($ch);

endif;?>
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

<?php if($result_data == "open"):?>
<tr>

    <td align="center">
<a href="http://support-navi.stagegroup.jp/login/?code=<?php echo UW_CUSTOMER_CODE;?>" target="_blank"><img src="img/read_contact.jpg" alt="せっかく作ったホームページ、活用できていますか?
STAGE GROUPのアフターサポートサービス
ホームページの効果を最大限に引き出します！
サポートナビをご覧ください" name="support" border="0" id="support" onMouseOver="MM_swapImage('support','','img/read_contact_on.jpg',1)" onMouseOut="MM_swapImgRestore()"></a></td>
  </tr>
  <tr>

    <td align="center">
    <IFRAME SRC="<?php echo UW_INFO_URL;?>info.php?code=<?php echo UW_CUSTOMER_CODE;?>" scrolling="auto" width="650" height="350" align="center">
インライン未対応の場合表示させる文字
</IFRAME>
</td>
  </tr>
<tr>
   <td align="center"><img src="img/pg_contact.jpg" width="650" height="95" alt=""></td>
  </tr>
<?php endif;?>
</table>

  </tr>

</table>
</body>
</html>