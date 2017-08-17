<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
D系表示用プログラム
View：取得したデータをHTML出力

***********************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	１．文字コードと言語：utf8で日本語
#	２．ＪＳとＣＳＳの設定：する
#	３．有効期限：設定しない
#	４．キャッシュ拒否：設定する
#	５．ロボット拒否：設定しない
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
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
<link href="../css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript" src="java.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

		<h1>ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト<br>ＣＳＶ書き出し&nbsp;(D1)</h5>
	<br>
			<!-- ↓商品一覧表示はここから↓ -->
			<?php
			for($i=0;$i<count($fetch);$i++):?>

			<table width="520px"  border="0" cellspacing="0" cellpadding="4" bgcolor="#999999">
			<tr bgcolor="#FFFFFF">
				<td colspan="2" align="left" class="s_midashi">
				<?php // タイトル（商品名）
					echo $fetch[$i]["TITLE"];?>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
			<?php
			// 画像がある場合は表示
			$img_path = "up_img/".$fetch[$i]['RES_ID'].'.jpg';
			if(file_exists($img_path)){?>

				<td width="230" class="s_image">
					<?php	echo "<img src=\"{$img_path}?r=".rand()."\" border=\"0\">\n";?>
				</td>

				<td width="290" align="left" class="s_text">
			<?php }
				else{?>
				<td width="520" colspan="2" align="left" valign="top" class="s_text2">

			<?php }?>

					<?php // コメント
					echo nl2br($fetch[$i]["CONTENT"]);?>
				</td>
			</tr>
			</table>
			<br>
			<?php endfor;?>

			<?php
			#===================================================================
			# 画面遷移のナビゲーションを表示（出力件数が“0”の場合は非表示）
			#===================================================================
			if($fetchCNT[0]["CNT"] > D1_DISP_MAXROW):?>
				<table width="520px" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" align="left">&nbsp;
					<?php
					#---------------------------------------------------------------------
					#“前へ”ページ遷移の制御用のナビゲートを作成
					#	１．パラメータに設置する値を設定（現在の開始値－最大表示件数）
					#	２．値が“0”以上の場合は“前へ”リンクを表示
					#---------------------------------------------------------------------
					$prev_start = ($start - D1_DISP_MAXROW);
					if($prev_start >= 0){
						echo "<a href=\"./?ns={$prev_start}\">&lt;&lt; 前へ</a>\n";
					}?>
					</td>
					<td width="50%" align="right">&nbsp;
				<?php
					#---------------------------------------------------------------------
					#“次へ”ページ遷移の制御用のナビゲートを作成
					#	１．パラメータに設置する値を設定（現在の開始値＋最大表示件数）
					#	２．値が現在のデータ登録数より小さい場合は“次へ”リンクを表示
					#---------------------------------------------------------------------
					$next_start = ($start +D1_DISP_MAXROW);
					if($next_start < $fetchCNT[0]["CNT"]){
						echo "<a href=\"./?ns={$next_start}\">次へ &gt;&gt;</a>\n";
					}?>
					</td>
				</tr>
				</table>
			<?php
			#===================================================================
			# 画面遷移のナビゲーション表示の終了
			#===================================================================
			endif;?>
	<form method="post" action="csv.php">
		<input type="submit" value="CSV出力">
	</form>
	</div>
	</div>

	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="../image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="../log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
</html>