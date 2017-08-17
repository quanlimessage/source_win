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

	// POSTデータの受け取りと共通な文字列処理
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["action"]):
case "update":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	$sql = "
	SELECT
		MEMBER_ID,
		SENDMAIL,
		NAME,
		KANA,
		ZIP_CD1,
		ZIP_CD2,
		STATE,
		ADDRESS1,
		ADDRESS2,
		TEL,
		FAX,
		EMAIL,
		GENERATION_CD,
		JOB_CD
	FROM
		" . MEMBER_LST . "
	WHERE
		(MEMBER_ID = '$res_id')
	";

	break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

	// 一覧表示用データの取得（リスト順番は設定ファイルに従う）
	$sql = "
	SELECT
		MEMBER_ID,
		NAME,
		EMAIL,
		SENDMAIL,
		SENDMAIL_FLG,
		YEAR(INS_DATE) AS Y,
		MONTH(INS_DATE) AS M,
		DAYOFMONTH(INS_DATE) AS D
	FROM
		" . MEMBER_LST . "
	WHERE
		(DEL_FLG = '0')
	";

/////////////////
// 抽出条件付加

//検索画面以外から来た場合セッションの内容を入れる
if($action != "search_result"){

$name = $_SESSION["search_cond"]["name"];
$email = $_SESSION["search_cond"]["email"];
$generation = $_SESSION["search_cond"]["generation"];
$mailmag = $_SESSION["search_cond"]["mailmag"];

}
	//条件の初期化
	$condition_sql = "";

//お名前
if($name):
	$condition_sql .= "
	AND
		(NAME LIKE '%" . utilLib::strRep($name,5) . "%')
	";
endif;

//E-MAIL
if($email):
	$condition_sql .= "
	AND
		(EMAIL = '" . utilLib::strRep($email,5) . "')
	";
endif;

// 年代
if($generation):
	$condition_sql .= "
	AND
		(GENERATION_CD = '" . utilLib::strRep($generation,5) . "')
	";
endif;

// メルマガ配信
if($mailmag != ""):
	$condition_sql .= "
	AND
		(SENDMAIL = '" . utilLib::strRep($mailmag,5) . "')
	";
endif;

//抽出条件を付与する
$sql .= $condition_sql;

/////////////////
// ソート条件　リミット条件　(登録日時順にグループ化)
$sql .="
ORDER BY
	INS_DATE ASC
LIMIT
	0," . MEMBER_DBMAX_CNT . "
";

// 新しく指定された検索条件をセッションに格納
// エラー等で戻ってきた場合、値を保持しておくため。

$_SESSION["search_cond"]["name"] = $name;
$_SESSION["search_cond"]["email"] = $email;
$_SESSION["search_cond"]["generation"] = $generation;
$_SESSION["search_cond"]["mailmag"] = $mailmag;

//csv出力で使用する
$_SESSION["condition"] = $condition_sql;

	break;

endswitch;

// ＳＱＬを実行
$fetch = $PDO -> fetch($sql);

if(count($fetchCustList) > MEMBER_DBMAX_CNT){
	$error_msg = "最大送信可能件数を超えております。<br>検索条件を絞って". MEMBER_DBMAX_CNT . "件以内に減らしてください。";
}

?>
