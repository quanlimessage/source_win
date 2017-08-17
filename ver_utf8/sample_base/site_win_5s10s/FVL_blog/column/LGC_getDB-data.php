<?php
/*******************************************************************************

    LOGIC:DBよりデータの取得

*******************************************************************************/

// 不正アクセスチェック
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#=============================================================================
# 共通処理：GETデータの受け取りと共通な文字列処理
#=============================================================================
if ($_GET) {
    extract(utilLib::getRequestParams("get", array(8,7,1,4,5)));
}

// 送信される可能性のあるパラメーターはデコードする
$p  = urldecode($p);
$ca = urldecode($ca);
$res_id = urldecode($id);
$y = urldecode($y);
$m = urldecode($m);

// パラメーターが送信されたら記事の絞り込み
$ca_quety = $columnTitle = "";
if (!empty($ca) && is_numeric($ca)) {
    $sql = "
    SELECT
        CATEGORY_CODE as code,
        CATEGORY_NAME as name
    FROM
        ".COLUMN_CATEGORY_MST."
    WHERE
        (DEL_FLG = '0')
    AND
        (DISPLAY_FLG = '1')
    AND
        (CATEGORY_CODE = '{$ca}')
    ";

    // ＳＱＬを実行
    $fetchCA = $PDO -> fetch($sql);
    if (count($fetchCA)) {
        $ca_quety = " AND (CATEGORY_CODE = ".$ca.")";
        $columnTitle = $fetchCA[0]['name'];
    }
} else if (!empty($y) && is_numeric($y) && !empty($m) && is_numeric($m)) {
    $getDate = New Datetime("$y-$m-01 00:00:00");
    if ($getDate) {
        $ca_quety = " AND (DISP_DATE >= '".$getDate->format('Y-m-01 00:00:00')."') AND (DISP_DATE <= '".$getDate->modify('last day of this months')->format('Y-m-d 23:59:59')."')";
        $columnTitle = $getDate->format('Y')."年".$getDate->format('m')."月";
    }
}
// ページ番号の設定(GET受信データがなければ1をセット)
if (empty($p) or !is_numeric($p)) {
    $p=1;
}

if (empty($res_id)) {
    #------------------------------------------------------------------------
    #	該当商品リスト用情報の取得
    #------------------------------------------------------------------------

    // 抽出開始位置の指定
    $st = ($p-1) * $page;

    // SQL組立て
    $sql = "
    SELECT
        RES_ID,
        TITLE,
        CONTENT1,
        YEAR(DISP_DATE) AS Y,
        MONTH(DISP_DATE) AS M,
        DAYOFMONTH(DISP_DATE) AS D,
        IMG_FLG
    FROM
        ".COLUMN_PRODUCT_LST."
    WHERE
        (DISPLAY_FLG = '1')
    AND
        (DEL_FLG = '0')
        {$ca_quety}
    ORDER BY
        DISP_DATE DESC
    ";

    $fetchCNT = $PDO -> fetch($sql);

    $sql .= "
    LIMIT
        $st,".DISP_MAXROW;

    $fetch = $PDO -> fetch($sql);

    // 商品が何も登録されていない場合に表示
    if (count($fetch) == 0) {
        $disp_no_data = '<br><div align="center"><br><br><br>ただいま準備中のため、もうしばらくお待ちください。<br><br><br></div>';
    } else {
        $disp_no_data = '';
    }

} else if (!empty($res_id)) {
    #-------------------------------------------------------------------------
    # 詳細ページへの処理関連
    #-------------------------------------------------------------------------

    // パラメータがないもしくは不正なデータを混入された状態でアクセスされた場合のエラー処理
    if (empty($res_id) || !preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res_id)) {
        header("Location: ../");
        exit();
    }

    // DBよりデータを取得
    $sql = "
    SELECT
        *,
        YEAR(DISP_DATE) AS Y,
        MONTH(DISP_DATE) AS M,
        DAYOFMONTH(DISP_DATE) AS D
    FROM
        ".COLUMN_PRODUCT_LST."
    WHERE
        (RES_ID = '".addslashes($res_id)."')
    AND
        (DISPLAY_FLG = '1')
    AND
        (DEL_FLG = '0')
    ";

    $fetch = $PDO -> fetch($sql);

    // ＳＱＬの実行を取得できてなければ処理をしない
    if (empty($fetch[0]['RES_ID'])) {
        header("Location: ../");
        exit();
    }

    // SQL組立て
    $sql = "
    SELECT
        RES_ID
    FROM
        ".COLUMN_PRODUCT_LST."
    WHERE
        (DISPLAY_FLG = '1')
    AND
        (DEL_FLG = '0')
        {$ca_quety}
    ORDER BY
        DISP_DATE DESC
    ";

    $fetchCNT = $PDO -> fetch($sql);

}
