<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic:以下の処理を行う
	・表示/非表示の切替(DISPLAY_FLGの切替)
	・削除処理	※完全にデータを削除します。(DELETE文)

※$_POST["action"]の内容で分岐

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../index.php");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4)));

// 対象記事IDデータのチェック
if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
	die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
}

#---------------------------------------------------------------
# $_POST["action"]の内容で処理を分岐
#---------------------------------------------------------------
switch($_POST["act"]):
case "del_data":
////////////////////////////////////////////////////////////////
// 該当データの完全削除

	// SQL実行
	$PDO -> regist("DELETE FROM ".S7_PRODUCT_LST." WHERE(RES_ID = '$res_id')");

	//並び順の削除
	$PDO -> regist("DELETE FROM ".S7_3_VIEW_ORDER_LIST." WHERE(RES_ID = '$res_id')");

//並び順補佐の削除
	$PDO -> regist("DELETE FROM ".S7_3_VIEW_ORDER_LIST2." WHERE(RES_ID = '$res_id')");

	// 記事画像の削除(対象はRES_IDが一致するファイル)
	search_file_del(IMG_PATH,$res_id."*");

	/*
	for($i=1;$i<=IMG_CNT;$i++){
	// 記事画像の削除
		if(file_exists(IMG_PATH.$res_id."_".$i.".jpg")){
			unlink(IMG_PATH.$res_id."_".$i.".jpg") or die("画像の削除に失敗しました。");
		}
		if(file_exists(IMG_PATH.$res_id."_".$i.".gif")){
			unlink(IMG_PATH.$res_id."_".$i."gif") or die("画像の削除に失敗しました。");
		}
		if(file_exists(IMG_PATH.$res_id."_".$i.".png")){
			unlink(IMG_PATH.$res_id."_".$i."png") or die("画像の削除に失敗しました。");
		}
	}*/

	break;
case "display_change":
////////////////////////////////////////////////////////////////
// 表示/非表示の切替（フラグを更新）

	// 表示／非表示のデータ調整
	$up_display = ($display_change == "t")?1:0;

	// SQLを実行
	$PDO -> regist("UPDATE ".S7_PRODUCT_LST." SET DISPLAY_FLG = '$up_display' WHERE(RES_ID = '$res_id')");

endswitch;

?>
