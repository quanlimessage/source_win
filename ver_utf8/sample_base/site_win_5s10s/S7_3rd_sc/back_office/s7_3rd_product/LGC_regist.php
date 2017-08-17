<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

//カテゴリー情報の取得
	$sql = "
	SELECT
		CATEGORY_CODE,CATEGORY_NAME,VIEW_ORDER
	FROM
		".S7_3_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

switch($_POST["regist_type"]):
case "update":
	// 画像ファイル名の決定（POSTで渡された既存の記事ID（$res_id）を使用）
	$for_imgname = $res_id; // POSTで渡された既存記事IDを使用
break;
case "new":
	// 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
	$res_id = $makeID();
	$for_imgname = $res_id;
break;
endswitch;

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(IMG_PATH);

// 設定ファイルの画像最大登録枚数分ループ
for($i=1;$i<= IMG_CNT;$i++):

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['up_img']['tmp_name'][$i])){

	//登録されているファイルは拡張子をワイルドカードにして削除（拡張子が違っている場合古いファイルは上書きで消えない為）
	search_file_del(IMG_PATH,$for_imgname."_".$i.".*");

	// アップされてきた画像のサイズを計る
		$size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

	//画像サイズを調整
		$size_x = $ox[$i-1];//横の固定サイズ
		$size_y = $size[1]/($size[0]/$size_x);

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
		//$imgObj->setSize($ox[$i-1],$oy[$i-1]);
		//$imgObj->isFixed=true;
		$imgObj->setSize($size_x, $size_y);//横固定、縦可変型

	if(!$imgObj->up($_FILES['up_img']['tmp_name'][$i],$for_imgname."_".$i)){
		exit("画像のアップロード処理に失敗しました。");
	}

}
endfor;

// MySQLにおいて危険文字をエスケープしておく
	$title = utilLib::strRep($title,5);
	$title_tag = utilLib::strRep($title_tag,5);
	//$content = utilLib::strRep($content,5);
	//$detail_content = utilLib::strRep($detail_content,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
	$content = html_tag($_POST['content']);
	$detail_content = html_tag($_POST['detail_content']);
	$youtube = html_tag($_POST['youtube']);

	$ca_data = "";

	for($i=0;$i < count($target_ca);$i++){
		$ca_data .= $target_ca[$i];
		$ca_data .= (($i+1) < count($target_ca))?",":"";//区切り設定
	}

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
	$sql_update_data = "
		CATEGORY_CODE = '$ca_data',
		TITLE = '$title',
		CONTENT = '$content',
		DETAIL_CONTENT = '$detail_content',
		TITLE_TAG = '$title_tag',
		YOUTUBE = '$youtube',
		IMG_FLG = '$img_flg',
		DISP_DATE = NOW(),
		DISPLAY_FLG = '$display_flg',
		DEL_FLG = '0'
	";

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	// 画像ファイル名の決定（POSTで渡された既存の記事ID（$res_id）を使用）
	$for_imgname = $res_id; // POSTで渡された既存記事IDを使用

	// 削除指示がされていたら実行(複数)
	if($_POST["regist_type"]=="update" && $del_img){
		  foreach($del_img as $k => $v){
		  	search_file_del(IMG_PATH,$res_id."_".$v.".*");
		}
	}

	// DB格納用のSQL文
	$sql = "
	UPDATE
		".S7_3_PRODUCT_LST."
	SET
		$sql_update_data
	WHERE
		(RES_ID = '$res_id')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////
// 新規登録

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".S7_3_PRODUCT_LST." WHERE(".S7_3_PRODUCT_LST.".DEL_FLG = '0')";
	$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//最大登録件数に達していない、そして、カテゴリーが存在している場合登録をする
	if($fetchCNT[0]["CNT"] < DBMAX_CNT):

		$sql = "
		INSERT INTO ".S7_3_PRODUCT_LST."
			SET
			RES_ID = '$res_id',
			$sql_update_data
		";

	else:
		header("Location: ./");
	endif;

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

