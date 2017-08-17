<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
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
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	$sql = "
	SELECT
	    RES_ID,
		TITLE,
		CONTENT,
		DETAIL_CONTENT,
		PDF_FLG,
		TYPE,
		SIZE,
		EXTENTION,
		DISPLAY_FLG
	FROM
		".S6_1PRODUCT_LST."
	WHERE
		(RES_ID = '$res_id')
	";

	break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// GETデータの受け取りと共通な文字列処理
	if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4)));

		// 送信される可能性のあるパラメーターはデコードする
	$p  = urldecode($p);
	$ca = urldecode($ca);

	$res_id = urldecode($res_id);

	#------------------------------------------------------------------------
	# ページング用
	# ページ遷移時にむだなパラメーターを付けないため
	# (カテゴリーが送信してない場合に?ca=&p=)
	# に送信パラメーターをチェックしてリンクパラメーターを設定する
	#------------------------------------------------------------------------
	$param="";
	if(!empty($p) && !empty($ca)){
		$param="?p=".urlencode($p)."&ca=".urlencode($ca);
	}elseif(!empty($p) && empty($ca)){
		$param="?p=".urlencode($p);
	}elseif( empty($p) && !empty($ca) ){
		$param="?ca=".urlencode($ca);
	}

	// ページ番号の設定(GET受信データがなければ1をセット)
	if(empty($p) or !is_numeric($p))$p=1;

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）

	// 抽出開始位置の指定
	$st = ($p-1) * DISP_MAXROW_BACK;

	$sql = "
	SELECT
		RES_ID,
		TITLE,
		CONTENT,
		PDF_FLG,EXTENTION,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		VIEW_ORDER,DISPLAY_FLG
	FROM
		".S6_1PRODUCT_LST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sql .= "
	LIMIT
		".$st.",".DISP_MAXROW_BACK."
	";

endswitch;

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//データを受け渡す
	$old_type = $fetch[0]["TYPE"];
	$old_size = $fetch[0]["SIZE"];
	$old_extension = $fetch[0]['EXTENTION'];

?>
