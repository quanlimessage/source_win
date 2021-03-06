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
/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
}

//カテゴリー情報の取得
	$sql = "
	SELECT
		".S8_CATEGORY_MST.".CATEGORY_CODE,
		".S8_CATEGORY_MST.".PARENT_CATEGORY_CODE,
		".S8_PARENT_CATEGORY_MST.".CATEGORY_NAME AS PARENT_CATEGORY_NAME,
		".S8_CATEGORY_MST.".CATEGORY_NAME,
		".S8_CATEGORY_MST.".VIEW_ORDER
	FROM
		".S8_CATEGORY_MST."
	INNER JOIN
		".S8_PARENT_CATEGORY_MST."
	ON
		".S8_CATEGORY_MST.".PARENT_CATEGORY_CODE = ".S8_PARENT_CATEGORY_MST.".CATEGORY_CODE
	WHERE
		(".S8_CATEGORY_MST.".DEL_FLG = '0')
	AND
		(".S8_PARENT_CATEGORY_MST.".DEL_FLG = '0')
	ORDER BY
		".S8_PARENT_CATEGORY_MST.".VIEW_ORDER ASC,
		".S8_CATEGORY_MST.".VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = $PDO -> fetch($sql);

//カテゴリー名の横に登録件数を表示させる

for($i=0;$i<count($fetchCA);$i++){
	${'ca_cnt'.$i} = $fetchCA[$i]['CATEGORY_CODE'];

	${'sql_ca'.$i} = "
	SELECT
		RES_ID,
		CATEGORY_CODE
	FROM
		".S8_PRODUCT_LST."
	WHERE
		(CATEGORY_CODE = '${'ca_cnt'.$i}')
	AND
		(DEL_FLG = '0')
	";

	// ＳＱＬを実行
	${'fetchCA_ca'.$i} = $PDO -> fetch(${'sql_ca'.$i});
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
//$copy_flg = false;
switch($_POST["act"]):
case "update":case "copy";
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得
//	$copu_flg = true;
	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	$sql = "
	SELECT
		RES_ID,TITLE,CONTENT,DETAIL_CONTENT,TITLE_TAG,YOUTUBE,IMG_FLG,CATEGORY_CODE,DISPLAY_FLG
	FROM
		".S8_PRODUCT_LST."
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

	//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
	if(empty($ca) || !is_numeric($ca)){$ca = $fetchCA[0]['CATEGORY_CODE'];$ca_name=$fetchCA[0]['CATEGORY_NAME'];$pca_name=$fetchCA[0]['PARENT_CATEGORY_NAME'];}

	//カテゴリーのコードが存在しない場合もエラー
		for($i=0,$j=0;$i < count($fetchCA);$i++){
			if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
				$ca_name=$fetchCA[$i]['CATEGORY_NAME'];
				$pca_name=$fetchCA[$i]['PARENT_CATEGORY_NAME'];
				$j=1;break;
			}
		}

	//カテゴリーコードと一致するのが無かった場合
	if(!$j){$ca = $fetchCA[0]['CATEGORY_CODE'];$ca_name=$fetchCA[0]['CATEGORY_NAME'];$pca_name=$fetchCA[0]['PARENT_CATEGORY_NAME'];}

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）

	// 抽出開始位置の指定
	$st = ($p-1) * DISP_MAXROW_BACK;

	$sql = "
	SELECT
		RES_ID,TITLE,CONTENT,CATEGORY_CODE,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		VIEW_ORDER,DISPLAY_FLG
	FROM
		".S8_PRODUCT_LST."
	WHERE
		(DEL_FLG = '0')
	AND
		(CATEGORY_CODE = '$ca')
	ORDER BY
		VIEW_ORDER ASC
	LIMIT
		".$st.",".DISP_MAXROW_BACK."
	";

	$sqlcnt = "
	SELECT
		".S8_PRODUCT_LST.".RES_ID,
		".S8_PRODUCT_LST.".TITLE,
		".S8_PRODUCT_LST.".DISPLAY_FLG
	FROM
		".S8_PRODUCT_LST."
		INNER JOIN
		".S8_CATEGORY_MST."
		ON
		(".S8_PRODUCT_LST.".CATEGORY_CODE = ".S8_CATEGORY_MST.".CATEGORY_CODE)

	WHERE
		(".S8_PRODUCT_LST.".DEL_FLG = '0')
		AND
		(".S8_CATEGORY_MST.".DEL_FLG = '0')
	";

	$fetchCNT = $PDO -> fetch($sqlcnt);

	$sqlcnt .= "
		AND
		(".S8_PRODUCT_LST.".CATEGORY_CODE = '$ca')
	";

	$fetchCNT_CA = $PDO -> fetch($sqlcnt);

endswitch;

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

?>
