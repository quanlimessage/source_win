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
			$link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">前のページへ</a>";
		}

		//次ページリンク
		if($totalpage > $p){
			$link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">次のページへ</a>";
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
?><!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
<meta charset="utf-8">
<title>新着情報テンプレート</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="description" content="">
<meta name="keywords" content="">
<!-- CSS -->
<link rel="stylesheet" href="../css/base.css" type="text/css" media="all">
<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="all">
<!-- 新着情報テンプレート用CSS -->
<link rel="stylesheet" href="../css/TMPL_news.css" type="text/css" media="all">
</head>
<body class="pages news">
<!-- #header -->
<header id="header">
	<h1>一覧ページ | 新着情報テンプレート</h1>
</header>
<!-- /#header -->
<!-- #wrap -->
<div id="wrap" class="clearfix">
	<!-- #main -->
	<div id="main">
		<p>記事の箇所はリキッドの為、親要素の横幅を指定すること。</p>
		<p>ブラウザ幅を小さくするとタブレット、スマホサイズも確認できます（＝レスポ対応可）</p>
		<!-- コピーここから -->
		<div id="news_list">

	<?php

			if(!count($fetch)):
				echo "<center><br>ただいま準備中のため、もうしばらくお待ちください。<br><br></center>\n";//表示件数が０件の場合

			else:
				for($i=0;$i<count($fetch);$i++):

				//ID
				$id = $fetch[$i]['RES_ID'];

				//日付
				$time = sprintf("%04d/%02d/%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);
				$datetime = sprintf("%04d-%02d-%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);

				//タイトル
				$title = ($fetch[$i]['TITLE'])?"<a href=\"./?id=".urlencode($id)."\">".$fetch[$i]['TITLE']."</a>":"&nbsp;";
				$ititle = ($fetch[$i]['TITLE'])?strip_tags($fetch[$i]['TITLE']):"";

				//コメント
				$content = ($fetch[$i]['CONTENT'])?mb_strimwidth(strip_tags($fetch[$i]['CONTENT']), 0, 420, "..."):"";
				//image拡大有（１）・無（０）表示
				$img_flg = $fetch[$i]['IMG_FLG'];

				// 画像
				if($_POST['act']){//プレビュー
					$img = $prev_img[1];
				}else{// 表示する画像を検索（拡張子の特定）
					if(search_file_flg("./up_img/",$id."_1.*")){
						$img = search_file_disp("./up_img/",$id."_1.*","",2);
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
						$image = "<a href=\"{$img}\" data-lightbox=\"lightbox\"><img src=\"{$img}\" alt=\"{$ititle}\"></a>";
					}else{
						$image = "<img src=\"{$img}\" alt=\"{$ititle}\">";
					}
					$image = "<div class=\"news_img\"><p>".$image."</p></div>";
				}

				//表示内容の格納
				$table = "
			<!-- .news_box -->
			<article class=\"news_box\">
				<header class=\"headline clearfix\">
					<time datetime=\"{$datetime}\">{$time}</time>
					<h2>{$title}</h2>
				</header>
				<div class=\"news_inner clearfix\">
					{$image}
					<div class=\"news_txt\">
						{$content}
					</div>
				</div>
			</article>
			<!-- /.news_box -->
				";

				//表示内容を表示する
				echo $table;

				endfor;
			endif;

				?>

		</div>
		<!-- .news_pager -->
		<div class="news_pager">
			<div class="clearfix">
				<p class="pager_btn prev_btn"><?php echo $link_prev;?></p>
				<p class="pager_btn next_btn"><?php echo $link_next;?></p>
			</div>
		</div>
		<!-- /.news_pager -->
		<!-- コピーここまで -->
	</div>
	<!-- /#main -->
	<!-- #side -->
	<div id="side">
		<p>サイドカラム</p>
	</div>
	<!-- /#side -->
</div>
<!-- /#wrap -->
<script src="../js/jquery.js"></script>
<script src="../js/lightbox.js"></script>
</body>
</html>
