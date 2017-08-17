<?php
/*******************************************************************************
ALL-INTERNET BLOG

並べ替えDB情報取得

*******************************************************************************/

#---------------------------------------------------------------
# アクセスチェック
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../../err.php");
    exit();
}

// カテゴリー情報取得
$sql = "
SELECT
	CATEGORY_CODE,
	CATEGORY_NAME,
	VIEW_ORDER
FROM
	BLOG_CATEGORY_MST
WHERE
	( DEL_FLG = '0' )
ORDER BY
	VIEW_ORDER
";

$fetchList = $PDO->fetch($sql);
