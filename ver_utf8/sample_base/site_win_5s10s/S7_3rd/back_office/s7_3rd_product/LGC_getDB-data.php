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
}
if(!$accessChk){
	header("Location: ../");exit();
}

//カテゴリー情報の取得
	$sql = "
	SELECT
		CATEGORY_CODE,
		CATEGORY_NAME,
		VIEW_ORDER,RES_ID
	FROM
		".S7_3_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//カテゴリー名の横に登録件数を表示させる
	for($i=0;$i<count($fetchCA);$i++){
		${'ca_cnt'.$i} = $fetchCA[$i]['RES_ID'];

		${'sql_ca'.$i} = "
		SELECT
			RES_ID,
			CATEGORY_CODE
		FROM
			".S7_3_PRODUCT_LST."
		WHERE
			(CATEGORY_CODE LIKE '%".${'ca_cnt'.$i}."%')
		AND
			(DEL_FLG = '0')
		";

		// ＳＱＬを実行
		${'fetchCA_ca'.$i} = dbOpe::fetch(${'sql_ca'.$i},DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["act"]):
case "update":case "copy";
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// 対象記事IDデータのチェック
	if(!ereg("^([0-9]{10,})-([0-9]{6})$",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	$sql = "
	SELECT
		*
	FROM
		".S7_3_PRODUCT_LST."
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

	// POSTでのカテゴリーのデータが無い場合GETを調べる
	if(!$ca){$ca = urldecode($_GET["ca"]);}

	//カテゴリーパラメータが無い場合または数字ではない場合
	if(empty($ca) || !is_numeric($ca)){
		$ca = "";
		$ca_res = "";
		$ca_name="全て表示";
	}else{//カテゴリーが在る場合

		//カテゴリーのコードが存在しない場合もエラー
			for($i=0,$j=0;$i < count($fetchCA);$i++){
				if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
					$ca_name=$fetchCA[$i]['CATEGORY_NAME'];
					$ca_res=$fetchCA[$i]['RES_ID'];
					$j=1;break;
				}
			}

		//カテゴリーコードと一致するのが無かった場合
		if(!$j){
			$ca = "";
			$ca_res = "";
			$ca_name="全て表示";
		}
	}

	//カテゴリーが在る場合は検索条件に付け加える
		$serch_sql .= ($ca_res)?"AND (".S7_3_PRODUCT_LST.".CATEGORY_CODE REGEXP '".$ca_res."')":"";

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）

	// 抽出開始位置の指定
	$st = ($p-1) * DISP_MAXROW_BACK;

	$sql = "
	SELECT
		".S7_3_PRODUCT_LST.".RES_ID,
		".S7_3_PRODUCT_LST.".TITLE,
		".S7_3_PRODUCT_LST.".CONTENT,
		".S7_3_PRODUCT_LST.".CATEGORY_CODE,
		YEAR(".S7_3_PRODUCT_LST.".DISP_DATE) AS Y,
		MONTH(".S7_3_PRODUCT_LST.".DISP_DATE) AS M,
		DAYOFMONTH(".S7_3_PRODUCT_LST.".DISP_DATE) AS D,
		".S7_3_VIEW_ORDER_LIST.".VIEW_ORDER,
		".S7_3_PRODUCT_LST.".DISPLAY_FLG
	FROM
		".S7_3_PRODUCT_LST."
			INNER JOIN
		".S7_3_VIEW_ORDER_LIST."
			ON
		(".S7_3_PRODUCT_LST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".RES_ID)
	WHERE
		(".S7_3_PRODUCT_LST.".DEL_FLG = '0')
		AND
		(".S7_3_VIEW_ORDER_LIST.".C_ID = '".$ca_res."')
		$serch_sql
	ORDER BY
		".S7_3_VIEW_ORDER_LIST.".VIEW_ORDER ASC
	LIMIT
		".$st.",".DISP_MAXROW_BACK."
	";

	$sqlcnt = "
	SELECT
		COUNT(*) AS CNT
	FROM
		".S7_3_PRODUCT_LST."
			INNER JOIN
		".S7_3_VIEW_ORDER_LIST."
			ON
		(".S7_3_PRODUCT_LST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".RES_ID)
	WHERE
		(".S7_3_PRODUCT_LST.".DEL_FLG = '0')
		AND
		(".S7_3_VIEW_ORDER_LIST.".C_ID = '')
	";

	$fetchCNT = dbOpe::fetch($sqlcnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sqlcnt = "
	SELECT
		COUNT(*) AS CNT
	FROM
		".S7_3_PRODUCT_LST."
			INNER JOIN
		".S7_3_VIEW_ORDER_LIST."
			ON
		(".S7_3_PRODUCT_LST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".RES_ID)
	WHERE
		(".S7_3_PRODUCT_LST.".DEL_FLG = '0')
		AND
		(".S7_3_VIEW_ORDER_LIST.".C_ID = '".$ca_res."')
		$serch_sql
	";

	$fetchCNT_CA = dbOpe::fetch($sqlcnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

endswitch;

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>