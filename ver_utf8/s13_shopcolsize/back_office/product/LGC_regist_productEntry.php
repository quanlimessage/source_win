<?php
/*******************************************************************************
カテゴリ対応
	ショッピングカートプログラム バックオフィス

商品の更新
Logic：入力情報をチェックし、ＤＢへ登録

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# 現在の登録件数が設定した件数未満の場合のみDBに格納
#--------------------------------------------------------------------------------
if($_POST["regist_type"]=="new"){
	if(PRODUCT_ENTRY_FLG){
		header("Location: ./");
		exit();
	}
}

// MySQLにおいて危険文字をエスケープしておく
$title_tag = utilLib::strRep($title_tag,5);
//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$item_details = html_tag($_POST['item_details']);

#=================================================================================
# 販売開始日時の設定
#=================================================================================
// 販売開始日時のタイムスタンプ作成
if(!empty($y1) && !empty($m1) && !empty($d1)){
	// 指定があれば指定日時のタイムスタンプ作成
	$start_time = "{$y1}-{$m1}-{$d1} {$h1}:00:00";
}else{
	// 指定が無ければ空
	$start_time = "";
}

// 販売終了日時のタイムスタンプ作成
if(!empty($y2) && !empty($m2) && !empty($d2)){
	// 指定があれば指定日時のタイムスタンプ作成
	$end_time = "{$y2}-{$m2}-{$d2} {$h2}:00:00";
}else{
	// 指定が無ければ空
	$end_time = "";
}

//複製する時のVIEW_ORDER
	$vosql = "
		SELECT
			MAX(VIEW_ORDER) AS VO
		FROM
			".PRODUCT_LST."
		WHERE
			(CATEGORY_CODE = '$category_code')
		AND
			(DEL_FLG = '0')
	";

	$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($_POST["copy_type"]=="new"){
			//複製データのVIEW_ORDER
			$vosql_old = "SELECT VIEW_ORDER AS VO FROM ".PRODUCT_LST." WHERE (PRODUCT_ID = '$product_id') AND (DEL_FLG = '0')";
			$fetchVO_old = dbOpe::fetch($vosql_old,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			//$view_order複製データのVIEW_ORDER+1
			$view_order_old = $fetchVO_old[0]["VO"];
			$vosql_new ="UPDATE ".PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$category_code') AND (VIEW_ORDER > $view_order_old)";
			if(!empty($vosql_new)){
				$db_result = dbOpe::regist($vosql_new,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = ($fetchVO_old[0]["VO"] + 1);
	}elseif($_POST["ins_chk"]=="1"){
			$vosql_new ="UPDATE ".PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$category_code')";
			if(!empty($vosql_new)){
				$db_result = dbOpe::regist($vosql_new,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = 1;
	}else{
	//新規登録
			$view_order = ($fetchVO[0]["VO"] + 1);
	}

#=========================================================================
# 画像ファイル名の決定（商品IDをファイル名とする）
#	新規：新しいIDを生成して使用。新規登録時のPRUDUCT_IDにもそれを使用する
#	更新：POSTで渡された既存の商品ID（$product_id）を使用
#=========================================================================
if($regist_type == "update"){
	$for_imgname = $product_id; // 既存
}
elseif($regist_type == "new"){
	$product_id = $makeID(); // 商品IDのIDとしても使用する
	$for_imgname = $product_id;
}

#=========================================================================
# 画像削除処理
#==============================================================================
# 更新処理で削除指示がされていたら実行
if($_POST["regist_type"]=="update" && $del_img){
	foreach($del_img as $k => $v){
		search_file_del(PRODUCT_IMG_FILEPATH,$product_id."_".$v.".*");
	}
}

#=========================================================================
# 画像アップロード処理
#==============================================================================

// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(PRODUCT_IMG_FILEPATH);

// 一覧ページ用画像
	// アップロードされた画像ファイルがあればアップロード処理
	if(is_uploaded_file($_FILES['thumbnail_file']['tmp_name'])){

		// 古いファイルを削除
			search_file_del(PRODUCT_IMG_FILEPATH,$product_id."_s.*");

		// アップされてきた画像のサイズを計る
			$size_data = getimagesize($_FILES['thumbnail_file']['tmp_name']);

		//画像サイズを調整
			$size_x = IMG_SIZE_SX;//横の固定サイズ
			$size_y = $size_data[1]/($size_data[0]/$size_x);

		// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
			$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
			//$imgObj->setSize(IMG_SIZE_SX, IMG_SIZE_SY);

		if( !$imgObj->up($_FILES['thumbnail_file']['tmp_name'], $for_imgname."_s") ){
			exit("一覧ページ用画像のアップロード処理に失敗しました。");
		}
	}

	#=========================================================================
	# アップロードした商品画像をアップした件数分固定サイズに変更する処理
	#=========================================================================

	// 設定ファイルの画像最大登録枚数(PRODUCT_IMG_NUM)分ループ
	for($i=1;$i<=PRODUCT_IMG_NUM;$i++):
		if(is_uploaded_file($_FILES['product_img_file']['tmp_name'][$i])){

			// 古いファイルを削除
				search_file_del(PRODUCT_IMG_FILEPATH,$product_id."_".$i.".*");

			// アップされてきた画像のサイズを計る
				$size_data = getimagesize($_FILES['product_img_file']['tmp_name'][$i]);

			//画像サイズを調整
				$size_x = IMG_SIZE_LX;//横の固定サイズ
				$size_y = $size_data[1]/($size_data[0]/$size_x);

			// 詳細ページ用画像
				$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
				//$imgObj->setSize(IMG_SIZE_LX, IMG_SIZE_LY);

			if( !$imgObj->up($_FILES['product_img_file']['tmp_name'][$i], $for_imgname."_".$i) ){
				exit("詳細画像{$i}のアップロード処理に失敗しました。");
			}

			// 拡大画像
/*			$imgObj->setSize(IMG_SIZE_LX, IMG_SIZE_LY);
			if( !$imgObj->up($_FILES['product_img_file']['tmp_name'][$i], $for_imgname."_".$i."b") )
				exit("詳細画像{$i}のアップロード処理に失敗しました。");*/
		}
	endfor;

