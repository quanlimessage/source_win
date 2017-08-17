<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理


*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQLにおいて危険文字をエスケープしておく
//$content = utilLib::strRep($content,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$content = html_tag($_POST['content']);
foreach($_POST['_block_id'] as $k => $v){
	$bids[] = sntz($v[0]);
}
// 使用したブロックID
$use_blosk = array_unique ($bids);
$use_block_str = implode(",",$use_blosk);



#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
// DB格納用のSQL文
$sql = "
UPDATE
	".CP1_PAGE_LST."
SET
	USE_BLOCK = '$use_block_str'
WHERE
	(RES_ID = '$res_id')
";


// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました : err02<hr>{$db_result}");	
}
#=================================================================================
# ブロックの削除
#=================================================================================

if(is_array($delete_res_id) && count($delete_res_id) > 0 ){
	
	for($i = 0; $i < count($delete_res_id);$i++){

		// ブロックID
		$b_res_id = $delete_res_id[$i];
		$bid = $delete_blcok_id[$i];


		$bsql = " DELETE FROM ".CP1_VALUES_LST." WHERE (DEL_FLG = '0') AND (RES_ID = '".$b_res_id."') ";

		//echo $bsql."<br>";
		$db_result = dbOpe::regist($bsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		for($j = 0; $j < CP1_BLOCK_MAX_IMG; $j++ ){
			search_file_del(CP1_IMG_PATH,$b_res_id."_".($j+1).".*");
		}
	}
}

//die();
#=================================================================================
# ブロックの登録
#=================================================================================

// ブロッククエリの作成
$vo = 0;
$_b_res_ids = array(); // 簡易追加で使用
foreach($_POST['_block_id'] as $k => $v){
	$b_res_id = ($_POST['_block_res_id'][$k]) ? sntz($_POST['_block_res_id'][$k]) : $makeID();

	$b_ext_flg = ($_POST['_block_res_id'][$k]) ? true : false;

	// ブロックID
	$bid = ($_POST['_block_id'][$k]) ? sntz($_POST['_block_id'][$k]) : "";

	
	$vo++;
	if($b_ext_flg){
		$bsql = "UPDATE ";
	}else{
		$bsql = "INSERT INTO ";
	}
	$bsql .= " ".CP1_VALUES_LST."
		SET
			RES_ID = '$b_res_id',
			PAGE_ID = '$res_id',
			BLOCK_ID = '$bid',
			VIEW_ORDER = '$vo',
			UPD_DATE = NOW(),
			DEL_FLG = '0'
	";
	if($b_ext_flg){
		$bsql .= "WHERE (DEL_FLG = '0') AND (RES_ID = '".$b_res_id."') ";
	}

	$db_result = dbOpe::regist($bsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました : err03<hr>{$db_result} <hr>".$bsql);
	
	$_b_res_ids[$k] = $b_res_id;

}


?>