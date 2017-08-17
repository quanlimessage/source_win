<?php
/*******************************************************************************
SiteWiN10 20 30（MySQL版）
カレンダーの内容を出力するプログラム

View：取得したデータをHTML出力

*******************************************************************************/

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
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
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
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>カレンダー２
		<br><br>
		<div align="left" style="width:300;border: solid 1px #cccccc;background-color:#FFFFFF;color:#333333;margin: 5px;padding: 5px;">
		サンプルプログラム カレンダー２<br>

		<br>
		<ul>
		<li>カレンダー２では管理画面で営業と休日の切り替え表示をします。</li>
		</ul>
		<br>

		こちらはデモ用のサンプルプログラムです。<br>
		サンプルプログラムのご使用は【sample_base】フォルダーのプログラムをご使用ください。
		</div>
		<br>

		 </h5>
		<br>

	<!-- ▼記事 -->
	<?php printf("%d 年　%02d 月",$y,$m);?>の営業カレンダー
	<table cellspacing="0" cellpadding="0" summary="イベントスケジュール" border="1">
		<tr>
		<th scope="col" class="sun" style="width:50px;">日</th>
		<th scope="col" style="width:50px;">月</th>
		<th scope="col" style="width:50px;">火</th>
		<th scope="col" style="width:50px;">水</th>
		<th scope="col" style="width:50px;">木</th>
		<th scope="col" style="width:50px;">金</th>
		<th scope="col" class="sat" style="width:50px;">土</th>
		</tr>
			<?php

			// 曜日NOを設定しておく
			$wday = 1;

			//スケジュールIDのセット
			$sid=$fetch[0]["SCHEDULE_ID"];

			while($Day = $Month->fetch()):

				if($Day->isFirst())echo "<tr>\n";

				// イベント情報の表示
				$show = "";
				switch($fetch[0][DAY_.$Day->thisDay()]):
					case "0":
						//1のばあいは営業しない
						$schedule = $Day->thisDay()."<br><span style=\"background-color: #55A8F9;padding:5px;\">営業</span>";
					break;
					case "1":
						//休日表示
							$schedule = $Day->thisDay()."<br><span style=\"background-color: #FF6366;padding:5px;\">休日</span>";

					break;

					default:
						//その月が管理画面で設定されていない場合　デフォルトで営業を表示
						//$schedule = $Day->thisDay();
						$schedule = $Day->thisDay()."<br><span style=\"background-color: #55A8F9;padding:5px;\">営業</span>";
				endswitch;

			if($Day->isEmpty()){
				echo "<td class=\"none\">&nbsp;</td>\n";
			}else{
				echo "<td valign=\"top\" height=\"50\">".$schedule."</td>\n";
			}

			// 曜日のインクリメント
				$wday++;
				if($Day->isLast()){
					// 曜日を初期化
					$wday = 1;
					echo "</tr>\n";
				}

			endwhile;

			?>
		</table>
	<br>
	<!-- ▲記事 ここまで -->
	<table cellspacing="0" cellpadding="0" class="nb_table" summary="次のページ・前のページへのリンク" width="500">

		<tr>

			<td class="nb_left"><a href="./?y=<?php if($m == 1){echo ($y-1);}else{echo $y;}?>&m=<?php if($m == 1){echo "12";}else{echo ($m-1);}?>">前のページへ</a></td>
			<td class="nb_right"><a href="./?y=<?php echo $y;?>&m=<?php echo ($m+1);?>">次のページへ</a></td>
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