#=================================================================================
# 新規か更新かによってＳＱＬを分岐	※判断は$_POST["regist_type"]
#=================================================================================

	#==================================================================
	# 更新する内容をここで記述をする
	# フィールドの追加・変更はここで修正
	#==================================================================

	$sql_update_data = "
		CATEGORY_CODE = '$category_code',
		PART_NO = '$part_no',
		PRODUCT_NAME = '$product_name',
		CAPACITY = '$capacity',
		STOCK_QUANTITY = '$stock_quantity',
		ITEM_DETAILS = '$item_details',
		PRODUCT_DETAILS = '$product_details',
		TITLE_TAG = '$title_tag',
		SELLING_PRICE = '$selling_price',
		DISPLAY_FLG  = '$display_flg',
		CART_CLOSE_FLG = '$cart_close_flg',
		SALE_START_DATE = '$start_time',
		SALE_END_DATE = '$end_time',
		UPD_DATE = NOW(),
		DEL_FLG = '0',
		NEW_ITEM_FLG = '$new_item_flg'
	";

switch($_POST["regist_type"]):
case "update":
/////////////////////////////////////////////////////////////////////////////////
// 更新

	#-----------------------------------------------------
	# 商品情報
	#-----------------------------------------------------
	$sql[] = "
	UPDATE
		".PRODUCT_LST."
	SET
		$sql_update_data
	WHERE
		(PRODUCT_ID = '$product_id')
	AND
		(DEL_FLG = '0')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////////////////////
// 新規

	// 並び順：現在のview_orderの一番最後に１を足したものを設定
