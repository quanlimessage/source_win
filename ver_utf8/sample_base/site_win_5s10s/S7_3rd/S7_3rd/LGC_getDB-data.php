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
$pid = urldecode($pid);

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

//選択されたカテゴリーのデータを表示させる。
	if(!empty($ca) && is_numeric($ca)){
		$ca_res = $fetchCA2['id'][$ca];//カテゴリーのIDを渡す
		$ca_quety = " AND (".S7_3_VIEW_ORDER_LIST.".C_ID = '".$ca_res."')";

//表示するカテゴリーが選択されていない場合は全てを表示させる。
	}else{
		$ca_quety = " AND (".S7_3_VIEW_ORDER_LIST.".C_ID = '')";

	}

// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;

if(empty($res_id)):
#------------------------------------------------------------------------
#	該当商品リスト用情報の取得
#------------------------------------------------------------------------

	// 抽出開始位置の指定
	$st = ($p-1) * DISP_MAXROW;

	// SQL組立て
	$sql = "
		SELECT
			".S7_3_PRODUCT_LST.".*,
			".S7_3_CATEGORY_MST.".CATEGORY_NAME,
			".S7_3_CATEGORY_MST.".CATEGORY_CODE
		FROM
			".S7_3_PRODUCT_LST."
				INNER JOIN
			".S7_3_VIEW_ORDER_LIST."
				ON
			(".S7_3_PRODUCT_LST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".RES_ID)
				LEFT JOIN
			".S7_3_CATEGORY_MST."
				ON
				(".S7_3_CATEGORY_MST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".C_ID)
					AND
				(".S7_3_CATEGORY_MST.".DISPLAY_FLG = '1')
					AND
				(".S7_3_CATEGORY_MST.".DEL_FLG = '0')
		WHERE
			(".S7_3_PRODUCT_LST.".DEL_FLG = '0')
			AND
			(".S7_3_PRODUCT_LST.".DISPLAY_FLG = '1')
			$ca_quety
		ORDER BY
			".S7_3_VIEW_ORDER_LIST.".VIEW_ORDER ASC
	";

	$fetchCNT = $PDO -> fetch($sql);

	$sql .= "
		LIMIT
			".$st.",".DISP_MAXROW."
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
# 詳細画面へのデータ処理関連
#-------------------------------------------------------------------------
if(!empty($res_id)):

	// パラメータがないもしくは不正なデータを混入された状態でアクセスされた場合のエラー処理
	if(empty($res_id) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id) ){
		header("Location: ../");exit();
	}
	//pidのデータがある場合チェックを行う、数字以外の場合はエラー
	if($pid && !is_numeric($pid)){
		header("Location: ../");exit();
	}

	// DBよりデータを取得
	$sql = "
	SELECT
			".S7_3_PRODUCT_LST.".*
	FROM
		".S7_3_PRODUCT_LST."
	WHERE
		(".S7_3_PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
		(".S7_3_PRODUCT_LST.".RES_ID = '".addslashes($res_id)."')
	";

	$fetch = $PDO -> fetch($sql);

	//ページネーション用に同じカテゴリーの記事一覧をview_listから取得
	$sql_view = "
		SELECT
			RES_ID
		FROM
			".S7_3_VIEW_ORDER_LIST."
		WHERE
			".S7_3_VIEW_ORDER_LIST.".C_ID = '".$fetchCA2['id'][$ca]."'
		ORDER BY
			VIEW_ORDER ASC
		";

		$fetch_view = $PDO -> fetch($sql_view);
	//もし、ここでデータが取得できない場合は一覧画面へもどる
	// ＳＱＬの実行を取得できてなければ処理をしない
	if(empty($fetch[0]["RES_ID"])){
		header("Location: ../");exit();
	}

endif;

?>
