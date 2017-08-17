<?php
/********************************************************************************
 買い物カゴ操作機能スクリプト２	カラー/サイズ/在庫数対応版

 １．買い物カゴに入れる（既にある商品なら個数を追加するのみ）
	 メソッド名：addItems(ハッシュ化された商品情報)
	 戻り値：実行結果（新規：new ／ 個数追加のみ：update）

	※引数はハッシュとして設定（インデックスの指定方法は下記の通り）
	product_id		:商品ID
	disc_di			:商品(カラー/在庫)ID
	property_id		:PRODUCT_PROPERTY_DATAの管理ID（これで各商品の識別を行う）
	color_id		:カラーID
	size_id		:サイズID
	part_no			:商品番号
	product_name	:商品名
	color_name		:カラーネーム
	size_name		:サイズネーム
	selling_price	:単価
	quantity		:購入個数

	※実行例
		$items["product_id"]	= $_SESSION["fetchProductData"][0]["PRODUCT_ID"];
		$items["property_id"]	= $fetchColorAndSize[0]["DSC_ID"];
		$items["color_id"]		= $fetchColorAndSize[0]["COLOR_ID"];
		$items["size_id"]		= $fetchColorAndSize[0]["SIZE_ID"];
		$items["part_no"]		= $_SESSION["fetchProductData"][0]["PART_NO"];
		$items["product_name"]	= $_SESSION["fetchProductData"][0]["PRODUCT_NAME"];
		$items["color_name"]	= $fetchColorAndSize[0]["COLOR_NAME"];
		$items["size_name"]		= $fetchColorAndSize[0]["SIZE_NAME"];
		$items["selling_price"] = $_SESSION["fetchProductData"][0]["SELLING_PRICE"];
		$items["quantity"]		= 1;	// 購入個数（原則は1ヶ）

		// 実行($resultには戻り値として“new”または“update”が格納される)
		$result = addItems($items);

 ２．買い物カゴから商品取り出す（戻す）
	メソッド名：returnItems(対象の商品ID,対象のカラーID,戻す個数)
 	戻り値：実行結果により下記の通りとなる
		削除成功：なし
		削除失敗：エラーメッセージ（削除対象の商品が買い物カゴの中にない場合）

 	※戻す個数の引数を指定しない場合、1ヶのみ戻す仕様とする
	※引数で指定した削除個数が買い物カゴの中の個数より多い
	　または両方引くと0になる場合は、買い物カゴから対象の商品を削除する

 ３．カゴの内容を全部削除
 	メソッド名：deleteItems()	※引数なし
	戻り値：なし

 ４．現在の買い物カゴの中身を取得（データそのものは削除はしない）
	メソッド名：getItems([return_type])	※引数（オプション）：戻り値の形式

	戻り値
		引数を“array”とした場合：全内容を多次元配列で返す
		引数指定なし：配列（一つの要素が買い物カゴの中身がタブ区切りでまとまっているデータ）

	※実行例：引数指定がない場合で取得した際はfor文で配列の要素分、
	　explode関数でタブ区切りデータを分解して各データを取得する
	$cart = getItems();
	for($i=0;$i<count($cart);$i++){
		$list = explode("\t",$cart[$i]);
		foreach($list as $e)echo $e;
	}

	※実行例：引数指定（array）とした場合（多次元配列で返す）
	$cart = getItems("array");
	for($i=0;$i<count($cart);$i++){
		foreach($cart[$i] as $k => $e)echo $k."は".$e."です<br>\n";
		echo "<hr>";
	}

*********************************************************************************/

