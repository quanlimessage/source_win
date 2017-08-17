<?php
/*******************************************************************************

Logic : カート操作ロジック
	コントローラー内のdefaultで実行。
	パラメーター（$_GET['dispcart']）の内容により処理を分岐する（なければ何もしない）

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");	exit();
}

#==========================================================================
# カート表示＆操作フラグ（$_GET['dispcart']）があればそれを優先して処理する
#==========================================================================
switch($_POST['dispcart']):
case 3:
/////////////////////////////////////////////////////
// カートの中身の商品の個数追加（“+”になっている）

	$_SESSION['shopping_step_flg'] = "default";//カート商品を変更した場合はデフォルトに来たデータを入れる
	// 不正パラメーターチェック
	if( !isset($_POST['pid']) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_POST['pid']) ){
		header("Location: ./");	exit();
	}

	#----------------------------------------------------------
	# 該当商品の個数を１つ追加して
	# カートリスト表示プログラムを読み込む
	#	※LF_cart_calc2.php（カート操作PG）のaddItems()を実行
	#----------------------------------------------------------
	$items['product_id']		= $_POST['pid'];
	$items['quantity']			= 1;	// 購入個数
	addItems($items,"1");

	break;
case 2:
/////////////////////////////////////////////////////
// カートの中身の商品の個数削除（“-”になっている）

	$_SESSION['shopping_step_flg'] = "default";//カート商品を変更した場合はデフォルトに来たデータを入れる
	// 不正パラメーターチェック
	if( !isset($_POST['pid']) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_POST['pid']) ){
		header("Location: ./");	exit();
	}

	#----------------------------------------------------------
	# 該当商品の個数を１つ削除して
	# カートリスト表示プログラムを読み込む
	#	※LF_cart_calc2.php（カート操作PG）のreturnItems()を実行
	#----------------------------------------------------------

	$del_cnt = 1;
	returnItems($_POST['pid'],$del_cnt);

	break;
case 1:
/////////////////////////////////////////////////////
// カートに商品を追加
// 詳細画面より“カートに入れる”ボタン押下時、該当の商品をカートに入れる。

	$_SESSION['shopping_step_flg'] = "default";//カート商品を変更した場合はデフォルトに来たデータを入れる
	// 不正パラメーターチェック
	# 商品番号
	if( !isset($_POST['pid']) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_POST['pid']) ){
		header("Location: ./");	exit();
	}

	# 該当商品情報を取得
		$sql="
		SELECT
			PRODUCT_ID,
			PART_NO,
			PRODUCT_NAME,
			STOCK_QUANTITY,
			SELLING_PRICE
		FROM
			".PRODUCT_LST."
		WHERE
			( PRODUCT_ID = '".$_POST['pid']."' )
		AND
			( DISPLAY_FLG = '1' )
		AND
			( DEL_FLG = '0' )
		";

		$fetchProperty = $PDO -> fetch($sql);

		# データ抽出失敗時
		if(!$fetchProperty):
			$error_message = "エラー：指定された商品が一致しません。";
			break;
		endif;

		// カートプログラム読込→データ設定→商品を買い物カゴに入れる（結果を取得）
		//タブで配列区切りをしているのでここで商品名などの余分なタブを削除する
		# 商品ID
		$items['product_id']		= $fetchProperty[0]['PRODUCT_ID'];
		# 商品番号
		$items['part_no']		= str_replace("\t","",$fetchProperty[0]['PART_NO']);
		# 商品名
		$items['product_name']		= str_replace("\t","",$fetchProperty[0]['PRODUCT_NAME']);
		# 販売価格
		$items['selling_price']		= str_replace("\t","",math_tax($fetchProperty[0]['SELLING_PRICE']));
		# 購入個数
		$items['quantity']		= 1;	// 購入個数（原則は1ヶ）
		# 現在の在庫数
		$items['stock_quantity']	= $fetchProperty[0]["STOCK_QUANTITY"];
		// 商品をカートに追加処理
		$addItemResult = addItems($items);

	break;
endswitch;

?>
