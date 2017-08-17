<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["action"]):
case "update":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

	// POSTデータの受け取りと共通な文字列処理
	extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// 対象記事IDデータのチェック
	if(!is_numeric($cate)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$cate}");
	}

	$sql = "
	SELECT
	    	CATEGORY_CODE,
		CATEGORY_NAME,
		CATEGORY_DETAILS,
		DISPLAY_FLG
	FROM
		".S7_CATEGORY_MST."
	WHERE
		(CATEGORY_CODE = '$cate')
	";

	break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）
	$sql = "
	SELECT
		CATEGORY_CODE,
		CATEGORY_NAME,
		VIEW_ORDER,
		DISPLAY_FLG
	FROM
		".S7_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

endswitch;

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

?>
