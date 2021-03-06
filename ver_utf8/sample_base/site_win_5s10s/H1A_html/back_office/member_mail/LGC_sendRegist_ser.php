<?php
/*******************************************************************************
会員メール配信 バックオフィス（MySQL対応版）
Logic：配信チェックの内容をDBに格納する

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("Location: ../"); exit();
}

///////////////////////////////////////////////////////////////////////
//メールを送るのIDを配列ごとに分ける（メールを配信）
	$nio_stock = explode(",", $sm_nd_id_ok_stock);

	for($i=0;$i < count($nio_stock);$i++){

		if($nio_stock[$i]){//データが存在すれば処理を行う

		// 対象記事IDデータのチェック
			if(preg_match("/^([0-9]{10,})-([0-9]{6})$/",$nio_stock[$i]) && !empty($nio_stock[$i])){

				//送信にチェックの処理をする
				$db_result = dbOpe::regist("UPDATE " . MEMBER_LST . " SET SENDMAIL_FLG = '1' WHERE(MEMBER_ID = '".$nio_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

			}

		}

	}

///////////////////////////////////////////////////////////////////////
//メールを送るのIDを配列ごとに分ける（メール配信拒否）

	$nin_stock = explode(",", $sm_nd_id_ng_stock);

	for($i=0;$i < count($nin_stock);$i++){

		if($nin_stock[$i]){//データが存在すれば処理を行う

		// 対象記事IDデータのチェック
			if(preg_match("/^([0-9]{10,})-([0-9]{6})$/",$nin_stock[$i]) && !empty($nin_stock[$i])){

				//送信にチェックなしの処理をする
				$db_result = dbOpe::regist("UPDATE " . MEMBER_LST . " SET SENDMAIL_FLG = '0' WHERE(MEMBER_ID = '".$nin_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

			}

		}

	}

#=================================================================================
# 前回送信のフラグを更新させる
#=================================================================================

$sql = "
UPDATE
	" . MEMBER_LST . "
SET
	OLD_SENDMAIL_FLG = SENDMAIL_FLG
";
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");
}

#=================================================================================
# メールを送信する件数を調べる
#=================================================================================

	$sql_cnt = "
	SELECT
		SENDMAIL_FLG,
		MEMBER_ID
	FROM
		" . MEMBER_LST . "
	WHERE
		SENDMAIL_FLG = '1'
	ORDER BY
		UPD_DATE ASC
	";

// DBの取得データをセッションに格納
$fetchCNT = dbOpe::fetch($sql_cnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
