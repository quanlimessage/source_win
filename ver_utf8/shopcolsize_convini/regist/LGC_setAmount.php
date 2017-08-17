<?php
/*******************************************************************************
アパレル対応
ショッピングカートプログラム

Logic : 購入商品の総計計算後、総計に応じて送料・代引き手数料を算出/セッションに格納

	#送料・代引き手数料設定の解説は設定ファイル(INI_config.php)を参照してください。

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

////////////////////////////////////////////////////////////////////////
// エラーチェックファイルでのエラーが無かったら、各料金計算・登録処理

if($_POST["status"]=="confirm" and empty($error_message)){

	// 都道府県のIDを取得（INI_pref_list.phpより都道府県名を取り出すのに使用）
	$dsid = $_SESSION['cust']['DELI_STATE'];	// 配送先用

	#--------------------------------------------------------------------------------
	#	【購入商品合計計算】
	#--------------------------------------------------------------------------------
	// カート内商品情報取得
	$cart_list = getItems();

	// 合計額取得
	for ( $i = 0; $i < count($cart_list); $i++ ){

		// 各データを取り出す
		list($product_id,$disc_id,$part_no,$product_name,$color_name,$size_name,$selling_price,$quantity,$stock_quantity) = explode("\t", $cart_list[$i]);

		// 単価×個数で小計金額$amountを算出
		$amount = ($selling_price * $quantity);

		// 合計金額$sum_priceを算出
		$sum_price += $amount;
	}

	// セッションのお客様情報・支払い情報に合計金額$sum_priceをセット
	$_SESSION["cust"]["sum_price"]=$sum_price;

	#------------------------------------------------------------------------------------------------
	#	【送料計算】タイプにより送料計算の処理分岐(設定ファイル定数SHIPPING_COND_TYPEにてタイプは定義)
	#------------------------------------------------------------------------------------------------

	switch(SHIPPING_COND_TYPE):

		#	送料無料
		####################################
		case "0":
				$_SESSION['cust']['shipping_amount'] = 0;
			break;

		#	送料一律
		####################################
		case "1":
				$_SESSION['cust']['shipping_amount'] = SHIPPING_COND;
			break;

		#	条件額以上で送料割引
		####################################
		case "2":
				if($_SESSION["cust"]["sum_price"] >= SHIPPING_FREE){
					$_SESSION['cust']['shipping_amount'] = 0;
				}else{
					$_SESSION['cust']['shipping_amount'] = SHIPPING_COND;
				}
			break;

		#	都道府県毎の送料・条件額なし
		####################################
		case "3":
			$_SESSION['cust']['shipping_amount'] = $shipping_list[$dsid]['shipping1'];
			break;

		#	都道府県毎の送料・条件額あり
		####################################
		case "4":
				if($_SESSION["cust"]["sum_price"] >= SHIPPING_FREE){
					//$_SESSION['cust']['shipping_amount'] = $shipping_list[$dsid]['shipping2'];
					$_SESSION['cust']['shipping_amount'] = 0;
				}else{
					$_SESSION['cust']['shipping_amount'] = $shipping_list[$dsid]['shipping1'];
				}
			break;

		#	送料のオリジナル設定
		####################################
		case "5":

			// 送料をオリジナルで定義する場合はここに記述

			break;
	endswitch;

	#-------------------------------------------------------------------------------------------------------
	#	【代引き手数料計算】タイプにより手数料計算の処理分岐(設定ファイル定数DAIBIKI_COND_TYPEにてタイプは定義)
	#-------------------------------------------------------------------------------------------------------

	//代引き時のみ処理発生
	if($_SESSION['cust']['PAYMENT_METHOD'] == 3){

		switch(DAIBIKI_COND_TYPE):

			#	代引き手数料無し
			####################################
			case "0":
					$_SESSION['cust']['daibiki_amount'] = 0;
				break;

			#	代引き手数料一律
			####################################
			case "1":
					$_SESSION['cust']['daibiki_amount'] = DAIBIKI_COND;
				break;

			#	商品合計額が条件額(DAIBIKI_FREE)以上なら送料無料
			####################################
			case "2":
				if($_SESSION["cust"]["sum_price"] >= DAIBIKI_FREE):
					$_SESSION['cust']['daibiki_amount'] = 0;
				else:
					$_SESSION['cust']['daibiki_amount'] = DAIBIKI_COND;
				endif;
				break;

			#	都道府県毎の代引き手数料・条件額なし
			####################################
			case "3":
				$dsid = $_SESSION['cust']['DELI_STATE'];	// 配送住所
				$_SESSION['cust']['daibiki_amount'] = $shipping_list[$dsid]['daibiki1'];
				break;

			#	都道府県毎の代引き手数料・条件額あり
			####################################
			case "4":
					if($_SESSION["cust"]["sum_price"] >= DAIBIKI_FREE){
						$_SESSION['cust']['daibiki_amount'] = $shipping_list[$dsid]['daibiki2'];
					}else{
						$_SESSION['cust']['daibiki_amount'] = $shipping_list[$dsid]['daibiki1'];
					}
				break;

			#	代引き手数料のオリジナル設定
			####################################
			case "5":

				// 代引き手数料をオリジナルで定義する場合はここに記述
				// お買い上げ10000円未満は代引き手数料315円
				if($_SESSION["cust"]["sum_price"] < 10000){
					$_SESSION["cust"]["daibiki_amount"] = 315;

				// お買い上げ10000円以上で30000円未満は代引き手数料315円
				}
				elseif(($_SESSION["cust"]["sum_price"] >= 10000) AND ($_SESSION["cust"]["sum_price"] < 30000)){
					$_SESSION["cust"]["daibiki_amount"] = 420;

				// お買い上げ30000円以上で100000円未満は代引き手数料630円
				}
				elseif(($_SESSION["cust"]["sum_price"] >= 30000) AND ($_SESSION["cust"]["sum_price"] < 100000)){
					$_SESSION["cust"]["daibiki_amount"] = 630;
				// お買い上げ100000円以上で3000000円までは代引き手数料1050円
				}
				elseif(($_SESSION["cust"]["sum_price"] >= 100000) AND ($_SESSION["cust"]["sum_price"] <= 300000)){
					 $_SESSION["cust"]["daibiki_amount"] = 1050;
				}

				break;
		endswitch;
	}elseif(isset($_SESSION['cust']['daibiki_amount'])){
			unset($_SESSION['cust']['daibiki_amount']);
	}

}
?>
