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

if($_POST['regist_type']=="new" || $_POST['regist_type']=="update"):
//////////////////////////////////////////////
// 新規登録・更新画面からのプレビュー

	#==================================================================
	# stripslashes処理した値を$fetch[0]に格納
	#（管理画面での入力項目を反映してください）
	#==================================================================
	//ID
	$fetch[0]['DIARY_ID'] = $diary_id;

	//タイトル
	$fetch[0]['SUBJECT'] = $subject;

	//コメント
	$fetch[0]['COMMENT'] = $comment;

	//日付
	$fetch[0]['Y'] = $y;
	$fetch[0]['M'] = $m;
	$fetch[0]['D'] = $d;

	#==================================================================
	# プレビュー画像に関する処理
	#==================================================================

	//画像枚数分ループ
	//for($i=1;$i<=IMG_CNT;$i++):

		// アクセスされる毎にプレビュー画像を削除する(拡張子はワイルドカードで検索)
		search_file_del("./up_img/","prev.jpg");

		//更新画面からのプレビューで、該当画像の新規アップがない場合
		if($_POST['regist_type']=="update" && !is_uploaded_file($_FILES['img_file']['tmp_name'])){

			///////////////////////////////////////////////////
			// プレビュー画像は現在登録されている画像とする

			//該当の画像に削除指示が出ていないかを確認
			$del_img_flg = false; // 初期化

			for($j=0;$j<=count($_POST['del_img']);$j++){
				if($_POST['del_img'][$j] == 1)$del_img_flg = true;
			}

			if(!$del_img_flg):

				//プレビュー画像
				if(search_file_flg("./up_img/",$diary_id.".jpg")){
					$prev_img[1] = search_file_disp("./up_img/",$diary_id.".jpg","",2);
				}else{
					$prev_img[1] = "";
				}

			else:
				$prev_img[1] = ""; //削除指示に合わせ、プレビュー画像を非表示に
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
			$imgObj = new imgOpe("./up_img/");

			// アップロードされた画像ファイルがあればアップロード処理
			if(is_uploaded_file($_FILES['img_file']['tmp_name'])):

				// ファイル名を取得
				$pathinfo = pathinfo($_FILES['img_file']['name']);

				// 拡張子を取得
				$extension = strtolower($pathinfo['extension']);
				if($extension=="jpeg")$extension = "jpg";

				// アップされてきた画像のサイズを計る
				$size = getimagesize($_FILES['img_file']['tmp_name']);

				//画像サイズを調整
				$size_x = N6_1IMGSIZE_MX;//横の固定サイズ
				$size_y = $size[1]/($size[0]/$size_x);

				// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
				$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

				if(!$imgObj->up($_FILES['img_file']['tmp_name'],$for_imgname)){
					exit("画像のアップロード処理に失敗しました。");
				}

			endif;

			//プレビュー画像
			if($extension == "jpg"){
				$prev_img[1] = "./up_img/prev.".$extension;
			}
		}

//	endfor;

else:
///////////////////////////////
//一覧画面からのプレビュー

	// 表示データは送信されたIDをもとに取得する
	$sql = "
		SELECT
			DIARY_ID,
			EMAIL,
			SUBJECT,
			COMMENT,
			YEAR(REG_DATE) AS Y,
			MONTH(REG_DATE) AS M,
			DAYOFMONTH(REG_DATE) AS D,
			LINK,
			LINK_FLG,
			IMG_FLG
		FROM
			".N6_1DIARY."
		WHERE
			(DIARY_ID = '".$diary_id."')
	";

	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//画像
	//for($i=1;$i<=IMG_CNT;$i++):

		if(search_file_flg("./up_img/",$diary_id.".jpg")){
			$prev_img[1] = search_file_disp("./up_img/",$diary_id.".jpg","",2);
		}else{
			$prev_img[1] = "";
		}

	//endfor;

endif;
?>
