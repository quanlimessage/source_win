<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	// DB格納用のSQL文
	$sql = "
	UPDATE
		" . MEMBER_LST . "
	SET
		SENDMAIL = '$mailmag',
		NAME = '$name',
		KANA = '$kana',
		TEL = '$tel',
		FAX = '$fax',
		ZIP_CD1 = '$zip1',
		ZIP_CD2 = '$zip2',
		STATE = '$state',
		ADDRESS1 = '$address1',
		ADDRESS2 = '$address2',
		EMAIL = '$email',
		GENERATION_CD = '$generation',
		JOB_CD = '$job',
		UPD_DATE = NOW()
	WHERE
		(MEMBER_ID = '$res_id')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////
// 新規登録

	// 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
	$res_id = $makeID();

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM " . MEMBER_LST . " WHERE(DEL_FLG = '0')";
	$fetchCNT = $PDO -> fetch($cnt_sql);

	if($fetchCNT[0]["CNT"] < MEMBER_DBMAX_CNT):

	$sql = "
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
			'$res_id',
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
		)";

	else:
		header("Location: ./");
	endif;

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
$PDO -> regist($sql);

?>
