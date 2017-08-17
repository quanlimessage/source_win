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
		$totalpage = ceil($tcnt/S4_DISP_MAXROW);

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
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>before／after（Ｓ４）</h5>
		<br>

			<!-- ↓商品一覧表示はここから↓ -->

			<?php
			// 登録がない場合のエラーメッセージ
			echo $disp_no_data;

				// 全商品分ループ(縦ループ)
				for($i=0;$i<count($fetch);$i++){
			?>

			<table cellpadding="0" cellspacing="0" class="list_table">
				<tr>

				<?php
					// クロス回数ループ(横ループ) ※クロス表示でなくてもそのまま(スクリプトの変更不要)
					for($j=0;$j<LINE_MAXCOL;$j++):

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
					$id = $fetch[$i+$j]['RES_ID'];

					// タイトル
					$title = $fetch[$i+$j]['TITLE'];

					// コメント
					$content = nl2br($fetch[$i+$j]['CONTENT']);

					// 画像1
					if($_POST['act']){//プレビュー
						$img1 = $prev_img[1];
					}else{// 表示する画像を検索（拡張子の特定）
						if(search_file_flg("./up_img/",$fetch[$i+$j]['RES_ID']."_1.*")){
							$img1 = search_file_disp("./up_img/",$fetch[$i+$j]['RES_ID']."_1.*","",2);
						}else{
							$img1 = "";
						}
					}

					// 画像2
						if($_POST['act']){//プレビュー
							$img2 = $prev_img[2];
						}else{// 表示する画像を検索（拡張子の特定）
							if(search_file_flg("./up_img/",$fetch[$i+$j]['RES_ID']."_2.*")){
								$img2 = search_file_disp("./up_img/",$fetch[$i+$j]['RES_ID']."_2.*","",2);
							}else{
								$img2 = "";
							}
						}

					if(!file_exists($img1)){
						$image1 = "&nbsp;";
					}else{
						//画像サイズ固定
							//$image = "<img src=\"".$img."?r=".rand()."\" width=\"".S1_IMGSIZE_MX."\" height=\"".S1_IMGSIZE_MY."\" alt=\"".$title."\">";

						//画像サイズが固定でない場合（サイズ自動調整、横固定縦可変など）
							$size = getimagesize($img1);//画像サイズを取得
							$image1 = "<img src=\"".$img1."?r=".rand()."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$fetch[$i+$j]['TITLE']."\">";
					}

					if(!file_exists($img2)){
						$image2 = "&nbsp;";
					}else{
						//画像サイズ固定
							//$image = "<img src=\"".$img."?r=".rand()."\" width=\"".S1_IMGSIZE_MX."\" height=\"".S1_IMGSIZE_MY."\" alt=\"".$title."\">";

						//画像サイズが固定でない場合（サイズ自動調整、横固定縦可変など）
							$size = getimagesize($img2);//画像サイズを取得
							$image2 = "<img src=\"".$img2."?r=".rand()."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$fetch[$i+$j]['TITLE']."\">";
					}

					#==============================================================================================
					# テーブルテンプレート貼り付け
					# HTMLから商品情報テーブルソースを貼り付け変数を展開
					# 必ずソースをすっきりさせるためヒアドキュメントは使用せず
					# 上記で変数を整形してから貼り付ける
					#==============================================================================================
					//最初のtdに設置する、表示するサイズを指定（指定が無い場合は空白)
					//（例　$tdsize = "style=\"width:180px;\""; $tdsize = "class=\"list_width\"";）
					$tdsize ="width=\"520px\"";

					$table = "
						<td {$tdsize}>
						<table border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"#999999\">
							<tr bgcolor=\"#FFFFFF\">
								<td colspan=\"3\" align=\"left\" class=\"s_midashi\" bgcolor=\"#E6E6E6\">
									{$title}
								</td>
							</tr>

							<tr bgcolor=\"#FFFFFF\">
								<td width=\"230\" bgcolor=\"#FFFFFF\">
									{$image1}
								</td>
								<td width=\"30\" rowspan=\"2\"><img src=\"../image/ya.jpg\"></td>
								<td width=\"230\" bgcolor=\"#FFFFFF\">
									{$image2}
								</td>
							</tr>

						<tr bgcolor=\"#FFFFFF\">
							<td width=\"230\">
								<img src=\"../image/befor.jpg\" alt=\"befor\" width=\"200\" height=\"28\" border=\"0\">

							</td>
							<td width=\"230\">
								<img src=\"../image/after.jpg\" alt=\"after\" width=\"200\" height=\"28\" border=\"0\">
							</td>
						</tr>

						<tr>

							<td width=\"520\" colspan=\"3\" align=\"left\" class=\"s_text2\" bgcolor=\"#E6E6E6\">

								{$content}
							</td>
						</tr>
						</table>

					</td>
					";

					#=====================================================================
					# テーブルを表示
					# ※クロス表示でなくてもそのまま
					# ＊＊＊ スクリプトの変更不要 ＊＊＊
					#=====================================================================
					if(empty($id) && !$_POST['act']){//IDが無くてプレビューのactデータが無い場合
						echo "<td>&nbsp;</td>";

					}else{
						//新規登録のプレビューはRES_IDが無いのでプレビュー用の条件を追加(プレビューのフラグと一番最初の時は表示)
						if(($_POST['act'] && $i==0 && $j==0) || $id){
							echo $table;
						}else{
							echo "<td>&nbsp;</td>";
						}
					}

					endfor;

					$i=$i+(LINE_MAXCOL-1);

				?>

				</tr>

			</table><br>

				<?php }?>

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