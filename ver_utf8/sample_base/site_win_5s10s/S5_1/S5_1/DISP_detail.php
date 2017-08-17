<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
新着情報の内容を表示するプログラム

DSP：DBより取得情報をHTML出力

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
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
<meta name="description" content="<?php echo $fetch[0]["TITLE"];?>／ＳＥＯワード">
<meta name="keywords" content="<?php echo $fetch[0]["TITLE"];?>／キーワード">
<meta name="robots" content="index,follow">
<title><?php echo $fetch[0]["TITLE"];?>／Winシリーズ-更新プログラム|サンプルサイト|</title>
<link href="../css/base.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.s5_table{
	padding:5px;
	border:solid 1px #999999;
}

.S5_table_img{
	padding: 10px 5px;
	border: solid 1px #999999;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript" src="java.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

		<h1><?php echo $fetch[0]["TITLE"];?>／ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">
	</ul>

	<div id="main">
		<div id="index_image">
		<br>
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>商品紹介（Ｓ５－１）&nbsp;ﾀﾃ表示</h5>
		<br>

		詳細画面<br><br>

				<?php

					#===============================================================================================
					# 変数を整形する
					# DBから取り出して整形が必要な変数等は軽い変数名に代入してテーブルテンプレートに貼り付ける
					# 例１）$id : 画像名等で頻繁に使用するので変数名を短くする
					# 例２）金額用変数 : number_formatを指定
					# 例３）改行込み文章 : nl2br
					# 例４）GET送信用変数 : urlencode
					# 例５）画像用変数
					#===============================================================================================

					//ＩＤ
						$id = $fetch[0]['RES_ID'];

					// タイトル
						$title = ($fetch[0]['TITLE'])?$fetch[0]['TITLE']:"&nbsp;";
						$ititle = ($fetch[0]['TITLE'])?$fetch[0]['TITLE']:"";

					// コメント
						$content = ($fetch[0]['DETAIL_CONTENT'])?nl2br($fetch[0]['DETAIL_CONTENT']):"&nbsp;";

					// 画像
					if($_POST['act']){//プレビュー
						$img = $prev_img[2];
					}else{// 表示する画像を検索（拡張子の特定）
						if(search_file_flg("./up_img/",$id."_2.*")){
							$img = search_file_disp("./up_img/",$id."_2.*","",2);
						}else{
							$img = "";
						}
					}

					if(!file_exists($img)){

						$image = "&nbsp;";//表示画像無し

					}else{

							$size = getimagesize($img);//画像サイズを取得
							$img = $img."?r=".rand();//キャッシュを読み込まないようにユニークな数値をあたえる

							$image = "<img src=\"".$img."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$ititle."\" border=\"0\">";

					}

					#==============================================================================================
					# テーブルテンプレート貼り付け
					# HTMLから商品情報テーブルソースを貼り付け変数を展開
					# 必ずソースをすっきりさせるためヒアドキュメントは使用せず
					# 上記で変数を整形してから貼り付ける
					#==============================================================================================

					//詳細内容を格納
					$detail = "
					<table width=\"500\" border=\"0\" bgcolor=\"#EEEEEE\" cellspacing=\"0\" cellpadding=\"0\" style=\"border: solid 1px #999999;\">
						<tr>

							<td rowspan=\"4\" width=\"250\" align=\"center\" valign=\"top\" class=\"S5_table_img\">{$image}</td>

							<td align=\"center\" valign=\"top\" class=\"s5_table\">商品名</td>
						</tr>

						<tr>
							<td align=\"left\" valign=\"top\" class=\"s5_table\">{$title}</td>
						</tr>

						<tr>
							<td align=\"center\" valign=\"top\" class=\"s5_table\">商品説明</td>
						</tr>

						<tr>
							<td align=\"left\" valign=\"top\" class=\"s5_table\">{$content}</td>
						</tr>
					</table>
						";

					//詳細内容を表示する
					echo $detail;

			//該当商品の並び位置の取得
				for($i=0,$target="";$i < count($fetchCNT);$i++):
					if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){$target = $i + 1;}
				endfor;

			//ページ位置の取得
				$p = ceil($target/S5_1DISP_MAXROW);

			?>

		<br>
		<a href="<?php echo "./?p=".urlencode($p)."";?>"><img src="../image/backList.jpg" align="right"></a>
		<br><br>

			<!-- ↓商品一覧表示はここまで↓ -->
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