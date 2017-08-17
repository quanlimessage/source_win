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
	VIEW_ORDER
FROM
	". S7_CATEGORY_MST ."
WHERE
	(DEL_FLG = '0')
ORDER BY
	VIEW_ORDER ASC
";

// ＳＱＬを実行
$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//カテゴリー名の横に登録件数を表示させる
for($i=0;$i<count($fetchCA);$i++){
	${'ca_cnt'.$i} = $fetchCA[$i]['CATEGORY_CODE'];

	${'sql_ca'.$i} = "
	SELECT
		RES_ID
	FROM
		". S7_PRODUCT_LST ."
	WHERE
		(CATEGORY_CODE = '${'ca_cnt'.$i}')
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
case "new_entry":
///////////////////////////////////////////
// 新規

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	break;
case "update":case "copy":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

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
		TITLE_TAG,
		YOUTUBE,
		IMG_FLG,
		CATEGORY_CODE,
		DISPLAY_FLG
	FROM
		". S7_PRODUCT_LST ."
	WHERE
		(RES_ID = '$res_id')
	";

	break;
case "list":
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// GETデータの受け取りと共通な文字列処理
	if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4)));

	// 送信される可能性のあるパラメーターはデコードする
	$p        = urldecode($p);
	$ca       = urldecode($ca);
	$res_id   = urldecode($res_id);

	$search_data = $_SESSION["search_cond"];

	#------------------------------------------------------------------------
	# ページング用
	# ページ遷移時にむだなパラメーターを付けないため
	# (カテゴリーが送信してない場合に?ca=&p=)
	# に送信パラメーターをチェックしてリンクパラメーターを設定する
	#------------------------------------------------------------------------
	$param="";
	if($_GET['p']){
		$p = $_GET['p'];
		$search_data['p'] = $p;
	}

	if(!empty($p) && !empty($ca)){
		$param="?p=".urlencode($p)."&ca=".urlencode($ca);
	}elseif(!empty($p) && empty($ca)){
		$param="?p=".urlencode($p);
	}elseif( empty($p) && !empty($ca) ){
		$param="?ca=".urlencode($ca);
	}

	// ページ番号の設定(GET受信データがなければ1をセット)
	if(empty($p) or !is_numeric($p))$p=1;

	// プレミアムサーバー
	if(PREMIUM_FLG == 1):

		// 共通な文字列処理
		$search_title     = mb_convert_kana($search_title,"KV");
		$search_ca        = mb_convert_kana($search_ca,"n");
		$search_display   = mb_convert_kana($search_display,"n");

		if($search_data["search_title"])   $search_title    = $search_data["search_title"];
		if($search_data["search_ca"])      $search_ca       = $search_data["search_ca"];
		if($search_data["search_display"]) $search_display  = $search_data["search_display"];

		// 検索条件をセッションに格納
		$search_data["search_title"]    = $search_title;
		$search_data["search_ca"]       = $search_ca;
		$search_data["search_display"]  = $search_display;

		$_SESSION["search_cond"] = $search_data;

		#---------------------------------------------------------------
		# 検索条件の有無によりWHERE句以下を組立て
		#---------------------------------------------------------------
		// 追加where節
		$addwhere ="";

		// カテゴリー
		if($search_ca){
			$addwhere .= "
			AND
				(a.CATEGORY_CODE = '". $search_ca ."')
			";
		}

		// 商品名
		if($search_title){
			$addwhere .= "
			AND
				(a.TITLE LIKE '%". $search_title ."%')
			";
		}

		// 表示/非表示
		switch($search_display):
			case "2":
					$addwhere .= "
					AND
						(a.DISPLAY_FLG = '1')
					";
				break;
			case "3":
					$addwhere .= "
					AND
						(a.DISPLAY_FLG = '0')
					";
				break;
		endswitch;

	else:

		// POSTでのカテゴリーのデータが無い場合GETを調べる
		if(!$ca){$ca = urldecode($_GET["ca"]);}

		//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
		if(empty($ca) || !is_numeric($ca)){$ca = $fetchCA[0]['CATEGORY_CODE'];$ca_name=$fetchCA[0]['CATEGORY_NAME'];}

		//カテゴリーのコードが存在しない場合もエラー
			for($i=0,$j=0;$i < count($fetchCA);$i++){
				if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
					$ca_name=$fetchCA[$i]['CATEGORY_NAME'];
					$j=1;break;
				}
			}

		//カテゴリーコードと一致するのが無かった場合
		if(!$j){$ca = $fetchCA[0]['CATEGORY_CODE'];$ca_name=$fetchCA[0]['CATEGORY_NAME'];}

		// 追加where節
		$addwhere ="";

		// カテゴリー
		if($ca){
			$addwhere .= "
			AND
				(a.CATEGORY_CODE = '". $ca ."')
			";
		}

	endif;

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）

	// 抽出開始位置の指定
	$st = ($p-1) * DISP_MAXROW_BACK;

	$select = "
	SELECT
		a.RES_ID,
		a.TITLE,
		a.CATEGORY_CODE,
		YEAR(a.DISP_DATE) AS Y,
		MONTH(a.DISP_DATE) AS M,
		DAYOFMONTH(a.DISP_DATE) AS D,
		a.VIEW_ORDER,
		a.DISPLAY_FLG
	";

	$select_cnt = "
	SELECT
		COUNT(*) AS CNT
	";

	$from = "
	FROM
		". S7_PRODUCT_LST ." AS a
		INNER JOIN
		". S7_CATEGORY_MST ." AS b
		ON
		(a.CATEGORY_CODE = b.CATEGORY_CODE)
	";

	$where = "
	WHERE
		(a.DEL_FLG = '0')
		AND
		(b.DEL_FLG = '0')
	";

	$order_by = "
	ORDER BY
		a.VIEW_ORDER ASC
	";

	$limit = "
	LIMIT
		". $st .",". DISP_MAXROW_BACK ."
	";

	$sql = "{$select} {$from} {$where} {$addwhere} {$order_by} {$limit}";

	$sqlscnt = "{$select_cnt} {$from} {$where} {$addwhere}";
	$fetchSCNT = dbOpe::fetch($sqlscnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sqlcnt = "{$select_cnt} {$from} {$where}";
	$fetchCNT = dbOpe::fetch($sqlcnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	break;
default:
	break;
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
}

?>
