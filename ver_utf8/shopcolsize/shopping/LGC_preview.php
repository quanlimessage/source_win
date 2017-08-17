<?php
/*******************************************************************************
	プレビュー表示
*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}
#=============================================================================
# 共通処理：POSTデータの受け取りと文字列処理
#=============================================================================
// 入力内容のタグ等を正しく表示するため、stripslashesのみを行う
extract(utilLib::getRequestParams("post",array(4)));

// アクセスされる毎にプレビュー画像を削除する(拡張子はワイルドカードで検索)
search_file_del("./product_img/","prev_*");

#-------------------------------------------------------------------------
# カテゴリー情報の取得
#-------------------------------------------------------------------------

	$sql = "
	SELECT
		CATEGORY_CODE,
		CATEGORY_NAME,
		CATEGORY_DETAILS,
		VIEW_ORDER
	FROM
		".CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

if($_POST['regist_type']=="new" || $_POST['regist_type']=="update"):
//////////////////////////////////////////////
// 新規登録・更新画面からのプレビュー

	#==================================================================
	# stripslashes処理した値を$fetch[0]に格納
	#（管理画面での入力項目を反映してください）
	#==================================================================
	$fetch[0]["CATEGORY_CODE"] = $category_code;
	$fetch[0]["PRODUCT_ID"] = $product_id;
	$fetch[0]["PRODUCT_NAME"] = $product_name;
	$fetch[0]["PART_NO"] = $part_no;
	$fetch[0]["CAPACITY"] = $capacity;
	$fetch[0]["SELLING_PRICE"] = $selling_price;
	$fetch[0]["ITEM_DETAILS"] = $item_details;
	$fetch[0]["TITLE_TAG"] = $title_tag;
	$fetch[0]["STOCK_QUANTITY"] = $stock_quantity;
	$fetch[0]["CART_CLOSE_FLG"] = $cart_close_flg;
	$fetch[0]["NEW_ITEM_FLG"] = $new_item_flg;

	// カラー情報とサイズ情報を格納
	$cnt = 0; //初期化
	for($i=0;$i<count($stock);$i++){
		if($stock[$i]>0){
			$fetchCS[$cnt]['SIZE_NAME']  = $size[$i];
			$fetchCS[$cnt]['COLOR_NAME'] = $color[$i];
			$cnt ++;
		}
	}

	#==================================================================
	# プレビュー画像に関する処理
	#==================================================================

	if($_POST['status']=="prev_d"):
	#-------------------------------------------------------------------------
	# 詳細用の処理
	#-------------------------------------------------------------------------

		//画像枚数分ループ
		for($i=1;$i<=PRODUCT_IMG_NUM;$i++):

			//更新画面からのプレビューで、該当画像の新規アップがない場合
			if($_POST['regist_type']=="update" && !is_uploaded_file($_FILES['product_img_file']['tmp_name'][$i])){

				///////////////////////////////////////////////////
				// プレビュー画像は現在登録されている画像とする

				//該当の画像に削除指示が出ていないかを確認
				$del_img_flg = false; // 初期化

				for($j=0;$j<=count($_POST['del_img']);$j++){
					if($_POST['del_img'][$j] == $i)$del_img_flg = true;
				}

				if(!$del_img_flg):

					//プレビュー画像
					if(search_file_flg("./product_img/",$_POST['product_id']."_".$i.".*")){
						$prev_img[$i] = search_file_disp("./product_img/",$_POST['product_id']."_".$i.".*","",2);
					}else{
						$prev_img[$i] = "";
					}

				else:
					$prev_img[$i] = ""; //削除指示に合わせ、プレビュー画像を非表示に
				endif;

			// 新規入力画面からのプレビューの場合 or
			// 更新画面から該当画像の新規アップがある場合
			}else{

				/** プレビュー画像は新規アップされる画像とする **/
				#-------------------------------------------------------------------------
				# プレビュー画像アップロード処理
				#-------------------------------------------------------------------------
				// 画像名
				$for_imgname = "prev";

				// 画像処理クラスimgOpeのインスタンス生成
				$imgObj = new imgOpe("./product_img/");

				// アップロードされた画像ファイルがあればアップロード処理
				if(is_uploaded_file($_FILES['product_img_file']['tmp_name'][$i])):

					// ファイル名を取得
					$pathinfo = pathinfo($_FILES['product_img_file']['name'][$i]);

					// 拡張子を取得
					$extension = strtolower($pathinfo['extension']);
					if($extension=="jpeg")$extension = "jpg";

					// アップされてきた画像のサイズを計る
					$size = getimagesize($_FILES['product_img_file']['tmp_name'][$i]);

					//画像サイズを調整
					$size_x = IMG_SIZE_LX;//横の固定サイズ
					$size_y = $size[1]/($size[0]/$size_x);

					// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
					$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

					if(!$imgObj->up($_FILES['product_img_file']['tmp_name'][$i],$for_imgname."_".$i)){
						exit("画像のアップロード処理に失敗しました。");
					}

				endif;

				//プレビュー画像
				$prev_img[$i] = "./product_img/prev_".$i.".".$extension;

			}

		endfor;

	else:
	#-------------------------------------------------------------------------
	# 一覧用の処理（サムネイル画像）
	#-------------------------------------------------------------------------

	//更新画面からのプレビューで、該当画像の新規アップがない場合
	if($_POST['regist_type']=="update" && !is_uploaded_file($_FILES['thumbnail_file']['tmp_name'])){

		///////////////////////////////////////////////////
		// プレビュー画像は現在登録されている画像とする

		//プレビュー画像
		if(search_file_flg("./product_img/",$_POST['product_id']."_s.*")){
			$prev_img_s = search_file_disp("./product_img/",$_POST['product_id']."_s.*","",2);
		}else{
			$prev_img_s = "";
		}

		// 新規入力画面からのプレビューの場合 or
		// 更新画面から該当画像の新規アップがある場合
		}else{

			/** プレビュー画像は新規アップされる画像とする **/
			#-------------------------------------------------------------------------
			# プレビュー画像アップロード処理
			#-------------------------------------------------------------------------
			// 画像名
			$for_imgname = "prev";

			// 画像処理クラスimgOpeのインスタンス生成
			$imgObj = new imgOpe("./product_img/");

			// アップロードされた画像ファイルがあればアップロード処理
			if(is_uploaded_file($_FILES['thumbnail_file']['tmp_name'])):

				// ファイル名を取得
				$pathinfo = pathinfo($_FILES['thumbnail_file']['name']);

				// 拡張子を取得
				$extension = strtolower($pathinfo['extension']);

				// アップされてきた画像のサイズを計る
				$size = getimagesize($_FILES['thumbnail_file']['tmp_name']);

				//画像サイズを調整
				$size_x = IMG_SIZE_SX;//横の固定サイズ
				$size_y = $size[1]/($size[0]/$size_x);

				// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
				$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

				if(!$imgObj->up($_FILES['thumbnail_file']['tmp_name'],$for_imgname."_s")){
					exit("画像のアップロード処理に失敗しました。");
				}

			endif;

			//プレビュー画像
			$prev_img_s = "./product_img/prev_s.".$extension;

		}

	endif;

