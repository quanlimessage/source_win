<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
カレンダー用プログラム
View：取得したデータをHTML出力

***********************************************************/

require_once('../common/config_calendar.php');
require_once('util_lib.php');
require_once('dbOpe.php');
#-------------------------------------------------------------
# HTTPヘッダーを出力
#	１．文字コードと言語：utf8で日本語
#	２．ＪＳとＣＳＳの設定：する
#	３．有効期限：設定しない
#	４．キャッシュ拒否：設定する
#	５．ロボット拒否：設定しない
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);

//IDの取得
$id = $_GET["id"];

//不正入力チェック
if(empty($id) || !is_numeric($id) ){
	header("Location: ../");exit();
}

	// SQL組立て
	$sql = "
	SELECT
		DETAIL_TITLE,
		DETAIL_CONTENT
	FROM
		".EVENT."
	WHERE
		(ID = '$id')
		AND
		(DEL_FLG = '0')
	";
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-language" content="ja">
	<meta http-equiv="Content-script-type" content="text/javascript">
	<meta http-equiv="Content-style-type" content="text/css">
	<meta http-equiv="imagetoolbar" content="no">
	<meta name="description" content="ＳＥＯワード">
	<meta name="keywords" content="キーワード">
	<meta name="robots" content="index,follow">
	<title>Winシリーズ-更新プログラム|サンプルサイト|</title>
	<link href="../css/base.css" rel="stylesheet" type="text/css">
	<link href="../css/index.css" rel="stylesheet" type="text/css">
</head>

<body onload="focus()">

	<div style="width:100%;text-align:center;">

		<?php if(!count($fetch)):?>
		<p>
			<center>ただいま準備中のため、もうしばらくお待ちください。</center>
		</p>
		<?php else:?>
			<p style="margin:10px 5px;font-size:20px;">
				<?php echo nl2br($fetch[0]["DETAIL_TITLE"]);?>
			</p>
			<p style="margin:10px 5px;">
				<?php echo nl2br($fetch[0]["DETAIL_CONTENT"]);?>
			</p>
		<?php endif;?>

		<div class="close"><a href="javascript:window.close();">閉じる</a></div>
	</div>

</body>
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="../log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
</html>
