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
$pca = urldecode($pca);
$ca = urldecode($ca);
$res_id = urldecode($id);
$pid = urldecode($pid);

#-------------------------------------------------------------------------
# カテゴリー情報の取得
#-------------------------------------------------------------------------

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

	//カテゴリー一覧表示のみ処理をする
	if(!$res_id){

		//カテゴリーが存在しないまたは全て非表示で無い場合チェックを行う
		if(count($fetchCA)){

			//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
			if(empty($ca) || !is_numeric($ca)){
				$ca = $fetchCA[0]['CATEGORY_CODE'];
				$ca_name = $fetchCA[0]['CATEGORY_NAME'];
				$pca_name = $fetchCA[0]['PARENT_CATEGORY_NAME'];
			}

			//カテゴリーのコードが存在しない場合もエラー
				for($i=0,$j=0;$i < count($fetchCA);$i++){
					if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
						$ca_name = $fetchCA[$i]['CATEGORY_NAME'];
						$pca_name = $fetchCA[$i]['PARENT_CATEGORY_NAME'];
						$j=1;break;
					}
				}

			//カテゴリーコードと一致するのが無かった場合
			if(!$j){
				$ca = $fetchCA[0]['CATEGORY_CODE'];
				$ca_name = $fetchCA[0]['CATEGORY_NAME'];
				$pca_name = $fetchCA[0]['PARENT_CATEGORY_NAME'];
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
	$ca_quety = " AND (".S8_PRODUCT_LST.".CATEGORY_CODE = ".$ca.")";
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
			".S8_PRODUCT_LST.".RES_ID,
			".S8_PRODUCT_LST.".TITLE,
			".S8_PRODUCT_LST.".CONTENT,
			".S8_PRODUCT_LST.".CATEGORY_CODE
		FROM
		".S8_PRODUCT_LST."
		INNER JOIN
		".S8_CATEGORY_MST."
		ON
		(".S8_PRODUCT_LST.".CATEGORY_CODE = ".S8_CATEGORY_MST.".CATEGORY_CODE)
	WHERE
		(".S8_CATEGORY_MST.".DISPLAY_FLG = '1')
		AND
		(".S8_CATEGORY_MST.".DEL_FLG = '0')
		AND
		(".S8_PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
		(".S8_PRODUCT_LST.".DEL_FLG = '0')
		".$ca_quety."
	";

	$sql .= "
		ORDER BY
			".S8_PRODUCT_LST.".VIEW_ORDER ASC
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
		".S8_PRODUCT_LST.".*,
		".S8_CATEGORY_MST.".PARENT_CATEGORY_CODE,
		".S8_CATEGORY_MST.".CATEGORY_NAME,
		".S8_PARENT_CATEGORY_MST.".CATEGORY_NAME AS PARENT_CATEGORY_NAME
	FROM
		".S8_PRODUCT_LST."
		INNER JOIN
		".S8_CATEGORY_MST."
		ON
		(".S8_PRODUCT_LST.".CATEGORY_CODE = ".S8_CATEGORY_MST.".CATEGORY_CODE)
		INNER JOIN
		".S8_PARENT_CATEGORY_MST."
		ON
		(".S8_CATEGORY_MST.".PARENT_CATEGORY_CODE = ".S8_PARENT_CATEGORY_MST.".CATEGORY_CODE)

	WHERE
		(".S8_CATEGORY_MST.".DISPLAY_FLG = '1')
		AND
		(".S8_CATEGORY_MST.".DEL_FLG = '0')
		AND
		(".S8_PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
		(".S8_PRODUCT_LST.".RES_ID = '".addslashes($res_id)."')
	";

	$fetch = $PDO -> fetch($sql);
	//もし、ここでデータが取得できない場合は一覧画面へもどる
	// ＳＱＬの実行を取得できてなければ処理をしない
	if(empty($fetch[0]["RES_ID"])){
		header("Location: ../");exit();
	}

	$ca = $fetch[0]['CATEGORY_CODE'];
	$ca_name = $fetch[0]['CATEGORY_NAME'];
	$pca = $fetch[0]['PARENT_CATEGORY_CODE'];
	$pca_name = $fetch[0]['PARENT_CATEGORY_NAME'];

	// SQL組立て
	$sql = "
		SELECT
			RES_ID
		FROM
			".S8_PRODUCT_LST."
		WHERE
			(DISPLAY_FLG = '1')
		AND
			(DEL_FLG = '0')
		AND
			(CATEGORY_CODE = ".$fetch[0]['CATEGORY_CODE'].")
		ORDER BY
			VIEW_ORDER ASC
	";

	$fetchCNT = $PDO -> fetch($sql);

	// ＳＱＬの実行を取得できてなければ処理をしない
	if(!count($fetchCNT)){
		header("Location: ../");exit();
	}

endif;

?>
