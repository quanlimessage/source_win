<?php
// ������饤�֥��ե������ɤ߹���
require_once("../common/INI_config.php");

#============================================================================
# GET�ǡ����μ����ʸ��������ʶ��̽�����	�����ѽ������饹�饤�֥������
#============================================================================
// ����������ν����ʸ��̵��������\�ɤ��롿Ⱦ�ѥ��ʤ����Ѥ��Ѵ�
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4),true));

//config.php�˸ܵҥ����ɤ���Ͽ����Ƥ���С��ʲ��ν�����Ԥ�
if(UW_CUSTOMER_CODE != ""):

		$params = "c_code=".UW_CUSTOMER_CODE;
		$params .= "&domain=".$_SERVER["SERVER_NAME"];

		//curl���Ѥ����̿���»�
		$ch = curl_init();

		$url = UW_INFO_URL;

	//curl�Υ��ץ���������
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//TRUE �����ꤹ��ȡ�curl_exec() ���֤��ͤ� ʸ������֤��ޤ����̾�ϥǡ�����ľ�ܽ��Ϥ��ޤ���
		//curl_setopt($ch, CURLOPT_PROXY, '*********');		//�ꥯ�����Ȥ��ͳ������ HTTP �ץ�������https �ꥯ�����Ȥ�����������ʤɤ˻��ѡ�
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	//FALSE �����ꤹ��ȡ�cURL �ϥ����о�����θ��ڤ�Ԥ��ޤ���
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);	//1 �� SSL �ԥ�������˰���̾��¸�ߤ��뤫�ɤ�����Ĵ�٤ޤ��� 2 �Ϥ���˲ä�������̾�����ۥ���̾�Ȱ��פ��뤳�Ȥ򸡾ڤ��ޤ���

		curl_setopt($ch, CURLOPT_POST, TRUE);			//TRUE �����ꤹ��ȡ�HTTP POST ��Ԥ��ޤ���
		//curl_setopt($ch, CURLOPT_HTTPHEADER,Array('Content-Type: text/xml;charset=shift_jis'));	//XML�ѥإå�������
		curl_setopt($ch, CURLOPT_URL, $url);//��³�������

		//HTTP "POST" ���������뤹�٤ƤΥǡ����� �ե��������������ˤϡ��ե�����̾����Ƭ�� @ ��Ĥ��ƥե�ѥ�����ꤷ�ޤ���

		//����ϡ� 'para1=val1&para2=val2&...' �Τ褦�� url ���󥳡��ɤ��줿ʸ����������Ϥ����Ȥ�Ǥ��ޤ����� �ե������̾�򥭡����ǡ������ͤȤ���������Ϥ����Ȥ�Ǥ��ޤ���
		//value ������ξ�硢 Content-Type �إå��ˤ� multipart/form-data �����ꤷ�ޤ���
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);

	//�ꥯ�����Ȥ����������쥹�ݥ󥹤����
		$result_data = curl_exec($ch);

endif;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
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

    <td align="center"><img src="img/title.gif" width="615" height="49"></td>
  </tr>
  <tr>

    <td align="center">�� ��Ͽ���������ɬ��JPEG�����ˤ��Ƥ���������<br>
            �� �֥饦���Ρ����٥ܥ���ϲ����ʤ��褦�ˤ��Ƥ���������<br>
            �� Ĺ�������򤷤ʤ��ȥ����ॢ���ȤȤʤ�ޤ������٥�����ξ塢�����Ѥ���������<br>
            �� Ⱦ�ѥ������ʡ��ڤ�Ⱦ�ѵ�������Ϥ��Ƥ�������ɽ������ʤ���礬����ޤ���<br></td>
  </tr>

<?php if($result_data == "open"):?>
<tr>

    <td align="center">
<a href="http://support-navi.stagegroup.jp/" target="_blank"><img src="img/support.jpg" alt="���ݡ��ȥʥ�" name="support" border="0" id="support" onMouseOver="MM_swapImage('support','','img/support_on.jpg',1)" onMouseOut="MM_swapImgRestore()"></a>
<p><strong>�����ݡ��ȥʥӥХʡ������Τ餻�󤫤�����˰�ư�����硢���ݡ��ȥʥ��Ѥ�ID/PASS��ɬ�פǤ���<br>
ID/PASS��˺�������[ <script type="text/javascript" language="javascript">
<!--

function cryptMail(){
var s="69K>H:GrHI6<:<GDJE`?E",r="";
for(i=0;i<s.length;i++)r+=String.fromCharCode((s.charCodeAt(i)+10)%93+33);
document.write('<a href="mailto:'+r+'">'+r+'</a>');
}
cryptMail();
//-->
</script>
<noscript>ad<!--Xzt-->vi<!--CW-->ser<!--hS-->@st<!--xO-->ag<!--
PWm-->eg<!--ZbH-->rou<!--Ri-->p<!--v-->.<!--QS-->j<!--
PM-->p</noscript>]�ޤǤ��䤤��碌����������</strong>
</p></td>
  </tr>
  <tr>

    <td align="center"><IFRAME SRC="<?php echo UW_INFO_URL;?>info.php" scrolling="auto" width="650" height="350" align="center">
����饤��̤�б��ξ��ɽ��������ʸ��
</IFRAME></td>
  </tr>
<?php endif;?>
</table>

  </tr>
</table>
</body>
</html>