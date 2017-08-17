<?php
/*******************************************************************************
BBSプログラム

	GET LOG DATA

*******************************************************************************/

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

	$sql = "
		SELECT
			WORDS
		FROM
			".NG_WORDS."
		WHERE
			(KEY_ID = '9999')
		";
	// ＳＱＬを実行
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($regist_type == "new"){

		$moji = $name.$title.$comment;

	}elseif($regist_type == "res_new"){

		$moji = $name.$comment;

	}

	$ng_word = array();
	$ng_word = explode(",",$fetch[0]["WORDS"]);
	$ngcount = "";

	foreach($ng_word as $value){

	if($ngcount)break;

	$ngcount = substr_count($moji,$value);

	}

if($ngcount>0)$error_mes .= "禁止されている言葉が入っています。<br>\n";

?>
