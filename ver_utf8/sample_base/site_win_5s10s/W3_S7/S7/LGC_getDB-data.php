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

#-------------------------------------------------------------------------
# カテゴリー情報の取得
#-------------------------------------------------------------------------

	$sql = "
	SELECT
		CATEGORY_CODE,CATEGORY_NAME,CATEGORY_DETAILS,VIEW_ORDER
	FROM
		".S7_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
		AND
		(DISPLAY_FLG = '1')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//カテゴリー一覧表示のみ処理をする
	if(!$res_id){

		//カテゴリーが存在しないまたは全て非表示で無い場合チェックを行う
		if(count($fetchCA)){
			//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
			if(empty($ca) || !is_numeric($ca)){
				$ca = $fetchCA[0]['CATEGORY_CODE'];
				$ca_name = $fetchCA[0]['CATEGORY_NAME'];
			}

			//カテゴリーのコードが存在しない場合もエラー
				for($i=0,$j=0;$i < count($fetchCA);$i++){
					if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
						$ca_name = $fetchCA[$i]['CATEGORY_NAME'];
						$j=1;break;
					}
				}

			//カテゴリーコードと一致するのが無かった場合
			if(!$j){
				$ca = $fetchCA[0]['CATEGORY_CODE'];
				$ca_name = $fetchCA[0]['CATEGORY_NAME'];
			}
		}else{//カテゴリーが無い場合、空にする
		$ca = "";
		}

	}

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
	$ca_quety = " AND (".S7_PRODUCT_LST.".CATEGORY_CODE = ".$ca.")";
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
			".S7_PRODUCT_LST.".RES_ID,
			".S7_PRODUCT_LST.".TITLE,
			".S7_PRODUCT_LST.".CONTENT,
			".S7_PRODUCT_LST.".CATEGORY_CODE
		FROM
		".S7_PRODUCT_LST."
		INNER JOIN
		".S7_CATEGORY_MST."
		ON
		(".S7_PRODUCT_LST.".CATEGORY_CODE = ".S7_CATEGORY_MST.".CATEGORY_CODE)
	WHERE
		(".S7_CATEGORY_MST.".DISPLAY_FLG = '1')
		AND
		(".S7_CATEGORY_MST.".DEL_FLG = '0')
		AND
		(".S7_PRODUCT_LST.".DISPLAY_FLG = '1')
		".$ca_quety."
	";

	$sql .= "
		ORDER BY
			".S7_PRODUCT_LST.".VIEW_ORDER ASC
	";

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sql .= "
		LIMIT
			".$st.",".DISP_MAXROW."
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
	if(empty($res_id) || !ereg("^([0-9]{10,})-([0-9]{6})$",$res_id) ){
		header("Location: ../");exit();
	}
	//pidのデータがある場合チェックを行う、数字以外の場合はエラー
	if($pid && !is_numeric($pid)){
		header("Location: ../");exit();
	}

	// DBよりデータを取得
	$sql = "
	SELECT
			".S7_PRODUCT_LST.".RES_ID,
			".S7_PRODUCT_LST.".TITLE,
			".S7_PRODUCT_LST.".DETAIL_CONTENT,
			".S7_PRODUCT_LST.".CATEGORY_CODE,
			".S7_CATEGORY_MST.".CATEGORY_NAME,
			".S7_PRODUCT_LST.".TITLE_TAG,
			".S7_PRODUCT_LST.".YOUTUBE,
			".S7_PRODUCT_LST.".IMG_FLG,

			".S7_PRODUCT_LST.".TYPE,
			".S7_PRODUCT_LST.".SIZE,
			".S7_PRODUCT_LST.".EXTENTION

	FROM
		".S7_PRODUCT_LST."
		INNER JOIN
		".S7_CATEGORY_MST."
		ON
		(".S7_PRODUCT_LST.".CATEGORY_CODE = ".S7_CATEGORY_MST.".CATEGORY_CODE)

	WHERE
		(".S7_CATEGORY_MST.".DISPLAY_FLG = '1')
		AND
		(".S7_CATEGORY_MST.".DEL_FLG = '0')
		AND
		(".S7_PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
		(".S7_PRODUCT_LST.".RES_ID = '".addslashes($res_id)."')
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	//もし、ここでデータが取得できない場合は一覧画面へもどる
	// ＳＱＬの実行を取得できてなければ処理をしない
	if(empty($fetch[0]["RES_ID"])){
		header("Location: ../");exit();
	}

	//カテゴリ名＆カテゴリコード別名保存
	$ca = $fetch[0]['CATEGORY_CODE'];
	$ca_name = $fetch[0]['CATEGORY_NAME'];

	// SQL組立て
	$sql = "
		SELECT
			RES_ID
		FROM
			".S7_PRODUCT_LST."
		WHERE
			(DISPLAY_FLG = '1')
		AND
			(DEL_FLG = '0')
		AND
			(CATEGORY_CODE = ".$fetch[0]['CATEGORY_CODE'].")
		ORDER BY
			VIEW_ORDER ASC
	";

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	// ＳＱＬの実行を取得できてなければ処理をしない
	if(!count($fetchCNT)){
		header("Location: ../");exit();
	}

endif;

?>