#################################################################################
#
# １．買い物カゴに入れる（既にある商品なら個数を追加するのみ）
# 	メソッド名：addItems()
#	$c_up カートページから商品の操作を行った場合のフラグ
#
#################################################################################
function addItems($items,$c_up = ""){

	// 実行結果が、新規登録／個数追加の結果を返す変数を初期化(格納値：new/update)
	$executionResult = "";

	// 購入個数の指定がない場合は、1ヶ追加とするため、数値の1を設定
	if(!$items["quantity"])$items["quantity"] = 1;

	// 買い物カゴのリストが設定されていない場合（初めてのアクセス）初期化
	if(!isset($_SESSION["cart_list"]))$_SESSION["cart_list"] = array();

	#============================================================================
	# 買い物カゴへの新規追加、または購入個数の追加
	# 	※現在の買い物カゴのリストの件数分、登録されている商品IDと引数で
	#	　指定された商品IDと比較する。
	#============================================================================

	#----------------------------------------------------------------------------
	# 既にカゴにある商品（商品IDとカラーIDが同じ）なら指定された個数分追加して
	# 再度買い物カゴに入れる（実行結果：update）。
	#----------------------------------------------------------------------------
	for($i=0;$i<count($_SESSION["cart_list"]);$i++):

		list($product_id,$disc_id,$part_no,$product_name,$color_name,$size_name,$selling_price,$quantity,$stock_quantity) = explode("\t",$_SESSION["cart_list"][$i]);

		// 商品IDと商品(カラー/在庫)詳細IDが同じなら追加
		if(($product_id == $items["product_id"])&&($disc_id == $items["disc_id"])):
			// 購入個数が現在の在庫数より上回らなければOK（逆ならエラー値を指定）
			if(($items["quantity"] + $quantity) <= $stock_quantity){

				// 個数を追加更新
				$quantity += $items["quantity"];

				// 更新データと元のデータをタブ区切りで一つにまとめ、
				// 再度買い物カゴに入れて実行結果をセットする。
				$data = $product_id."\t";
				$data .= $disc_id."\t";
				//$data .= $color_id."\t";
				//$data .= $size_id."\t";
				$data .= $part_no."\t";
				$data .= $product_name."\t";
				$data .= $color_name."\t";
				$data .= $size_name."\t";
				$data .= $selling_price."\t";
				$data .= $quantity."\t";
				$data .= $stock_quantity;

				$_SESSION["cart_list"][$i] = $data;
				$executionResult = "update";
			}
			else{

				// もし在庫以上商品を購入されたら在庫文だけ購入可(追加購入)
				$quantity = $items["stock_quantity"];

				// 更新データと元のデータをタブ区切りで一つにまとめ、
				// 再度買い物カゴに入れて実行結果をセットする。
				$data = $product_id."\t";
				$data .= $disc_id."\t";
				//$data .= $color_id."\t";
				//$data .= $size_id."\t";
				$data .= $part_no."\t";
				$data .= $product_name."\t";
				$data .= $color_name."\t";
				$data .= $size_name."\t";
				$data .= $selling_price."\t";
				$data .= $quantity."\t";
				$data .= $stock_quantity;

				$_SESSION["cart_list"][$i] = $data;
				$executionResult = "addstock";
			}

		endif;

	endfor;

	#----------------------------------------------------------------------------
	# 上記の比較結果により、実行結果変数（$executionResult）がセットされていない
	# 場合は新規追加データとみなし、引数の配列データ（追加情報）をタブ区切りで
	# 一つにまとめて買い物カゴに入れる（実行結果：new）。
	# $c_upでカート側の操作での追加で新しい商品を追加はさせない
	#----------------------------------------------------------------------------
	if(!$executionResult && !$c_up){
		// もし在庫以上商品を購入されたら在庫文だけ購入可(新規購入)
		if($items["quantity"] >= $items["stock_quantity"]){
			$items["quantity"] = $items["stock_quantity"];
		}

		$data = implode("\t",$items);
		array_push($_SESSION["cart_list"],$data);
		$executionResult = "new";
	}

	#====================================
	# 実行結果を返す
	#====================================
	return $executionResult;

}

