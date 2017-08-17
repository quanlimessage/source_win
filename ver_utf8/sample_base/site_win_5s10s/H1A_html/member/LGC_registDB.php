<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	DB情報の更新

*******************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=================================================================================
# カウントSQL
#=================================================================================
$sql_cnt = "
	SELECT
		COUNT(*) AS CNT
	FROM
		" . MEMBER_LST . "
	WHERE
		(DEL_FLG = '0')
	";
	$fetchCNT = dbOpe::fetch($sql_cnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=================================================================================
# 指定件数以上だったらインサートしない。
# 指定件数以内ならDBに突っ込む
#=================================================================================
if($fetchCNT[0]["CNT"] > MEMBER_DBMAX_CNT){

	$alert = MEMBER_DBMAX_CNT . "レコードを超えましたので、DBに登録は行いませんでした。";

}else{

	#=================================================================================
	# SQL組立
	#=================================================================================

	//メンバーIDの発番
	$member_id = $makeID();

	$sql1 = "
	INSERT INTO " . MEMBER_LST . "(
			MEMBER_ID,
			SENDMAIL,
			SENDMAIL_FLG,
			NAME,
			KANA,
			TEL,
			FAX,
			ZIP_CD1,
			ZIP_CD2,
			STATE,
			ADDRESS1,
			ADDRESS2,
			EMAIL,
			GENERATION_CD,
			JOB_CD,
			INS_DATE,
			UPD_DATE,
			DEL_FLG
		)VALUES(
			'$member_id',
			'$mailmag',
			'$mailmag',
			'$name',
			'$kana',
			'$tel',
			'$fax',
			'$zip1',
			'$zip2',
			'$state',
			'$address1',
			'$address2',
			'$email',
			'$generation',
			'$job',
			NOW(),
			NOW(),
			'0'
		)
	";

	#=================================================================================
	# SQL実行
	#=================================================================================
	if(!empty($sql1)){
		$db_result = dbOpe::regist($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("DB更新失敗しました<hr>{$db_result}");
	}

}

?>
