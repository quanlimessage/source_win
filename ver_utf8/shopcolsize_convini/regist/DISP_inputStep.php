<?php
/*******************************************************************************
ショッピングサイト

View：個人情報入力画面と購入商品一覧（買い物カゴの中身）表示
Status：step1（エラー時： ／ 修正時：edit）

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
if(!file_exists("TMPL_input.html"))die("Template File Is Not Found!!");
$tmpl = new Tmpl2("TMPL_input.html");

#=================================================================================
# テンプレートを使用してHTML出力の設定
#=================================================================================

// TITLE
$tmpl->assign("title",SHOPPING_TITLE);

// HEADのTITLE
$tmpl->assign("shopping_title", SHOPPING_TITLE);

// エラーメッセージがある場合は表示（認証チェック後にエラーがあり再入力）
if($error_message){
    $mes = "\n{$error_message}\n";
	$tmpl->assign("error_message",$mes);
}
else{
	$tmpl->assign("error_message","&nbsp;");
}

#---------------------------------------------------------------------
# 現在の買い物カゴの中身を取得して表示（ループセット）
#	※getItems()はLF_cart_calc2.phpより
#---------------------------------------------------------------------
$cart_list = getItems();

$tmpl->loopset("cart_list_loop");

for ( $i = 0; $i < count($cart_list); $i++ ){

	// 各データを取り出す
	list($product_id,$disc_id,$part_no,$product_name,$color_name,$size_name,$selling_price,$quantity,$stock_quantity) = explode("\t", $cart_list[$i]);

	// 単価×個数で小計金額を算出
	$amount = ($selling_price * $quantity);

	// 合計金額を算出
	$sum_price += $amount;

	// HTML出力の設定
	$tmpl->assign("part_no", ($part_no)?$part_no:"&nbsp;");					// 型番
	$tmpl->assign("product_name", ($product_name)?$product_name:"&nbsp;");	// 商品名
	$tmpl->assign("size", ($size_name)?$size_name:"-");				// サイズ
	$tmpl->assign("color", ($color_name)?$color_name:"-");				// カラー
	$tmpl->assign("selling_price", number_format($selling_price));			// 単価
	$tmpl->assign("quantity", $quantity);									// 数量

	// 小計のHTML出力
	$tmpl->assign("amount", number_format($amount));

	$tmpl->loopnext("cart_list_loop");
}
$tmpl->loopend("cart_list_loop");

// 合計金額（送料／代引きは含まず）のHTML出力
$tmpl->assign("sum_price", number_format($sum_price));

//代引き手数料のチェックボックスを３０万を越えたら出さない
if(DAIBIKI_LIMIT_FLG == 1 && $sum_price <= 300000){
	$tmpl->assign_def("rowprice",true);
}

//ゼウスのコンビニ決済の送金金額は【200円未満、300,001円以上不可】の仕様となっている
if((200 <= $sum_price) && ($sum_price <= 300000)){
	$tmpl->assign_def("conv_rowprice",true);
}

#------------------------------------------------------------------------------
# 個人情報入力画面のHTML設定
#------------------------------------------------------------------------------
$tmpl->assign("payment_method_ck1", ($_SESSION['cust']['PAYMENT_METHOD'] == 1)?" checked":"");
$tmpl->assign("payment_method_ck2", ($_SESSION['cust']['PAYMENT_METHOD'] == 2)?" checked":"");
$tmpl->assign("payment_method_ck3", ($_SESSION['cust']['PAYMENT_METHOD'] == 3)?" checked":"");
$tmpl->assign("payment_method_ck4", ($_SESSION['cust']['PAYMENT_METHOD'] == 4)?" checked":"");
$tmpl->assign("payment_method_ck5", ($_SESSION['cust']['PAYMENT_METHOD'] == 5)?" checked":"");
$tmpl->assign("last_name", ($_SESSION['cust']['LAST_NAME'])?$_SESSION['cust']['LAST_NAME']:"");
$tmpl->assign("first_name", ($_SESSION['cust']['FIRST_NAME'])?$_SESSION['cust']['FIRST_NAME']:"");
$tmpl->assign("last_kana", ($_SESSION['cust']['LAST_KANA'])?$_SESSION['cust']['LAST_KANA']:"");
$tmpl->assign("first_kana", ($_SESSION['cust']['FIRST_KANA'])?$_SESSION['cust']['FIRST_KANA']:"");
$tmpl->assign("zip1", ($_SESSION['cust']['ZIP_CD1'])?$_SESSION['cust']['ZIP_CD1']:"");
$tmpl->assign("zip2", ($_SESSION['cust']['ZIP_CD2'])?$_SESSION['cust']['ZIP_CD2']:"");
$tmpl->assign("address1", ($_SESSION['cust']['ADDRESS1'])?$_SESSION['cust']['ADDRESS1']:"");
$tmpl->assign("address2", ($_SESSION['cust']['ADDRESS2'])?$_SESSION['cust']['ADDRESS2']:"");
$tmpl->assign("email", ($_SESSION['cust']['EMAIL'])?$_SESSION['cust']['EMAIL']:"");
$tmpl->assign("tel1", ($_SESSION['cust']['TEL1'])?$_SESSION['cust']['TEL1']:"");
$tmpl->assign("tel2", ($_SESSION['cust']['TEL2'])?$_SESSION['cust']['TEL2']:"");
$tmpl->assign("tel3", ($_SESSION['cust']['TEL3'])?$_SESSION['cust']['TEL3']:"");
$tmpl->assign("email", ($_SESSION['cust']['EMAIL'])?$_SESSION['cust']['EMAIL']:"");
$tmpl->assign("remarks", ($_SESSION['cust']['REMARKS'])?$_SESSION['cust']['REMARKS']:"");

//パスワードの入力欄の表示
if($_SESSION['cust']['m'] != "1"){
	$tmpl->assign_def("input_pw",true);
	//$tmpl->assign("password", ($_SESSION['cust']['ALPWD'])?$_SESSION['cust']['PASSWORD']:"");
	//$tmpl->assign("password2", ($_SESSION['cust']['CALPWD'])?$_SESSION['cust']['PASSWORD']:"");
}

//都道府県情報の設定（ループ）
	$tmpl->loopset("state_data_loop");
	for ( $i = 0; $i < count($shipping_list); $i++ ){

		$tmpl->assign("state_id",$shipping_list[$i]['id']);
		$tmpl->assign("state_name",$shipping_list[$i]['pref']);

		// 既に選択された項目をチェック（あれば“ selected”を挿入。デフォルトは東京都）
		$stt = ($_SESSION['cust']['STATE'] === NULL)?12:$_SESSION['cust']['STATE'];

		if($shipping_list[$i]['id'] == $stt):
			$tmpl->assign("state_selected", " selected");
		else:
			$tmpl->assign("state_selected", "");
		endif;

		$tmpl->loopnext("state_data_loop");
	}
	$tmpl->loopend("state_data_loop");

$tmpl->assign("deli_last_name", ($_SESSION['cust']['DELI_LAST_NAME'])?$_SESSION['cust']['DELI_LAST_NAME']:"");
$tmpl->assign("deli_first_name", ($_SESSION['cust']['DELI_FIRST_NAME'])?$_SESSION['cust']['DELI_FIRST_NAME']:"");
$tmpl->assign("deli_zip1", ($_SESSION['cust']['DELI_ZIP_CD1'])?$_SESSION['cust']['DELI_ZIP_CD1']:"");
$tmpl->assign("deli_zip2", ($_SESSION['cust']['DELI_ZIP_CD2'])?$_SESSION['cust']['DELI_ZIP_CD2']:"");
$tmpl->assign("deli_address1", ($_SESSION['cust']['DELI_ADDRESS1'])?$_SESSION['cust']['DELI_ADDRESS1']:"");
$tmpl->assign("deli_address2", ($_SESSION['cust']['DELI_ADDRESS2'])?$_SESSION['cust']['DELI_ADDRESS2']:"");
$tmpl->assign("deli_tel1", ($_SESSION['cust']['DELI_TEL1'])?$_SESSION['cust']['DELI_TEL1']:"");
$tmpl->assign("deli_tel2", ($_SESSION['cust']['DELI_TEL2'])?$_SESSION['cust']['DELI_TEL2']:"");
$tmpl->assign("deli_tel3", ($_SESSION['cust']['DELI_TEL3'])?$_SESSION['cust']['DELI_TEL3']:"");

// 配送先都道府県情報の設定
$tmpl->loopset("state_data_loop");

for ( $i = 0; $i < count($shipping_list); $i++ ){

	$tmpl->assign("state_id", $shipping_list[$i]['id']);
	$tmpl->assign("state_name", $shipping_list[$i]['pref']);

	// 既に選択された項目をチェック（あれば“ selected”を挿入。デフォルトは東京都）
	$stt = ($_SESSION['cust']['DELI_STATE'] === NULL)?12:$_SESSION['cust']['DELI_STATE'];

	if ( $shipping_list[$i]['id'] == $stt ):
		$tmpl->assign("state_selected", " selected");
	else:
		$tmpl->assign("state_selected", "");
	endif;

	$tmpl->loopnext("state_data_loop");
}
$tmpl->loopend("state_data_loop");

// 住所と配送先が同じ場合（一度Step2までいった場合はYESになる事があるので）
//$tmpl->assign("data_copy_flg_checked", ($_SESSION['cust']['data_copy_flg'] == 1)?" checked":"");

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
