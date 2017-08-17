<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

//大カテゴリー情報の取得
	$sql = "
	SELECT
		COLOR_CODE,
		COLOR_NAME,
		VIEW_ORDER
	FROM
		COLOR_MST
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = $PDO -> fetch($sql);

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
		COLOR_CODE,
		SIZE_CODE,
		SIZE_NAME,
		SIZE_DETAILS,
		DISPLAY_FLG
	FROM
		SIZE_MST
	WHERE
		(SIZE_CODE = '$cate')
	";

	break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// POSTでのカテゴリーのデータが無い場合GETを調べる
	if(!$ca){$ca = urldecode($_GET["ca"]);}

	//カテゴリーパラメータが無い場合または数字ではない場合（全て表示されてしまうため）
	if(empty($ca) || !is_numeric($ca)){$ca = $fetchCA[0]['COLOR_CODE'];}

	//カテゴリーのコードが存在しない場合もエラー
		for($i=0,$j=0;$i < count($fetchCA);$i++){
			if($fetchCA[$i]['COLOR_CODE'] == $ca){
				$j=1;break;
			}
		}

	//カテゴリーコードと一致するのが無かった場合
	if(!$j){$ca = $fetchCA[0]['COLOR_CODE'];}

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）
	$sql = "
	SELECT
		SIZE_CODE,
		SIZE_NAME,
		VIEW_ORDER,
		DISPLAY_FLG
	FROM
		SIZE_MST
	WHERE
		(DEL_FLG = '0')
	AND
		(COLOR_CODE = '$ca')
	ORDER BY
		VIEW_ORDER ASC
	";

endswitch;

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

?>
