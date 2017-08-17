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

		// 次ページ番号
		$next = $p + 1;
		// 前ページ番号
		$prev = $p - 1;

		// 商品全件数
		$tcnt = count($fetchCNT);
		// 全ページ数
		$totalpage = ceil($tcnt/S10_DISP_MAXROW);

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

		////////////////////////////////////////////////////////////////////////
		// S10のページネーションの表示処理
		//

		// Page_nation_dataファンクションの説明
		// $reg_max	現在の登録数
		// $disp_max	現在の１ページの最大表示数
		// $now_p	今現在閲覧しているページ
		// $cate	もし、カテゴリーなどがありGETでデータを渡したい場合
		// は記述する、リンクにその内容が追加される。
		////////////////////////////////////////////////////////////////////////
		function Page_nation_data($reg_max,$disp_max,$now_p,$cate){

			//返値を初期化
			$page_count = "";

			#===================================================================
			# 画面遷移のナビゲーションを表示（出力件数が“0”の場合は非表示）
			#===================================================================
				if($reg_max > $disp_max):

					// 全ページ数を算出
					$t_page = ceil($reg_max / $disp_max);//小数点は切り上げる

					for($i = 1;$i <= $t_page;$i++){
						//ページにあった無い様に変更させる
						if($now_p == $i){//現在の閲覧しているページはリンクさせない
							$page_count .= "[".$i."]";
						}else{//現在の見ているページ以外
							$page_count .= "<a href=\"./?p=".$i.$cate."\">[".$i."]</a>";
						}
					}

				endif;

			//内容を出力させる
			return $page_count;
		}

		$pn_data = Page_nation_data(count($fetchCNT),S10_DISP_MAXROW,$p,"");

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
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>商品紹介（Ｓ１０）&nbsp;ページ番号表示</h5>
		<br>
			<?php

			//ページ遷移の表示
			echo $pn_data;

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

					// 画像
					$img = "./up_img/".$id.".jpg"; // 本番はこうなる
					if(!file_exists($img)){
						$image = "";
						$tsize1 = "";
						$tsize2 = " width=\"520\" align=\"left\" valign=\"top\" class=\"s_text2\"";
					}else{
						//画像サイズ固定
							//$image = "<img src=\"".$img."?r=".rand()."\" width=\"".S1_IMGSIZE_MX."\" height=\"".S1_IMGSIZE_MY."\" alt=\"".$title."\">";

						//画像サイズが固定でない場合（サイズ自動調整、横固定縦可変など）
							$size = getimagesize($img);//画像サイズを取得
							$image = "<img src=\"".$img."?r=".rand()."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$title."\">";
						//画像を表示するためのスペース
						$tsize1 = "width=\"230\" class=\"s_image\"";//画像を表示するためのスペース
						$tsize2 = "width=\"290\" align=\"left\" class=\"s_text\"";//画像を表示するためのスペース
					}

					#==============================================================================================
					# テーブルテンプレート貼り付け
					# HTMLから商品情報テーブルソースを貼り付け変数を展開
					# 必ずソースをすっきりさせるためヒアドキュメントは使用せず
					# 上記で変数を整形してから貼り付ける
					#==============================================================================================
					//最初のtdに設置する、表示するサイズを指定（指定が無い場合、1件づつ表示の場合は空白)
					//（例　$tdsize = "style=\"width:180px;\""; $tdsize = "class=\"list_width\"";）
					$tdsize ="";

					$table = "
						<td {$tdsize}>
							<table width=\"520px\"  border=\"0\" cellspacing=\"0\" cellpadding=\"4\" bgcolor=\"#999999\">
								<tr bgcolor=\"#FFFFFF\">
									<td colspan=\"3\" align=\"left\" class=\"s_midashi\">
									{$title}
									</td>
								</tr>
								<tr bgcolor=\"#FFFFFF\">
									<td {$tsize1}>
										{$image}
									</td>

									<td {$tsize2}>
										{$content}
									</td>
								</tr>
							</table>
							<br>
						</td>
					";

					#=====================================================================
					# テーブルを表示
					# ※クロス表示でなくてもそのまま
					# ＊＊＊ スクリプトの変更不要 ＊＊＊
					#=====================================================================
					echo (!empty($id))?$table:"<td {$tdsize}>&nbsp;</td>";

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