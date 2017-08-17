<?php
/*******************************************************************************

	LOGIC:DBよりデータの取得

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================================
# 共通処理：GETデータの受け取りと共通な文字列処理
#=============================================================================
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));

// 送信される可能性のあるパラメーターはデコードする
$p  = urldecode($p);
$ca = urldecode($ca);
$res_id = urldecode($id);

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

// カテゴリーパラメーターが送信されたらカテゴリーごとの商品を表示
if(!empty($ca) && is_numeric($ca)){
	$ca_quety = " AND (CATEGORY_CODE = ".$ca.")";
}

// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;

if(empty($res_id)):
#------------------------------------------------------------------------
#	該当商品リスト用情報の取得
#------------------------------------------------------------------------

// 抽出開始位置の指定
	$st = ($p-1) * $page;

// SQL組立て
$sql = "
SELECT
	RES_ID,
	TITLE,
	CONTENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D,
	DISPLAY_FLG,
	IMG_FLG
FROM
	".N3_2WHATSNEW."
WHERE
	(DISPLAY_FLG = '1')
AND
	(DEL_FLG = '0')
".$ca_quety."
";

	$sql .= "
		ORDER BY
			DISP_DATE DESC
	";

	$fetchCNT = $PDO -> fetch($sql);

	$sql .= "
		LIMIT
			".$st.",".$page."
	";

	$fetch = $PDO -> fetch($sql);

	// 商品が何も登録されていない場合に表示
	if(count($fetch) == 0):
		$disp_no_data = "<br><div align=\"center\"><br><br><br>ただいま準備中のため、もうしばらくお待ちください。<br><br><br></div>";
	else:
		$disp_no_data = "";
	endif;

endif;

#-------------------------------------------------------------------------
# 詳細ページへの処理関連
#-------------------------------------------------------------------------
if(!empty($res_id)):

	// パラメータがないもしくは不正なデータを混入された状態でアクセスされた場合のエラー処理
	if(empty($res_id) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id) ){
		header("Location: ../");exit();
	}

	// DBよりデータを取得
	$sql = "
	SELECT
		RES_ID,
		TITLE,
		CONTENT,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		DISPLAY_FLG,
		IMG_FLG
	FROM
		".N3_2WHATSNEW."
	WHERE
		(RES_ID = '".addslashes($res_id)."')
		AND
		(DISPLAY_FLG = '1')
		AND
		(DEL_FLG = '0')
	";

	$fetch = $PDO -> fetch($sql);

	// ＳＱＬの実行を取得できてなければ処理をしない
	if(empty($fetch[0]["RES_ID"])){
		header("Location: ../");exit();
	}

	// SQL組立て
	$sql = "
		SELECT
			RES_ID
		FROM
			".N3_2WHATSNEW."
		WHERE
			(DISPLAY_FLG = '1')
		AND
			(DEL_FLG = '0')
		ORDER BY
			DISP_DATE DESC
	";

	$fetchCNT = $PDO -> fetch($sql);

endif;

?>
