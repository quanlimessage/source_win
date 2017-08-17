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

    $sqlecnt = "
	SELECT
		COUNT(*) AS CNT
	FROM
		BLOG_ENTRY_LST
	WHERE
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	";
// ＳＱＬを実行
$fetche_cnt = $PDO->fetch($sqlecnt);

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch ($_POST["act"]):

case "new_entry":

    // 新規エントリーの場合カテゴリープルダウンメニューの情報のみ取得
    $sql = "SELECT CATEGORY_CODE,CATEGORY_NAME FROM BLOG_CATEGORY_MST WHERE (DEL_FLG = '0') ORDER BY VIEW_ORDER";

    break;
case "update":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

    // POSTデータの受け取りと共通な文字列処理
    extract(utilLib::getRequestParams("post", [8,7,1,4]));

    // 対象記事IDデータのチェック
    if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res_id)||empty($res_id)) {
        die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
    }

    $sql = "
	SELECT
		RES_ID,
		CATEGORY_CODE,
		TITLE,
		CONTENT,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		EXTENTION1,EXTENTION2,EXTENTION3,EXTENTION4,
		TB_FLG,
		DISPLAY_FLG
	FROM
		BLOG_ENTRY_LST
	WHERE
		(RES_ID = '$res_id')
	";

    $sql_ca = "SELECT CATEGORY_CODE,CATEGORY_NAME FROM BLOG_CATEGORY_MST WHERE (DEL_FLG = '0') ORDER BY VIEW_ORDER";
    $fetch_Ca = $PDO->fetch($sql_ca);

    break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

    // 一覧表示用データの取得（リスト順番は設定ファイルに従う）

    // 抽出開始位置の指定
    if ($_POST) {
        extract(utilLib::getRequestParams("post", [8,7,1,4,5]));
    }
    if ($_GET) {
        extract(utilLib::getRequestParams("get", [8,7,1,4,5]));
    }
    if (empty($p) or !is_numeric($p)) {
        $p=1;
    }
    $p  = urldecode($p);

    $st = ($p-1) * DISP_ENTRY_MAXROW;//表示位置を取得

    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		BLOG_CATEGORY_MST.CATEGORY_NAME,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_ENTRY_LST.EXTENTION1
	FROM
		BLOG_ENTRY_LST,BLOG_CATEGORY_MST
	WHERE
		(BLOG_ENTRY_LST.CATEGORY_CODE = BLOG_CATEGORY_MST.CATEGORY_CODE)
	AND
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	";

    //全件数を取得
    $fetchALL = $PDO->fetch($sql);

    // カテゴリーコードが送信されてきたらカテゴリーごとに一覧情報を表示する
    if ($_REQUEST["category_code"]) {
        $category_code = urldecode($_REQUEST["category_code"]);
        $sql .= "AND (BLOG_ENTRY_LST.CATEGORY_CODE = '".$category_code."')";
    }

    $sql .= "ORDER BY DISP_DATE DESC";

    $sql_ca = "SELECT CATEGORY_CODE,CATEGORY_NAME FROM BLOG_CATEGORY_MST WHERE (DEL_FLG = '0') ORDER BY VIEW_ORDER";

    $fetch_Ca = $PDO->fetch($sql_ca);

    //全件数を取得
    $fetchCNT = $PDO->fetch($sql);

    //表示する部分を設定
    $sql .= "
	LIMIT
		".$st.",".DISP_ENTRY_MAXROW."
	";

endswitch;

// ＳＱＬを実行
$fetch = $PDO->fetch($sql);
