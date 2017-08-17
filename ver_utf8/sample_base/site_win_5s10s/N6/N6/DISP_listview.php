<?php

/*******************************************************************************
SiteWin10 20 30（MySQL対応版）
N系写メールプログラム

View：取得したデータをHTML出力

*******************************************************************************/

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
//utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);

#-------------------------------------------------------------
# ページ遷移の設定
#-------------------------------------------------------------
/*
$NEXT = $page + 1;

$PREVIOUS = $page - 1;
// CHECK ALL DATA
$TCNT = $fetchCNT[0]["CNT"];
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/N6_1PAGE_MAX);

// SET DISPLAY
if($page > 1){
	$PREVIOUS_PAGE = "<a href=\"./?page={$PREVIOUS}\">＜＜前のページ</a>";
}else{
	$PREVIOUS_PAGE = "";
}

if($TOTLE_PAGES > $page){
	$NEXT_PAGE = "<a href=\"./?page={$NEXT}\">次のページ＞＞</a> ";
}else{
	$NEXT_PAGE = "";
}
*/
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
		$totalpage = ceil($tcnt/$page_flg);

		// カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
		if($ca)$cpram = "&ca=".urlencode($ca);

		// 前ページへのリンク
		if($p > 1){
			$link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">＜＜前のページ</a>";
		}

		//次ページリンク
		if($totalpage > $p){
			$link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">次のページ＞＞</a>";
		}
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
	<br><br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br> 写メール日記（Ｎ６）</h5>
	<br>
		<table border="0" cellapdding="0" cellspacing="0" width="501">
		<tr>
			<td width="500">
			 <?php

		 	if(!count($fetch)):
				echo "<center><br>ただいま準備中のため、もうしばらくお待ちください。<br><br></center>\n";//表示件数が０件の場合
			else:

				 for($i=0;$i<count($fetch);$i++):

			 	//ID
				$id = $fetch[$i]["DIARY_ID"];

			 	//日付
				$time = sprintf("%04d/%02d/%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);

			 	//タイトル
				$title = ($fetch[$i]["SUBJECT"])?$fetch[$i]["SUBJECT"]:"&nbsp;No Subject&nbsp;";

			 	//コメント
				$content = ($fetch[$i]["COMMENT"])?nl2br($fetch[$i]["COMMENT"]):"&nbsp;";

				//リンク先、リンク設定タイプ
				$link = $fetch[$i]["LINK"];
				$link_flg = $fetch[$i]["LINK_FLG"];
				//イメージ拡大有り・無し
				$img_flg = $fetch[$i]["IMG_FLG"];

				if($link){
						if($link_flg == 2){
							$title = "<a href=\"{$link}\" target=\"_blank\">{$title}</a>";
						}
						elseif($link_flg == 3){
							$title = "<a href=\"{$link}\">{$title}</a>";
						}

				}

			 	//画像
			 	//$img = "./up_img/".$id.".jpg";
				// 画像
				if($_POST['status']){//プレビュー
					$img = $prev_img[1];
				}else{// 表示する画像を検索
					$img = "./up_img/".$id.".jpg";
				}

				if(file_exists($img)):
					$img = $img."?r=".rand();
					$image = "<img src=\"{$img}\" border=\"0\">";
				else:
					$image = "";
				endif;

				//表示内容を格納
				$table = "
					<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"500\">
						<tr>
							<td colspan=\"2\" bgcolor=\"#FFFFFF\" class=\"midashi\">
								<b>～{$title}～</b>
							</td>
						</tr>
						<tr>
							<td align=\"left\" class=\"text\" bgcolor=\"#FFFFFF\">
								{$image}
								{$content}
								<p class=\"day\" align=\"right\">{$time}</p>
							</td>
						</tr>
					</table>
					<br>
				";

				//表示内容を表示
			 	echo $table;

				endfor;
			endif;

			 ?>
			  <table width="500" border="0" cellpadding="0" cellspacing="0" class="table_main">
				<!--<tr>
				   <td align="left" class="table_comment2"><?php echo $PREVIOUS_PAGE;?></td>
				   <td align="right" class="table_comment2"><?php echo $NEXT_PAGE;?></td>
				 </tr>-->
				 <tr>
				   <td align="left" class="table_comment2"><?php echo $link_prev;?></td>
				   <td align="right" class="table_comment2"><?php echo $link_next;?></td>
				 </tr>
			  </table>
			  <img src="../image/spacer.gif" alt="" border="0" width="50" height="20">
			  <br>
			</td>
		</tr>
		</table>
		<br>
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
