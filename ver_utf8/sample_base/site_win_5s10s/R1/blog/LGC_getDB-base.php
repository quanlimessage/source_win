<?php
/*******************************************************************************
ALL-INTERNETBLOG

    ブログ設定情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#---------------------------------------------------------------------------
# 常に表示する内容を取得
# ・タイトル＆サブタイトル 外部リンク
# ・カテゴリー一覧
# ・新着情報(コンテンツの新しい順に5件)
# ・過去ログ過去の記事を年月でリンク
#---------------------------------------------------------------------------

    // タイトル表示
    $sql_title = "
	SELECT
		TITLE,
		SUB_TITLE,
		DIR,
		IMG_SELECT,
		HEADER_IMG,
		LINK_TITLE1,
		LINK_URL1,
		LINK_TITLE2,
		LINK_URL2,
		LINK_TITLE3,
		LINK_URL3,
		LINK_TITLE4,
		LINK_URL4,
		LINK_TITLE5,
		LINK_URL5,
		LINK_TITLE6,
		LINK_URL6,
		LINK_TITLE7,
		LINK_URL7,
		LINK_TITLE8,
		LINK_URL8,
		LINK_TITLE9,
		LINK_URL9,
		LINK_TITLE10,
		LINK_URL10,
		DISP_MAXROW
	FROM
		BLOG_TITLE
	WHERE
		T_ID = '1'";

    $fetch_title = $PDO->fetch($sql_title);

    // カテゴリー
    $sql_category = "
		SELECT
			BLOG_CATEGORY_MST.CATEGORY_CODE,
			BLOG_CATEGORY_MST.CATEGORY_NAME,
			COUNT(BLOG_ENTRY_LST.RES_ID) AS C_CNT
		FROM
			BLOG_CATEGORY_MST,BLOG_ENTRY_LST
		WHERE
			(BLOG_CATEGORY_MST.DEL_FLG = '0')
		AND
			(BLOG_CATEGORY_MST.DISPLAY_FLG = '1')
		AND
			(BLOG_ENTRY_LST.DEL_FLG = '0')
		AND
			(BLOG_ENTRY_LST.DISPLAY_FLG = '1')
		AND
			(BLOG_CATEGORY_MST.CATEGORY_CODE = BLOG_ENTRY_LST.CATEGORY_CODE)
		GROUP BY BLOG_ENTRY_LST.CATEGORY_CODE
		ORDER BY BLOG_CATEGORY_MST.VIEW_ORDER
	";

    $fetchCategory = $PDO->fetch($sql_category);

    // 新着情報
    $sql_new = "
		SELECT RES_ID,TITLE,MONTH(DISP_DATE) AS M,DAYOFMONTH(DISP_DATE) AS D
	FROM
		BLOG_ENTRY_LST
	WHERE
		(DEL_FLG = '0')
	AND
		(DISPLAY_FLG = '1')
	ORDER BY
		DISP_DATE DESC
	LIMIT
		0 , ".$fetch_title[0]['DISP_MAXROW']."
	";

    $fetchNew = $PDO->fetch($sql_new);

    // 過去ログ
    $sql_kako = "
	SELECT
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		COUNT(RES_ID) AS KAKO_CNT
	FROM
		BLOG_ENTRY_LST
	WHERE
		(DEL_FLG = '0')
	AND
		(DISPLAY_FLG = '1')
	GROUP BY
		EXTRACT(YEAR_MONTH FROM DISP_DATE)
	ORDER BY
		DISP_DATE DESC
	";

    $fetchKako = $PDO->fetch($sql_kako);
