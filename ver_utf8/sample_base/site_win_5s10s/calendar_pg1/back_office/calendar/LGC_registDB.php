<?php
/*******************************************************************************
更新プログラム

	DB登録・更新処理

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# POST受信データの処理
#--------------------------------------------------------------------------------
// POSTデータの受け取りと共通な文字列処理
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

// 直接登録日が指定された場合は指定の年月を表示すつ
if($id_select == 1):

	$sql_select = "SELECT YEAR,MONTH FROM SCHEDULE WHERE (SCHEDULE_ID = '$schedule_id') AND (DEL_FLG = '0')";
	$fetch_select = dbOpe::fetch($sql_select,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	$y = $fetch_select[0]["YEAR"];
	$m = $fetch_select[0]["MONTH"];

else:

// 直接していでなければここから
// 年の指定がなければ初期値を今年
if(!$y){
	$y = date('Y');
}

// 月の指定がなければ初期値を今年
if(strlen($m) == 0){
	$m = date('n');
}else{
	// もし月が０で送信されてきたら月を１２にして年を１マイナス
	if($m == '0'){
		$m = 12;
		$y = $y - 1;
	}

	// もし月が１２月以上(１３)で送信されたら月を１にして年を１プラス
	if($m > 12){
		$m = 1;
		$y = $y + 1;
	}
}

endif;

// 新規追加か更新か判別の為にデータベースの情報を確認する
$sql = "SELECT SCHEDULE_ID FROM SCHEDULE WHERE (YEAR = '$y') AND (MONTH = '$m') AND (DEL_FLG = '0')";
// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// データがなければ当月のデータ作成新規登録
if(empty($fetch[0]["SCHEDULE_ID"])){
	$insert_type = "new";
	$schedule_id = $makeID();

	#-----------------------------------------------------
	# 初期化
	#-----------------------------------------------------
	$sql = "
	INSERT INTO ".SCHEDULE." SET
		DAY_1 = '0',
		DAY_2 = '0',
		DAY_3 = '0',
		DAY_4 = '0',
		DAY_5 = '0',
		DAY_6 = '0',
		DAY_7 = '0',
		DAY_8 = '0',
		DAY_9 = '0',
		DAY_10 = '0',
		DAY_11 = '0',
		DAY_12 = '0',
		DAY_13 = '0',
		DAY_14 = '0',
		DAY_15 = '0',
		DAY_16 = '0',
		DAY_17 = '0',
		DAY_18 = '0',
		DAY_19 = '0',
		DAY_20 = '0',
		DAY_21 = '0',
		DAY_22 = '0',
		DAY_23 = '0',
		DAY_24 = '0',
		DAY_25 = '0',
		DAY_26 = '0',
		DAY_27 = '0',
		DAY_28 = '0',
		DAY_29 = '0',
		DAY_30 = '0',
		DAY_31 = '0',
		COMM_1 = '',
		COMM_2 = '',
		COMM_3 = '',
		COMM_4 = '',
		COMM_5 = '',
		COMM_6 = '',
		COMM_7 = '',
		COMM_8 = '',
		COMM_9 = '',
		COMM_10 = '',
		COMM_11 = '',
		COMM_12 = '',
		COMM_13 = '',
		COMM_14 = '',
		COMM_15 = '',
		COMM_16 = '',
		COMM_17 = '',
		COMM_18 = '',
		COMM_19 = '',
		COMM_20 = '',
		COMM_21 = '',
		COMM_22 = '',
		COMM_23 = '',
		COMM_24 = '',
		COMM_25 = '',
		COMM_26 = '',
		COMM_27 = '',
		COMM_28 = '',
		COMM_29 = '',
		COMM_30 = '',
		COMM_31 = '',
		SCHEDULE_ID = '$schedule_id',
		YEAR        = '$y',
		MONTH       = '$m',
		INS_DATE    = NOW(),
		DEL_FLG     = '0'
	";

	// ＳＱＬを実行
	if(!empty($sql)){
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	}

}

?>