<?php
// セッション管理スタート(検索指定情報管理)
session_start();
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
	require_once("../../common/INI_config.php");	// 共通設定情報
	require_once("dbOpe.php");					// DB操作クラスライブラリ
	require_once("util_lib.php");				// 汎用処理クラスライブラリ

	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));
	if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));

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
			(COLOR_CODE = '".$s_color."')
		ORDER BY
			VIEW_ORDER ASC
	";

	$fetchCateCheck = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

if($ReturnStr == ""){

	$ReturnStr = "";

	for($i=0;$i<count($fetchCateCheck);$i++){

	$ReturnStr .= "<table width=\"105\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\" style=\"float:left;\">
		<tr>
			<td width=\"5%\"><input type=\"checkbox\" name=\"copy_size[]\" value=\"".$fetchCateCheck[$i]["SIZE_NAME"]."\"></td>
			<td width=\"95%\">".$fetchCateCheck[$i]["SIZE_NAME"]."</td>
		</tr>
	</table>";

	}

}

echo $ReturnStr;
?>
