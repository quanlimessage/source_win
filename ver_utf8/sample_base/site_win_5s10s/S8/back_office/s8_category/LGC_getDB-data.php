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
}/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
}

#--------------------------------------------------------------------------------
# 親カテゴリーの取得
#--------------------------------------------------------------------------------
$sql = "
SELECT
	CATEGORY_CODE,
	CATEGORY_NAME,
	CATEGORY_DETAILS,
	DISPLAY_FLG
FROM
	".S8_PARENT_CATEGORY_MST."
WHERE
	(DEL_FLG = '0')
ORDER BY
	VIEW_ORDER ASC

";
$fetchPCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//カテゴリー名の横に登録件数を表示させる

for($i=0;$i<count($fetchPCA);$i++){
	${'ca_cnt'.$i} = $fetchPCA[$i]['CATEGORY_CODE'];

	${'sql_ca'.$i} = "
	SELECT
		COUNT(*) AS CNT
	FROM
		".S8_CATEGORY_MST."
	WHERE
		(PARENT_CATEGORY_CODE = '${'ca_cnt'.$i}')
	AND
		(DEL_FLG = '0')
	";

	// ＳＱＬを実行
	${'fetchPCA_ca'.$i} = dbOpe::fetch(${'sql_ca'.$i},DB_USER,DB_PASS,DB_NAME,DB_SERVER);
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
	if(!is_numeric($cate)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$cate}");
	}

	$sql = "
	SELECT
		PARENT_CATEGORY_CODE,
		CATEGORY_CODE,
		CATEGORY_NAME,
		CATEGORY_DETAILS,
		DISPLAY_FLG
	FROM
		".S8_CATEGORY_MST."
	WHERE
		(CATEGORY_CODE = '$cate')
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
	$pca = urldecode($pca);

	//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
	if(empty($pca) || !is_numeric($pca)){$pca = $fetchPCA[0]['CATEGORY_CODE'];$pca_name=$fetchPCA[0]['CATEGORY_NAME'];}

	//カテゴリーのコードが存在しない場合もエラー
		for($i=0,$j=0;$i < count($fetchPCA);$i++){
			if($fetchPCA[$i]['CATEGORY_CODE'] == $pca){
				$pca_name=$fetchPCA[$i]['CATEGORY_NAME'];
				$j=1;break;
			}
		}

	//カテゴリーコードと一致するのが無かった場合
	if(!$j){$pca = $fetchPCA[0]['CATEGORY_CODE'];$pca_name=$fetchPCA[0]['CATEGORY_NAME'];}

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）
	$sql = "
	SELECT
		CATEGORY_CODE,
		CATEGORY_NAME,
		VIEW_ORDER,
		DISPLAY_FLG
	FROM
		".S8_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	AND
		(PARENT_CATEGORY_CODE = '$pca')
	ORDER BY
		VIEW_ORDER ASC
	";

endswitch;

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
