<?php
/*******************************************************************************
会員メール配信 バックオフィス（MySQL対応版）
メインコントローラー

Logic：指定された検索条件を元にＤＢより情報を取得

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

// メールアドレスチェック用正規表現　簡易なものです。
$email_pattern = '^([a-zA-Z0-9])+([a-zA-Z0-9\\._\\+-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\\._-]+)+$';

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["action"]):
case "send_data":

	$sql = "
	SELECT
		MEMBER_ID,
		NAME,
		EMAIL
	FROM
		" . MEMBER_LST . "
	WHERE
		SENDMAIL_FLG = '1'
	GROUP BY
		EMAIL
	ORDER BY
		UPD_DATE ASC
	";
	break;

default:

// SQLベース

#--------------------------------------------------------------------------------
# 選択された検索項目により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------

// SQLベース

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
	$sqlwhere = "";

	if($status == "pagen"){//ページネーションだった場合
		$sqlwhere = $_SESSION['pagen'];//検索条件を渡す

		//ページネーションでの送られてきたデータを処理していく

		///////////////////////////////////////////////////////////////////////
		//メールを送るのIDを配列ごとに分ける（メールを配信）
			$nio_stock = explode(",", $nd_id_ok_stock);

			for($i=0;$i < count($nio_stock);$i++){

				if($nio_stock[$i]){//データが存在すれば処理を行う

				// 対象記事IDデータのチェック
					if(ereg("^([0-9]{10,})-([0-9]{6})$",$nio_stock[$i]) && !empty($nio_stock[$i])){

						//送信にチェックの処理をする
						$db_result = dbOpe::regist("UPDATE " . MEMBER_LST . " SET SENDMAIL_FLG = '1' WHERE(MEMBER_ID = '".$nio_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
						if($db_result)die("DB登録失敗しました<hr>{$db_result}");

					}

				}

			}

		///////////////////////////////////////////////////////////////////////
		//メールを送るのIDを配列ごとに分ける（メール配信拒否）

			$nin_stock = explode(",", $nd_id_ng_stock);

			for($i=0;$i < count($nin_stock);$i++){

				if($nin_stock[$i]){//データが存在すれば処理を行う

				// 対象記事IDデータのチェック
					if(ereg("^([0-9]{10,})-([0-9]{6})$",$nin_stock[$i]) && !empty($nin_stock[$i])){

						//送信にチェックなしの処理をする
						$db_result = dbOpe::regist("UPDATE " . MEMBER_LST . " SET SENDMAIL_FLG = '0' WHERE(MEMBER_ID = '".$nin_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
						if($db_result)die("DB登録失敗しました<hr>{$db_result}");

					}

				}

			}

	}else{//それ以外は検索から来た場合と判定
//お名前
if($name):
	$sqlwhere .= "
	AND
		(NAME LIKE '%" . utilLib::strRep($name,5) . "%')
	";
endif;

//E-MAIL
if($email):
	$sqlwhere .= "
	AND
		(EMAIL = '" . utilLib::strRep($email,5) . "')
	";
endif;

// 年代
if($generation):
	$sqlwhere .= "
	AND
		(GENERATION_CD = '" . utilLib::strRep($generation,5) . "')
	";
endif;

// メルマガ配信
if($mailmag != ""):
	$sqlwhere .= "
	AND
		(SENDMAIL = '" . utilLib::strRep($mailmag,5) . "')
	";
endif;

	$_SESSION['pagen'] = $sqlwhere;//検索条件をセッションに保存させる。
	}

/////////////////
// 不正メールアドレスをリストアップ
$sqlMISS = $sql.$sqlwhere."
AND (EMAIL NOT REGEXP '" . $email_pattern . "')

ORDER BY
	" . MEMBER_LST . ".INS_DATE ASC
LIMIT
	0," . MEMBER_DBMAX_CNT . "
";
$fetchMISS = dbOpe::fetch($sqlMISS,DB_USER,DB_PASS,DB_NAME,DB_SERVER);


/////////////////
// 不正メールアドレスを省く

	// ページ番号の設定(GET受信データがなければ1をセット)
	if(empty($p) or !is_numeric($p))$p=1;
	$st = ($p-1) * MEMBER_DISP_MAXROW;

$sql = $sql.$sqlwhere."
AND
	(EMAIL REGEXP '" . $email_pattern . "')
ORDER BY
	" . MEMBER_LST . ".INS_DATE ASC
LIMIT
	$st," . MEMBER_DISP_MAXROW . "
";

/////////////////
//出力する全体の数を取得する
	$sqlcnt = "
	SELECT
		COUNT(*) AS CNT
	FROM
		" . MEMBER_LST . "
	WHERE
		(DEL_FLG = '0')
		$sqlwhere
	AND
		(EMAIL REGEXP '" . $email_pattern . "')
	";

	$fetchCNT = dbOpe::fetch($sqlcnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

/////////////////
// 検索に該当しなかった人はメール送信拒否にしておく
	if($status == "search_result"){

			//前回の送信内容を反映させる。
				$sqlng = "
				UPDATE
					" . MEMBER_LST . "
				SET
					SENDMAIL_FLG = OLD_SENDMAIL_FLG
				";

			$db_result = dbOpe::regist($sqlng,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//検索に該当しなかった人はフラグを落とす
				$sqlng = "
				UPDATE
					" . MEMBER_LST . "
				SET
					SENDMAIL_FLG   = '0'
			    WHERE
			     !(
			     ".preg_replace('/AND/', '',$sqlwhere ,1)."
			     )
				";

			$db_result = dbOpe::regist($sqlng,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	}

/*
/////////////////
// ソート条件　リミット条件　(登録日時順にグループ化)
$sql .="
ORDER BY
	" . MEMBER_LST . ".INS_DATE ASC
LIMIT
	0," . MEMBER_DBMAX_CNT . "
";
*/
	break;

endswitch;
	
	
	//ここの時点で不正メールアドレスの人は配信しないにデータベースを変更させる。
	//前回のOLD_SENDMAIL_FLGの処理前だと送る判定になってしまう。
	for($i=0;$i<count($fetchMISS);$i++){

	$sqlng = "
	UPDATE
		" . MEMBER_LST . "
	SET
		SENDMAIL_FLG   = '0'
	WHERE
		(MEMBER_ID = '".$fetchMISS[$i]['MEMBER_ID']."')
	";

		#=================================================================================
		# SQL実行
		#=================================================================================
		if(!empty($sqlng)){
			$db_result = dbOpe::regist($sqlng,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result)die("DB登録失敗しました<hr>{$db_result}");
		}
	}

// 新しく指定された検索条件をセッションに格納
// エラー等で戻ってきた場合、値を保持しておくため。

$_SESSION["search_cond"]["name"] = $name;
$_SESSION["search_cond"]["email"] = $email;
$_SESSION["search_cond"]["generation"] = $generation;
$_SESSION["search_cond"]["mailmag"] = $mailmag;

// DBの取得データをセッションに格納
$fetchCustList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

if(count($fetchCustList) > MEMBER_DBMAX_CNT){
	$error_msg = "最大送信可能件数を超えております。<br>検索条件を絞って". MEMBER_DBMAX_CNT . "件以内に減らしてください。";
}

?>
