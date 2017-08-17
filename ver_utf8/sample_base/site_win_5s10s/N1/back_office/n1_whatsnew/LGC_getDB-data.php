<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["act"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["act"]):
case "update":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

	// POSTデータの受け取りと共通な文字列処理
	extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	$sql = "
	SELECT
		RES_ID,TITLE,CONTENT,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		DISPLAY_FLG,
		LINK,
		LINK_FLG,
		IMG_FLG
	FROM
		".N1_WHATSNEW."
	WHERE
		(RES_ID = '$res_id')
	AND
		(DEL_FLG = '0')
	";

	break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と一覧表示用データの取得

	$sql = "
	SELECT
		RES_ID,TITLE,CONTENT,
		DISP_DATE,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		DISPLAY_FLG
	FROM
		".N1_WHATSNEW."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		DISP_DATE DESC
	";

endswitch;

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

?>
