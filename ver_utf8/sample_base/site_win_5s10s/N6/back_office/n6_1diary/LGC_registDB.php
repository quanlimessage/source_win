<?php
/*******************************************************************************
更新プログラム

	Logic:DB登録・更新処理

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=================================================================================
# 表示日時の設定
#=================================================================================
// 表示日時のタイムスタンプ作成
if(!empty($y) && !empty($m) && !empty($d)){
	// 指定があれば指定日時
	$reg_time = "{$y}-{$m}-{$d}"." ".date("H:i:s");
}else{
	// 無ければ現在日時用文字列
	$reg_time = date("Y")."-".date("n")."-".date("j")." ".date("H:i:s");
}

#=========================================================================
# 画像ファイル名の決定（IDをファイル名とする）
#=========================================================================
if($regist_type == "update"){
	$for_imgname = $diary_id;

	// 編集の時の削除フラグ指示がされていたら実行
	if($del_img == 1){

		if(file_exists(N6_1IMG_PATH.$diary_id.".jpg")){
			unlink(N6_1IMG_PATH.$diary_id.".jpg") or die("画像の削除に失敗しました。");
		}
	}
}
elseif($regist_type == "new"){
	$diary_id = $makeID();

	$for_imgname = $diary_id;
}

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$comment = html_tag($_POST['comment']);

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(N6_1IMG_PATH);

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['img_file']['tmp_name'])){

	// アップされてきた画像のサイズを計る
		$size = getimagesize($_FILES['img_file']['tmp_name']);

	//画像サイズを調整
		$size_x = N6_1IMGSIZE_MX;//横の固定サイズ
		$size_y = $size[1]/($size[0]/$size_x);

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
		$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
		//$imgObj->setSize(N6_1IMGSIZE_MX,N6_1IMGSIZE_MY);

	//$imgObj->isFixed=true;//指定範囲内に収まるように縮小

	if(!$imgObj->up($_FILES['img_file']['tmp_name'],$for_imgname)){
		exit("画像のアップロード処理に失敗しました。");
	}

}

#=================================================================================
# 新規か更新かによってＳＱＬを分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "page_flg":
	// DB格納用のSQL文
	$sql = "
	UPDATE
		".N6_1DIARY_PAGE."
	SET
		PAGE_FLG = '$page_flg'
	WHERE
		(RES_ID = '1')
	";

	break;
case "update":
/////////////////////////////////////////////////////////////////////////////////
// 更新

	#-----------------------------------------------------
	# 既存更新用SQLの組立
	#-----------------------------------------------------
	$sql = "
	UPDATE
		N6_1DIARY
	SET
		EMAIL = '$email',
		SUBJECT = '$subject',
		COMMENT = '$comment',
		DISPLAY_FLG = '$display_flg',
		REG_DATE = '$reg_time',
		LINK = '$link',
		LINK_FLG = '$link_flg',
		IMG_FLG = '$img_flg'
	WHERE
		(DIARY_ID = '$diary_id')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////////////////////
// 新規

	#-----------------------------------------------------
	# 新規登録用SQLの組立
	#-----------------------------------------------------
	$sql = "
	INSERT INTO N6_1DIARY(
		DIARY_ID,
		EMAIL,
		SUBJECT,
		COMMENT,
		REG_DATE
	)
	VALUES(
		'$diary_id',
		'$email',
		'$subject',
		'$comment',
		'$reg_time'
	)";

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

?>