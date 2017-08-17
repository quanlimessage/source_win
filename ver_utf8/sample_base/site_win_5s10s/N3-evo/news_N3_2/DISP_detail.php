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

//該当商品の並び位置の取得
for($i=0;$i < count($fetchCNT);$i++):
	if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){
		$target = $i + 1;
		$next = $fetchCNT[$i+1]['RES_ID'];
		$prev = $fetchCNT[$i-1]['RES_ID'];
		if($prev){
			$link_prev = "<a href=\"./?id=".$prev."\">前のページへ</a>";
		}
		if($next){
			$link_next = "<a href=\"./?id=".$next."\">次のページへ</a>";
		}
	}

endfor;

//ページ位置の取得
	$p = ceil($target/$page);

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
<title><?php echo $fetch[0]['TITLE'];?>｜新着情報テンプレート</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
<meta name="description" content="<?php echo $fetch[0]['TITLE'];?>｜ＳＥＯワード">
<meta name="keywords" content="<?php echo $fetch[0]['TITLE'];?>,キーワード">
<!-- CSS -->
<link rel="stylesheet" href="../css/base.css" type="text/css" media="all">
<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="all">
<!-- 新着情報テンプレート用CSS -->
<link rel="stylesheet" href="../css/TMPL_news.css" type="text/css" media="all">
</head>
<body class="pages news">
<!-- #header -->
<header id="header">
	<h1><?php echo $fetch[0]['TITLE'];?>｜詳細ページ | 新着情報テンプレート</h1>
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

				//ID
				$id = $fetch[0]['RES_ID'];

				//日付
				$time = sprintf("%04d/%02d/%02d", $fetch[0]['Y'], $fetch[0]['M'], $fetch[0]['D']);
				$datetime = sprintf("%04d-%02d-%02d", $fetch[0]['Y'], $fetch[0]['M'], $fetch[0]['D']);

				//タイトル
				$title = ($fetch[0]['TITLE'])?$fetch[0]['TITLE']:"&nbsp;";
				$ititle = ($fetch[0]['TITLE'])?strip_tags($fetch[0]['TITLE']):"";

				//コメント
				$content = ($fetch[0]['CONTENT'])?nl2br($fetch[0]['CONTENT']):"";
				//image拡大有（１）・無（０）表示
				$img_flg = $fetch[0]['IMG_FLG'];

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

			endif;

				?>

		</div>
		<!-- .news_pager -->
		<div class="news_pager">
			<div class="clearfix">
				<p class="pager_btn prev_btn"><?php echo $link_prev;?></p>
				<p class="pager_btn next_btn"><?php echo $link_next;?></p>
			</div>
			<p class="pager_btn back_list"><a href="<?php echo "./?p=".urlencode($p).""?>">一覧へ戻る</a></p>
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
