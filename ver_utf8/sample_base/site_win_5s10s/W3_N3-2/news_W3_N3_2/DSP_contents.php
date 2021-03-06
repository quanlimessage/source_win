<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム
View：取得したデータをHTML出力

***********************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

	#--------------------------------------------------------
	# ページング用リンク文字列処理
	#--------------------------------------------------------

		//ページリンクの初期化
		$link_prev = "";
		$link_next = "";

		// 次ページ番号
		$next = $p + 1;
		// 前ページ番号
		$prev = $p - 1;

		// 商品全件数
		$tcnt = count($fetchCNT);
		// 全ページ数
		$totalpage = ceil($tcnt/$page);

		// カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
		if($ca)$cpram = "&ca=".urlencode($ca);

		// 前ページへのリンク
		if($p > 1){
			$link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">&lt;&lt; Prev</a>";
		}

		//次ページリンク
		if($totalpage > $p){
			$link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">Next &gt;&gt;</a>";
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
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
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
/*
//ポップアップ表示用
window.onload = function pop(){
window.moveTo(0,0);
window.focus();
}
pop();
*/
//-->
</script>
<!--<script language="JavaScript" type="text/JavaScript" src="../JS/lightbox.js"></script>-->
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
		<div id="index_image"><br><br>
	<img src="../image/new_n2.jpg" alt="" width="520" height="31" border="0"><br><br>

	<!-- ▼記事 -->
	<?php

			if(!count($fetch)):
				echo "<center><br>ただいま準備中のため、もうしばらくお待ちください。<br><br></center>\n";//表示件数が０件の場合

			else:
				for($i=0;$i<count($fetch);$i++):

				//ID
				$id = $fetch[$i]['RES_ID'];

				//日付
				$time = sprintf("%04d/%02d/%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);

				//タイトル
				$title = ($fetch[$i]['TITLE'])?$fetch[$i]['TITLE']:"&nbsp;";

				//コメント
				$content = ($fetch[$i]['CONTENT'])?nl2br($fetch[$i]['CONTENT']):"&nbsp;";
				//image拡大有（１）・無（０）表示
				$img_flg = $fetch[$i]['IMG_FLG'];

				// 画像
				if($_POST['act']){//プレビュー
					$img = $prev_img[1];
				}else{// 表示する画像を検索（拡張子の特定）
					//画像
						if(search_file_flg("./up_img/",$fetch[$i]['RES_ID']."_1.*")){
							$img = search_file_disp("./up_img/",$fetch[$i]['RES_ID']."_1.*","",2);
						}else{
							$img = "";
						}

				}

				// 画像表示処理
					if(!file_exists($img)){
						$image = "";

					}else{
						//画像サイズが固定でない場合（サイズ自動調整、横固定縦可変など）
						$size = getimagesize($img);//画像サイズを取得
						if($img_flg==1){
							$image = "<a href=\"{$img}\"><img src=\"{$img}\" border=\"0\" width=\"{$size[0]}\" height=\"{$size[1]}\"></a>";
						}else{
							$image = "<img src=\"{$img}\" border=\"0\" width=\"{$size[0]}\" height=\"{$size[1]}\">";
						}

					}

				// 資料ファイル
					if($_POST['act']){//プレビュー
						$pdf_file = $prev_pdf;
					}else{// 表示する画像を検索（拡張子の特定）

						//資料ファイル
							//ファイルパス
							$pdf_file = "./up_img/".$fetch[$i]['RES_ID'].".".$fetch[$i]['EXTENTION'];
							$pdf_size = $fetch[$i]['SIZE'];//KB単位で表示
					}

					//ファイルのチェック処理
					if(file_exists($pdf_file)){

						$icon = $fetch[$i]['EXTENTION'];//アイコン用
						$file_path = "<a href=\"{$pdf_file}\" style=\"float:left;\" target=\"blank\"><img src=\"./icon_img/icon_{$icon}.jpg\" border=\"0\"></a>";
						$file_size = number_format($fetch[$i]['SIZE'] / 1024)."KB<br>";//KB単位で表示

						//ファイルの種類、マインと拡張子両方準備版
							switch($fetch[$i]["EXTENTION"]){
								case "xls":case "xlsx":case "application/vnd.ms-excel":
									$type = "EXCEL";
									break;
								case "doc":case "docx":case "application/msword":
									$type = "WORD";
									break;
								case "ppt":case "pptx":case "application/vnd.ms-powerpoint":
									$type = "POWER POINT";
									break;
								case "pdf": case "application/pdf":
									$type = "PDF";
									break;
								case "mp3":case "audio/mpeg":
									$type = "MP3";
									break;
								case "wmv":case "video/x-ms-wmv":
									$type = "Windows Media Video";
									break;
								default:
								$type = "";
							}

					}else{//ファイルが存在しない場合
						$file_path = "&nbsp;";
						$file_size = "&nbsp;";
						$type = "&nbsp;";
					}

				//表示内容の格納
				$table = "
				<a name=\"{$id}\"></a>
				<TABLE width=\"520\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#D0D0D0\">
					<TR>
						<TD align=\"left\" bgcolor=\"#ffffff\">
							<b>{$title}</b>
						</TD>
						<TD width=\"15%\" align=\"center\" bgcolor=\"#ffffff\">
							{$time}
						</TD>
					</TR>
					<TR>
						<TD colspan=\"2\" width=\"100%\" align=\"left\" bgcolor=\"#ffffff\">
							{$image}
							{$content}
						</TD>
					</TR>
					<TR>
						<td colspan=\"2\" align=\"center\" bgcolor=\"#ffffff\">
							{$file_size}
							{$type}
							{$file_path}
						</td>
					</TR>
				</TABLE>
				<br>
				";

				//表示内容を表示する
				echo $table;

				endfor;
			endif;

				?>
	<!-- ▲記事 ここまで -->
	<table width="520px" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" align="left">&nbsp;
					<?php echo $link_prev;?>
					</td>
					<td width="50%" align="right">&nbsp;
					<?php echo $link_next;?>
					</td>
				</tr>
				</table>
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
