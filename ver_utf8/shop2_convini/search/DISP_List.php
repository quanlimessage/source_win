<?php
/*******************************************************************************

 LOGIC:商品一覧ページを表示

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# ヘッダー調整
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);
#----------------------------------------------------------------------------
# テンプレートクラスライブラリ読込みと出力用HTMLテンプレートをセット
# ※$category_codeの内容により分岐
#----------------------------------------------------------------------------
$tmpl_file = "TMPL_list.html";

if(!file_exists($tmpl_file))die("Template File Is Not Found!!");
$tmpl = new Tmpl2($tmpl_file);

#------------------------------------------------------------------------------------------
# 共通HTML出力の設定
#------------------------------------------------------------------------------------------

// HEADのTITLE
$tmpl->assign("shopping_title",SHOPPING_TITLE);

#--------------------------------------------------------------------------------
# HTML出力
#--------------------------------------------------------------------------------

	// 商品が何も登録されていない場合に表示
	if($message):
		$tmpl->assign("message","<br><div align=\"center\"><font style=\"color:#FFFFFF;\">{$message}</font></div>");
	else:
		$tmpl->assign("message","");
	endif;

	// ループセットと取得レコード分のHTML出力設定
	$tmpl->loopset("product_list");

	// 全商品分ループ
	for($i=0;$i<count($fetch);$i++){

		// クロス回数ループ ※クロス表示でなくてもそのまま(スクリプトの変更不要)
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

		// 商品ID
		$id = $fetch[$i+$j]['PRODUCT_ID'];
		// 商品名
		$pname = $fetch[$i+$j]['PRODUCT_NAME'];
		// 価格
		$price = ($fetch[$i+$j]["SELLING_PRICE"])?number_format(math_tax($fetch[$i+$j]["SELLING_PRICE"]))."円":"";
		// 商品番号
		$part_no = $fetch[$i+$j]["PART_NO"];
		// 詳細情報
		$details = nl2br($fetch[$i+$j]["PRODUCT_DETAILS"]);
		// 詳細画面へリンク
		if(strlen($param)>0){
			$for_detail = "../shopping/".$param."&pid=".urlencode($fetch[$i+$j]['PRODUCT_ID']);
		}else{
			$for_detail = "../shopping/?pid=".urlencode($fetch[$i+$j]['PRODUCT_ID']);
		}

		// 画像
		$img = "../shopping/product_img/".$id."_s.jpg"; // 本番はこうなる
		if(!file_exists($img)){
			$image = "";
		}else{
			$image  = "<a href=\"".$for_detail."\">";
			$image .= "<img src=\"".$img."?r=".rand()."\" width=\"".IMG_SIZE_SX."\" height=\"".IMG_SIZE_SY."\" alt=\"".$pname."\"></a>";
		}

		#==============================================================================================
		# テーブルテンプレート貼り付け
		# HTMLから商品情報テーブルソースを貼り付け変数を展開
		# 必ずソースをすっきりさせる為ヒアドキュメントは使用せず
		# 上記で変数を整形してから貼り付ける
		#==============================================================================================
		$table = "
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"5\" style=\"margin:12px;\" bgcolor=\"#FFFFFF\">
				<tr>
					<td colspan=\"2\">".$image."</td>
				</tr>
				<tr>
					<td width=\"53\">商品名：</td>
					<td align=\"left\" width=\"137\">".$pname."</td>
				</tr>
				<tr>
					<td>価格：</td>
					<td align=\"left\">".$price."</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align=\"right\"><a href=\"".$for_detail."\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image31".($i+$j)."','','../image/shop_btnon02.jpg',1)\" title=\"".$pname."詳細はこちら\"><img src=\"../image/shop_btnoff02.jpg\" alt=\"".$pname."詳細はこちら\" name=\"Image31".($i+$j)."\" width=\"105\" height=\"20\" border=\"0\"></a></div></td>
				</tr>
			</table>
		";

		#=====================================================================
		# テーブルをテンプレートに展開
		# ※クロス表示でなくてもそのまま
		# ＊＊＊ スクリプトの変更不要 ＊＊＊
		#=====================================================================
		$tmpl->assign("table".($j+1),(!empty($id))?$table:"");

		endfor;

		$i=$i+(LINE_MAXCOL-1);

		$tmpl->loopnext("product_list");

	}

	$tmpl->loopend("product_list");

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
$totalpage = ceil($tcnt/SHOP_MAXROW);

// 検索ワードがあればページ遷移もパラメーターをつける
if($search_word)$spram = "&search_word=".urlencode($search_word);

// 前ページへのリンク
$pr = "<a href=\"./?p=".urlencode($prev).$spram."\">&lt;&lt; Prev</a>";
if($p <= 1){
	$pr = "";
}

//次ページリンク
$nx = "<a href=\"./?p=".urlencode($next).$spram."\">Next &gt;&gt;</a>";
if($totalpage <= $p){
	$nx = "";
}

// $page = $pr." &nbsp; ".$nx;

$tmpl->assign("pr",$pr);
$tmpl->assign("nx",$nx);

#------------------------------------------------------------
# 設定した内容を一括出力
#------------------------------------------------------------
$tmpl->flush();
?>
