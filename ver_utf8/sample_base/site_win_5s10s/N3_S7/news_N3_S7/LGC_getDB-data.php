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
	$ca_quety = " AND (CATEGORY_CODE = ".$ca.")";
}

// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;

	// 抽出開始位置の指定
	$st = ($p-1) * $page;

	// SQL組立て
	$sql = "
		SELECT
			*,
			YEAR(DISP_DATE) AS Y,
			MONTH(DISP_DATE) AS M,
			DAYOFMONTH(DISP_DATE) AS D
		FROM
			".N3_S7_WHATSNEW."
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

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sql .= "
		LIMIT
			".$st.",".$page."
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	// 商品が何も登録されていない場合に表示
	if(count($fetch) == 0):
		$disp_no_data = "<br><div align=\"center\"><br><br><br>ただいま準備中のため、もうしばらくお待ちください。<br><br><br></div>";
	else:
		$disp_no_data = "";
	endif;

?>
