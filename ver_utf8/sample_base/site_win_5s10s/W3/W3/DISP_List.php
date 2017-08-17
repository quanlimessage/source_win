<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
W系表示用プログラム
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
		$totalpage = ceil($tcnt/W3_DISP_MAXROW);

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
	<h2><img src="../image/header.jpg" alt="" width="760" height="55"></h2>

	<ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>ダウンロード&nbsp;(Ｗ３)

		<br><br>
		<div style="width:300;border: solid 1px #cccccc;background-color:#FFFFFF;color:#333333;margin: 5px;padding: 5px;" align="left">
		サンプルプログラムＷ１<br>

		<ul>
		<li>Ｗ３はファイルダウンロードページです。</li>
		<li>最大登録件数１０件</li>
		<li>ファイルアップロードはファイルのデータサイズを確認してください。</li>
		<li>登録できるファイルは「EXCEL」「WORD」「POWER POINT」「Windows Media Video」「MP3」です。jpegなどの画像ファイルのアップロードは行えません。</li>
		<li>クリックした時にファイルダウンロードのダイアログを表示したい場合は【download.php】を使用してください。</li>
		<li>クリックしたらファイルを表示したい場合は資料ファイルのパスをリンクに指定してください。また、リンクのターゲットの指定を【_blank】に指定してください、デフォルトでは使用しているウィンドウで表示してしまいます。</li>
		</ul>

		こちらはデモ用のサンプルプログラムです。<br>
		サンプルプログラムのご使用は【sample_base】フォルダーのプログラムをご使用ください。
		</div>

	 </h5>
	<br>

	<!-- ↓ファイル一覧一覧表示はここから↓ -->
			<?php
			// 登録がない場合のエラーメッセージ
			echo $disp_no_data;?>

<?php if(count($fetch)){?>
	<table width="520px"  border="0" cellspacing="2" cellpadding="2" bgcolor="#999999">
	 <tr bgcolor="#FFFFFF">
		<td align="center">ファイル</td>
		<td align="center" width="10%">サイズ</td>
		<td align="center" width="20%">種別</td>
		<td align="center" width="20%">DOWNLOAD</td>
	 </tr>
			<?php

				// 全商品分ループ(縦ループ)
				for($i=0;$i<count($fetch);$i++){

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

					//リンク
					$url = $fetch[$i+$j]['LINKURL'];

					//URLとタイトルが入力している場合のみにリンクをする
					$link = ($url && $title)?"<a href=\"{$url}\" target=\"_blank\">{$title}</a>":$title;

					//ファイルサイズ
					$size_data = $fetch[$i+$j]["SIZE"];
					//$size_data = round(($fetch[$i+$j]["SIZE"] / 1024),2)."KB";//KB単位にしたい場合

					//ファイルの種類
					switch($fetch[$i+$j]["TYPE"]){
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

					//office2007用TYPEが解らない為拡張子で判別
					switch($fetch[$i+$j]['EXTENTION']){
						case "xlsx":
							$type = "EXCEL";
							break;
						case "docx":
							$type = "WORD";
							break;
						case "pptx":
							$type = "POWER POINT";
							break;
					}

					//アイコン用
					$icon = $fetch[$i+$j]['EXTENTION'];

					//ファイルパス
					$fine_name = $fetch[$i+$j]['RES_ID'].".".$fetch[$i+$j]['EXTENTION'];
					$file = "./up_img/".$fine_name;

					//ファイルが存在しているかチェックする
					if(file_exists($file)){
						$file_path = "<a href=\"{$file}\" style=\"float:left;\" target=\"blank\"><img src=\"./icon_img/icon_{$icon}.jpg\" border=\"0\"></a>";
						$download_link = "<a href=\"download.php?prm={$fine_name}\">ダウンロードする</a>";
					}else{//ファイルが存在しない場合
						$file_path = "";
						$download_link = "";
					}

					#==============================================================================================
					# テーブルテンプレート貼り付け
					# HTMLから商品情報テーブルソースを貼り付け変数を展開
					# 必ずソースをすっきりさせるためヒアドキュメントは使用せず
					# 上記で変数を整形してから貼り付ける
					#==============================================================================================
					//最初のtdに設置する、表示するサイズを指定（指定が無い場合、1件づつ表示の場合は空白)
					//（例　$tdsize = "style=\"width:180px;\""; $tdsize = "class=\"list_width\"";）
					$tdsize ="width=\"520px\"";

					$table = "
					 <tr bgcolor=\"#FFFFFF\">
					    <td align=\"left\">
							{$file_path}
							<div class=\"s_text\">
							<b>{$title}</b>
							<br>
							{$content}</div>
					    </td>
						<td>{$size}</td>
						<td>{$type}		</td>
						<td>
							{$download_link}
						</td>
					 </tr>
					";

					#=====================================================================
					# テーブルを表示
					# ※クロス表示でなくてもそのまま
					# ＊＊＊ スクリプトの変更不要 ＊＊＊
					#=====================================================================
					echo (!empty($id))?$table:"<td {$tdsize}>&nbsp;</td>";

					endfor;

					$i=$i+(LINE_MAXCOL-1);

			}
		}
			?>
	</table>

	<br><br>

	<!--
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
	-->
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
