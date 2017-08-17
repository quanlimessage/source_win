<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム
View：取得したデータをHTML出力

***********************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("Location: ../");exit();
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
<meta name="description" content="<?php echo $fetch[0]["TITLE"];?>/ＳＥＯワード">
<meta name="keywords" content="<?php echo $fetch[0]["TITLE"];?>/キーワード">
<meta name="robots" content="index,follow">
<title><?php echo $fetch[0]["TITLE"];?>/Winシリーズ-更新プログラム|サンプルサイト|</title>
<link href="../css/base.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.s6_table{
	padding:5px;
	border:solid 1px #999999;
}

.S6_table_img{
	padding: 10px 5px;
	border: solid 1px #999999;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript" src="java.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
<script type="text/javascript">
<!--
// プリロード
for_preload=new Image();for_preload.src="./up_img/<?php echo $id; ?>_1.jpg"
for_preload=new Image();for_preload.src="./up_img/<?php echo $id; ?>_2.jpg"
for_preload=new Image();for_preload.src="./up_img/<?php echo $id; ?>_3.jpg"
//-->
</script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

		<h1><?php echo $fetch[0]["TITLE"];?>/ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image">
		<br>
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>商品紹介（Ｓ６－１）&nbsp;ﾀﾃ表示／ﾎﾟｯﾌﾟｱｯﾌﾟ写真詳細</h5>
		<br>

			<!-- ↓商品一覧表示はここから↓ -->

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
					$img = "./up_img/".$id."_2.jpg";
					if(!file_exists($img)){
						$image = "";
					}else{
						$size = getimagesize($img);//画像サイズを取得
						$img = $img."?r=".rand();//キャッシュを読み込まないようにユニークな数値をあたえる

						$image .= "<img src=\"{$img}\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"{$ititle}\" border=\"0\"><br>";
					}

					//画像ボタンの設定
					$button = "";
					for($i=1,$j=1;$i<=3;$i++):
						if(file_exists("./up_img/{$id}_".($i+2).".jpg")):
							$button .= "<input type=\"image\" onClick=\"javascript:void(window.open('./?id=". urlencode($id)."&pid=".urlencode($i)."','popphoto','width=500,height=500,scrollbars=yes'));\" target=\"_blank\"";
							$button .= " src=\"./images/{$j}.gif\" alt=\"{$j}\" width=\"22\" height=\"22\" border=\"0\">&nbsp;";
							$j++;
						endif;
					endfor;

					if($button){$button .= "<br><br>※上側の数字をクリックすると拡大写真が見れます。";}

					//資料ファイル

					//ファイルサイズ
						//$size_data = $fetch[0]["SIZE"]."B";
						$size_data = number_format($fetch[0]["SIZE"] / 1024)."KB";//KB単位にしたい場合

						//ファイルの種類
						switch($fetch[0]["TYPE"]){
							case "application/vnd.ms-excel":
								$type = "EXCEL";
								break;
							case "application/msword":
								$type = "WORD";
								break;
							case "application/vnd.ms-powerpoint":
								$type = "POWER POINT";
								break;
							case "application/pdf":
								$type = "PDF";
								break;
							case "audio/mpeg":
								$type = "MP3";
								break;
							case "video/x-ms-wmv":
								$type = "Windows Media Video";
								break;
							default:
							$type = "";
						}

						//ファイルパス
						$file = "./up_img/".$fetch[0]['RES_ID'].".".$fetch[0]['EXTENTION'];

						//アイコン用
						$icon = $fetch[0]['EXTENTION'];

						//ファイルが存在しているかチェックする
						if(file_exists($file)){
							$file_path = "<tr><td align=\"left\" valign=\"top\" class=\"s5_table\">資料ファイル</td><td><a href=\"{$file}\" style=\"float:left;\" target=\"blank\"><img src=\"./icon_img/icon_{$icon}.jpg\" border=\"0\"></a>{$size_data}<br></td></tr>";
						}else{//ファイルが存在しない場合
							$file_path = "";
						}

					#==============================================================================================
					# テーブルテンプレート貼り付け
					# HTMLから商品情報テーブルソースを貼り付け変数を展開
					# 必ずソースをすっきりさせるためヒアドキュメントは使用せず
					# 上記で変数を整形してから貼り付ける
					#==============================================================================================
					//最初のtdに設置する、表示するサイズを指定（指定が無い場合は空白)
					//（例　$tdsize = "style=\"width:180px;\""; $tdsize = "class=\"list_width\"";）
					$tdsize ="";

					//詳細内容を格納
					$detail = "
					<table width=\"500\" border=\"0\" bgcolor=\"#EEEEEE\" cellspacing=\"0\" cellpadding=\"0\" style=\"border: solid 1px #999999;\">
						<tr>

							<td rowspan=\"4\" width=\"250\" align=\"center\" valign=\"top\" class=\"S6_table_img\">
							{$image}<br>
							{$button}
							</td>

							<td align=\"center\" valign=\"top\" class=\"S6_table_img\">商品名</td>
						</tr>

						<tr>
							<td align=\"left\" valign=\"top\" class=\"S6_table_img\">{$title}</td>
						</tr>

						<tr>
							<td align=\"center\" valign=\"top\" class=\"S6_table_img\">商品説明</td>
						</tr>

						<tr>
							<td align=\"left\" valign=\"top\" height=\"100%\" class=\"S6_table_img\">{$content}</td>
						</tr>
						{$file_path}
					</table>
						";

					//詳細内容を表示する
					echo $detail;

			//該当商品の並び位置の取得
				for($i=0,$target="";$i < count($fetchCNT);$i++):
					if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){$target = $i + 1;}
				endfor;

			//ページ位置の取得
				$p = ceil($target/S6_1DISP_MAXROW);

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