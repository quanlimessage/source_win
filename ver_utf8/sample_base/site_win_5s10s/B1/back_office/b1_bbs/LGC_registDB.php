<?php
/*******************************************************************************
更新プログラム

	BBS REGIST DATA

2005/05/06 Author K.C
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
# 新規か更新かによってＳＱＬを分岐	※判断は$_POST["type"]
#=================================================================================
if($_POST["regist_type"] == "res_new"){

	// MAKE SUB ID
	$sub_id = $makeID();

	#=========================================================================
	# 画像削除処理
	#=========================================================================
	if(BBS_IMAGE == 1){

		// 画像処理クラスimgOpeのインスタンス生成
		$imgObj = new imgOpe("../../bbs/bbs_img/");

		#=========================================================================
		# アップロードした画像をアップした件数分固定サイズに変更する処理
		#=========================================================================

		// アップロードされた画像ファイルがあればアップロード処理
		if(is_uploaded_file($_FILES['img_file']['tmp_name'])){

			// GET IMAGE SIZE
			$size = getimagesize($_FILES['img_file']['tmp_name']);

			// SET IMAGE SIZE
			if($size[0]>$size[1]){

				$ox = BBS_IMG;
				$oy = ($size[1] * BBS_IMG) / $size[0];

			}elseif($size[0]<$size[1]){

				$ox = ($size[0] * BBS_IMG) / $size[1];
				$oy = BBS_IMG;

			}elseif($size[0]=$size[1]){

				$ox = BBS_IMG;
				$oy = BBS_IMG;

			}

			// UPLOAD BIG IMAGE
			$imgObj->setSize($ox,$oy);
			if( !$imgObj->up($_FILES['img_file']['tmp_name'], $sub_id) )
			exit("画像のアップロード処理に失敗しました。");

		}

	}

	// レス投稿
	$sql = "
	INSERT INTO ".BBS_LOG_SUB_DATA."(
		SUB_ID,
		MASTER_ID,
		NAME,
		IP,
		COMMENT,
		REG_DATE
	)
	VALUES(
		'$sub_id',
		'$master_id',
		'$name',
		'".$_SERVER['REMOTE_ADDR']."',
		'$comment',
		NOW()
	)";

	// ＳＱＬを実行
	if(!empty($sql)){
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	}

}elseif($_POST["regist_type"] == "new"){

	// MAKE MASTER ID
	$master_id = $makeID();

	#=========================================================================
	# 画像削除処理
	#=========================================================================
	if(BBS_IMAGE == 1){

		// 画像処理クラスimgOpeのインスタンス生成
		$imgObj = new imgOpe("../../bbs_img/");

		#=========================================================================
		# アップロードした画像をアップした件数分固定サイズに変更する処理
		#=========================================================================

		// アップロードされた画像ファイルがあればアップロード処理
		if(is_uploaded_file($_FILES['img_file']['tmp_name'])){

			// GET IMAGE SIZE
			$size = getimagesize($_FILES['img_file']['tmp_name']);

			// SET IMAGE SIZE
			if($size[0]>$size[1]){

				$ox = BBS_IMG;
				$oy = ($size[1] * BBS_IMG) / $size[0];

			}elseif($size[0]<$size[1]){

				$ox = ($size[0] * BBS_IMG) / $size[1];
				$oy = BBS_IMG;

			}elseif($size[0]=$size[1]){

				$ox = BBS_IMG;
				$oy = BBS_IMG;

			}

			//echo "W = ".$ox."<br>H = ".$oy;

			// UPLOAD BIG IMAGE
			$imgObj->setSize($ox,$oy);
			if( !$imgObj->up($_FILES['img_file']['tmp_name'], $master_id) )
			exit("画像のアップロード処理に失敗しました。");

		}

	}

	// 新規投稿
	$sql = "
	INSERT INTO ".BBS_LOG_MST_DATA."(
		MASTER_ID,
		NAME,
		IP,
		TITLE,
		COMMENT,
		REG_DATE
	)
	VALUES(
		'$master_id',
		'$name',
		'".$_SERVER['REMOTE_ADDR']."',
		'$title',
		'$comment',
		NOW()
	)";

	// ＳＱＬを実行
	if(!empty($sql)){
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	}

}else{

	die("致命的エラー：登録フラグ（regist_type）が設定されていません");

}

?>