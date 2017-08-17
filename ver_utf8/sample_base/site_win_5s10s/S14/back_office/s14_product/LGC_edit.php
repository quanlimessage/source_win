<?php
/*******************************************************************************
PICKUP更新プログラム

	Logic：DB登録・更新処理

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# POST受信データの処理
#--------------------------------------------------------------------------------
//画像ファイルがあった場合
	//$imgの存在確認
if($_FILES['thumbnail_file']['tmp_name']){

	//現在ファイルの存在の確認
	$rcd = file_exists($img);
	if($rcd){unlink($img);}//存在すれば削除をする

		$imgObj = new imgOpe(S14_IMG_PATH);

		// アップされてきた画像のサイズを計る
			$size = getimagesize($_FILES['thumbnail_file']['tmp_name']);

		//画像サイズを調整
			$size_x = S14_IMGSIZE_MX;//横の固定サイズ
			$size_y = $size[1]/($size[0]/$size_x);

		// 画像リサイズのサイズセット（横、縦）
		$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
		//$imgObj->setSize(S14_IMGSIZE_MX,S14_IMGSIZE_MY);

		// アップロード画像のコピー → リサイズ処理
		if(!$imgObj->up($_FILES['thumbnail_file']['tmp_name'],"top")){
			exit("画像のアップロード処理に失敗しました。");
		}

}

// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

//データを比較用に取得させる
	$sql = "SELECT * FROM ".S14_PICKUP;
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
	$sql_update_data = "
		TITLE= '".$title."',
		PICKUP_COMMENT = '".$comment."',
		UPD_DATE = NOW()
	";

if($comment && $fetch){//データベースのデータと本文がある場合（更新登録）

	$sql = "
	UPDATE
		".S14_PICKUP."
	SET
		$sql_update_data
	";

	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}
elseif($comment && !$fetch){//データベースのデータが無く本文がある場合（新規登録）

	$sql = "
	INSERT INTO
		S14_PICKUP
	SET
		$sql_update_data
	";

	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

	//処理が完了したら入力画面に処理が完了したのを表示させる
	$message="更新が完了いたしました。";

include("DISP_input.php");
exit();
?>