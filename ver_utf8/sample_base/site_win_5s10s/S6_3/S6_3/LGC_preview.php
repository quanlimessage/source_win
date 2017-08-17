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
	/*
	$fetch[0]['RES_ID'] = $res_id;

	//タイトル
	$fetch[0]['TITLE'] = $title;

	//コメント
	$fetch[0]['CONTENT'] = $content;

	//詳細コメント
	$fetch[0]['DETAIL_CONTENT'] = $detail_content;

	//youtube
	$fetch[0]['YOUTUBE'] = $youtube;
	*/
	//小文字の変数名を大文字に変換して入れる、データベースに入れるカラム名が変数名と違う場合は個々対応させる
		foreach($_POST as $key => $val){
			if(!is_array($val)){//配列以外は入れる。（データベースに入れる性質上　文字列の為、配列はありえない）
				$fetch[0][strtoupper($key)] = utilLib::strRep($val,4);//データベースに入れないのでエスケープ処理をはずす
			}
		}

	#==================================================================
	# プレビュー画像に関する処理
	#==================================================================

	//画像枚数分ループ
	for($i=1;$i<=S6_3IMG_CNT;$i++):

		// アクセスされる毎にプレビュー画像を削除する(拡張子はワイルドカードで検索)
		search_file_del("./up_img/","prev_".$i.".*");

		//更新画面からのプレビューで、該当画像の新規アップがない場合
		if($_POST['regist_type']=="update" && !is_uploaded_file($_FILES['up_img']['tmp_name'][$i])){

			///////////////////////////////////////////////////
			// プレビュー画像は現在登録されている画像とする

			//該当の画像に削除指示が出ていないかを確認
			$del_img_flg = false; // 初期化

			for($j=0;$j<=count($_POST['del_img']);$j++){
				if($_POST['del_img'][$j] == $i)$del_img_flg = true;
			}

			if(!$del_img_flg):

				//プレビュー画像
				if(search_file_flg("./up_img/",$res_id."_".$i.".*")){
					$prev_img[$i] = search_file_disp("./up_img/",$res_id."_".$i.".*","",2);
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
			$imgObj = new imgOpe("./up_img/");

			// アップロードされた画像ファイルがあればアップロード処理
			if(is_uploaded_file($_FILES['up_img']['tmp_name'][$i])):

				// ファイル名を取得
				$pathinfo = pathinfo($_FILES['up_img']['name'][$i]);

				// 拡張子を取得
				$extension = strtolower($pathinfo['extension']);
				if($extension=="jpeg")$extension = "jpg";

				// アップされてきた画像のサイズを計る
				$size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

				//画像サイズを調整
				$size_x = $ox[$i-1];//横の固定サイズ
				$size_y = $size[1]/($size[0]/$size_x);

				// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
				$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

				if(!$imgObj->up($_FILES['up_img']['tmp_name'][$i],$for_imgname."_".$i)){
					exit("画像のアップロード処理に失敗しました。");
				}

			endif;

			//プレビュー画像
			$prev_img[$i] = "./up_img/prev_".$i.".".$extension;

		}

	endfor;

else:
///////////////////////////////
//一覧画面からのプレビュー

	// 表示データは送信されたIDをもとに取得する
	$sql = "
		SELECT
			*
		FROM
			".S6_3PRODUCT_LST."
		WHERE
			(RES_ID = '".$res_id."')
	";

	// ＳＱＬを実行
	$fetch = $PDO -> fetch($sql);

	//画像
	for($i=1;$i<=S6_3IMG_CNT;$i++):

		if(search_file_flg("./up_img/",$res_id."_".$i.".*")){
			$prev_img[$i] = search_file_disp("./up_img/",$res_id."_".$i.".*","",2);
		}else{
			$prev_img[$i] = "";
		}

	endfor;

endif;
?>
