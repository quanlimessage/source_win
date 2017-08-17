<?php
/*******************************************************************************

View：購入商品一覧（買い物カゴの中身）表示とEmailでの認証方法選択画面
Status:なし（default）

	１．初めての利用→個人情報入力画面を表示（このプログラムの一連の処理に乗る）
	２．２回目以降→認証チェックプログラム→個人情報入力画面（失敗：エラー表示で終）
	３．パスワードを忘れた方→パスワード送信プログラムを実行（別プログラムとして独立）
	４．Emial変更届け→Email変更プログラムを実（別プログラムとして独立）
		※３と４はこのプログラムのコントローラーからは軌道が外れる（別プログラムとして独立）

s*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#------------------------------------------------------------------------
# HTTPヘッダーを出力
# 文字コード／JSとCSSの設定／無効な有効期限／キャッシュ拒否／ロボット拒否
#------------------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

// テンプレートクラスライブラリ読込みと出力用HTMLテンプレートをセット
if ( !file_exists("TMPL_cartList.html") )	die("Template File Is Not Found!!");
$tmpl = new Tmpl2("TMPL_cartList.html");

#=================================================================================
# テンプレートを使用してHTML出力の設定
#=================================================================================
// TITLE
$tmpl->assign("title",SHOPPING_TITLE);

// HEADのTITLE
$tmpl->assign("shopping_title",SHOPPING_TITLE);

// エラーメッセージがある場合は表示（認証チェック後にエラーがあり再入力）
if($error_message){
    $mes = "\n{$error_message}\n";
	$tmpl->assign("error_message", $mes);
}
else{
	$tmpl->assign("error_message", "&nbsp;");
}

#---------------------------------------------------------------------
# ループセット。現在の買い物カゴの中身を取得して表示
#	※getItems()はLF_cart_calc2.phpより
#---------------------------------------------------------------------
$cart_list = getItems();

$tmpl->loopset("cart_list_loop");

for ( $i = 0; $i < count($cart_list); $i++ ){

	// 各データを取り出す
	list($product_id,$part_no,$product_name,$selling_price,$quantity,$stock_quantity) = explode("\t", $cart_list[$i]);

	// 単価×個数で小計金額を算出
	$amount = ($selling_price * $quantity);

	// 合計金額を算出
	$sum_price += $amount;

	// HTML出力の設定
	$tmpl->assign("part_no", ($part_no)?$part_no:"&nbsp;");					// 型番
	$tmpl->assign("product_name", ($product_name)?$product_name:"&nbsp;");	// 商品名
	$tmpl->assign("selling_price", number_format($selling_price));			// 単価
	$tmpl->assign("quantity", $quantity);									// 数量
	$tmpl->assign("product_id", $product_id);								// 商品ID

	// 小計のHTML出力
	$tmpl->assign("amount", number_format($amount));

	// 追加用リンクボタン
	# 在庫数を超過したら追加用リンクは表示しない。
		// 在庫数取得
		$sql="
		SELECT
			STOCK_QUANTITY
		FROM
			".PRODUCT_LST."
		WHERE
			PRODUCT_ID = '".$product_id."'
		";

		$fetchCnt = $PDO -> fetch($sql);

	// 在庫数が購入個数以上の場合
	if($fetchCnt[0]["STOCK_QUANTITY"] > $quantity){
		// 個数の追加用リンクのHTML出力
		//$tmpl->assign("addItem_link", "<input type=\"image\" src=\"./images/icon_plus.gif\" width=\"18\" height=\"18\" alt=\"追加\">");
		$tmpl->assign("addItem_link", "<input type=\"image\" src=\"./images/add.gif\" width=\"18\" height=\"18\" alt=\"追加\">");

	}else{
		$tmpl->assign("addItem_link", "&nbsp;");
	}

	$tmpl->loopnext("cart_list_loop");
}
$tmpl->loopend("cart_list_loop");

// 合計金額（送料／代引きは含まず）のHTML出力
$tmpl->assign("sum_price", number_format($sum_price));

#------------------------------------------------------------------------------------------------
#	 残りいくらで送料無料になるか
#	【送料計算】タイプにより送料計算の処理分岐(設定ファイル定数SHIPPING_COND_TYPEにてタイプは定義)
#------------------------------------------------------------------------------------------------
/*
switch(SHIPPING_COND_TYPE){
case 0:
	$tmpl->assign_def("nokoriprice",false);
break;
case 1:
	$tmpl->assign_def("nokoriprice",false);
break;
*/
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
