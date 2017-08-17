<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
設定ファイルの定義

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");    // 共通設定情報

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('TITLE', 'コラムの更新');                  // Sx系（商品紹介）のタイトル
define('CATE_TITLE', 'コラムカテゴリーの更新');    // Sx系（商品紹介）カテゴリーのタイトル

#=================================================================================
# 最大登録件数の指定
# カテゴリーは無制限で登録が可能
#=================================================================================
define('DBMAX_CNT', 100);    // Sx系（商品紹介）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('DISP_MAXROW', 10);    // Sx系（商品紹介）の 1ページの最大表示件数

define('DISP_MAXROW_BACK', 10);    // 管理画面での1ページの最大表示件数

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL', 3);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('COLUMN_PRODUCT_LST', 'COLUMN_PRODUCT_LST');    //商品データ
define('COLUMN_CATEGORY_MST', 'COLUMN_CATEGORY_MST');    //カテゴリー

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH', '../../column/');

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('IMG_PATH', '../../column/up_img/');
// アップロードサイズ上限(MB)
define('MAX_MB', 2);

// 詳細画像と本文のセット数
define('IMG_SET_CNT', 1);

// 画像枚数
define('IMG_CNT', (1 + IMG_SET_CNT));

// アーカイブの最新記事の取得数
define('LatestCnt', 5);

// 画像ファイルサイズ
define('IMGSIZE_SX', 40);    // 管理画面サムネイル用
define('IMGSIZE_SY', 30);    // 管理画面サムネイル用
define('IMGSIZE_MX1', 600);    // アップロード画像幅（商品紹介／高自動算出）
define('IMGSIZE_MY1', 100);    // アップロード画像高（商品紹介／幅自動算出）
define('IMGSIZE_MX2', 600);    // アップロード画像幅（商品紹介／高自動算出）
define('IMGSIZE_MY2', 200);    // アップロード画像高（商品紹介／幅自動算出）

//定数を配列に格納しておく（back_office/s5_evo_product/LGC_regist.phpで使用）
$ox = array(IMGSIZE_MX1);
$oy = array(IMGSIZE_MY1);
for ($i = 1; $i <= IMG_SET_CNT; $i++) {
    $ox[] = IMGSIZE_MX2;
    $oy[] = IMGSIZE_MY2;
}


function archiveLatest ()
{
    global $PDO;
    $getSql = "
    select
        RES_ID as id,TITLE as title
    from
        ".COLUMN_PRODUCT_LST."
    where
        (DEL_FLG = '0')
    and
        (DISPLAY_FLG = '1')
    order by
        DISP_DATE DESC
    limit
        0,".LatestCnt."
    ";

    $fetch = array_column($PDO->fetch($getSql), 'title', 'id');
    if (!count($fetch)) {
        echo "";
    } else {
        foreach ($fetch as $id => $title) {
            echo "<li><a href=\"./?id=".urlencode($id)."\">{$title}</a></li>";
        }
    }
}

function archiveMonth ()
{
    global $PDO;
    $getSql = "
    select
        YEAR(DISP_DATE) as y,
        MONTH(DISP_DATE) as m,
        COUNT(*) as count
    from
        ".COLUMN_PRODUCT_LST."
    where
        (DEL_FLG = '0')
    and
        (DISPLAY_FLG = '1')
    group by
        DATE_FORMAT(DISP_DATE, '%Y%m')
    ";

    $fetch = $PDO->fetch($getSql);
    if (!count($fetch)) {
        echo "";
    } else {
        foreach ($fetch as $val) {
            echo "<li><a href=\"./?y=".urlencode($val['y'])."&m=".urlencode($val['m'])."\">".$val['y']."年".$val['m']."月<span class=\"number\">（".$val['count']."）</span></a></li>";
        }
    }
}

function archiveCate ()
{
    global $PDO;
    $getSql = "
    select
        CATEGORY_NAME as name,
        product.CATEGORY_CODE as code,
        COUNT(*) as count
    from
        ".COLUMN_PRODUCT_LST." as product
    left join
        ".COLUMN_CATEGORY_MST." as cate
    on
        (product.CATEGORY_CODE = cate.CATEGORY_CODE)
    where
        (product.DEL_FLG = '0')
    and
        (product.DISPLAY_FLG = '1')
    and
        (cate.DEL_FLG = '0')
    and
        (cate.DISPLAY_FLG = '1')
    group by
        product.CATEGORY_CODE
    order by
        cate.VIEW_ORDER
    ";

    $fetch = $PDO->fetch($getSql);
    if (!count($fetch)) {
        echo "";
    } else {
        foreach ($fetch as $val) {
            echo "<li><a href=\"./?ca=".urlencode($val['code'])."\">".$val['name']."<span class=\"number\">（".$val['count']."）</span></a></li>";
        }
    }
}
