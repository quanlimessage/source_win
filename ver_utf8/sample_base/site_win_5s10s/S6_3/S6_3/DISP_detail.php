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
<title><?php echo $fetch[0]["TITLE_TAG"];?></title>
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
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>商品紹介（Ｓ６－３）&nbsp;ﾀﾃ表示／写真切替</h5>
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
						$img_path = array(); // 初期化

						for($i=2;$i<=S6_3IMG_CNT;$i++):

							if($_POST['act']){ //プレビュー
								$img_path[] = $prev_img[$i];
							}else{//

								$img_path[] = search_file_disp("./up_img/",$id."_".$i.".*","",2);
							}

						endfor;

						$image = "";//最初に表示する画像の設定初期化

						//最初に表示する画像を探す パスが入った時点で処理をやめる
						for($i=0;($i < count($img_path)) && ($image == "");$i++):
							if(file_exists($img_path[$i])){
								$image = "<img src=\"".$img_path[$i]."?r=".rand()."\" border=\"0\" name=\"main_img\" id=\"img01\">\n";
							}
						endfor;

						//表示画像が無ければスペースを表示
						if(!$img){$img = "./images/spacer.gif";}

						//画像ボタンの設定
						$button = "";//初期化
						for($i=0,$j=1;$i<= count($img_path);$i++):
							if(file_exists($img_path[$i])):
								$button .= "<input type=\"image\" onClick=\"document.main_img.src='".$img_path[$i]."'\"";
								$button .= " src=\"./images/{$j}.gif\" alt=\"{$j}\" width=\"22\" height=\"22\" border=\"0\">&nbsp;";
								$j++;
							endif;
						endfor;

						if($button){$button .= "<br><br>※上側の数字をクリックすると写真が変わります。";}

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
					</table>
						";

					//詳細内容を表示する
					echo $detail;

			//該当商品の並び位置の取得
				for($i=0,$target="";$i < count($fetchCNT);$i++):
					if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){$target = $i + 1;}
				endfor;

			//ページ位置の取得
				$p = ceil($target/S6_3DISP_MAXROW);

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