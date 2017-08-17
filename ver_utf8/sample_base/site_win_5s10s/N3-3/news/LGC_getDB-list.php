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
	$nid = (!empty($nid) && preg_match("/^([0-9]{10,})-([0-9]{6})$/",$nid) )?$nid:"";

#------------------------------------------------------------------------
# ページング用
# ページ遷移時にむだなパラメーターを付けないため
# (カテゴリーが送信してない場合に?ca=&p=)
# に送信パラメーターをチェックしてリンクパラメーターを設定する
#------------------------------------------------------------------------
// ページ番号の設定(GET受信データがなければ1をセット)
	if(empty($p) or !is_numeric($p))$p=1;

// 抽出開始位置の指定
	$st = ($p-1) * $page;
	
// IDがあれば該当の記事を取得する
	if(!empty($nid) && ereg("^([0-9]{10,})-([0-9]{6})$",$nid) ){
		$sql_id = "AND (RES_ID = '".$nid."')";
	}

// SQL組立て
	////////////////////////////////////////////////
	//ページネーション用(一覧、詳細　兼用)
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
	
	////////////////////////////////////////////////
	//表示用(一覧、詳細　兼用)
	$sql = "
		SELECT
			*,
			YEAR(DISP_DATE) AS Y,
			MONTH(DISP_DATE) AS M,
			DAYOFMONTH(DISP_DATE) AS D
		FROM
			".N3_2WHATSNEW."
		WHERE
			(DISPLAY_FLG = '1')
		AND
			(DEL_FLG = '0')
			{$sql_id}
		ORDER BY
			DISP_DATE DESC
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

?>
