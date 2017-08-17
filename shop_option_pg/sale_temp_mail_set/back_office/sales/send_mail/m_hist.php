<?php
/*******************************************************************************
メールの送信記録を表示

*******************************************************************************/
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../../common/INI_config.php");		// 共通設定情報
require_once("../../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("../../../common/INI_pref_list.php");		// 都道府県＆送料データ（配列）

require_once("dbOpe.php");					// DB操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once('imgOpe.php');					// 画像アップロードクラスライブラリ

	// POSTデータの受け取りと共通な文字列処理（メール送信用）
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}

	//送信記録を取得する
		$sql = "
		SELECT
			*
		FROM
			MAIL_HIST_LST
		WHERE
			(ORDER_ID  = '$target_order_id')
		ORDER BY
			INS_DATE DESC
		";

		$fetchMH = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>送信記録</title>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<p class="page_title">売上管理：メール送信記録</p>
<p class="explanation">
▼今まで送られたメールの内容が表示されます。
</p>

<?php if(count($fetchMH) == 0):?>
<strong>送信記録がありません</strong>
<br><br><br>
<?php else:?>

<table width="800" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="4" class="tdcolored">■メール送信記録</th>
	</tr>
	<tr>
		<th class="tdcolored" width="5%">送信日</th>
		<th class="tdcolored" width="10%">送信先</th>
		<th class="tdcolored" width="20%">件名</th>
		<th class="tdcolored">本文</th>
	</tr>
	<?php for($i=0;$i < count($fetchMH);$i++){?>
	<tr>
		<th class="other-td"><?php echo $fetchMH[$i]["INS_DATE"];?></th>
		<th class="other-td" ><?php echo $fetchMH[$i]["EMAIL"];?></th>
		<th class="other-td" ><?php echo $fetchMH[$i]["SUBJECT"];?></th>
		<th class="other-td" align="left">
			<span  style="overflow:auto;width:100%;height:400px;">
			<?php echo nl2br($fetchMH[$i]["CONTENT"]);?>
			</span>
		</th>
	</tr>
	<?php }?>
</table>
<?php endif;?>

</body>
</html>