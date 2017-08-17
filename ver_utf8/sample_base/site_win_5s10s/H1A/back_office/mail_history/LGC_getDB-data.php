<?php
/*******************************************************************************
会員メール配信 バックオフィス（MySQL対応版）
メインコントローラー

Logic：指定された検索条件を元にＤＢより情報を取得

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

// POSTデータの受け取りと共通な文字列処理
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["status"]):

case "history_detail":
#--------------------------------------------------------------------------------
# メールの履歴から詳細データを取得
#--------------------------------------------------------------------------------
	$sql = "
	SELECT
		RES_ID,
		TITLE,
		CONTENT,
		MEMBER_ID,
		SEND_NUMBER,
		YEAR(INS_DATE) AS Y,
		MONTH(INS_DATE) AS M,
		DAYOFMONTH(INS_DATE) AS D
	FROM
		MAIL_HISTORY
	WHERE
		(DEL_FLG = '0')
	AND
		(RES_ID = '".$res_id."')
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//「|」をデリミタとして、取得したIDを配列にする
	$id_list = explode('|',$fetch[0][MEMBER_ID]);

	//$fetchCustListを配列として初期化
	$fetchCustList = array();

	//配列の要素数だけループし、各IDに対応した顧客データを取得
	for($i=0;$i<count($id_list);$i++){
		$sql = "
		SELECT
			".MEMBER_LST.".MEMBER_ID,
			".MEMBER_LST.".NAME,
			".MEMBER_LST.".EMAIL
		FROM
			".MEMBER_LST."
		WHERE
			".MEMBER_LST.".MEMBER_ID = '".$id_list[$i]."'
		";

		$fetchLST = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//得られた値を$fetchCustListに格納
		$fetchCustList[] = $fetchLST[0];
	}

break;

default:
#--------------------------------------------------------------------------------
# メールの履歴を取得
#--------------------------------------------------------------------------------

	$sql = "
	SELECT
		RES_ID,
		TITLE,
		CONTENT,
		MEMBER_ID,
		SEND_NUMBER,
		YEAR(INS_DATE) AS Y,
		MONTH(INS_DATE) AS M,
		DAYOFMONTH(INS_DATE) AS D
	FROM
		MAIL_HISTORY
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		INS_DATE DESC
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	/*
	//「|」をデリミタとして、取得したIDを配列にする
	$id_list = explode('|',$fetch[0][MEMBER_ID]);

	//$fetchCustListを配列として初期化
	$fetchCustList = array();

	//配列の要素数だけループし、各IDに対応した顧客データを取得
	for($i=0;$i<count($id_list);$i++){
		$sql = "
		SELECT
			MEMBER_ID,
			LAST_NAME,
			FIRST_NAME
			EMAIL
		FROM
			" . MEMBER_LST . "
		WHERE
			(DEL_FLG = '0')
		AND
			MEMBER_ID = '".$id_list[$i]."'
		";

		$fetchLST = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//得られた値を$fetchCustListに格納
		$fetchCustList[] = $fetchLST[0];
	}

	*/

break;

endswitch;
?>