else:
///////////////////////////////
//一覧画面からのプレビュー

	// 表示データは送信されたIDをもとに取得する
	$sql = "
		SELECT
			*
		FROM
			".PRODUCT_LST."
		WHERE
			(PRODUCT_ID = '".$_POST['product_id']."')
	";

	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//////////////////////////////////
	// 該当商品サイズ/カラー情報
	$sql_cs="
	SELECT
		DSC_ID,
		SIZE_NAME,
		COLOR_NAME
	FROM
		".PRODUCT_PROPERTY_DATA."
	WHERE
		STOCK_QUANTITY > '0'
	AND
		PRODUCT_ID = '".$fetch[0]["PRODUCT_ID"]."'
	AND
		( DEL_FLG = '0' )
	ORDER BY
		DSC_ID ASC
	";

	$fetchCS = dbOpe::fetch($sql_cs, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

	//画像
	if($_POST['status']=="prev_d"){

		for($i=1;$i<=PRODUCT_IMG_NUM;$i++):

			if(search_file_flg("./product_img/",$_POST['product_id']."_".$i.".*")){
				$prev_img[$i] = search_file_disp("./product_img/",$_POST['product_id']."_".$i.".*","",2);
			}else{
				$prev_img[$i] = "";
			}

		endfor;

	}else{

		$prev_img_s = search_file_disp("./product_img/",$_POST['product_id']."_s.*","",2);

	}
endif;
?>
