<?php
/*******************************************************************************
ALL-INTERNET BLOG

Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
if (!$accessChk) {
    header("Location: ../");
    exit();
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch ($_POST["action"]):

case "update":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

    // POSTデータの受け取りと共通な文字列処理
    extract(utilLib::getRequestParams("post", [8,7,1,4]));

    // 対象記事IDデータのチェック
    if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $comment_id)||empty($comment_id)) {
        die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
    }

    $sql = "
	SELECT
		RES_ID,
		COMMENT_ID,
		TITLE,
		NAME,
		EMAIL,
		ENTRY_TITLE,
		CONTENT,
		IP,
		DISP_DATE,
		DISPLAY_FLG
	FROM
		BLOG_COMMENT_LST
	WHERE
		(COMMENT_ID = '$comment_id')
	";

    break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

    // 一覧表示用データの取得（リスト順番は設定ファイルに従う）
    $sql = "
	SELECT
		RES_ID,
		COMMENT_ID,
		TITLE,
		CONTENT,
		ENTRY_TITLE,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		VIEW_ORDER,
		DISPLAY_FLG
	FROM
		BLOG_COMMENT_LST
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		DISP_DATE DESC
	";

    // 抽出開始位置の指定
    if ($_GET) {
        extract(utilLib::getRequestParams("get", [8,7,1,4,5]));
    }
    if (empty($p) or !is_numeric($p)) {
        $p=1;
    }
    $p  = urldecode($p);

    $st = ($p-1) * DISP_COMMENT_MAXROW;//表示位置を取得

    $sqlCNT = "
	SELECT
		COMMENT_ID
	FROM
		BLOG_COMMENT_LST
	WHERE
		(DEL_FLG = '0')
	";

    //全件数を取得
    $fetchCNT = $PDO->fetch($sql);

    //表示する部分を設定
    $sql .= "
	LIMIT
		".$st.",".DISP_COMMENT_MAXROW."
	";

endswitch;

// ＳＱＬを実行
$fetch = $PDO->fetch($sql);
