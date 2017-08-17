<?php
/*******************************************************************************
ALL-INTERNETBLOG

    ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#--------------------------------------------------------------------------------
# データの処理
#--------------------------------------------------------------------------------
// GETまたはPOSTの判定して処理を変える
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $method2 = "post";
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    $method2 = "get";
}

// データの受け取りと共通な文字列処理
@extract(utilLib::getRequestParams("$method2", [8,7,1,4,5]));

    if (!empty($id)) {
        $ck_data = substr($id, 0, 1);//調べるデータの頭文字から何のデータか判断する為、頭文字を取得する
        $get_data = substr($id, 1);//頭文字以外を取ったデータを取得する

        if ($ck_data == "r") {
            $res = $get_data;
        } elseif ($ck_data == "u") {
            $uid = $get_data;
        } elseif ($ck_data == "n") {
            $next = $get_data;
        } elseif ($ck_data == "p") {
            $prev = $get_data;
        } elseif ($ck_data == "c") {
            $ca   = $get_data;
        } elseif ($ck_data == "l") {
            $log  = $get_data;
        } elseif ($ck_data == "d") {
            $day  = $get_data;
            $act = "date";
        }
    }

// GETデーターのデコード
$res  = urldecode($res);
$next = urldecode($next);
$prev = urldecode($prev);
$ca   = urldecode($ca);
$log  = urldecode($log);
$day  = urldecode($day);

// 単体記事表示
$uid  = urldecode($uid);

// 検索された文字をデコード
if ($_POST["query"]) {
    $query = mb_convert_encoding(urldecode($_POST["query"]), "UTF-8", auto);
} elseif ($_GET["query"]) {
    $query = mb_convert_encoding(urldecode($_GET["query"]), "UTF-8", auto);
}

$q_comment = "";
// 送信された文字が半角1文字では検索できないようにする。
if (!empty($search)):
    if (strlen($query) == 1) {
        $query = "";
        $q_comment    = "検索文字が半角1文字だけでは検索できません。";
    } elseif (strlen($query) == 0) {

        // 検索文字がなかったらエラーメッセージを表示
        $query = "";
        $q_comment    = "検索文字がありません。";
    }
endif;

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch ($act):

case "date":

    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		BLOG_ENTRY_LST.CATEGORY_CODE,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.TB_FLG,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_CATEGORY_MST.CATEGORY_NAME
	FROM
			BLOG_ENTRY_LST
		INNER JOIN
			BLOG_CATEGORY_MST
		ON
			(BLOG_CATEGORY_MST.CATEGORY_CODE = BLOG_ENTRY_LST.CATEGORY_CODE)
	WHERE
		(DATE_FORMAT(BLOG_ENTRY_LST.DISP_DATE, '%Y%m%d') = '".$day."')
	AND
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	AND
		(BLOG_ENTRY_LST.DISPLAY_FLG = '1')
	";

    $fetchList = $PDO->fetch($sql);

    break;
case "com":
///////////////////////////////////////////
// 更新指示のあった該当記事データの取得

    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		BLOG_ENTRY_LST.CATEGORY_CODE,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_CATEGORY_MST.CATEGORY_NAME
	FROM
			BLOG_ENTRY_LST
		INNER JOIN
			BLOG_CATEGORY_MST
		ON
			(BLOG_CATEGORY_MST.CATEGORY_CODE = BLOG_ENTRY_LST.CATEGORY_CODE)
	WHERE
		(BLOG_ENTRY_LST.RES_ID = '".$res."')
	AND
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	AND
		(BLOG_ENTRY_LST.DISPLAY_FLG = '1')
	";

    $fetchList = $PDO->fetch($sql);

#-----------------------------------------------------------
# ・コメント入力フォームより記事の投稿があったら内容をDBに格納
# ・アクセス制限処理
#-----------------------------------------------------------
    if ($regist_type == "input_data"):

        #--------------------------------------------------------
        # パラメーターチェック
        # 投稿され始めてから１時間以内なら投稿可
        #--------------------------------------------------------
        // $sec = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
        // if( (intval($randdata) <= $sec) && ( (intval($randdata)+360) >= $sec) ){
        if ($randdata == "78dbh4a2nd7") {

            // 対象記事IDデータのチェック
            if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res)||empty($res)) {
                die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res}");
            } else {
                $comment_id = $makeID();

                $sql = "
				INSERT INTO BLOG_COMMENT_LST(
					RES_ID,COMMENT_ID,TITLE,NAME,EMAIL,CONTENT,ENTRY_TITLE,CATEGORY_CODE,IP,DISP_DATE,INS_DATE,DISPLAY_FLG
				)
				VALUES(
					'".$fetchList[0]['RES_ID']."','$comment_id','$title','$name','$e_mail','$content','".$fetchList[0]['TITLE']."','".$fetchList[0]['CATEGORY_CODE']."','".$_SERVER['REMOTE_ADDR']."',NOW(),NOW(),'0'
				)";

                // アクセス制限
                $sql_ip = "SELECT IP_ADD FROM BLOG_TITLE WHERE T_ID = '1'";
                $fetchIp = $PDO->fetch($sql_ip);

                $ip = explode(",", $fetchIp[0]["IP_ADD"]);

                for ($i=0;$i<count($ip);$i++) {
                    // IPアドレスがアクセス制限したIPの対象ならsql文を破棄してコメント登録させない
                    if ($ip[$i] == $_SERVER['REMOTE_ADDR']) {
                        $sql = "";
                        $title = "";
                    }
                }

                // ＳＱＬを実行
                if (!empty($sql) && !empty($title)) {
                    $PDO->regist($sql);
                }
            }

            header("Location: {$_SERVER['PHP_SELF']}?act=com&res=".urlencode($fetchList[0]['RES_ID'])."&regist_type=1");
        }

    endif;

    break;
default:
///////////////////////////////////////////
// 記事リスト一覧用データの取得と

// ページ番号の設定(GET受信データがなければ1をセット)
if (empty($p) or !is_numeric($p)) {
    $p=1;
}

// 抽出開始位置の指定
$st = ($p-1) * $fetch_title[0]['DISP_MAXROW'];

    // 一覧表示用データの取得（リスト順番は設定ファイルに従う）
    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.E_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		BLOG_ENTRY_LST.TB_FLG,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_CATEGORY_MST.CATEGORY_NAME
	FROM
			BLOG_ENTRY_LST
		INNER JOIN
			BLOG_CATEGORY_MST
		ON
			(BLOG_CATEGORY_MST.CATEGORY_CODE = BLOG_ENTRY_LST.CATEGORY_CODE)
	WHERE
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	AND
		(BLOG_ENTRY_LST.DISPLAY_FLG = '1')
	";

    // カテゴリーコードが送信されてきたらカテゴリーごとに一覧情報を表示する
    if (!empty($uid)) {
        if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $uid)||empty($uid)) {
            $uid = "0000000000-000000";
        }
        $sql .= "AND (BLOG_ENTRY_LST.RES_ID = '".$uid."')";
    }

    // カテゴリーコードが送信されてきたらカテゴリーごとに一覧情報を表示する
    if (!empty($ca)) {
        $sql .= "AND (BLOG_ENTRY_LST.CATEGORY_CODE = '".$ca."')";
    }

    // 過去ログ(年、月)が送信されたら過去ログ一覧を表示
    if (!empty($log)) {
        $sql .= "AND (EXTRACT(YEAR_MONTH FROM BLOG_ENTRY_LST.DISP_DATE) = '".$log."')";
    }

    // 検索キーワードが送信されたら
    if (!empty($query)) {
        $sql .= "
		AND
		(
		(BLOG_ENTRY_LST.TITLE LIKE '%".utilLib::strRep($query, 5)."%')
		OR
		(BLOG_ENTRY_LST.CONTENT LIKE '%".utilLib::strRep($query, 5)."%')
		)
		";
    }

    $sql .= "ORDER BY BLOG_ENTRY_LST.DISP_DATE DESC";

    // ページ遷移用リミット句
    $sql .= " LIMIT ".$st.",".$fetch_title[0]['DISP_MAXROW']."";

    $fetchList = $PDO->fetch($sql);

    $sql_ca = "SELECT CATEGORY_CODE,CATEGORY_NAME FROM BLOG_CATEGORY_MST WHERE (DEL_FLG = '0') ORDER BY VIEW_ORDER";

    $fetch_Ca = $PDO->fetch($sql_ca);

    // 記事現在登録数取得
    $tnum_sql="
	SELECT
		COUNT(RES_ID) AS CNT
	FROM
		BLOG_ENTRY_LST
	WHERE
		(DEL_FLG = '0')
	AND
		(DISPLAY_FLG = '1')
	";

    if (!empty($uid)) {
        if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $uid)||empty($uid)) {
            $uid = "0000000000-000000";
        }
        $tnum_sql .= "AND (BLOG_ENTRY_LST.RES_ID = '".$uid."')";
    }
    // カテゴリーコードが送信されてきたらカテゴリーごとに一覧情報を表示する
    if (!empty($ca)) {
        $tnum_sql .= "AND (BLOG_ENTRY_LST.CATEGORY_CODE = '".$ca."')";
    }

    // 過去ログ(年、月)が送信されたら過去ログ一覧を表示
    if (!empty($log)) {
        $tnum_sql .= "AND (EXTRACT(YEAR_MONTH FROM BLOG_ENTRY_LST.DISP_DATE) = '".$log."')";
    }

    // 検索キーワードが送信されたら
    if (!empty($query)) {
        $tnum_sql .= "
		AND
		(
		(BLOG_ENTRY_LST.TITLE LIKE '%".utilLib::strRep($query, 5)."%')
		OR
		(BLOG_ENTRY_LST.CONTENT LIKE '%".utilLib::strRep($query, 5)."%')
		)
		";
    }

    $fetchCnt = $PDO->fetch($tnum_sql);

    for ($i = 0; $i < count($fetchList);$i++) {
        $tb_sql="
		SELECT
			ENTRY_TITLE,
			BLOG_TITLE,
			BLOG_DETAIL,
			BLOG_URL,
			YEAR(INS_DATE) AS Y,
			MONTH(INS_DATE) AS M,
			DAYOFMONTH(INS_DATE) AS D
		FROM
			BLOG_TRACKBACK_LST
		WHERE
			(DEL_FLG = '0')
		AND
			(E_ID = '".$fetchList[$i]['E_ID']."')
		";

        $fetchTB = $PDO->fetch($tb_sql);
        $fetchList[$i]['__TB'] = $fetchTB;
    }

endswitch;