#################################################################################
#
# ２．カゴから商品を指定した個数分戻す（削除）
# 		メソッド名：returnItems()
#
#################################################################################
function returnItems($target_product_id,$target_disc_id,$del_cnt=""){

	// 実行結果フラグを初期化
	$executionResult = "";

	// 戻す個数の指定がない場合は、1ヶ削除とするため、数値の1を設定
	if(!$del_cnt)$del_cnt = 1;

	#============================================================================
	# 買い物カゴから指定された個数の削除
	# 	※現在の買い物カゴのリストの件数分、登録されている商品ID／カラーIDと
	#	　引数で指定された商品ID／カラーIDで比較する。
	#============================================================================
	for($i=0;$i<count($_SESSION["cart_list"]);$i++):

		list($product_id,$disc_id,$part_no,$product_name,$color_name,$size_name,$selling_price,$quantity,$stock_quantity) = explode("\t",$_SESSION["cart_list"][$i]);

		if(($product_id == $target_product_id)&&($disc_id == $target_disc_id)):

			#----------------------------------------------------------------
			# 指定された削除数が現在の個数より多いまたは両方引くと0の場合は
			# 買い物カゴから該当データをすべて削除し、それ以外（通常）の場合
			# は指定された個数分、現在の個数から引き、データをまとめて直して
			# 買い物カゴへ入れる
			#----------------------------------------------------------------
			if($del_cnt>$quantity||(($del_cnt - $quantity) == 0)){
				array_splice($_SESSION["cart_list"],$i,1);
			}
			else{
				$quantity -= $del_cnt;

				$data = $product_id."\t";
				$data .= $disc_id."\t";
				//$data .= $color_id."\t";
				//$data .= $size_id."\t";
				$data .= $part_no."\t";
				$data .= $product_name."\t";
				$data .= $color_name."\t";
				$data .= $size_name."\t";
				$data .= $selling_price."\t";
				$data .= $quantity."\t";
				$data .= $stock_quantity; // 該当商品の現時点での在庫数を格納

				$_SESSION["cart_list"][$i] = $data;

			}

			// 実行結果フラグを立てる
			$executionResult = 1;

		endif;

	endfor;

	#============================================================================
	# 実行結果フラグがfalse（指定された商品IDが一致しない）の場合は
	# エラーメッセージを返す
	#============================================================================
	if(!$executionResult)return "エラー：指定した商品は買い物カゴの中にありません";

}

#################################################################################
#
# ３．買い物カゴの中身を全部戻す（データを全部削除）
#		メソッド名：deleteItems()
#
#################################################################################
function deleteItems(){
	$_SESSION["cart_list"] = array();
}

#################################################################################
#
# ４．現在の買い物カゴの中身を取得（データそのものは削除はしない）
#		メソッド名：getItems([return_type])
#
#################################################################################
function getItems($return_type = ""){

	#---------------------------------------------------------------------
	# 戻り値の形式指定が配列（引数：“array”）なら多次元配列で結果を返す
	#	※指定がない場合は生のそのままの形で結果を返す
	#---------------------------------------------------------------------
	if($return_type == "array"):

		$cart_list = array();
		for($i=0;$i<count($_SESSION["cart_list"]);$i++):

			list($product_id,$disc_id,$part_no,$product_name,$color_name,$size_name,$selling_price,$quantity,$stock_quantity) = explode("\t",$_SESSION["cart_list"][$i]);
			$cart_list[$i]["product_id"] = $product_id;
			$cart_list[$i]["disc_id"] = $disc_id;
			//$cart_list[$i]["color_id"] = $color_id;
			//$cart_list[$i]["size_id"] = $size_id;
			$cart_list[$i]["part_no"] = $part_no;
			$cart_list[$i]["product_name"] = $product_name;
			$cart_list[$i]["color_name"] = $color_name;
			$cart_list[$i]["size_name"] = $size_name;
			$cart_list[$i]["selling_price"] = $selling_price;
			$cart_list[$i]["quantity"] = $quantity;
			$cart_list[$i]["stock_quantity"] = $stock_quantity;

		endfor;
		return $cart_list;

	else:
		return $_SESSION["cart_list"];
	endif;
}

?>