#==================================================================
# カテゴリーごとでの並び順を制御する
#==================================================================
	//登録する内容の格納場所を初期化
		$ca_list_reg	 = array();//既存のデータを一時保存用
		$fetchVList	 = array();//データベースから既存のデータを一時保存用
		$reg_vo_sql	 = array();//初期化 INSERT用
		$reg_vo_move_sql = array();//トップに登録する場合、属するカテゴリーの既存データの並び順を１つずらす用

	#=================================================================================
	# 更新とコピーの場合はデータベースから情報を取得する
	#=================================================================================
		if($_POST["regist_type"] == "update"){

			// 既存のカテゴリーごとの並び順データの取得（全表示の部分は除く）
				$sqlCL = "
				SELECT
					*
				FROM
					".S7_3_VIEW_ORDER_LIST."
				WHERE
					(RES_ID = '$res_id')
					AND
					(C_ID != '')
				";

				// データの取得
				$fetchVList = dbOpe::fetch($sqlCL,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//取得したデータを扱いやすいようにカテゴリー番号を連想配列にして入れなおす
				for($i=0;$i < count($fetchVList);$i++){//連想配列にC_IDを入れる

					$c_number = $fetchVList[$i]['C_ID'];//カテゴリーのIDデータを渡す

					$ca_list_reg[$c_number]['C_ID']		 = $fetchVList[$i]['C_ID'];//カテゴリー
					$ca_list_reg[$c_number]['RES_ID']	 = $fetchVList[$i]['RES_ID'];//データのユニークID
					$ca_list_reg[$c_number]['VIEW_ORDER']	 = $fetchVList[$i]['VIEW_ORDER'];//並び順
				}

		}

		//複製の場合は複製元の並び順を取得する
			if($_POST["copy_flg"] == "1"){

				// 既存のカテゴリーごとの並び順データの取得（全表示の部分は除く）
					$sqlCL = "
					SELECT
						*
					FROM
						".S7_3_VIEW_ORDER_LIST."
					WHERE
						(RES_ID = '$copy_res_id')
						AND
						(C_ID != '')
					";

					// データの取得
					$fetchVList = dbOpe::fetch($sqlCL,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

				//取得したデータを扱いやすいようにカテゴリー番号を連想配列にして入れなおす
					for($i=0;$i < count($fetchVList);$i++){//連想配列にC_IDを入れる

						$c_number = $fetchVList[$i]['C_ID'];//カテゴリーのIDデータを渡す

						$ca_list_reg[$c_number]['C_ID']		 = $fetchVList[$i]['C_ID'];//カテゴリー
						$ca_list_reg[$c_number]['RES_ID']	 = $res_id;//データのユニークID
						$ca_list_reg[$c_number]['VIEW_ORDER']	 = $fetchVList[$i]['VIEW_ORDER'];//並び順
					}

			}

	//並び順の設定をする
		for($i=0,$j=0;$i < count($target_ca);$i++){

			//カテゴリーの番号とＩＤが在った場合はそのデータを引き継がせる
				if($ca_list_reg[$target_ca[$i]]['C_ID'] && $ca_list_reg[$target_ca[$i]]['RES_ID']){

				///20120629 柴田修正 ここから
				//複製時に複製元より並びが後ろのデータが並びに+1されないため修正

					//複製の場合それぞれのカテゴリーの順番をずらす。
					if($_POST["copy_flg"] == "1"){
						$set_copy_view_value = $ca_list_reg[$target_ca[$i]]['VIEW_ORDER']+1;
						$reg_vo_sql[$i] = "
							INSERT INTO
								".S7_3_VIEW_ORDER_LIST."
							SET
								C_ID	= '".$target_ca[$i]."',
								RES_ID	= '".$res_id."',
								VIEW_ORDER	= '".$set_copy_view_value."'
						";

						array_push($reg_vo_move_sql,"
							UPDATE
								".S7_3_VIEW_ORDER_LIST."
							SET
								VIEW_ORDER = VIEW_ORDER + 1
							WHERE
								(C_ID = '".$ca_list_reg[$target_ca[$i]]['C_ID']."')
							AND
								(VIEW_ORDER >= '".$set_copy_view_value."')
						");
					}else{
						$reg_vo_sql[$i] = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST."
						SET
							C_ID	= '".$target_ca[$i]."',
							RES_ID	= '".$res_id."',
							VIEW_ORDER	= '".$ca_list_reg[$target_ca[$i]]['VIEW_ORDER']."'
					";
					}

				///ここまで

					/*
					$reg_vo_sql[$i] = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST."
						SET
							C_ID	= '".$target_ca[$i]."',
							RES_ID	= '".$res_id."',
							VIEW_ORDER	= '".$ca_list_reg[$target_ca[$i]]['VIEW_ORDER']."'
					";
					*/
				//無い場合は新規に登録
					}else{

						//新規登録分のデータは一番最初に登録させる場合
						if($ins_chk == 1){
							//属するカテゴリーの並び順を１つずらす
								$reg_vo_move_sql[$j] = "
									UPDATE
										".S7_3_VIEW_ORDER_LIST."
									SET
										VIEW_ORDER = VIEW_ORDER + 1
									WHERE
										(C_ID = '".$target_ca[$i]."')
								";

								$j++;//カテゴリーでのカウントを増やす

							//並び順は最初に登録させる
								$reg_vo_sql[$i] = "
									INSERT INTO
										".S7_3_VIEW_ORDER_LIST."
									SET
										C_ID	= '".$target_ca[$i]."',
										RES_ID	= '".$res_id."',
										VIEW_ORDER	= '1'
								";

						//新規登録分のデータは一番最後に登録させる場合
						}else{

							//入れ子で最後の数値に1足した番号で登録
							//mysqlのバージョンが4.0.14以降だと使用できる（自身にinsert select）
							/*
							$reg_vo_sql[$i] = "
								INSERT INTO
									".S7_3_VIEW_ORDER_LIST." (C_ID,RES_ID,VIEW_ORDER)
									SELECT
										'".$target_ca[$i]."' AS C_ID,
										'".$res_id."' AS RES_ID,
										CASE

											WHEN COUNT(C_ID = '".$target_ca[$i]."') != '' THEN MAX(CASE WHEN (C_ID = '".$target_ca[$i]."') THEN VIEW_ORDER + 1 ELSE 1 END)
											ELSE 1
										END AS VIEW_ORDER
									FROM
										".S7_3_VIEW_ORDER_LIST."

							";
							*/

							//mysqlのバージョンが4.0.14前の対応バージョン（補佐用のテーブルからinsert select）
							$reg_vo_sql[$i] = "
								INSERT INTO
									".S7_3_VIEW_ORDER_LIST." (C_ID,RES_ID,VIEW_ORDER)
									SELECT
										'".$target_ca[$i]."' AS C_ID,
										'".$res_id."' AS RES_ID,
										CASE

											WHEN COUNT(C_ID = '".$target_ca[$i]."') != '' THEN MAX(CASE WHEN (C_ID = '".$target_ca[$i]."') THEN VIEW_ORDER + 1 ELSE 1 END)
											ELSE 1
										END AS VIEW_ORDER
									FROM
										".S7_3_VIEW_ORDER_LIST2."

							";
						}

				}
		}

#==================================================================
# 全表示での並び順を制御する
#==================================================================

///20120629 柴田修正 ここから
//複製時に複製元より並びが後ろのデータが並びに+1されないため修正
	//複製の場合はデータベースから情報を取得する
	if($_POST["copy_flg"] == "1"){
			// 全件の並び順データの取得（全表示の部分は除く）
			$sqlCL_ALL = "
				SELECT
					*
				FROM
					".S7_3_VIEW_ORDER_LIST."
				WHERE
					(RES_ID = '".$copy_res_id."')
					AND
					(C_ID = '')
			";

			// データの取得
			$fetchVList_ALL = dbOpe::fetch($sqlCL_ALL,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			$set_copy_view_value = $fetchVList_ALL[0]['VIEW_ORDER']+1;

			$reg_vo_all = "
				INSERT INTO
					".S7_3_VIEW_ORDER_LIST."
				SET
					C_ID	= '',
					RES_ID	= '".$res_id."',
					VIEW_ORDER	= '".$set_copy_view_value."'
			";

			array_push($reg_vo_move_sql,"
				UPDATE
					".S7_3_VIEW_ORDER_LIST."
				SET
					VIEW_ORDER = VIEW_ORDER + 1
				WHERE
					(C_ID = '')
				AND
					(VIEW_ORDER >= '".$set_copy_view_value."')
			");

		//更新の場合はデータベースから情報を取得する

	}else

///ここまで

	if($_POST["regist_type"] == "update"){

			// 既存のカテゴリーごとの並び順データの取得（全表示の部分は除く）
				$sqlCL = "
				SELECT
					*
				FROM
					".S7_3_VIEW_ORDER_LIST."
				WHERE
					(RES_ID = '$res_id')
					AND
					(C_ID = '')
				";

				// データの取得
				$fetchVList = array();//初期化
				$fetchVList = dbOpe::fetch($sqlCL,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			$reg_vo_all = "
				INSERT INTO
					".S7_3_VIEW_ORDER_LIST."
				SET
					C_ID	= '".$fetchVList[0][C_ID]."',
					RES_ID	= '".$res_id."',
					VIEW_ORDER = '".$fetchVList[0][VIEW_ORDER]."'
			";

	//新規の場合
		}else{

			//新規登録分のデータは一番最初に登録させる場合
			if($ins_chk == 1){
				//全表示の並び順を１つずらす
					array_push($reg_vo_move_sql,"
						UPDATE
							".S7_3_VIEW_ORDER_LIST."
						SET
							VIEW_ORDER = VIEW_ORDER + 1
						WHERE
							(C_ID = '')
					");

				//並び順は最初に登録させる
					$reg_vo_all = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST."
						SET
							C_ID	= '',
							RES_ID	= '".$res_id."',
							VIEW_ORDER	= '1'
					";

			//新規登録分のデータは一番最後に登録させる場合
			}else{

				if($_POST["copy_flg"] == "1"){//複製の場合は複製元の並び順から１段下に登録

					// 既存のカテゴリーごとの並び順データの取得（全表示の部分は除く）
						$sqlCL = "
						SELECT
							*
						FROM
							".S7_3_VIEW_ORDER_LIST."
						WHERE
							(RES_ID = '$copy_res_id')
							AND
							(C_ID = '')
						";

						// データの取得
						$fetchVList = array();//初期化
						$fetchVList = dbOpe::fetch($sqlCL,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

					$reg_vo_all = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST."
						SET
							C_ID	= '',
							RES_ID	= '".$res_id."',
							VIEW_ORDER = '".($fetchVList[0][VIEW_ORDER] + 1)."'
					";

				}else{
					//入れ子で最後の数値に1足した番号で登録
					//mysqlのバージョンが4.0.14以降だと使用できる（自身にinsert select）
					/*
					$reg_vo_all = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST." (C_ID,RES_ID,VIEW_ORDER)
							SELECT
								'' AS C_ID,
								'".$res_id."' AS RES_ID,
								CASE

									WHEN COUNT(C_ID = '') != '' THEN MAX(CASE WHEN (C_ID = '') THEN VIEW_ORDER + 1 ELSE 1 END)
									ELSE 1
								END AS VIEW_ORDER
							FROM
								".S7_3_VIEW_ORDER_LIST."
					";
					*/

					//mysqlのバージョンが4.0.14前の対応バージョン（補佐用のテーブルからinsert select）
					$reg_vo_all = "
						INSERT INTO
							".S7_3_VIEW_ORDER_LIST." (C_ID,RES_ID,VIEW_ORDER)
							SELECT
								'' AS C_ID,
								'".$res_id."' AS RES_ID,
								CASE

									WHEN COUNT(C_ID = '') != '' THEN MAX(CASE WHEN (C_ID = '') THEN VIEW_ORDER + 1 ELSE 1 END)
									ELSE 1
								END AS VIEW_ORDER
							FROM
								".S7_3_VIEW_ORDER_LIST2."
					";

				}
			}

		}

			//全表示の並び順を最後に追加する
			array_push($reg_vo_sql,$reg_vo_all);

#==================================================================
# 並び順のSQLを実行させる
#==================================================================

	//データを取り出したので、一旦RES_IDが同じデータは削除して新たに入れる。（SQLは最後に実行）
		$sqlVODel ="DELETE FROM ".S7_3_VIEW_ORDER_LIST." WHERE (RES_ID = '$res_id')";

	//最後にSQL文を実行させる

		// 一旦既に入っている同じRES_IDデータを削除
			if(!empty($sqlVODel)){
				$db_result = dbOpe::regist($sqlVODel,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

			}

		//並び順をずらすSQL文があれば実行
			if(!empty($reg_vo_move_sql)){
				$db_result = dbOpe::regist($reg_vo_move_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

			}

		//最後に登録するSQL文を実行
			if(!empty($reg_vo_sql)){

				$db_result3 = dbOpe::regist($reg_vo_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result3)die("DB登録失敗しました<hr>{$db_result3}");

			}

	//mysqlのバージョンが4.0.14前の対応バージョン　S7_3_VIEW_ORDER_LISTとS7_3_VIEW_ORDER_LIST2の差分を無くす為
	//S7_3_VIEW_ORDER_LIST2を全削除してS7_3_VIEW_ORDER_LISTを入れて差分を無くす
		//補佐テーブルのデータ削除
			$db_result3 = dbOpe::regist("DELETE FROM ".S7_3_VIEW_ORDER_LIST2,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//補佐テーブルにS7_3_VIEW_ORDER_LISTのデータを入れる
			$db_result3 = dbOpe::regist("INSERT INTO ".S7_3_VIEW_ORDER_LIST2." SELECT * FROM ".S7_3_VIEW_ORDER_LIST,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
