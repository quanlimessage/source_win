<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
新着情報の内容を表示するプログラム

DSP：DBより取得情報をHTML出力

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("Location: ../");exit();
}

//該当商品の並び位置の取得
for($i=0,$target="";$i < count($fetchCNT);$i++):
if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){$target = $i + 1;}
endfor;

//ページ位置の取得
$p = ceil($target/CP1_DISP_MAXROW);


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
<meta name="description" content="<?php echo $fetch[0]["TITLE"];?><?php echo $ca_name;?>|ＳＥＯワード">
<meta name="keywords" content="<?php echo $fetch[0]["TITLE"];?>,<?php echo $ca_name;?>,キーワード">
<meta name="robots" content="index,follow">
<title><?php echo $fetch[0]["TITLE"];?><?php echo $ca_name;?></title>
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

		<h1><?php echo $fetch[0]["TITLE"];?><?php echo $ca_name;?>|ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">
	</ul>

	<div id="main">
		<div id="index_image">
		<br>
		 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>自由レイアウト（CP1）&nbsp;一覧形式</h5>
		<br>
	 	<?php
		//カテゴリーがある場合項目名を表示
	 	if(count($fetchCA)):?>
		 	<table width="520px" border="0" cellpadding="0" cellspacing="1">
				 <tr bgcolor="#FFFFFF">
				 <?php
				 	for($i=0;$i < count($fetchCA);$i++):
				  		echo "<td align=\"center\"><a href=\"./?ca=".urlencode($fetchCA[$i]['CATEGORY_CODE'])."\">".$fetchCA[$i]['CATEGORY_NAME']."</a></td>\n";
					endfor;
				?>
				 </tr>
			</table>
		<?php
		endif;
		?><br>
		詳細画面<br><br>

				<?php
					for($i = 0; $i < count($fetchRgBlock); $i++){
						$bid = $fetchRgBlock[$i]['BLOCK_ID'];
						$bpath = _COMMON_BLOCK_PATH.getBlockName($bid);
						if(file_exists($bpath)) {
							include_once($bpath);
						}else{
							continue;
						}
	
						$param = array(	"color" => _COLOR,
										"group_id" => $gid,
										"lid" => _LAYOUT_TYPE,
										"lng" => _LANGUAGE,
										"group_folder_name" => $gfn,
									  );
						$htmlbody .= $BLOCK_DATA[$bid]['FUNCTION']($fetchRgBlock[$i],$param);
					}

					echo $htmlbody;


			?>
<?php
//該当商品の並び位置の取得
for($i=0;$i < count($fetchCNT);$i++):
	if($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']){
		$next = $fetchCNT[$i+1]['RES_ID'];
		$prev = $fetchCNT[$i-1]['RES_ID'];
		if($prev){
			$link_prev = "<a href=\"./?id=".$prev."\">&lt;&lt; Prev</a>";
		}
		if($next){
			$link_next = "<a href=\"./?id=".$next."\">Next &gt;&gt;</a>";
		}
	}

endfor;

//ページ位置の取得
$p = ceil($target/DISP_MAXROW);
?>

		<br>
		<!-- ↓商品ことのページネーション↓ -->
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
		<br>
		<br>
		<a href="<?php echo "./?p=".urlencode($p)."&ca=".urlencode($fetch[0]['CATEGORY_CODE']).""?>"><img src="../image/backList.jpg" align="right"></a>
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
