<?php
/*******************************************************************************
通常ショップベース
	ショッピングカートプログラム

View：確認画面を表示
Status：confirm

	※確認ボタンで完了画面（ＤＢ格納とメール送信後）
	※修正ボタンで前のページ（配送先情報入力画面）へ飛ぶ

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");	exit();
}

#------------------------------------------------------------------------
# HTTPヘッダーを出力
# 文字コード／JSとCSSの設定／無効な有効期限／キャッシュ拒否／ロボット拒否
#------------------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

// テンプレートクラスライブラリ読込みと出力用HTMLテンプレートをセット
if ( !file_exists("TMPL_confirm.html") )	die("Template File Is Not Found!!");
$tmpl = new Tmpl2("TMPL_confirm.html");

#=================================================================================
# テンプレートを使用してHTML出力の設定
#=================================================================================
// 都道府県のIDを取得（INI_pref_list.phpより都道府県名を取り出すのに使用）
$sid  = $_SESSION['cust']['STATE'];				// お客様用

// TITLE
$tmpl->assign("title",SHOPPING_TITLE);

// HEADのTITLE
$tmpl->assign("shopping_title", SHOPPING_TITLE);

#---------------------------------------------------------------------
# 現在の買い物カゴの中身を取得して表示（ループセット）
#	※getItems()はLF_cart_calc2.phpより
#---------------------------------------------------------------------
$cart_list = getItems();	// セッションのカート情報を取得、$cart_listにセット

$tmpl->loopset("cart_list_loop");
for ( $i = 0; $i < count($cart_list); $i++ ){

	// 各データを取り出す
	list($product_id,$part_no,$product_name,$selling_price,$quantity,$stock_quantity) = explode("\t", $cart_list[$i]);

	// 単価×個数で小計金額を算出
	$amount = ($selling_price * $quantity);

	// HTML出力の設定
	$tmpl->assign("part_no", ($part_no)?$part_no:"&nbsp;");					// 型番
	$tmpl->assign("product_name", ($product_name)?$product_name:"&nbsp;");	// 商品名
	$tmpl->assign("selling_price", number_format($selling_price));			// 単価
	$tmpl->assign("quantity", $quantity);									// 数量

	// 小計のHTML出力(小計$amountはLGC_setAmount.phpにて算出されてる)
	$tmpl->assign("amount", number_format($amount));

	$tmpl->loopnext("cart_list_loop");
}
$tmpl->loopend("cart_list_loop");

// 合計金額（送料／代引きは含まず）のHTML出力
$tmpl->assign("sum_price", number_format($_SESSION["cust"]["sum_price"]));

// 送料表示
$tmpl->assign("shipping_amount", number_format($_SESSION['cust']['shipping_amount']));
// 代引き手数料表示
$tmpl->assign("daibiki_amount", number_format($_SESSION['cust']['daibiki_amount']));
// コンビニ決済手数料表示
$tmpl->assign("conv_amount", number_format($_SESSION['cust']['conv_amount']));

// 支払い総額	※合計＋手数料＋送料＋コンビに決済手数料
$total_price = ($_SESSION["cust"]["sum_price"] + $_SESSION['cust']['daibiki_amount'] + $_SESSION['cust']['shipping_amount'] + $_SESSION['cust']['conv_amount']);
$tmpl->assign("total_price", number_format($total_price));

#------------------------------------------------------------------------------
# 個人情報のHTML設定
#
#		セッションに格納されてる各情報から表示させる。
#			$_SESSION['cust']	お客様情報・支払い情報・配送先情報
#------------------------------------------------------------------------------
// 支払方法
switch($_SESSION['cust']['PAYMENT_METHOD']){
	case 1: $tmpl->assign("payment_method", "クレジット"); break;
	case 2: $tmpl->assign("payment_method", "銀行振込"); break;
	case 3: $tmpl->assign("payment_method", "代引き"); break;
	case 4: $tmpl->assign("payment_method", "コンビニ決済"); break;
	case 5: $tmpl->assign("payment_method", "郵便振替"); break;
}

// お客様情報
$tmpl->assign("name", $_SESSION['cust']['LAST_NAME']."&nbsp;".$_SESSION['cust']['FIRST_NAME']);
$tmpl->assign("kana", $_SESSION['cust']['LAST_KANA']."&nbsp;".$_SESSION['cust']['FIRST_KANA']);
$tmpl->assign("zip", $_SESSION['cust']['ZIP_CD1']."-".$_SESSION['cust']['ZIP_CD2']);
$tmpl->assign("address", $shipping_list[$sid]['pref'].$_SESSION['cust']['ADDRESS1'].$_SESSION['cust']['ADDRESS2']);
$tmpl->assign("tel", $_SESSION['cust']['TEL1']."-".$_SESSION['cust']['TEL2']."-".$_SESSION['cust']['TEL3']);
$tmpl->assign("email", ($_SESSION['cust']['EMAIL'])?$_SESSION['cust']['EMAIL']:"");
$tmpl->assign("remarks", nl2br($_SESSION['cust']['REMARKS']));

#------------------------------------------------------------------------------
# 配送先のHTML設定
#------------------------------------------------------------------------------
$tmpl->assign("deli_name", $_SESSION['cust']['DELI_LAST_NAME']."&nbsp;".$_SESSION['cust']['DELI_FIRST_NAME']);
$tmpl->assign("deli_zip", $_SESSION['cust']['DELI_ZIP_CD1']."-".$_SESSION['cust']['DELI_ZIP_CD2']);
$tmpl->assign("deli_address", $shipping_list[$dsid]['pref'].$_SESSION['cust']['DELI_ADDRESS1'].$_SESSION['cust']['DELI_ADDRESS2']);
$tmpl->assign("deli_tel", $_SESSION['cust']['DELI_TEL1']."-".$_SESSION['cust']['DELI_TEL2']."-".$_SESSION['cust']['DELI_TEL3']);

#------------------------------------------------------------------------------------------------
#	 残りいくらで送料無料になるか
#	【送料計算】タイプにより送料計算の処理分岐(設定ファイル定数SHIPPING_COND_TYPEにてタイプは定義)
#------------------------------------------------------------------------------------------------

if(SHIPPING_COND_TYPE == 2){
	if($sum_price < SHIPPING_FREE){
		$tmpl->assign_def("nokoriprice",true);
	}
}

$nokori_price = SHIPPING_FREE - $sum_price;
$tmpl->assign("nokori_price", number_format($nokori_price));

#------------------------------------------------------------
# 設定した内容を一括出力
#------------------------------------------------------------
$tmpl->flush();

?>