/*	$vosql = "
		SELECT
			MAX(VIEW_ORDER) AS VO
		FROM
			".PRODUCT_LST."
		WHERE
			(CATEGORY_CODE = '$category_code')
		AND
			(DEL_FLG = '0')
	";

	$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	#-----------------------------------------------------------------
	#	VIEW_ORDER用の値を作成
	#		※現在登録されている記事データ中の最大VIEW_ORDER値を取得
	#		  それに1足したものを$view_orderに格納して使用
	#		※登録場所チェックが入っていたらVIEW_ORDER値を全て1繰上げ
	#		　$view_orderに1をセットし結果的に登録を一番上にする
	#-----------------------------------------------------------------
	if($_POST["copy_type"]=="new" or  $_POST["ins_chk"]=="1"){
			$vosql2 ="UPDATE ".PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (CATEGORY_CODE = '$category_code')";
			if(!empty($vosql2)){
				$db_result = dbOpe::regist($vosql2,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = 1;
	}else{
			$view_order = ($fetchVO[0]["VO"] + 1);
	}
*/

	#-----------------------------------------------------
	# 商品情報
	#-----------------------------------------------------
	$sql[] = "
	INSERT INTO
		".PRODUCT_LST."
	SET
		PRODUCT_ID = '$product_id',
		VIEW_ORDER = '$view_order',
		INS_DATE = NOW(),

		$sql_update_data
	";

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");
}

#=================================================================================
# 最後にカラーサイズの在庫数を設定
#=================================================================================
	//新規でも更新でも最初は商品に紐付く在庫数を削除処理

		$del_sql = "
			DELETE FROM
				".PRODUCT_PROPERTY_DATA."
			WHERE
				(PRODUCT_ID = '$product_id')
			";

			if(!empty($del_sql)):
				$del_rt = dbOpe::regist($del_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($del_rt)die("既存カラー＆サイズ情報の削除に失敗しました{$del_rt}");
			endif;

	//カラーサイズの在庫データがあれば処理を行う
	//不要な在庫データはLGC_inputChk.phpで削っている（不要なデータまで登録しているとカラーサイズの量が多くなればなるほど不要なデータが増える為、またデータベースの管理が難しくなる為）
		if(count($stock)){

			//基本的に登録するデータはSIZE_CODEと在庫数のみ（サイズ名を登録後に変更された場合は各商品ごとに直さないといけなくなるのでカラーサイズ名はここでは登録させない）
			//$kがSIZE_CODEで$vが在庫数になる
			foreach($stock as $k => $v){

				$sql_stock_add = "
					INSERT INTO
						".PRODUCT_PROPERTY_DATA."
					SET
						PRODUCT_ID	 = '".$product_id."',
						SIZE_CODE	 = '".$k."',
						STOCK_QUANTITY	 = '".$v."',
						INS_DATE	 = NOW(),
						UPD_DATE	 = NOW(),
						DEL_FLG		 = '0'
				";

				if(!empty($sql_stock_add)){
					$db_result_add = dbOpe::regist($sql_stock_add,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
					if($db_result_add)die("DB登録失敗しました<hr>{$db_result_add}");
				}

			}

		}

	//最後にカラーサイズ無しの在庫数をセットする（SIZE_CODEで存在しない0を入れたのをカラーサイズ無しにする　PRODUCT_PROPERTY_DATAに一緒に入れないと在庫管理が難しい為）

		$sql_stock_add = "
			INSERT INTO
				".PRODUCT_PROPERTY_DATA."
			SET
				PRODUCT_ID	 = '".$product_id."',
				SIZE_CODE	 = '0',
				STOCK_QUANTITY	 = '".$stock_quantity."',
				INS_DATE	 = NOW(),
				UPD_DATE	 = NOW(),
				DEL_FLG		 = '0'
		";

		if(!empty($sql_stock_add)){
			$db_result_add = dbOpe::regist($sql_stock_add,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result_add)die("DB登録失敗しました<hr>{$db_result_add}");
		}

?>
